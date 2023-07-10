<!--begin::Menu 1-->
<div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_610d480c9e86f">
    <!--begin::Header-->
    <div class="px-7 py-5">
        <div class="fs-5 text-dark fw-bolder">{{__('trans_admin.filter_options')}}</div>
    </div>
    <!--end::Header-->
    <!--begin::Menu separator-->
    <div class="separator border-gray-200"></div>
    <!--end::Menu separator-->
    <!--begin::Form-->
    <form action="{{request()->url()}}">
    <div class="px-7 py-5">
        <div class="scroll-y me-n5 pe-5 h-200px h-lg-auto" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_header, #kt_toolbar, #kt_footer, #kt_chat_contacts_header" data-kt-scroll-wrappers="#kt_content, #kt_chat_contacts_body" data-kt-scroll-offset="0px">

        <!--begin::Input group-->
        <div class="mb-10">
            <label class="form-label fw-bold">{{__('admin.order_number')}}:</label>
            <div>
                <input type="text" class="form-control" placeholder="100234" name="order_number" value="{{request()->get('order_number')}}">
            </div>
        </div>
        <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.phone')}}:</label>
                <div>
                    <input type="text" class="form-control" placeholder="551234567" name="mobile" value="{{request()->get('mobile')}}">
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.name')}}:</label>
                <div>
                    <input type="text" class="form-control" placeholder="محمد خالد" name="name" value="{{request()->get('name')}}">
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.city')}}:</label>
                <div>
                    <select name="city" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="{{__('trans_admin.select_options')}}" data-dropdown-parent="#kt_menu_610d480c9e86f" data-allow-clear="true">
                        <option></option>
                        @foreach(citiesList() as $key => $value)
                            <option value="{{$key}}" @if($key == request()->get('payment_method')) selected @endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--end::Input group-->

        <!--begin::Input group-->
        <div class="mb-10">
            <label class="form-label fw-bold">{{__('admin.status')}}:</label>
            <div>
                <select name="status" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="{{__('trans_admin.select_options')}}" data-dropdown-parent="#kt_menu_610d480c9e86f" data-allow-clear="true">
                    <option></option>
                    @foreach(orderStatusesList() as $key => $value)
                    <option value="{{$key}}" @if($key == request()->get('status')) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.payment_method')}}:</label>
                <div>
                    <select name="payment_method" class="form-select form-select-solid" data-kt-select2="true" data-placeholder="{{__('trans_admin.select_options')}}" data-dropdown-parent="#kt_menu_610d480c9e86f" data-allow-clear="true">
                        <option></option>
                        @foreach(paymentMethodsList() as $key => $value)
                            <option value="{{$key}}" @if($key == request()->get('payment_method')) selected @endif>{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.created_at')}}:</label>
                <div>
                    <input name="created_at" type="text" class="form-control created-at-date-picker" placeholder="01-01-2022 >> 01-01-2023" value="{{request()->get('created_at')}}">
                </div>
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('admin.deliverydate')}}:</label>
                <div>
                    <input name="delivery_date" type="text" class="form-control delivered-at-date-picker" placeholder="01-01-2022 >> 01-01-2023" value="{{request()->get('delivery_date')}}">
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('trans_admin.show_result_count')}}:</label>
                <div>
                    <input type="number" class="form-control" placeholder="10" name="show_result_count" value="{{request()->get('show_result_count', 15)}}" min="1">
                </div>
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-10">
                <label class="form-label fw-bold">{{__('trans_admin.export_result_count')}}:</label>
                <div>
                    <input type="number" class="form-control" placeholder="100" name="export_result_count" value="{{request()->get('export_result_count', 100)}}" min="1">
                </div>
            </div>
            <!--end::Input group-->
    </div> <!--end::Filter options inputs-->
        <!--begin::Actions-->
        <div class="d-flex justify-content-end">
            <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" onClick="location.href='{{request()->url()}}'">Reset</button>
            <button type="submit" class="btn btn-sm btn-primary">Apply</button>
        </div>
        <!--end::Actions-->
    </div>
    </form>
    <!--end::Form-->
</div>
<!--end::Menu 1-->
