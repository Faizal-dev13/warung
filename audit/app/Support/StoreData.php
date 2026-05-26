<?php

namespace App\Support;

class StoreData
{
    public static function categories(): array
    {
        return [
            'all' => 'Semua Kategori',
            'design' => 'Desain & Kreatif',
            'productivity' => 'Produktivitas',
            'business' => 'Bisnis & UMKM',
            'utility' => 'Utility & Tools',
            'template' => 'Template Digital',
        ];
    }

    public static function vouchers(): array
    {
        return [
            'HEMAT5' => ['type' => 'fixed', 'value' => 5000, 'label' => 'Potongan Rp5.000'],
            'DIGI10' => ['type' => 'percent', 'value' => 10, 'label' => 'Diskon 10%'],
        ];
    }

    public static function products(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'starter-design-kit',
                'name' => 'Starter Design Kit',
                'category' => 'design',
                'badge' => 'Populer',
                'price' => 25000,
                'old_price' => 50000,
                'summary' => 'Paket asset desain untuk konten promosi, banner, dan sosial media.',
                'description' => 'Cocok untuk bisnis kecil yang ingin membuat visual promosi lebih rapi tanpa mulai dari nol. Berisi layout siap edit, contoh copy, dan panduan warna.',
                'features' => ['Template banner promosi', 'Asset sosial media', 'Panduan warna dan font', 'File siap edit'],
                'icon' => 'ph-palette',
                'accent' => 'from-fuchsia-500 to-violet-500',
                'latest' => false,
            ],
            [
                'id' => 2,
                'slug' => 'invoice-umkm-template',
                'name' => 'Invoice UMKM Template',
                'category' => 'business',
                'badge' => 'Best Deal',
                'price' => 20000,
                'old_price' => 40000,
                'summary' => 'Template invoice profesional untuk jasa, toko, dan bisnis online.',
                'description' => 'Memudahkan pembuatan invoice yang konsisten, rapi, dan mudah dikirim ke pelanggan melalui WhatsApp atau email.',
                'features' => ['Format invoice rapi', 'Rekap sederhana', 'Instruksi penggunaan', 'Cocok untuk jasa dan toko'],
                'icon' => 'ph-receipt',
                'accent' => 'from-emerald-500 to-teal-500',
                'latest' => false,
            ],
            [
                'id' => 3,
                'slug' => 'social-media-planner',
                'name' => 'Social Media Planner',
                'category' => 'productivity',
                'badge' => 'Diskon',
                'price' => 30000,
                'old_price' => 60000,
                'summary' => 'Planner konten bulanan untuk ide, jadwal posting, dan tracking performa.',
                'description' => 'Membantu owner dan admin konten menyusun jadwal posting agar tidak bingung menentukan ide harian.',
                'features' => ['Kalender konten', 'Bank ide konten', 'Tracking status produksi', 'Format mudah dipakai'],
                'icon' => 'ph-calendar-check',
                'accent' => 'from-blue-500 to-cyan-500',
                'latest' => false,
            ],
            [
                'id' => 4,
                'slug' => 'landing-page-copy-kit',
                'name' => 'Landing Page Copy Kit',
                'category' => 'template',
                'badge' => 'Baru',
                'price' => 35000,
                'old_price' => 70000,
                'summary' => 'Kerangka copywriting landing page untuk jasa, kursus, dan produk digital.',
                'description' => 'Berisi struktur headline, problem, benefit, testimoni, FAQ, dan CTA agar landing page terasa lebih menjual.',
                'features' => ['Struktur section siap pakai', 'Contoh headline', 'Checklist CTA', 'FAQ dan trust element'],
                'icon' => 'ph-layout',
                'accent' => 'from-orange-500 to-rose-500',
                'latest' => true,
            ],
            [
                'id' => 5,
                'slug' => 'admin-dashboard-ui-kit',
                'name' => 'Admin Dashboard UI Kit',
                'category' => 'template',
                'badge' => 'Premium',
                'price' => 45000,
                'old_price' => 90000,
                'summary' => 'Komponen dashboard untuk stok, order, customer, dan laporan sederhana.',
                'description' => 'Cocok sebagai referensi awal ketika membangun dashboard admin bisnis atau web app custom.',
                'features' => ['Card statistik', 'Tabel data', 'Status order', 'Layout responsive'],
                'icon' => 'ph-chart-line-up',
                'accent' => 'from-indigo-500 to-sky-500',
                'latest' => true,
            ],
            [
                'id' => 6,
                'slug' => 'file-organizer-checklist',
                'name' => 'File Organizer Checklist',
                'category' => 'utility',
                'badge' => 'Ringan',
                'price' => 15000,
                'old_price' => 30000,
                'summary' => 'Checklist pengelolaan file kerja agar folder bisnis lebih rapi.',
                'description' => 'Membantu tim kecil merapikan folder desain, invoice, dokumen pelanggan, dan arsip proyek.',
                'features' => ['Struktur folder rekomendasi', 'Checklist arsip', 'Naming convention', 'Contoh alur kerja'],
                'icon' => 'ph-folders',
                'accent' => 'from-slate-500 to-zinc-500',
                'latest' => true,
            ],
            [
                'id' => 7,
                'slug' => 'crm-follow-up-sheet',
                'name' => 'CRM Follow-up Sheet',
                'category' => 'business',
                'badge' => 'Terlaris',
                'price' => 30000,
                'old_price' => 60000,
                'summary' => 'Sheet sederhana untuk mencatat leads, follow-up, dan status closing.',
                'description' => 'Membantu bisnis jasa agar prospek dari WhatsApp, DM, dan website tidak mudah tercecer.',
                'features' => ['Kolom status leads', 'Reminder follow-up manual', 'Segmentasi prospek', 'Rekap closing'],
                'icon' => 'ph-users-three',
                'accent' => 'from-lime-500 to-emerald-500',
                'latest' => false,
            ],
            [
                'id' => 8,
                'slug' => 'mini-brand-guideline',
                'name' => 'Mini Brand Guideline',
                'category' => 'design',
                'badge' => 'Rekomendasi',
                'price' => 28000,
                'old_price' => 56000,
                'summary' => 'Template guideline brand sederhana untuk warna, font, logo, dan tone visual.',
                'description' => 'Berguna untuk menjaga konsistensi visual bisnis saat membuat konten, proposal, dan halaman website.',
                'features' => ['Panduan warna', 'Panduan font', 'Contoh penggunaan logo', 'Moodboard sederhana'],
                'icon' => 'ph-brush',
                'accent' => 'from-purple-500 to-pink-500',
                'latest' => false,
            ],
        ];
    }

    public static function product(string|int $idOrSlug): ?array
    {
        foreach (self::products() as $product) {
            if ((string) $product['id'] === (string) $idOrSlug || $product['slug'] === $idOrSlug) {
                return $product;
            }
        }

        return null;
    }

    public static function rupiah(int $value): string
    {
        return 'Rp'.number_format($value, 0, ',', '.');
    }
}
