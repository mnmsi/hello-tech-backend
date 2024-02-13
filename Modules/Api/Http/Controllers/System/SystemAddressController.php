<?php

namespace Modules\Api\Http\Controllers\System;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\System\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Api\Http\Traits\System\SystemTrait;

class SystemAddressController extends Controller
{
    use SystemTrait;

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function division()
    {
        $divisions = Cache::rememberForever('divisions', function () {
            return $this->getDivision();
        });

        return $this->respondWithSuccessWithData($divisions);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function city($division_id = null)
    {
        $citiesByDivision = Cache::rememberForever("$division_id.cities", function () use ($division_id) {
            return $this->getCityByDivision($division_id);
        });

        return $this->respondWithSuccessWithData($citiesByDivision);
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function area($city_id = null)
    {
        $areasByCity = Cache::rememberForever("$city_id.areas", function () use ($city_id) {
            return $this->getAreaByCity($city_id);
        });

        return $this->respondWithSuccessWithData($areasByCity);
    }
}
