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
        $data = HomePageSection::with('homePageSection.product')
            ->get();
        return $this->respondWithSuccessWithData(
            HomePageSectionResource::collection($data)
        );
    }

    public function featured(){
            $data = HomePageSection::with(['homePageSection.product' => function($q){
                $q->where('is_featured', 1);
                $q->where('is_active', 1);
            }])
            ->get();
        return $this->respondWithSuccessWithData(
            HomePageSectionResource::collection($data)
        ); }
}
