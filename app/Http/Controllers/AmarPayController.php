<?php

namespace App\Http\Controllers;

use App\Models\Order\Order;
use App\Models\PaymentDetails;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Modules\Api\Http\Resources\User\UserAddressResource;

class AmarPayController extends Controller
{
    public function payment($orderData)
    {

        if (isset($orderData['user_id'])) {
            $user = User::where('id', $orderData['user_id'])->first();
            $address = UserAddress::where([['user_id', $user->id], ['is_default', 1]])->first();
            $user_address = $address->load('division', 'city', 'area');
            $tran_id = $orderData['transaction_id'];
            $currency = "BDT"; //aamarPay support Two type of currency USD & BDT
            $amount = $orderData['total_price'];   //10 taka is the minimum amount for show card option in aamarPay payment gateway
            //For live Store Id & Signature Key please mail to support@aamarpay.com
            $store_id = "aamarpaytest";
            $signature_key = "dbb74894e82415a2f7ff0ec3a97e4183";
            $url = "https://sandbox.aamarpay.com/jsonpost.php"; // for Live Transection use "https://secure.aamarpay.com/jsonpost.php"
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
            "store_id": "' . $store_id . '",
            "tran_id": "' . $tran_id . '",
            "success_url": "' . route('success') . '",
            "fail_url": "' . route('fail') . '",
            "cancel_url": "' . route('cancel') . '",
            "amount": "' . $amount . '",
            "currency": "' . $currency . '",
            "signature_key": "' . $signature_key . '",
            "desc": "Merchant Registration Payment",
            "cus_name": "' . $user->name . '",
            "cus_email": "' . $user->email . '",
            "cus_add1": "' . $address->address_line . '",
            "cus_add2": "",
            "cus_city": "' . $user_address->city->name . '",
            "cus_state": "' . $user_address->division->name . '",
            "cus_postcode": "1206",
            "cus_country": "Bangladesh",
            "cus_phone": "' . $user_address->phone . '",
            "type": "json"
        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        } else {
            $tran_id = $orderData['transaction_id'];
            $currency = "BDT"; //aamarPay support Two type of currency USD & BDT
            $amount = $orderData['total_price'];   //10 taka is the minimum amount for show card option in aamarPay payment gateway
            //For live Store Id & Signature Key please mail to support@aamarpay.com
            $store_id = "aamarpaytest";
            $signature_key = "dbb74894e82415a2f7ff0ec3a97e4183";
            $url = "https://sandbox.aamarpay.com/jsonpost.php"; // for Live Transection use "https://secure.aamarpay.com/jsonpost.php"
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
            "store_id": "' . $store_id . '",
            "tran_id": "' . $tran_id . '",
            "success_url": "' . route('success') . '",
            "fail_url": "' . route('fail') . '",
            "cancel_url": "' . route('cancel') . '",
            "amount": "' . $amount . '",
            "currency": "' . $currency . '",
            "signature_key": "' . $signature_key . '",
            "desc": "Merchant Registration Payment",
            "cus_name": "' . $orderData['name'] . '",
            "cus_email": "' . $orderData['email'] ?? null . '",
            "cus_add1": "' . $orderData['address_line'] . '",
            "cus_add2": "",
            "cus_city": "' . $orderData['city'] . '",
            "cus_state": "' . $orderData['division'] . '",
            "cus_postcode": "",
            "cus_country": "Bangladesh",
            "cus_phone": "' . $orderData['phone'] . '",
            "type": "json"
        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
        }

        $response = curl_exec($curl);

        curl_close($curl);
        // dd($response);

        $responseObj = json_decode($response);

        if (isset($responseObj->payment_url) && !empty($responseObj->payment_url)) {
            $paymentUrl = $responseObj->payment_url;
            // dd($paymentUrl);
            return redirect()->away($paymentUrl);
        } else {
            echo $response;
        }


    }

    public function success(Request $request)
    {


        $request_id = $request->mer_txnid;

        //verify the transection using Search Transection API

        $url = "http://sandbox.aamarpay.com/api/v1/trxcheck/request.php?request_id=$request_id&store_id=aamarpaytest&signature_key=dbb74894e82415a2f7ff0ec3a97e4183&type=json";

        //For Live Transection Use "http://secure.aamarpay.com/api/v1/trxcheck/request.php"

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        DB::beginTransaction();
        try {
            $payment = new PaymentDetails();
            $payment->order_id = Order::where('transaction_id', $request->mer_txnid)->first()->id ?? null;
            $payment->tran_id = $request->mer_txnid;
            $payment->amount = $request->amount;
            $payment->customer_name = $request->cus_name ?? null;
            $payment->customer_email = $request->cus_email ?? null;
            $payment->customer_phone = $request->cus_phone ?? null;
            $payment->currency = $request->currency ?? null;
            $payment->pay_time = $request->pay_time ?? null;
            $payment->bank_txn = $request->bank_txn ?? null;
            $payment->card_type = $request->card_type ?? null;
            $payment->save();
            if ($request->pay_status == 'Successful') {
                DB::commit();
                return redirect()->away(Env::get('FRONT_END') . 'order/success');
            } else {
                return redirect()->route('fail');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }
        curl_close($curl);
//        echo $response;
    }

    public function fail(Request $request)
    {
        return $request;
    }

    public function cancel()
    {
        return 'Canceled';
    }
}
