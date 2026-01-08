<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Middleware extends HttpKernel
{
    // Look for a method like this
    protected function middlewareAliases(): array
    {
        return [
            'auth' => \App\Http\Middleware\Authenticate::class,
            // Add your middleware here
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'cashier' => \App\Http\Middleware\CashierMiddleware::class,
            'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        ];
    }
}