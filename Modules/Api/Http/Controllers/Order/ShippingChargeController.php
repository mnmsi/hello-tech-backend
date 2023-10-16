<?php

namespace Modules\Api\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
class ShippingChargeController extends Controller
{
    public function shippingCharges($name=null)
    {
        $text = strtolower($name);
        if($text == 'dhaka metro'){
            $shipping_charge = ShippingCharge::where('title', '=', 'Inside Dhaka')->where('active', 1)->first();
        }else{
            $shipping_charge = ShippingCharge::whereNotIn('title', ['Inside Dhaka'])->where('active', 1)->first();
        }
        return response()->json([
            'status' => 'success',
            'data' => $shipping_charge
        ]);
    }
}
