<?php

namespace App\Filters\Tours;

use Illuminate\Http\Request;

class DateFilter
{

    public function __construct(protected Request $request)
    {
    }

    public function handle($builder, \Closure $next)
    {
        $req = $this->request;

        return $next($builder)
            ->when(
                $req->has('dateFrom'),
                fn ($q) => $q->where('starting_date', '>=', $req->dateFrom)
            )
            ->when(
                $req->has('dateTo'),
                fn ($q) => $q->where('ending_date', '<=', $req->dateTo)
            );
    }
}
