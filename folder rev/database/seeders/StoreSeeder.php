<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            ['name' => 'Template Website', 'icon' => 'ph-browser', 'sort_order' => 1],
            ['name' => 'Dashboard Admin', 'icon' => 'ph-chart-line-up', 'sort_order' => 2],
            ['name' => 'Asset Digital', 'icon' => 'ph-file-zip', 'sort_order' => 3],
            ['name' => 'Tools Bisnis', 'icon' => 'ph-briefcase', 'sort_order' => 4],
        ])->mapWithKeys(function (array $category) {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                $category + ['slug' => Str::slug($category['name']), 'is_active' => true]
            );

            return [$model->slug => $model];
        });

        $products = [
            [
                'category' => 'template-website',
                'name' => 'Landing Page UMKM Pro',
                'summary' => 'Template landing page untuk bisnis jasa, produk lokal, dan campaign promosi.',
                'description' => 'Template landing page responsive dengan hero, fitur, paket harga, testimoni, FAQ, dan CTA WhatsApp.',
                'price' => 149000,
                'old_price' => 249000,
                'badge' => 'Best Seller',
                'icon' => 'ph-browser',
                'accent' => 'from-blue-600 to-indigo-600',
                'features' => ['Responsive mobile', 'Section CTA WhatsApp', 'Clean UI', 'Mudah custom konten'],
                'is_latest' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'category' => 'dashboard-admin',
                'name' => 'Admin Dashboard Sales',
                'summary' => 'UI dashboard untuk pantau penjualan, order, customer, dan performa produk.',
                'description' => 'Dashboard statis siap dikembangkan menjadi panel admin dengan ringkasan order, grafik sales, tabel customer, dan status follow-up.',
                'price' => 199000,
                'old_price' => 299000,
                'badge' => 'New',
                'icon' => 'ph-chart-line-up',
                'accent' => 'from-emerald-600 to-teal-600',
                'features' => ['Stat card', 'Table order', 'UI grafik', 'Layout admin clean'],
                'is_latest' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'category' => 'tools-bisnis',
                'name' => 'Mini CRM Follow Up',
                'summary' => 'Template flow CRM sederhana untuk merapikan prospek dan follow-up customer.',
                'description' => 'Cocok untuk bisnis yang masih mencatat lead dari WhatsApp secara manual dan butuh alur follow-up lebih rapi.',
                'price' => 179000,
                'old_price' => 279000,
                'badge' => 'Recommended',
                'icon' => 'ph-users-three',
                'accent' => 'from-violet-600 to-fuchsia-600',
                'features' => ['Pipeline lead', 'Status follow-up', 'Catatan customer', 'Prioritas prospek'],
                'is_latest' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'category' => 'asset-digital',
                'name' => 'Social Media Kit Bisnis',
                'summary' => 'Asset visual untuk promo produk, jasa, dan posting edukatif brand.',
                'description' => 'Paket asset visual untuk membantu brand tampil lebih konsisten di media sosial.',
                'price' => 99000,
                'old_price' => 159000,
                'badge' => 'Promo',
                'icon' => 'ph-palette',
                'accent' => 'from-orange-500 to-rose-500',
                'features' => ['Template feed', 'Template story', 'Copy section', 'Mudah edit'],
                'is_latest' => false,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'category' => 'template-website',
                'name' => 'Company Profile Modern',
                'summary' => 'Template website profil bisnis dengan tampilan profesional dan rapi.',
                'description' => 'Berisi halaman tentang kami, layanan, portfolio, kontak, dan CTA WhatsApp.',
                'price' => 169000,
                'old_price' => 259000,
                'badge' => 'Clean UI',
                'icon' => 'ph-buildings',
                'accent' => 'from-slate-700 to-slate-950',
                'features' => ['Profil bisnis', 'Section layanan', 'Portfolio', 'CTA kontak'],
                'is_latest' => false,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'category' => 'tools-bisnis',
                'name' => 'Katalog Produk WA',
                'summary' => 'Flow katalog produk yang mengarahkan customer langsung ke WhatsApp admin.',
                'description' => 'Cocok untuk bisnis yang belum butuh payment gateway dan ingin validasi pesanan manual di WhatsApp.',
                'price' => 129000,
                'old_price' => 199000,
                'badge' => 'WA Checkout',
                'icon' => 'ph-whatsapp-logo',
                'accent' => 'from-green-600 to-emerald-600',
                'features' => ['Keranjang', 'Checkout WA', 'Filter produk', 'Form customer'],
                'is_latest' => false,
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            $category = $categories[$product['category']];
            unset($product['category']);

            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                $product + [
                    'category_id' => $category->id,
                    'slug' => Str::slug($product['name']),
                    'is_active' => true,
                ]
            );
        }

        foreach ([
            ['code' => 'HEMAT20', 'label' => 'Diskon 20% untuk semua produk', 'type' => 'percent', 'value' => 20, 'minimum_order' => 0],
            ['code' => 'DIGITAL50', 'label' => 'Potongan Rp50.000 minimal order Rp200.000', 'type' => 'fixed', 'value' => 50000, 'minimum_order' => 200000],
        ] as $voucher) {
            Voucher::updateOrCreate(['code' => $voucher['code']], $voucher + ['is_active' => true]);
        }

        foreach ([
            [
                'label' => 'Checkout via WhatsApp',
                'title' => 'Katalog produk digital tanpa payment gateway.',
                'subtitle' => 'Customer pilih produk, masuk keranjang, lalu pesanan tersimpan di database dan lanjut konfirmasi ke WhatsApp admin.',
                'button_text' => 'Belanja Sekarang',
                'button_url' => '#produk',
                'icon' => 'ph-shopping-cart-simple',
                'accent' => 'from-slate-950 to-blue-950',
                'sort_order' => 1,
            ],
            [
                'label' => 'Data lebih rapi',
                'title' => 'Produk, voucher, banner, dan order sudah berbasis database.',
                'subtitle' => 'Cocok untuk toko digital yang ingin alur sederhana tapi tetap punya data transaksi tersimpan.',
                'button_text' => 'Lihat Katalog',
                'button_url' => '#produk',
                'icon' => 'ph-database',
                'accent' => 'from-indigo-700 to-violet-900',
                'sort_order' => 2,
            ],
        ] as $banner) {
            Banner::updateOrCreate(['title' => $banner['title']], $banner + ['is_active' => true]);
        }
    }
}
