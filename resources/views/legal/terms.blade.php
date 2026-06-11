<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Syarat dan Ketentuan - Alokasi</title>
</head>

<body class="h-full font-sans">
    <main class="relative z-10 min-h-full px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="mb-6 inline-flex items-center gap-3 text-gray-950 dark:text-slate-50">
                <span class="inline-flex size-10 items-center justify-center rounded-lg bg-green-50 text-green-500 ring-1 ring-green-100 dark:bg-green-500/10 dark:ring-green-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-7">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM9 7.5A.75.75 0 0 0 9 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 0 0 9 12h3.622a2.251 2.251 0 0 1-2.122 1.5H9a.75.75 0 0 0-.53 1.28l3 3a.75.75 0 1 0 1.06-1.06L10.8 14.988A3.752 3.752 0 0 0 14.175 12H15a.75.75 0 0 0 0-1.5h-.825A3.733 3.733 0 0 0 13.5 9H15a.75.75 0 0 0 0-1.5H9Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text-xl font-bold">Alokasi</span>
            </a>

            <article class="panel p-5 sm:p-8">
                <div class="eyebrow">Legal</div>
                <h1 class="page-title">Syarat dan Ketentuan</h1>

                <div class="mt-6 space-y-6 text-sm leading-6 text-gray-600 dark:text-slate-300">
                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Menggunakan Alokasi</h2>
                        <p class="mt-2">Dengan membuat akun atau menggunakan Alokasi, kamu menyetujui syarat ini. Alokasi adalah alat perencanaan pribadi dan pencatatan pengeluaran untuk mengatur rencana, pengeluaran, label, platform, status, dan catatan mutasi investasi.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Bukan Nasihat Keuangan</h2>
                        <p class="mt-2">Alokasi membantu kamu mencatat dan meninjau data milikmu sendiri. Alokasi bukan bank, broker, akuntan, konsultan pajak, atau penasihat keuangan. Keputusan apa pun yang kamu buat berdasarkan rencana, pengeluaran, laporan, atau catatan investasi adalah tanggung jawab kamu.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Akun Kamu</h2>
                        <p class="mt-2">Kamu bertanggung jawab menjaga kredensial akun tetap aman dan memastikan informasi yang kamu masukkan atau impor akurat. Kamu tidak boleh menggunakan akun orang lain atau mencoba mengakses data yang bukan milikmu.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Data Kamu</h2>
                        <p class="mt-2">Kamu tetap bertanggung jawab atas data rencana, pengeluaran, label, platform, status, dan investasi yang kamu tambahkan ke Alokasi. Fitur impor dan ekspor disediakan untuk membantu memindahkan data, tetapi kamu tetap perlu meninjau file impor dan spreadsheet ekspor agar akurat.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Penggunaan Yang Diperbolehkan</h2>
                        <p class="mt-2">Jangan menyalahgunakan Alokasi, mengganggu aplikasi, mencoba akses tanpa izin, mengunggah file berbahaya, merekayasa balik bagian layanan yang dilindungi, atau memakai aplikasi dengan cara yang melanggar hukum yang berlaku.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Ketersediaan Dan Perubahan</h2>
                        <p class="mt-2">Alokasi dapat mengubah, menjeda, atau menghentikan sebagian layanan. Fitur seperti laporan, login sosial, impor, ekspor, dan verifikasi email dapat bergantung pada layanan pihak ketiga atau konfigurasi lokal.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Penghapusan Akun</h2>
                        <p class="mt-2">Kamu bisa menghapus akun dari Pengaturan. Menghapus akun akan menghapus data Alokasi milik akun sesuai alur penghapusan aplikasi. Ekspor data yang ingin kamu simpan sebelum menghapus akun.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Batasan Tanggung Jawab</h2>
                        <p class="mt-2">Alokasi disediakan apa adanya. Sejauh diizinkan oleh hukum, pemilik aplikasi tidak bertanggung jawab atas kerugian tidak langsung, data yang salah dimasukkan pengguna, keputusan keuangan, layanan pihak ketiga yang tidak tersedia, atau masalah yang disebabkan oleh file impor.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Contact</h2>
                        <p class="mt-2">Untuk pertanyaan tentang syarat ini, hubungi administrator Alokasi di <span class="font-semibold text-gray-950 dark:text-slate-50">{{ config('mail.from.address') }}</span>.</p>
                    </section>
                </div>
            </article>
        </div>
    </main>
</body>

</html>
