@extends('dashboard.customers.layout')
@section('sub_content')
    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{__('admin.shipping_address')}}</h3>
            </div>
        </div>
        <div class="card-body p-9 pagination-content">
            @include('dashboard.customers.partials.addresses_table')
        </div>
    </div>
@endsection

