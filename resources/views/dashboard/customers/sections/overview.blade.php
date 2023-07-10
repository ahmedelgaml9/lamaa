@extends('dashboard.customers.layout')
@section('sub_content')
     <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
           <div class="card-header cursor-pointer">
               <div class="card-title m-0">
                   <h3 class="fw-bolder m-0">{{__('admin.profile_details')}}</h3>
               </div>
          </div>
       
           <div class="card-body p-9">
              <div class="row mb-7">
                  <label class="col-lg-4 fw-bold text-muted">{{__('admin.name')}}</label>
                     <div class="col-lg-8">
                         <span class="fw-bolder fs-6 text-gray-800">{{$customer->name}}</span>
                     </div>
               </div>
           
               <div class="row mb-7">
                   <label class="col-lg-4 fw-bold text-muted">{{__('admin.phone')}}</label>
                     <div class="col-lg-8 fv-row">
                         <span class="fw-bold text-gray-800 fs-6">{{$customer->mobile}}</span>
                     </div>
                </div>
           
                 <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">{{__('admin.email')}}</label>
                      <div class="col-lg-8 fv-row">
                         <span class="fw-bold text-gray-800 fs-6">{{$customer->email?:'---'}}</span>
                     </div>
                 </div>

                  <div class="row mb-7">
                     <label class="col-lg-4 fw-bold text-muted">{{__('admin.city')}}</label>
                        <div class="col-lg-8 fv-row">
                           <span class="fw-bold text-gray-800 fs-6">{{$customer->city_name }}</span>
                       </div>
                  </div>
           
                    <div class="row mb-7">
                       <label class="col-lg-4 fw-bold text-muted">{{__('admin.created_at')}}</label>
                           <div class="col-lg-8 fv-row">
                               <span class="fw-bold text-gray-800 fs-6">{{$customer->created_at->format('d-m-Y h:i A')}}</span>
                           </div>
                       </div>
                   </div>
              </div>
@endsection

