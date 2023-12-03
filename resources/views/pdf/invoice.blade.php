<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style type="text/css">
        h4 {
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .margin-top {
            margin-top: 1.25rem;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241 245 249);
        }

        table {
            width: 100%;
            border-spacing: 0;
        }

        table.products {
            font-size: 0.875rem;
        }

        table.products tr {
            /*background-color: rgb(94, 94, 94);*/
            background-color: rgb(241 90 41);
        }

        table.products th {
            color: #ffffff;
            padding: 0.5rem;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }

        table tr.items {
            background-color: rgb(241 245 249);
        }

        table tr.items td {
            padding: 0.5rem;
        }

        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .top-info {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            line-height: 20px;
            letter-spacing: .05rem;
        }

        /*    customer*/
        .customer .header {
            /*background-color: rgb(94, 94, 94);*/
            background-color: rgb(241 90 41);
        }

        .customer .header td {
            color: #ffffff;
            padding: 0.5rem;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }

        .customer .body {
            background-color: #fff;
        }

        .customer .body td {
            color: #000;
            padding: 0.5rem;
            font-weight: 400;
            vertical-align: top;
        }

        .customer .body td.title {
            font-weight: 500;
        }
    </style>
</head>
<body>
{{--top logo invoce--}}
<table class="w-full">
    <tr>
        <td class="w-half">
            <img src="https://admin.hellotech.store/img/mail-logo.png" width="auto" height="52" alt="logo"/>
        </td>
        <td class="w-half">
            <h2 class="text-center">Invoice</h2>
            <table class="w-full">
                <tr>
                    <td class="w-half">
                        <p class="top-info">Invoice</p>
                        <p class="top-info">Invoice Date</p>
                    </td>
                    <td class="w-half">
                        <p class="top-info">{{$order->transaction_id ?? ""}}</p>
                        <p class="top-info">{{date('d-m-Y')}}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
{{--customer info--}}
<div class="margin-top">
    <table class="w-full customer">
        <tr class="header">
            <td class="w-half">Our Info:</td>
            <td class="w-half">Customer</td>
        </tr>
        <tr class="body">
            <td class="w-half">
                <div><h4>{{$site->name ?? "Hello Techs"}}</h4></div>
                <div>Dhaka, Bangladesh</div>
                <div>Phone: {{$site->phone ?? "-"}}</div>
                <div>Email: {{$site->email ?? "-"}}</div>
            </td>
            <td class="w-half">
                <div><h4>Name: {{$order->name ?? "-"}}</h4></div>
                <div>{{$order->address_line ?? "-"}} {{$order->area ?? "-"}}</div>
                <div>{{$order->city ?? "-"}}, {{$order->division ?? "-"}}</div>
                <div>Phone: {{$order->phone ?? "-"}}</div>
                <div>{{!empty($order->email) ? "Email: " . $order->email : ""}}</div>
            </td>
        </tr>
    </table>
</div>
{{--order info--}}
<div class="margin-top">
    <table class="products">
        <tr>
            <th style="text-align: left">Description</th>
            <th style="text-align: left">Price</th>
            <th style="text-align: left">Qty</th>
            <th style="text-align: right">SubTotal</th>
        </tr>
        <tr class="items">
            @if(!empty($order->orderDetails))
                @foreach($order->orderDetails as $item)
                    <td>
                        {{ !empty($item["product"]["name"]) ? $item["product"]["name"] : "" }}
                    </td>
                    <td>
                        {{ !empty($item["product"]["price"]) ? $item["product"]["price"] : 0 }}
                    </td>
                    <td>
                        {{ !empty($item["quantity"]) ? $item["quantity"] : 0 }}
                    </td>
                    <td style="text-align: right">{{ !empty($item["subtotal_price"]) ? $item["subtotal_price"] : 0 }}</td>
                @endforeach
            @endif
        </tr>
    </table>
</div>
{{--summary--}}
<div class="margin-top">
    <table class="w-full customer">
        <tr class="header" style="background-color: #fff">
            <td class="w-half" style="color: #000">
                <h4>Status: {{$order->status ?? ""}}</h4>
            </td>
            <td class="w-half" style="color: #000">
                <h4>Summary: </h4>
            </td>
        </tr>
        <tr class="body">
            <td class="w-half">
                {{--                <div>Total Amount</div>--}}
                {{--                <div>Paid Amount:</div>--}}
            </td>
            <td class="w-half">
                <div style="">
                    <table class="w-full">
                        <tr>
                            <td class="w-half" style="padding-left: 0">Shipping:</td>
                            <td class="w-half text-right">{{$order->shipping_amount ?? 0}}</td>
                        </tr>
                        @if($order->discount_rate !== null)
                            <tr>
                                <td class="w-half" style="padding-left: 0">Discount:</td>
                                <td class="w-half text-right">{{$order->discount_rate}} %</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="total">
    <h2>Total: {{$order->subtotal_price ?? 0}}</h2>
</div>
{{--auth--}}
<div class="margin-top">
    <table class="w-full customer">
        <tr class="header" style="background-color: #fff">
            <td class="w-half" style="color: #000">
                {{--                <h4>Status:</h4>--}}
            </td>
            <td class="w-half" style="color: #000">
                <h4 class="text-center"> Authorized Person</h4>
                <p></p>
                <hr/>
                <p class="text-center" style="margin: 0">{{$data["person"] ?? ""}}</p>
                <p class="text-center" style="margin-top: 0">(Sales Person)</p>
            </td>
        </tr>
    </table>
</div>
<hr style="margin: 20px 0"/>

<div class="footer margin-top">
    <div><h4>Terms:</h4></div>
    <div>{{$data["term"] ?? ""}}</div>
</div>
</body>
</html>
