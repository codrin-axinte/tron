<?php

namespace App\Http\Controllers;

class TronWalletController extends Controller
{
    public function __invoke()
    {
       return \Http::get('http://localhost:3000/api/tokens?search=USDT')->json();
    }
    /**
     * 5741561676:AAETW0xvprMzdZD5JHPmq4eBrTrMG8V42Lk
     */
}
