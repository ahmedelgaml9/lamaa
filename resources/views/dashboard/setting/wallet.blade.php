@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
    <div class="card card-xl-stretch mb-5 mb-xl-10">
        @if(Session::has('success'))
            <div class="col-sm-6">
                <div class="alert alert-success text-center"><em> {!! session('success') !!}</em></div>
            </div>
        @endif
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{$page_title}}</span>
            </h3>
            <div class="card-toolbar">
                <ul class="nav">
                    @foreach(supportedLanguages() as $keyLang => $valueLang)
                        <li class="nav-item">
                            <a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary {{$loop->first?'active':''}} fw-bolder px-4 me-1"
                               data-bs-toggle="tab" href="#lang_tab_{{$keyLang}}">{{$valueLang}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {!! Form::open(['url' => route('setting.update'), 'method' => 'post', 'files'=>true]) !!}

        <div class="card-body py-3">
            <div class="tab-content">

                @foreach(supportedLanguages() as $keyLang => $valueLang)
                    <div class="tab-pane fade {{$loop->first?'show active':''}}" id="lang_tab_{{$keyLang}}">

                        <div class="row">
                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('نسبة قيمة الكاش باك من إجمالي الطلبات السابقة')}}</label>
                                <div class="mb-5">
                                    <input type="hidden" name="types[]" value="cashback_percent">
                                    <input type="number" step=".01" class="form-control"  name="cashback_percent" value="{{ get_setting('cashback_percent')}}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('مدة صلاحية رصيد الكاش باك (بالايام)')}}</label>
                                <div class="mb-5">
                                    <input type="hidden" name="types[]" value="cashback_expiry_duration">
                                    <input type="number" step=".01" class="form-control"  name="cashback_expiry_duration" value="{{ get_setting('cashback_expiry_duration')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('الحد الادنى لإجمالي الطلبات السابقة للحصول على رصيد كاش باك')}}</label>
                                <div class="mb-5">
                                    <input type="hidden" name="types[]" value="min_value_to_get_cashback_balance">
                                    <input type="number" step=".01" class="form-control"  name="min_value_to_get_cashback_balance" value="{{ get_setting('min_value_to_get_cashback_balance')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('الحد الأدنى لقيمة الرصيد الحالي لإستخدامه')}}</label>
                                <div class="mb-5">
                                    <input type="hidden" name="types[]" value="min_balance_value_to_use">
                                    <input type="number" step=".01" class="form-control"  name="min_balance_value_to_use" value="{{ get_setting('min_balance_value_to_use')}}">
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
