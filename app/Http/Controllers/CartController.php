<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Support\Money;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function add(Request $request, string $slug): JsonResponse|RedirectResponse
    {
        $product = Product::with('activeVariants')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $variant = $this->selectedVariant($request, $product);

        if ($product->activeVariants->isNotEmpty() && ! $variant) {
            return $this->cartErrorResponse($request, 'Pilih varian produk terlebih dahulu.');
        }

        $cart = session('cart', []);
        $key = $variant ? 'p'.$product->id.'-v'.$variant->id : 'p'.$product->id;
        $name = $variant ? $product->name.' - '.$variant->name : $product->name;
        $price = (int) ($variant?->price ?? $product->price);
        $nextQty = (int) ($cart[$key]['qty'] ?? 0) + 1;

        if ($message = $this->variantQuantityMessage($variant, $nextQty)) {
            return $this->cartErrorResponse($request, $message);
        }

        if (isset($cart[$key])) {
            $cart[$key]['qty'] = $nextQty;
            $cart[$key]['price'] = $price;
            $cart[$key]['name'] = $name;
            $cart[$key]['product_name'] = $product->name;
            $cart[$key]['variant_name'] = $variant?->name;
            $cart[$key]['duration'] = $variant?->duration;
            $cart[$key]['image_path'] = $product->image_path;
        } else {
            $cart[$key] = [
                'key' => $key,
                'id' => $product->id,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'slug' => $product->slug,
                'name' => $name,
                'product_name' => $product->name,
                'variant_name' => $variant?->name,
                'duration' => $variant?->duration,
                'price' => $price,
                'qty' => 1,
                'icon' => $product->icon,
                'image_path' => $product->image_path,
            ];
        }

        session(['cart' => $cart]);

        return $this->cartResponse($request, $name.' masuk keranjang.');
    }

    public function update(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $validated = $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:99']]);
        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $variant = ! empty($cart[$key]['variant_id'])
                ? ProductVariant::where('is_active', true)->find($cart[$key]['variant_id'])
                : null;

            if ($message = $this->variantQuantityMessage($variant, (int) $validated['qty'])) {
                return $this->cartErrorResponse($request, $message);
            }

            $cart[$key]['qty'] = (int) $validated['qty'];
            session(['cart' => $cart]);
        }

        return $this->cartResponse($request, 'Keranjang diperbarui.');
    }

    public function remove(Request $request, string $key): JsonResponse|RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);

        return $this->cartResponse($request, 'Produk dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'phone' => ['nullable', 'string', 'max:30'],
            'voucher' => ['nullable', 'string', 'max:30'],
            'note' => ['nullable', 'string', 'max:200'],
        ]);

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['qty']);
        $voucherCode = Str::upper(trim((string) ($validated['voucher'] ?? '')));
        $voucher = $voucherCode !== '' ? Voucher::where('code', $voucherCode)->first() : null;
        $discount = $voucher?->discountFor($subtotal) ?? 0;
        $total = max(0, $subtotal - $discount);
        $invoice = 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));

        $message = $this->buildWhatsappMessage($invoice, $validated, $cart, $subtotal, $discount, $total, $voucherCode);

        $order = DB::transaction(function () use ($invoice, $validated, $cart, $subtotal, $discount, $total, $voucherCode, $message) {
            $this->lockAndValidateCart($cart);

            $order = Order::create([
                'invoice_number' => $invoice,
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'] ?? null,
                'voucher_code' => $discount > 0 ? $voucherCode : null,
                'note' => $validated['note'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'whatsapp_message' => $message,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? $item['id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name' => $item['product_name'] ?? $item['name'],
                    'variant_name' => $item['variant_name'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                if (! empty($item['variant_id'])) {
                    $variant = ProductVariant::find($item['variant_id']);

                    if ($variant && ! is_null($variant->stock)) {
                        $variant->decrement('stock', (int) $item['qty']);
                    }
                }
            }

            return $order;
        });

        session()->forget('cart');

        $wa = preg_replace('/\D+/', '', (string) config('store.whatsapp'));

        return redirect()->away('https://wa.me/'.$wa.'?text='.rawurlencode($order->whatsapp_message));
    }

    private function selectedVariant(Request $request, Product $product): ?ProductVariant
    {
        $variantId = $request->input('variant_id') ?: $request->input('product_variant_id');

        if (! $variantId) {
            return null;
        }

        return $product->activeVariants->firstWhere('id', (int) $variantId);
    }

    private function lockAndValidateCart(array $cart): void
    {
        foreach ($cart as $item) {
            if (empty($item['variant_id'])) {
                continue;
            }

            $variant = ProductVariant::whereKey($item['variant_id'])->lockForUpdate()->first();

            if (! $variant || ! $variant->is_active) {
                throw ValidationException::withMessages([
                    'cart' => 'Salah satu varian di keranjang sudah tidak tersedia.',
                ]);
            }

            if ($message = $this->variantQuantityMessage($variant, (int) $item['qty'])) {
                throw ValidationException::withMessages(['cart' => $message]);
            }
        }
    }

    private function variantQuantityMessage(?ProductVariant $variant, int $qty): ?string
    {
        if (! $variant || is_null($variant->stock)) {
            return null;
        }

        if ($variant->stock <= 0) {
            return 'Varian '.$variant->name.' sedang habis.';
        }

        if ($qty > $variant->stock) {
            return 'Varian '.$variant->name.' hanya tersedia '.$variant->stock.' item.';
        }

        return null;
    }

    private function cartResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        $cart = $this->cartSummary();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => $message,
                'cart' => [
                    'count' => $cart['count'],
                    'subtotal' => $cart['subtotal'],
                    'subtotal_formatted' => $cart['subtotal_formatted'],
                ],
                'html' => view('partials.cart-items', ['cart' => $cart])->render(),
            ]);
        }

        return back()->with('success', $message);
    }

    private function cartErrorResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => false,
                'message' => $message,
            ], 422);
        }

        return back()->with('error', $message);
    }

    private function cartSummary(): array
    {
        $items = session('cart', []);
        $subtotal = collect($items)->sum(fn ($item) => $item['price'] * $item['qty']);

        return [
            'items' => $items,
            'count' => collect($items)->sum('qty'),
            'subtotal' => $subtotal,
            'subtotal_formatted' => Money::rupiah($subtotal),
        ];
    }

    private function buildWhatsappMessage(string $invoice, array $customer, array $cart, int $subtotal, int $discount, int $total, string $voucherCode): string
    {
        $lines = [
            '*Checkout Produk Digital*',
            'Invoice: '.$invoice,
            'Nama: '.$customer['name'],
        ];

        if (! empty($customer['phone'])) {
            $lines[] = 'No. HP: '.$customer['phone'];
        }

        $lines[] = '';
        $lines[] = '*Detail Pesanan:*';

        foreach ($cart as $item) {
            $lines[] = '- '.$item['name'].' x'.$item['qty'].' = '.Money::rupiah($item['price'] * $item['qty']);
        }

        $lines[] = '';
        $lines[] = 'Subtotal: '.Money::rupiah($subtotal);

        if ($discount > 0) {
            $lines[] = 'Voucher '.$voucherCode.': -'.Money::rupiah($discount);
        }

        $lines[] = '*Total: '.Money::rupiah($total).'*';

        if (! empty($customer['note'])) {
            $lines[] = '';
            $lines[] = 'Catatan: '.$customer['note'];
        }

        return implode("\n", $lines);
    }
}
