@component('mail::message')
<div style="text-align:center; margin-bottom: 24px;">
    <div style="display:inline-block; padding: 10px 14px; border-radius: 14px; background: #ecfdf5; color: #16a34a; font-weight: 700; letter-spacing: .02em;">
        {{ $appName }}
    </div>
</div>

<div style="font-size: 24px; line-height: 1.25; font-weight: 700; color: #0f172a; text-align:center; margin-bottom: 10px;">
    Verifikasi email kamu
</div>

<p style="text-align:center; color:#475569; margin-top:0;">
    Halo {{ $displayName }}, tinggal satu langkah lagi supaya budget dan catatan finansialmu aman di {{ $appName }}.
</p>

@component('mail::button', ['url' => $url, 'color' => 'success'])
Verifikasi email
@endcomponent

<div style="margin-top: 24px; padding: 16px; border-radius: 14px; background: #f8fafc; border: 1px solid #e2e8f0; color:#475569;">
    Link ini aktif selama {{ $expireMinutes }} menit. Kalau kamu tidak merasa membuat akun di {{ $appName }}, abaikan email ini dengan tenang.
</div>

<p style="color:#64748b; font-size: 13px; margin-top: 24px;">
    Jika tombol di atas tidak bisa dibuka, salin link berikut ke browser kamu:
    <br>
    <a href="{{ $url }}" style="color:#16a34a; word-break: break-all;">{{ $url }}</a>
</p>

Terima kasih,<br>
Tim {{ $appName }}
@endcomponent
