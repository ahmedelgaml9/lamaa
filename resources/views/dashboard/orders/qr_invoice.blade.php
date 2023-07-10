<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8" />
    <title>فاتورة #{{$order->orderNumber}}</title>

    <style>

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: right;
        }
    </style>
</head>

<body>
<div class="invoice-box rtl">
    <h2 style="text-align: center;">{!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(110)->generate(route('orders.view_invoice', ['order_number' => $order->order_number])); !!}
    </h2>
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="4">
                <table>
                    <tr>

                        <td>
                            {{__('trans_admin.invoice_no')}}: {{$order->orderNumber}}<br />
                            {{__('trans_admin.invoice_created_at')}}: {{$order->created_at->format('Y-m-d H:i')}}<br />
                            {{__('trans_admin.payment_status')}}: {{__('trans_admin.payment_status_options.'.(int)$order->payment_status)}}<br />
                        </td>
                        <td></td>
                        <td></td>
                        <td class="title">
                            <img src="{{asset('images/mawared-dark-logo.png')}}" style="width: 100%; max-width: 80px" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="4">
                <table>
                    <tr>
                        <td>
                            <strong>{{__('trans_admin.provider_info')}}</strong><br />
                            <span>{{__('شركة مياه موارد')}} </span><br />
                            <span>920008086 | 16292</span><br />
                            <span>info@mawaredal-hayat.com</span><br />
                            <span> الشديدة . الخرج . الرياض . المملكة العربية السعودية</span><br />
                            <span><strong>الرقم الضريبي</strong>: 300185066300003</span>
                        </td>


                        <td>
                            <strong>{{__('trans_admin.customer_info')}}</strong><br />
                            <span>{{$customer?$customer->name:'--'}}</span><br />
                            <span>{{$customer?$customer->mobile:'--'}}</span><br />
                            <span>{{$customer?$customer->email:'--'}}</span><br />
                            <span>{{$address?$address->address:'--'}}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

{{--        <tr class="heading">--}}
{{--            <td>Payment Method</td>--}}

{{--            <td>Check #</td>--}}
{{--        </tr>--}}

{{--        <tr class="details">--}}
{{--            <td>Check</td>--}}

{{--            <td>1000</td>--}}
{{--        </tr>--}}

        <tr class="total">
            <td colspan="3"><strong>{{__('trans_admin.total_without_tax')}} : </strong>{{$orderProductsDetails['productsPrice']}} {{'ريال'}}<br />
                <strong>{{__('trans_admin.total_vat')}} : </strong>{{$orderProductsDetails['taxValue']}} {{'ريال'}}<br />
                <strong>{{__('trans_admin.total_amount_due')}} : </strong>{{$orderProductsDetails['finalPrice']}} {{'ريال'}}<br />
            </td>


        </tr>
    </table>
    <p style="color: #e80404; text-align: right;"> جميع الاسعار بعملة الريال السعودي</p>
</div>
</body>
</html>
