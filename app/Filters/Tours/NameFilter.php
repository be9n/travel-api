<?php

namespace App\Filters\Tours;

use Illuminate\Http\Request;

class NameFilter
{

    public function __construct(protected Request $request)
    {
    }

    public function handle($builder, \Closure $next)
    {
        $req = $this->request;

        return $next($builder)
            ->when(
                $req->has('name'),
                fn ($q) => $q->where('name', 'REGEXP', $req->name)
            );
    }
}
