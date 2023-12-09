<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Str;

trait Searchable
{
    public function scopeSearch(Builder $builder, string $term): Builder
    {
        $builder->where(function ($query) use ($term) {
            foreach ($this->searchable as $searchable) {
                if (str_contains($searchable, '.')) {
                    $relation = Str::beforeLast($searchable, '.');
                    $column = Str::afterLast($searchable, '.');

                    $query->orWhereRelation($relation, $column, 'like', "%$term%");

                    continue;
                }

                $query->orWhere($searchable, 'like', "%$term%");
            }
        });

        return $builder;
    }
}
