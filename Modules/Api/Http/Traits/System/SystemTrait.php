<?php

namespace Modules\Api\Http\Traits\System;

use App\Models\System\Division;
use App\Models\System\City;
use App\Models\System\Area;

trait SystemTrait
{
    public function getDivision()
    {
        return Division::all()->sort();
    }

    public function getCityByDivision($division_id = null)
    {
        return City::where('division_id', $division_id)->orderBy('name', 'asc')->get();
    }


    public function getAreaByCity($city_id = null)
    {
        return Area::where('city_id', $city_id)->orderBy('name', 'asc')->get();
    }
}
