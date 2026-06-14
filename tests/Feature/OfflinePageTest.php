<?php

it('shows the offline fallback page', function () {
    $this->get(route('offline'))
        ->assertOk()
        ->assertSee('Kamu Sedang Offline')
        ->assertSee('Koneksi Terputus')
        ->assertSee('Alokasi memerlukan koneksi internet');
});
