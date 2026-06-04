<?php

it('shows the privacy policy page', function () {
    $this->get(route('privacy'))
        ->assertOk()
        ->assertSee('Privacy Policy')
        ->assertSee('Canopy stores the account and finance-management data');
});

it('shows the terms and conditions page', function () {
    $this->get(route('terms'))
        ->assertOk()
        ->assertSee('Terms and Conditions')
        ->assertSee('No Financial Advice');
});
