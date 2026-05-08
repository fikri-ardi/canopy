<x-layouts.guest title="Login">
    <section class="panel px-5 py-6 sm:px-6">
        <div>
            <div class="eyebrow">Welcome back</div>
            <h1 class="page-title">Login</h1>
            <p class="page-subtitle">Masuk untuk membuka budget management milikmu.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <label for="email" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus class="input-field">
                @error('email')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Password</label>
                <input id="password" name="password" type="password" autocomplete="current-password" class="input-field">
                @error('password')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-300">
                <input name="remember" type="checkbox" value="1" class="rounded border-gray-300 text-green-500 focus:ring-green-400 dark:border-slate-700 dark:bg-slate-800">
                <span>Remember me</span>
            </label>

            <button type="submit" class="btn-primary w-full">Login</button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-500 dark:text-slate-400">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:text-green-700 dark:text-green-400">Register</a>
        </p>
    </section>
</x-layouts.guest>
