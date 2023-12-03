<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\System\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Redirect;
use Matrix\Exception;

class OrderController extends Controller
{
    public function orderInvoiceGenerate(Request $request, $id)
    {
        try {
            $order = Order::with("orderDetails", "orderDetails.product", "orderDetails.product_color")->find($id);
            $site = SiteSetting::first();
            $pdf = Pdf::loadView('pdf.invoice', [
                'order' => $order,
                'site' => $site,
                'data' => $request->all()
            ]);
//            return $pdf->stream('invoice.pdf');
            return $pdf->download('invoice.pdf');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function text(Request $request)
    {
        $order = Order::with("orderDetails", "orderDetails.product:id,name,price", "orderDetails.product_color:id,name,price")->find(2);
        $site = SiteSetting::first();
//        $pdf = Pdf::loadView('pdf.invoice', ['data' => $data]);
        return view('pdf.invoice', ['order' => $order, 'site' => $site]);
    }
}
