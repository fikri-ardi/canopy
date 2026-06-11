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
    <title>Kebijakan Privasi - Alokasi</title>
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
                <h1 class="page-title">Kebijakan Privasi</h1>

                <div class="mt-6 space-y-6 text-sm leading-6 text-gray-600 dark:text-slate-300">
                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Data Yang Dikumpulkan</h2>
                        <p class="mt-2">Alokasi menyimpan data akun dan pengelolaan keuangan yang kamu berikan, termasuk nama, alamat email, password terenkripsi saat kamu membuatnya, identitas provider login sosial, rencana, nilai pemasukan, pengeluaran, label, platform, status, mutasi investasi, target investasi, file impor saat diproses, dan preferensi ekspor.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Cara Data Dipakai</h2>
                        <p class="mt-2">Data dipakai untuk autentikasi akun, verifikasi email, menampilkan dashboard dan laporan, mengelola rencana dan pengeluaran, mengelompokkan aktivitas investasi, impor atau ekspor data Alokasi, menjaga sesi tetap aman, dan memelihara aplikasi.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Login Sosial</h2>
                        <p class="mt-2">Jika kamu masuk dengan Google atau GitHub, Alokasi menerima informasi akun dari provider tersebut, seperti alamat email, nama tampilan, ID pengguna provider, dan avatar jika tersedia. Alokasi hanya memakai data ini untuk membuat atau menghubungkan akun kamu.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Pembagian Data</h2>
                        <p class="mt-2">Alokasi tidak menjual data pribadi atau data keuangan kamu. Data dapat diproses oleh penyedia infrastruktur atau layanan yang dibutuhkan untuk menjalankan aplikasi, seperti hosting, pengiriman email, provider autentikasi, dan penyimpanan. Data juga dapat dibuka jika diwajibkan hukum atau untuk melindungi keamanan layanan.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Keamanan Dan Retensi</h2>
                        <p class="mt-2">Password disimpan dalam bentuk hash, dan akses ke data milik akun dibatasi pada akun pengguna kamu. Alokasi menyimpan data selama akun kamu masih ada. Saat kamu menghapus akun, rencana, pengeluaran, label, platform, status, dan catatan terkait milik akun akan dihapus sesuai alur penghapusan aplikasi.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Pilihan Kamu</h2>
                        <p class="mt-2">Kamu bisa memperbarui profil, mengatur atau mengubah password, ekspor/impor data, dan menghapus akun dari Pengaturan. Kamu juga bisa tidak memakai login sosial dan memakai email/password jika password sudah dibuat.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Contact</h2>
                        <p class="mt-2">Untuk pertanyaan privasi, hubungi administrator Alokasi di <span class="font-semibold text-gray-950 dark:text-slate-50">{{ config('mail.from.address') }}</span>.</p>
                    </section>
                </div>
            </article>
        </div>
    </main>
</body>

</html>
