@extends('dashboard.layouts.master')
@section('toolbar')
    <div class="d-flex align-items-center py-1">
        <a href="{{route('products.create')}}" class="btn btn-sm btn-primary">{{__('admin.create')}}</a>
    </div>
@endsection
@section('content')
    <div class="d-flex flex-wrap flex-stack pb-7">
        <div class="d-flex flex-wrap align-items-center my-1">
            <!--begin::Title-->
            <div class="d-flex flex-wrap align-items-center my-1">
                <h3 class="fw-bolder me-5 my-1">{{__('admin.products')}} ({{\App\Models\Product::where('type','=','product')->count()}})</h3>
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <form action="{{route('products.index')}}" id="search_with_word_form" style="display: inline-flex">
                        <input name="search_word" type="text" id="search_with_word_input" class="form-control form-control-white form-control-sm w-150px ps-9" placeholder="بحث سريع" value="{{request()->get('search_word')}}"/> <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-search"></i></button>
                    </form>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Search-->
            </div>
            <!--end::Title-->
        </div>
        <div class="d-flex flex-wrap my-1">
            <!--begin::Tab nav-->
            <ul class="nav nav-pills me-6 mb-2 mb-sm-0">
                <li class="nav-item m-0">
                    <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary me-3 active" data-bs-toggle="tab" href="#kt_products_grid_view">
                        <!--begin::Svg Icon | path: icons/duotone/Layout/Layout-4-blocks-2.svg-->
                        <span class="svg-icon svg-icon-2">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="5" y="5" width="5" height="5" rx="1" fill="#000000" />
																<rect x="14" y="5" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
																<rect x="5" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
																<rect x="14" y="14" width="5" height="5" rx="1" fill="#000000" opacity="0.3" />
															</g>
														</svg>
													</span>
                        <!--end::Svg Icon-->
                    </a>
                </li>
                <li class="nav-item m-0">
                    <a class="btn btn-sm btn-icon btn-light btn-color-muted btn-active-primary" data-bs-toggle="tab" href="#kt_products_list_view">
                        <!--begin::Svg Icon | path: icons/duotone/Layout/Layout-horizontal.svg-->
                        <span class="svg-icon svg-icon-2">
														<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
															<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																<rect x="0" y="0" width="24" height="24" />
																<rect fill="#000000" opacity="0.3" x="4" y="5" width="16" height="6" rx="1.5" />
																<rect fill="#000000" x="4" y="13" width="16" height="6" rx="1.5" />
															</g>
														</svg>
													</span>
                        <!--end::Svg Icon-->
                    </a>
                </li>
            </ul>
            <!--end::Tab nav-->

        </div>
        <!--end::Controls-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Tab Content-->
    <div class="tab-content">
        <!--begin::Tab pane-->
        <div id="kt_products_grid_view" class="tab-pane fade show active">
            @include('dashboard.products.partials.products_grid')
        </div>
        <!--end::Tab pane-->
        <!--begin::Tab pane-->
        <div id="kt_products_list_view" class="tab-pane fade">
            <!--begin::Card-->
        @include('dashboard.products.partials.products_list')
        <!--end::Card-->
        </div>
    </div>
    <!--end::Tab Content-->
@endsection

@section('script')
    <script>
        $(function () {
            $('body').on('click', '.page-item a', function (e) {
                e.preventDefault();
                let btn = $(this);
                let url = btn.attr('href');
                getPaginationItem(btn);
                window.history.pushState("", "", url);
            });

            function getPaginationItem(btn) {
                let url = btn.attr('href');
                $.ajax({
                    url: url,
                    cache: false
                }).done(function (data) {
                    $('#kt_products_grid_view').html(data.gridView);
                    $('#kt_products_list_view').html(data.listView);
                }).fail(function () {
                    alert('Items could not be loaded.');
                });
            }
        });
    </script>
@endsection
