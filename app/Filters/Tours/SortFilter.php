<?php

namespace App\Filters\Tours;

use Illuminate\Http\Request;

class SortFilter
{

    public function __construct(protected Request $request)
    {
    }

    public function handle($builder, \Closure $next)
    {
        $req = $this->request;

        return $next($builder)
            ->when(
                $req->has('sortBy') && $req->has('sortOrder'),
                fn ($q) => $q->orderBy($req->sortBy, $req->sortOrder)
            );
    }
}
