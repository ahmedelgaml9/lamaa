@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')

    <div class="card">
            <div class="row">
                <div class="col-sm-3" style="padding-top:18px;">
                    <a href=""  target="__blank" class="btn btn-outline-default" id="print"><i class="fa fa-print"></i>  {{__('طباعة')}}</a>
                </div>
            </div>
        <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="d-flex flex-wrap py-5">
                                <div class="flex-equal me-5">
                                    <table class="table fs-6 fw-bold gs-0 gy-2 gx-2 m-0">
                                        <tr aria-colspan="2"><td><h3>تقييم العملاء للخدمة</h3></td></tr>
                                        <tr>
                                            <td class="text-gray-400"> {{__('خدمة العملاء')}} </td>
                                            <td class="text-gray-800"> {{$statistics['customer_services_ratings']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-400"> {{__('المناديب')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['drivers_ratings'] }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('المنتجات')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['products_ratings'] }}</td>
                                        </tr>

                                        <tr aria-colspan="2"><td><h3>الطلبات والمنتجات</h3> </td></tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('إسترجاع الطلبات')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['return_products'] }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('الغاء الطلبات')}} </td>
                                            <td class="text-gray-800"> {{$statistics['cancel_products']}}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('عدم توفر المنتجات')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['products_unavailability'] }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('مدة التوصيل')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['delivery_time'] }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('ايام التوقف عن إستقبال الطلبات')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['orders_stopping_days'] }}</td>
                                        </tr>

                                        <tr aria-colspan="2"><td><h3>محادثات خدمة العملاء </h3></td></tr>

                                        <tr>
                                            <td class="text-gray-400"> {{__('عدد المحادثات المفتوحة')}} </td>
                                            <td class="text-gray-800"> {{$statistics['chats_opining']}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-400"> {{__('عدد المحادثات المغلقة')}} </td>
                                            <td class="text-gray-800"> {{ $statistics['chats_closing'] }}</td>
                                        </tr>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
        </div>
    </div>


@endsection
@section('after_content')
@endsection
@section('css')
@endsection
@section('script')
@endsection
