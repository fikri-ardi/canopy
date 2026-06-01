@php
    $providers = [
        'google' => [
            'label' => 'Google',
            'class' => 'social-auth-google',
            'configured' => filled(config('services.google.client_id')) && filled(config('services.google.client_secret')),
        ],
        'facebook' => [
            'label' => 'Facebook',
            'class' => 'social-auth-facebook',
            'configured' => filled(config('services.facebook.client_id')) && filled(config('services.facebook.client_secret')),
        ],
        'github' => [
            'label' => 'GitHub',
            'class' => 'social-auth-github',
            'configured' => filled(config('services.github.client_id')) && filled(config('services.github.client_secret')),
        ],
    ];
@endphp

@error('social')
    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-600 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">
        {{ $message }}
    </div>
@enderror

<div class="mt-5 grid gap-2">
    @foreach ($providers as $provider => $meta)
        @if ($meta['configured'])
            <a href="{{ route('social.redirect', $provider) }}" class="social-auth-button {{ $meta['class'] }}">
        @else
            <button type="button" class="social-auth-button social-auth-disabled {{ $meta['class'] }}" disabled title="{{ $meta['label'] }} login belum dikonfigurasi">
        @endif
                <span class="social-auth-mark" aria-hidden="true">
                    @switch($provider)
                        @case('google')
                            <svg viewBox="0 0 24 24" class="size-4">
                                <path fill="#4285F4" d="M21.6 12.23c0-.78-.07-1.53-.2-2.23H12v4.22h5.38a4.6 4.6 0 0 1-2 3.02v2.51h3.24c1.9-1.75 2.98-4.33 2.98-7.52Z" />
                                <path fill="#34A853" d="M12 22c2.7 0 4.97-.9 6.62-2.25l-3.24-2.51c-.9.6-2.04.96-3.38.96-2.6 0-4.8-1.76-5.59-4.12H3.06v2.59A9.996 9.996 0 0 0 12 22Z" />
                                <path fill="#FBBC05" d="M6.41 14.08A6.01 6.01 0 0 1 6.1 12c0-.72.12-1.42.31-2.08V7.33H3.06A9.996 9.996 0 0 0 2 12c0 1.61.39 3.14 1.06 4.67l3.35-2.59Z" />
                                <path fill="#EA4335" d="M12 5.8c1.47 0 2.78.5 3.82 1.5l2.87-2.87C16.96 2.82 14.7 2 12 2a9.996 9.996 0 0 0-8.94 5.33l3.35 2.59C7.2 7.56 9.4 5.8 12 5.8Z" />
                            </svg>
                            @break

                        @case('facebook')
                            <svg viewBox="0 0 24 24" class="size-4">
                                <path fill="#1877F2" d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.53-4.7 1.31 0 2.68.23 2.68.23v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.27h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z" />
                                <path fill="#FFFFFF" d="m16.67 15.56.53-3.49h-3.33V9.8c0-.96.47-1.89 1.96-1.89h1.51V4.94s-1.37-.23-2.68-.23c-2.74 0-4.53 1.67-4.53 4.7v2.66H7.08v3.49h3.05V24a12.16 12.16 0 0 0 3.74 0v-8.44h2.8Z" />
                            </svg>
                            @break

                        @default
                            <svg viewBox="0 0 24 24" class="size-4">
                                <path fill="currentColor" d="M12 .5a11.5 11.5 0 0 0-3.64 22.41c.58.1.79-.25.79-.56v-2c-3.22.7-3.9-1.38-3.9-1.38-.53-1.34-1.29-1.7-1.29-1.7-1.05-.72.08-.7.08-.7 1.16.08 1.77 1.2 1.77 1.2 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.73-1.54-2.57-.3-5.27-1.29-5.27-5.73 0-1.27.45-2.3 1.19-3.11-.12-.3-.52-1.48.11-3.07 0 0 .97-.31 3.17 1.19A10.86 10.86 0 0 1 12 6.08c.98 0 1.96.13 2.88.39 2.2-1.5 3.17-1.19 3.17-1.19.63 1.59.23 2.77.11 3.07.74.81 1.19 1.84 1.19 3.11 0 4.46-2.71 5.43-5.29 5.72.42.36.78 1.07.78 2.16v3.21c0 .31.21.67.8.56A11.5 11.5 0 0 0 12 .5Z" />
                            </svg>
                    @endswitch
                </span>
                <span>Continue with {{ $meta['label'] }}</span>
        @if ($meta['configured'])
            </a>
        @else
            </button>
        @endif
    @endforeach
</div>

<div class="my-5 flex items-center gap-3 text-xs font-semibold uppercase text-gray-400 dark:text-slate-600">
    <span class="h-px flex-1 bg-gray-200 dark:bg-slate-800"></span>
    <span>Email</span>
    <span class="h-px flex-1 bg-gray-200 dark:bg-slate-800"></span>
</div>
