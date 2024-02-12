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
            // Check if the divisions are cached
            if (Cache::has('divisions')) {
                return Cache::get('divisions');
            }

            $divisions = $this->getDivision();

            // Cache the response forever
            Cache::forever('divisions', $divisions);

            return $this->respondWithSuccessWithData($divisions);
        }

        /**
         * Display a listing of the resource.
         * @return JsonResponse
         */
        public function city($division_id = null)
        {
            // Check if the cities are cached
            if (Cache::has("$division_id.cities")) {
                return Cache::get("$division_id.cities");
            }

            $citiesByDivision = $this->getCityByDivision($division_id);

            // Cache the response forever
            Cache::forever("$division_id.cities", $citiesByDivision);

            return $this->respondWithSuccessWithData($citiesByDivision);
        }

        /**
         * Display a listing of the resource.
         * @return JsonResponse
         */
        public function area($city_id = null)
        {
            // Check if the areas are cached
            if (Cache::has("$city_id.areas")) {
                return Cache::get("$city_id.areas");
            }

            $areasByCity = $this->getAreaByCity($city_id);

            // Cache the response forever
            Cache::forever("$city_id.areas", $areasByCity);

            return $this->respondWithSuccessWithData($areasByCity);
        }
    }
