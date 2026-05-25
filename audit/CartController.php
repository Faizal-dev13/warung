<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use App\Support\Money;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function add(string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $cart = session('cart', []);
        $key = (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['qty']++;
        } else {
            $cart[$key] = [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'price' => $product->price,
                'qty' => 1,
                'icon' => $product->icon,
                'image_path' => $product->image_path,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', $product->name.' masuk keranjang.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate(['qty' => ['required', 'integer', 'min:1', 'max:99']]);
        $cart = session('cart', []);

        if (isset($cart[(string) $id])) {
            $cart[(string) $id]['qty'] = $validated['qty'];
            session(['cart' => $cart]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(int $id): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[(string) $id]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang.');
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
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);
            }

            return $order;
        });

        session()->forget('cart');

        $wa = preg_replace('/\D+/', '', (string) config('store.whatsapp'));

        return redirect()->away('https://wa.me/'.$wa.'?text='.rawurlencode($order->whatsapp_message));
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
