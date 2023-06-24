<?php
namespace Modules\Api\Http\Controllers\Product;
use App\Models\Warranty;
use App\Http\Controllers\Controller;

class WarrantyController extends Controller
{
    public function index()
    {
        $warranties = Warranty::select('id','name')->get();
        return response()->json([
            'success' => true,
            'message' => 'Warranty List',
            'data' => $warranties
        ]);
    }
}
