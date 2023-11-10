<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TravelController extends Controller
{
    public function store(TravelRequest $request)
    {
        $travel = Travel::create($request->validated())->saveMorphedImages($request->images);

        return TravelResource::make($travel);
    }

    public function update(TravelRequest $request, Travel $travel)
    {
        $travel->update($request->validated());
        if ($travel)
            $travel->saveMorphedImages($request->images);

        return TravelResource::make($travel);
    }
}
