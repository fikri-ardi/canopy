<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kamu Sedang Offline - Alokasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        document.documentElement.classList.toggle('dark', localStorage.getItem('theme') === 'dark');
    </script>
    <style>
        :root {
            --bg-color: #f9fafb;
            --card-bg: #ffffff;
            --text-primary: #030712;
            --text-secondary: #4b5563;
            --primary: #22c55e;
            --primary-hover: #16a34a;
            --primary-light: #ecfdf5;
            --border-color: #e5e7eb;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        html.dark {
            --bg-color: #090d16;
            --card-bg: #111827;
            --text-primary: #f9fafb;
            --text-secondary: #9ca3af;
            --primary: #22c55e;
            --primary-hover: #4ade80;
            --primary-light: rgba(34, 197, 94, 0.1);
            --border-color: #1f2937;
            --shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .container {
            max-width: 440px;
            width: 100%;
            text-align: center;
        }

        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
            transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #22c55e, #10b981);
        }

        .logo-container {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2.5rem;
            text-decoration: none;
        }

        .logo-icon {
            display: inline-flex;
            width: 2.75rem;
            height: 2.75rem;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background-color: var(--primary-light);
            color: var(--primary);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .illustration-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pulse-circle {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: var(--primary-light);
            opacity: 0.15;
            animation: pulse 2.5s infinite ease-in-out;
        }

        .pulse-circle-inner {
            position: absolute;
            width: 70%;
            height: 70%;
            border-radius: 50%;
            background-color: var(--primary-light);
            opacity: 0.25;
            animation: pulse-inner 2.5s infinite ease-in-out;
        }

        .icon-wrapper {
            position: relative;
            z-index: 10;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .wifi-off-svg {
            width: 32px;
            height: 32px;
            stroke-width: 1.75;
            color: var(--primary);
        }

        h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.025em;
            color: var(--text-primary);
        }

        p {
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.875rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            outline: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.2);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: 1.25rem;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background-color: #ef4444;
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.6); opacity: 0; }
            50% { opacity: 0.25; }
            100% { transform: scale(1.1); opacity: 0; }
        }

        @keyframes pulse-inner {
            0% { transform: scale(0.6); opacity: 0; }
            50% { opacity: 0.4; }
            100% { transform: scale(1.15); opacity: 0; }
        }

        @keyframes blink {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="/" class="logo-container">
            <span class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 1.75rem; height: 1.75rem;">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM9 7.5A.75.75 0 0 0 9 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 0 0 9 12h3.622a2.251 2.251 0 0 1-2.122 1.5H9a.75.75 0 0 0-.53 1.28l3 3a.75.75 0 1 0 1.06-1.06L10.8 14.988A3.752 3.752 0 0 0 14.175 12H15a.75.75 0 0 0 0-1.5h-.825A3.733 3.733 0 0 0 13.5 9H15a.75.75 0 0 0 0-1.5H9Z" clip-rule="evenodd" />
                </svg>
            </span>
            <span class="logo-text">Alokasi</span>
        </a>

        <div class="card">
            <div class="status-badge">
                <span class="status-dot"></span>
                Offline
            </div>

            <div class="illustration-container">
                <div class="pulse-circle"></div>
                <div class="pulse-circle-inner"></div>
                <div class="icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="wifi-off-svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M21 21h1.5M3 21h1.5m0 0V8.25m0 12.75h1.5m-1.5 0H21M3 12h1.5M3 6h1.5m1.5-1.5h1.5M21 12h-1.5M21 6h-1.5m-1.5-1.5h-1.5M8.25 9.75h1.5m0 0V21m-1.5-11.25H4.5m8.25-3.75h1.5m0 0V21m-1.5-16.5H12" />
                    </svg>
                </div>
            </div>

            <h1>Koneksi Terputus</h1>
            <p>Alokasi memerlukan koneksi internet untuk menyinkronkan data keuanganmu. Silakan periksa koneksimu dan coba hubungkan kembali.</p>

            <button onclick="tryReload()" class="btn btn-primary">
                Coba Hubungkan Kembali
            </button>
        </div>
    </div>

    <script>
        function tryReload() {
            const btn = document.querySelector('.btn');
            btn.textContent = 'Menghubungkan...';
            btn.disabled = true;
            btn.style.opacity = '0.75';
            btn.style.cursor = 'not-allowed';
            
            // Check if online first, or just reload after a short delay
            if (navigator.onLine) {
                window.location.reload();
            } else {
                setTimeout(() => {
                    btn.textContent = 'Coba Hubungkan Kembali';
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    btn.style.cursor = 'pointer';
                    // Show a subtle toast or alert if it's still offline
                }, 800);
            }
        }

        // Listen for online event to automatically reload
        window.addEventListener('online', () => {
            window.location.reload();
        });
    </script>
</body>
</html>
