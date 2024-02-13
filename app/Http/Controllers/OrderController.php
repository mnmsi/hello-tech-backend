<?php

namespace App\Http\Controllers;

use App\Models\GuestOrder;
use App\Models\Order\Order;
use App\Models\System\SiteSetting;
use App\Models\Voucher;
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
//            dd($order);
            $site = SiteSetting::first();
            $discount = null;
            if (!empty($order->voucher_id)) {
                $discount = Voucher::find($order->voucher_id);
            }
            $pdf = Pdf::loadView('pdf.invoice', [
                'order' => $order,
                'site' => $site,
                'discount' => $discount,
                'data' => $request->all()
            ]);
//            return $pdf->stream('invoice.pdf');
            return $pdf->download($order->transaction_id . '_invoice.pdf');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function guestOrderInvoiceGenerate(Request $request, $id)
    {
        try {
            $order = GuestOrder::with("orderItems", "orderItems.product", "orderItems.productColor")->find($id);
            $site = SiteSetting::first();
            $discount = null;
            if (!empty($order->voucher_id)) {
                $discount = Voucher::find($order->voucher_id);
            }
            $pdf = Pdf::loadView('pdf.guest_invoice', [
                'order' => $order,
                'site' => $site,
                'discount' => $discount,
                'data' => $request->all()
            ]);

//            return $pdf->stream('invoice.pdf');
            return $pdf->download($order->transaction_id . '_invoice.pdf');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function text(Request $request)
    {
        $order = Order::with("orderDetails", "orderDetails.product:id,name,price", "orderDetails.product_color:id,name,price")->find(5);
        $site = SiteSetting::first();
        $discount = null;
        if (!empty($order->voucher_id)) {
            $discount = Voucher::find($order->voucher_id);
        }
//        $pdf = Pdf::loadView('pdf.invoice', ['data' => $data]);
        return view('pdf.invoice', ['order' => $order, 'site' => $site, 'discount' => $discount,]);
    }
}
