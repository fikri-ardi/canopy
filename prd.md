# Product Requirements Document (PRD) - iplants

## 1. Project Overview
- **Project Name:** iplants
- **Project Type:** Personal Finance Management System
- **Target Audience:** Dewasa muda dan profesional yang memerlukan manajemen arus kas yang ketat setelah menerima pendapatan (payday).
- **Technology Stack:** TALL Stack (Tailwind CSS, Alpine.js, Laravel, Livewire).

## 2. Objective
Tujuan utama dari **iplants** adalah menyediakan sistem pencatatan keuangan yang disiplin untuk mencegah fenomena "dana menguap" setelah hari gajian. Aplikasi ini memfasilitasi pengguna untuk mengalokasikan pendapatan ke dalam kategori budget tertentu (misal: bulanan atau event khusus) dan melacak setiap pengeluaran secara presisi hingga ke platform pembayaran yang digunakan.

## 3. User Features
### 3.1. Authentication & Security
- Sistem login dan registrasi standar menggunakan fitur bawaan Laravel (Breeze/Jetstream).
- Pengamanan sesi pengguna untuk memastikan privasi data keuangan.

### 3.2. Flexible Budgeting
- Pengguna dapat membuat entitas budget dengan penamaan bebas (Contoh: "Januari 2026", "Dana Darurat", "Liburan Bali").
- Setiap budget memiliki atribut **Income** sebagai batas maksimal pengeluaran yang direncanakan.

### 3.3. Granular Expense Tracking (Spends)
- Pencatatan transaksi yang mencakup nama pengeluaran, nominal, kategori budget, platform pembayaran, dan status transaksi.
- Memungkinkan pengguna mengetahui ke mana setiap unit mata uang dialokasikan.

### 3.4. Analytics & Reporting
- Visualisasi distribusi pengeluaran berdasarkan platform (E-wallet vs Bank vs Cash).
- Laporan sisa saldo (Remaining Balance) dari setiap kategori budget secara real-time.

## 4. Database Schema (Detailed)
Berdasarkan analisis sistem, berikut adalah struktur tabel yang diimplementasikan:

| Table Name | Columns | Description |
| :--- | :--- | :--- |
| **users** | `id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `timestamps` | Menyimpan data otentikasi pengguna. |
| **budgets** | `id`, `name`, `income`, `created_at`, `updated_at` | Menyimpan kategori budget dan target pendapatan/alokasi dana. |
| **platforms** | `id`, `name`, `created_at`, `updated_at` | Daftar penyedia layanan keuangan (e.g., Jago, BNI, ShopeePay). |
| **statuses** | `id`, `body`, `created_at`, `updated_at` | Status transaksi (e.g., Allocated, Withdrawn, Done). |
| **spends** | `id`, `budget_id`, `platform_id`, `status_id`, `name`, `amount`, `created_at`, `updated_at` | Tabel utama transaksi yang menghubungkan budget, platform, dan status. |

## 5. UI/UX Requirements
### 5.1. Visual Style
- **Interface:** Modern, minimalis, dan clean.
- **Typography:** Mengikuti panduan teknis menggunakan font variable:
    - Sans: `Geist Mono`, `ui-monospace`, `monospace`
    - Mono: `JetBrains Mono`, `monospace`

### 5.2. Appearance Modes
- **Dark Mode:** Tema default (High Contrast Dark).
- **Light Mode:** Tema terang yang bersih dengan penekanan pada keterbacaan teks.
- **Toggle:** Tersedia pengatur mode yang dapat diakses melalui dashboard.

### 5.3. Interaction
- Penggunaan komponen reaktif Livewire untuk input data tanpa perlu memuat ulang halaman (Single Page Experience).