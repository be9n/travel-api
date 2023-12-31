<?php

namespace App\Http\Controllers\Api;

use App\Filters\Tours\DateFilter;
use App\Filters\Tours\NameFilter;
use App\Filters\Tours\PriceFilter;
use App\Filters\Tours\SortFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ToursListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;
use Illuminate\Support\Facades\Pipeline;

class TourController extends Controller
{
    public function index(Travel $travel, ToursListRequest $request)
    {
        $tours = Pipeline::send($travel->tours())
            ->through([
                DateFilter::class,
                PriceFilter::class,
                SortFilter::class,
            ])
            ->thenReturn()
            ->search($request->term)
            ->orderBy('starting_Date')
            ->with('images', 'travel')
            ->paginate(config('app.paginationCount.tours'));

        if ($tours->isEmpty())
            return response(['message' => 'No tours found']);

        return TourResource::collection($tours);
    }
}
