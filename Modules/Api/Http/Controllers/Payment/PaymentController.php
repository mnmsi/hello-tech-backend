<?php

namespace Modules\Api\Http\Controllers\Payment;

use App\Library\SslCommerz\SslCommerzNotification;
use App\Models\Order\Order;
use App\Models\PaymentDetails;
use DGvai\SSLCommerz\SSLCommerz;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;

class PaymentController
{
    public function test()
    {
        $post_data = array();
        $post_data['total_amount'] = '10'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'customer@mail.com';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = '8801XXXXXXXXX';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        $sslc = new SslCommerzNotification();
        return $sslc->makePayment($post_data);
    }

    public function success(Request $request)
    {
        DB::beginTransaction();
        try {
            $payment = new PaymentDetails();
            $payment->order_id = Order::where('transaction_id', $request->tran_id)->first()->id ?? null;
            $payment->tran_id = $request->tran_id;
            $payment->val_id = $request->val_id;
            $payment->amount = $request->amount;
            $payment->card_type = $request->card_type ? $request->card_type : null;
            $payment->store_amount = $request->store_amount;
            $payment->card_no = $request->card_no;
            $payment->bank_tran_id = $request->bank_tran_id;
            $payment->status = $request->status;
            $payment->tran_date = $request->tran_date;
            $payment->currency = $request->currency;
            $payment->card_issuer = $request->card_issuer;
            $payment->card_brand = $request->card_brand;
            $payment->card_sub_brand = $request->card_sub_brand;
            $payment->card_issuer_country = $request->card_issuer_country;
            $payment->card_issuer_country_code = $request->card_issuer_country_code;
            $payment->currency_type = $request->currency_type;
            $payment->currency_amount = $request->currency_amount;
            $payment->currency_rate = $request->currency_rate;
            $payment->amount = $request->amount;
            $payment->risk_title = $request->risk_title;
            $payment->save();
            if ($request->status == 'VALID') {
                DB::commit();
                return redirect()->away(Env::get('FRONT_END') . 'order/success');
            } else {
                return view('api::payment.failure');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
    }

    public function failure()
    {
        return view('api::payment.failure');
    }

    public function cancel()
    {
        return view('api::payment.cancel');
    }

    public function ipn()
    {
        return view('api::payment.ipn');
    }
}
