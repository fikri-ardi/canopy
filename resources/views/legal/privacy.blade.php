<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Privacy Policy - Canopy</title>
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
                <span class="text-xl font-bold">Canopy</span>
            </a>

            <article class="panel p-5 sm:p-8">
                <div class="eyebrow">Legal</div>
                <h1 class="page-title">Privacy Policy</h1>
                <p class="page-subtitle">Last updated: June 4, 2026</p>

                <div class="mt-6 space-y-6 text-sm leading-6 text-gray-600 dark:text-slate-300">
                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">What Canopy Collects</h2>
                        <p class="mt-2">Canopy stores the account and finance-management data you provide, including your name, email address, encrypted password when you set one, social sign-in provider identifiers, budgets, income values, expenses/spends, labels, platforms, statuses, investment movements, investment targets, imported files while they are being processed, and export preferences.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">How The Data Is Used</h2>
                        <p class="mt-2">The data is used to authenticate your account, verify your email, show dashboards and reports, manage budgets and expenses, group investment activity, import or export your Canopy data, secure sessions, and maintain the application.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Social Sign In</h2>
                        <p class="mt-2">If you sign in with Google or GitHub, Canopy receives account information from that provider, such as your email address, display name, provider user ID, and avatar when available. Canopy uses this only to create or connect your account.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Sharing</h2>
                        <p class="mt-2">Canopy does not sell your personal or financial data. Data may be processed by infrastructure or service providers needed to run the app, such as hosting, email delivery, authentication providers, and storage. Data may also be disclosed if required by law or to protect the security of the service.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Security And Retention</h2>
                        <p class="mt-2">Passwords are stored as hashes, and access to your account-owned data is scoped to your user account. Canopy keeps your data while your account exists. When you delete your account, account-owned budgets, expenses, labels, platforms, statuses, and related records are removed according to the app's deletion flow.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Your Choices</h2>
                        <p class="mt-2">You can update your profile, set or change your password, export/import data, and delete your account from Settings. You can also avoid social sign-in and use email/password sign-in when a password is set.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Contact</h2>
                        <p class="mt-2">For privacy questions, contact the Canopy administrator at <span class="font-semibold text-gray-950 dark:text-slate-50">{{ config('mail.from.address') }}</span>.</p>
                    </section>
                </div>
            </article>
        </div>
    </main>
</body>

</html>
