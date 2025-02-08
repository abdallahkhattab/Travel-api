<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    //

    public function index(Travel $travel, TourListRequest $request)
    {

        return $travel->tours()
            ->when($request->dateFrom, function ($query) use ($request) {
                return $query->where('price', '>=', $request->priceFrom * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                return $query->where('price', '<=', $request->priceTo * 100);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                return $query->where('starting_date', '>=', $request->dateFrom);
            })
            ->when($request->dateFrom, function ($query) use ($request) {
                return $query->where('starting_date', '<=', $request->dateTo);
            })
            ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                // if(!in_array($request->sortOrder,['asc','desc'])) return;
                return $query->orderBy($request->sortBy, $request->sortOrder);
            })
            ->orderBy('starting_date')
            ->paginate();

        /*
        if($request('dateFrom')){
            $query->where();
        }*/

        // $tours = Travel::where('travel_id',$travel->id)->get();

        return TourResource::collection($tours);

    }
}
