<x-layouts.guest title="Verify Email">
    <section class="panel px-5 py-6 sm:px-6">
        <div>
            <div class="eyebrow">Verify email</div>
            <h1 class="page-title">Cek inbox kamu</h1>
        </div>

        <div class="mt-6 space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary w-full">Kirim ulang email verifikasi</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-secondary w-full">Logout</button>
            </form>
        </div>
    </section>
</x-layouts.guest>
