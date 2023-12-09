<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $travels = Travel::public()->search($request->term)->with(['images', 'tours', 'cover'])->paginate();

        
        if ($travels->isEmpty())
            return response(['message' => 'No travels found']);

        return TravelResource::collection($travels);
    }
}
