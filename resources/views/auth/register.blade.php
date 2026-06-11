<x-layouts.guest title="Daftar">
    <section class="panel px-5 py-6 sm:px-6">
        <div>
            <div class="eyebrow">Akun baru</div>
            <h1 class="page-title">Daftar</h1>
            <p class="page-subtitle">Buat akun dan mulai Alokasi.</p>
        </div>

        @include('auth.partials.social-buttons')

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" autofocus placeholder="Nama kamu" class="input-field">
                @error('name')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" placeholder="nama@email.com" class="input-field">
                @error('email')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Buat password" class="input-field">
                @error('password')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Ulangi password" class="input-field">
            </div>

            <button type="submit" class="btn-primary w-full">Buat Akun</button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-500 dark:text-slate-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" wire:navigate class="font-semibold text-green-600 hover:text-green-700 dark:text-green-400">Masuk</a>
        </p>
    </section>
</x-layouts.guest>
