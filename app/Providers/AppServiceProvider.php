<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->environment('local')) {
            URL::forceScheme('https');
        }

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $appName = config('app.name', 'Alokasi');
            $displayName = trim((string) ($notifiable->name ?? '')) ?: 'teman';
            $firstName = strtok($displayName, ' ') ?: $displayName;
            $expireMinutes = config('auth.verification.expire', 60);

            return (new MailMessage)
                ->subject("Verifikasi email {$appName} kamu")
                ->markdown('mail.auth.verify-email', [
                    'appName' => $appName,
                    'displayName' => $firstName,
                    'url' => $url,
                    'expireMinutes' => $expireMinutes,
                ]);
        });
    }
}
