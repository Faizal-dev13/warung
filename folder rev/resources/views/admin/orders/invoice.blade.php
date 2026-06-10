<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->invoice_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #e2e8f0; color: #0f172a; font-family: Poppins, Arial, sans-serif; }
        .page { width: 210mm; min-height: 297mm; margin: 24px auto; background: #fff; padding: 20mm; box-shadow: 0 20px 60px rgba(15,23,42,.18); }
        .top { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #0f766e; padding-bottom: 18px; }
        .brand { display: flex; gap: 12px; align-items: center; }
        .logo { width: 48px; height: 48px; border-radius: 16px; background: #0f766e; color: #fff; display: grid; place-items: center; font-weight: 800; overflow: hidden; }
        .logo img { width: 100%; height: 100%; object-fit: cover; }
        h1, h2, p { margin: 0; }
        h1 { font-size: 28px; line-height: 1; }
        .muted { color: #64748b; font-size: 12px; line-height: 1.7; }
        .invoice-title { text-align: right; }
        .invoice-title b { display: block; font-size: 18px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 24px; }
        .box { border: 1px solid #e2e8f0; border-radius: 18px; padding: 16px; }
        .box h2 { font-size: 13px; text-transform: uppercase; letter-spacing: .1em; color: #0f766e; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; font-size: 13px; }
        th { background: #0f172a; color: #fff; text-align: left; padding: 12px; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; }
        td { border-bottom: 1px solid #e2e8f0; padding: 12px; vertical-align: top; }
        .right { text-align: right; }
        .summary { width: 340px; margin-left: auto; margin-top: 22px; font-size: 13px; }
        .summary-row { display: flex; justify-content: space-between; padding: 9px 0; border-bottom: 1px solid #e2e8f0; }
        .total { margin-top: 10px; display: flex; justify-content: space-between; border-radius: 16px; background: #0f172a; color: #fff; padding: 16px; font-size: 16px; font-weight: 800; }
        .actions { position: sticky; top: 0; z-index: 10; display: flex; justify-content: center; gap: 10px; padding: 12px; background: rgba(255,255,255,.9); backdrop-filter: blur(12px); }
        .btn { border: 0; border-radius: 14px; background: #0f766e; color: #fff; padding: 10px 16px; font-weight: 800; cursor: pointer; }
        .btn.secondary { background: #0f172a; }
        .note { margin-top: 28px; border-top: 1px solid #e2e8f0; padding-top: 16px; font-size: 12px; color: #64748b; line-height: 1.7; }
        @media print {
            body { background: #fff; }
            .actions { display: none; }
            .page { margin: 0; width: auto; min-height: auto; box-shadow: none; padding: 16mm; }
        }
        @media (max-width: 720px) {
            .page { width: calc(100% - 24px); min-height: auto; padding: 22px; }
            .top, .grid { grid-template-columns: 1fr; display: grid; text-align: left; }
            .invoice-title { text-align: left; }
            .summary { width: 100%; }
        }
    </style>
</head>
<body>
    @php
        $invoiceLogoUrl = \App\Models\Setting::logoUrl();
        $invoiceStoreName = \App\Models\Setting::getValue('store_name', config('store.name'));
    @endphp
    <div class="actions">
        <button class="btn" onclick="window.print()">Cetak / Simpan PDF</button>
        <button class="btn secondary" onclick="window.close()">Tutup</button>
    </div>

    <main class="page">
        <section class="top">
            <div class="brand">
                <div class="logo">@if($invoiceLogoUrl)<img src="{{ $invoiceLogoUrl }}" alt="{{ $invoiceStoreName }}">@else{{ strtoupper(substr($invoiceStoreName, 0, 2)) }}@endif</div>
                <div>
                    <h1>{{ $invoiceStoreName }}</h1>
                    <p class="muted">Invoice pesanan customer</p>
                </div>
            </div>
            <div class="invoice-title">
                <p class="muted">INVOICE</p>
                <b>{{ $order->invoice_number }}</b>
                <p class="muted">{{ $order->created_at->format('d M Y H:i') }}</p>
            </div>
        </section>

        <section class="grid">
            <div class="box">
                <h2>Ditagihkan Kepada</h2>
                <p><b>{{ $order->customer_name }}</b></p>
                <p class="muted">{{ $order->customer_phone ?: '-' }}</p>
            </div>
            <div class="box">
                <h2>Informasi Order</h2>
                <p>Status: <b>{{ $statusLabels[$order->status] ?? str_replace('_',' ',$order->status) }}</b></p>
                <p class="muted">Voucher: {{ $order->voucher_code ?: '-' }}</p>
            </div>
        </section>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="right">Harga</th>
                    <th class="right">Qty</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td><b>{{ $item->product_name }}</b>@if($item->variant_name)<br><span class="muted">Varian: {{ $item->variant_name }}</span>@endif</td>
                        <td class="right">Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right"><b>Rp {{ number_format($item->subtotal,0,',','.') }}</b></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <section class="summary">
            <div class="summary-row"><span>Subtotal</span><b>Rp {{ number_format($order->subtotal,0,',','.') }}</b></div>
            <div class="summary-row"><span>Diskon</span><b>- Rp {{ number_format($order->discount,0,',','.') }}</b></div>
            <div class="total"><span>Total</span><span>Rp {{ number_format($order->total,0,',','.') }}</span></div>
        </section>

        @if($order->note)
            <section class="note">
                <b>Catatan:</b><br>
                {{ $order->note }}
            </section>
        @endif
    </main>
</body>
</html>
