<?php

it('shows the privacy policy page', function () {
    $this->get(route('privacy'))
        ->assertOk()
        ->assertSee('Kebijakan Privasi')
        ->assertSee('Alokasi menyimpan data akun');
});

it('shows the terms and conditions page', function () {
    $this->get(route('terms'))
        ->assertOk()
        ->assertSee('Syarat dan Ketentuan')
        ->assertSee('Bukan Nasihat Keuangan');
});
