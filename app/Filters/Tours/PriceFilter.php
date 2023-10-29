<?php

namespace App\Filters\Tours;

use Illuminate\Http\Request;

class PriceFilter
{

    public function __construct(protected Request $request)
    {
    }

    public function handle($builder, \Closure $next)
    {
        $req = $this->request;

        return $next($builder)
            ->when(
                $req->has('priceFrom'),
                fn ($q) => $q->where('price', '>=', $req->priceFrom * 100)
            )
            ->when(
                $req->has('priceTo'),
                fn ($q) => $q->where('price', '<=', $req->priceTo * 100)
            );
    }
}
