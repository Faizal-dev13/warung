<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SortPositionManager
{
    public static function next(string $modelClass, ?Closure $scope = null): int
    {
        $max = (int) self::applyScope($modelClass::query(), $scope)->max('sort_order');

        return $max + 1;
    }

    public static function makeRoomForCreate(string $modelClass, int|string|null $requestedPosition, ?Closure $scope = null): int
    {
        self::normalizeSequence($modelClass, $scope);

        $position = self::normalizeForCreate($modelClass, $requestedPosition, $scope);

        self::applyScope($modelClass::query(), $scope)
            ->where('sort_order', '>=', $position)
            ->increment('sort_order');

        return $position;
    }

    public static function move(Model $model, int|string|null $requestedPosition, ?Closure $scope = null): int
    {
        $modelClass = $model::class;

        self::normalizeSequence($modelClass, $scope);
        $model->refresh();

        $keyName = $model->getKeyName();
        $key = $model->getKey();
        $oldPosition = max(0, (int) $model->getOriginal('sort_order'));
        $position = self::normalizeForUpdate($model, $requestedPosition, $scope);

        if ($oldPosition < 1) {
            self::applyScope($modelClass::query(), $scope)
                ->where($keyName, '!=', $key)
                ->where('sort_order', '>=', $position)
                ->increment('sort_order');

            return $position;
        }

        if ($position === $oldPosition) {
            return $position;
        }

        $query = self::applyScope($modelClass::query(), $scope)
            ->where($keyName, '!=', $key);

        if ($position < $oldPosition) {
            $query->where('sort_order', '>=', $position)
                ->where('sort_order', '<', $oldPosition)
                ->increment('sort_order');
        } else {
            $query->where('sort_order', '>', $oldPosition)
                ->where('sort_order', '<=', $position)
                ->decrement('sort_order');
        }

        return $position;
    }

    public static function closeGap(string $modelClass, int|string|null $deletedPosition, ?Closure $scope = null, int|string|null $ignoreKey = null, string $keyName = 'id'): void
    {
        $position = (int) $deletedPosition;

        if ($position < 1) {
            return;
        }

        $query = self::applyScope($modelClass::query(), $scope)
            ->where('sort_order', '>', $position);

        if ($ignoreKey !== null) {
            $query->where($keyName, '!=', $ignoreKey);
        }

        $query->decrement('sort_order');

        self::normalizeSequence($modelClass, $scope, $ignoreKey, $keyName);
    }

    private static function normalizeSequence(string $modelClass, ?Closure $scope = null, int|string|null $ignoreKey = null, string $keyName = 'id'): void
    {
        $query = self::applyScope($modelClass::query(), $scope);

        if ($ignoreKey !== null) {
            $query->where($keyName, '!=', $ignoreKey);
        }

        $items = $query
            ->orderByRaw('CASE WHEN sort_order < 1 THEN 1 ELSE 0 END')
            ->orderBy('sort_order')
            ->orderBy($keyName)
            ->get([$keyName, 'sort_order']);

        foreach ($items as $index => $item) {
            $position = $index + 1;

            if ((int) $item->sort_order !== $position) {
                $item->forceFill(['sort_order' => $position])->saveQuietly();
            }
        }
    }

    private static function normalizeForCreate(string $modelClass, int|string|null $requestedPosition, ?Closure $scope = null): int
    {
        $max = (int) self::applyScope($modelClass::query(), $scope)->max('sort_order');
        $requested = (int) $requestedPosition;

        if ($requested < 1) {
            return $max + 1;
        }

        return min($requested, $max + 1);
    }

    private static function normalizeForUpdate(Model $model, int|string|null $requestedPosition, ?Closure $scope = null): int
    {
        $modelClass = $model::class;
        $keyName = $model->getKeyName();
        $key = $model->getKey();
        $currentPosition = max(1, (int) $model->getOriginal('sort_order'));
        $requested = (int) $requestedPosition;

        if ($requested < 1) {
            return $currentPosition;
        }

        $max = (int) self::applyScope($modelClass::query(), $scope)
            ->where($keyName, '!=', $key)
            ->max('sort_order');

        return min($requested, max(1, $currentPosition, $max));
    }

    private static function applyScope(Builder $query, ?Closure $scope = null): Builder
    {
        if ($scope) {
            $scope($query);
        }

        return $query;
    }
}
