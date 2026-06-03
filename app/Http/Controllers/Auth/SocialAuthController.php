<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use App\Support\InitializesUserAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    use InitializesUserAccount;

    private const PROVIDERS = ['google', 'github'];

    public function redirect(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        if (! $this->providerConfigured($provider)) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' login belum dikonfigurasi. Isi client ID dan client secret di file .env terlebih dahulu.']);
        }

        if (! $this->providerRedirectAllowed($provider)) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' redirect URI harus memakai HTTPS untuk domain custom, atau HTTP localhost untuk development.']);
        }

        try {
            return $this->providerDriver($provider)->redirect();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' login belum dikonfigurasi. Tambahkan client ID dan secret terlebih dahulu.']);
        }
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);

        if (! $this->providerConfigured($provider)) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' login belum dikonfigurasi. Isi client ID dan client secret di file .env terlebih dahulu.']);
        }

        if (! $this->providerRedirectAllowed($provider)) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' redirect URI harus memakai HTTPS untuk domain custom, atau HTTP localhost untuk development.']);
        }

        try {
            $socialUser = $this->providerDriver($provider)->user();
        } catch (Throwable) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => 'Login social gagal. Coba lagi atau gunakan email dan password.']);
        }

        $providerUserId = (string) $socialUser->getId();
        $email = $socialUser->getEmail();

        if (! $providerUserId || ! $email) {
            return redirect()
                ->route('login')
                ->withErrors(['social' => ucfirst($provider).' tidak mengirim data akun lengkap. Pastikan izin email aktif lalu coba lagi.']);
        }

        $account = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        $user = $account?->user ?? User::where('email', $email)->first();
        $isNewUser = false;

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: Str::before($email, '@'),
                'email' => $email,
                'password' => null,
                'email_verified_at' => now(),
            ]);

            $isNewUser = true;
        } elseif (! $user->email_verified_at) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        SocialAccount::updateOrCreate(
            [
                'user_id' => $user->id,
                'provider' => $provider,
            ],
            [
                'provider_user_id' => $providerUserId,
                'avatar' => $socialUser->getAvatar(),
            ],
        );

        if ($isNewUser) {
            $this->initializeUserAccount($user);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    private function providerDriver(string $provider)
    {
        $driver = Socialite::driver($provider);

        return match ($provider) {
            'github' => $driver->scopes(['read:user', 'user:email']),
            default => $driver,
        };
    }

    private function providerConfigured(string $provider): bool
    {
        return filled(config("services.{$provider}.client_id"))
            && filled(config("services.{$provider}.client_secret"));
    }

    private function providerRedirectAllowed(string $provider): bool
    {
        $redirect = config("services.{$provider}.redirect");
        $parts = parse_url($redirect ?: '');
        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');

        if ($scheme === 'https') {
            return true;
        }

        return $scheme === 'http' && in_array($host, ['localhost', '127.0.0.1', '::1'], true);
    }
}
