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
    <title>Terms and Conditions - Alokasi</title>
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
                <h1 class="page-title">Terms and Conditions</h1>
                <p class="page-subtitle">Last updated: June 4, 2026</p>

                <div class="mt-6 space-y-6 text-sm leading-6 text-gray-600 dark:text-slate-300">
                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Using Alokasi</h2>
                        <p class="mt-2">By creating an account or using Alokasi, you agree to these terms. Alokasi is a personal budgeting and expense-tracking tool for organizing budgets, spends, labels, platforms, statuses, and investment movement records.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">No Financial Advice</h2>
                        <p class="mt-2">Alokasi helps you record and review your own data. It is not a bank, broker, accountant, tax advisor, or financial advisor. Any decisions you make based on your budgets, expenses, reports, or investment records are your responsibility.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Your Account</h2>
                        <p class="mt-2">You are responsible for keeping your account credentials secure and for the accuracy of the information you enter or import. You should not use another person's account or attempt to access data that does not belong to you.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Your Data</h2>
                        <p class="mt-2">You retain responsibility for the budget, expense, label, platform, status, and investment data you add to Alokasi. Import and export tools are provided to help move your data, but you should review imported files and exported spreadsheets for accuracy.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Acceptable Use</h2>
                        <p class="mt-2">Do not misuse Alokasi, interfere with the application, attempt unauthorized access, upload malicious files, reverse engineer protected parts of the service, or use the app in a way that violates applicable law.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Availability And Changes</h2>
                        <p class="mt-2">Alokasi may change, pause, or discontinue parts of the service. Features such as reports, social sign-in, imports, exports, and email verification may depend on third-party services or local configuration.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Account Deletion</h2>
                        <p class="mt-2">You can delete your account from Settings. Deleting an account removes account-owned Alokasi data according to the app's deletion flow. You should export anything you want to keep before deleting your account.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Limitation Of Liability</h2>
                        <p class="mt-2">Alokasi is provided as-is. To the maximum extent allowed by law, the application owner is not responsible for indirect losses, incorrect user-entered data, financial decisions, unavailable third-party services, or issues caused by imported files.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Contact</h2>
                        <p class="mt-2">For questions about these terms, contact the Alokasi administrator at <span class="font-semibold text-gray-950 dark:text-slate-50">{{ config('mail.from.address') }}</span>.</p>
                    </section>
                </div>
            </article>
        </div>
    </main>
</body>

</html>
