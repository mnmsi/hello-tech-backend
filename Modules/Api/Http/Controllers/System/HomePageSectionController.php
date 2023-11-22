<?php

namespace Modules\Api\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\System\HomePageSection;
use App\Models\System\SiteSetting;
use Modules\Api\Http\Resources\System\HomePageSectionResource;

class HomePageSectionController extends Controller
{
    public function homePageSections()
    {
        $data = HomePageSection::with(['homePageSection'=>function($q){
            $q->with(['product'=>function($q){
                $q->with(['colors'=>function($q){
                    $q->where('stock','>',0);
                }]);
            }])->orderBy('order','asc');
        }])
            ->get();
        return $this->respondWithSuccessWithData(
            HomePageSectionResource::collection($data)
        );
    }

}
