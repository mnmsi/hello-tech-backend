<?php
namespace Modules\Api\Http\Controllers\System;
use App\Http\Controllers\Controller;

class AboutController extends Controller{
    public function index(){
        $about = \App\Models\About::first();
        return response()->json([
            'status' => 'success',
            'data' => $about,
        ]);
    }
}
