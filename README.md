<<<<<<< HEAD
# warung
# DigitalKit WA Store - Laravel 12

Project Laravel 12 untuk katalog produk digital seperti referensi Arkasoft, tetapi checkout diarahkan ke WhatsApp, bukan payment gateway.

## Fitur

- Landing page katalog produk digital
- Search, filter kategori, dan sort harga/diskon
- Detail produk
- Keranjang berbasis session
- Update quantity dan hapus produk dari keranjang
- Voucher fixed/percent
- Checkout WhatsApp otomatis dengan format pesan rapi
- Dark mode / light mode dengan localStorage
- UI responsive mobile, tablet, dan desktop
- Menggunakan Blade, Tailwind CDN, Poppins, dan Phosphor Icons CDN

## Cara Install

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000
```

## Setting WhatsApp Checkout

Edit file `.env`:

```env
STORE_WHATSAPP=6281234567890
STORE_NAME="DigitalKit"
STORE_TAGLINE="Produk digital legal, cepat, dan siap dipakai."
```

Gunakan format nomor WhatsApp kode negara tanpa tanda `+`.

## Edit Produk

Produk dummy ada di:

```text
app/Support/StoreData.php
```

Ubah array `products()`, `categories()`, dan `vouchers()` sesuai kebutuhan.

## Catatan Deploy Hosting

Jika upload ke hosting shared:

1. Upload semua file Laravel ke folder project.
2. Arahkan document root domain/subdomain ke folder `public`.
3. Jalankan `composer install` di lokal lalu upload folder `vendor` kalau hosting tidak menyediakan Composer/SSH.
4. Pastikan `.env` sudah diisi dan `APP_KEY` sudah dibuat.

## Dibuat untuk

Fistechno IT / Ahmad Faizal
