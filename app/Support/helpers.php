<?php

function rupiah(int|float|null $amount): string
{
    return 'Rp '.number_format($amount ?? 0, 0, ',', '.');
}
