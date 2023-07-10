@extends('dashboard.layouts.master')
@section('content')

<div class="col-lg-7 mx-auto">
<h5 class="mb-0 h6">{{__('admin.role_information')}}</h5>
<br>
    <div class="card">
        <div class="card-header border-0 pt-5">
          <h5 class="card-title align-items-start flex-column">
             {{__('admin.role_information')}}</h5>
             <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body p-0">
            <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST">
                <input name="_method" type="hidden" value="PATCH">
            	   @csrf
                   <div class="row mb-10">
                    <label class="col-md-3 col-from-label" for="name">{{__('admin.name')}} </label>
                    <div class="col-md-9">
                        <input type="text" placeholder="{{__('admin.name')}}" id="name" name="name" class="form-control"  value="{{$role->name}}" required>
                    </div>
                </div>
                <div class="card-header border-0 pt-5">
                  <h5 class="card-title align-items-start flex-column">
                       {{__('admin.permissions') }}</h5>
                 </div>
                  <br>
                  @php
                      $permissions = json_decode($role->permissions);
                  @endphp
                <div class="form-group row">
                    <label class="col-md-2 col-from-label" for="banner"></label>
                      <div class="col-md-8">
                         <div class="card">
                             {{__('admin.orders') }}
                          <div class="card-body">
                             <div class="row mb-10">
                                <div class="col-md-10">
                                     <label class="col-from-label">{{__('admin.view_orders') }}</label>
                                </div>

                               <div class="col-md-2">
                                  <div class="form-check form-check-custom form-check-solid">
                                      <input type="checkbox" name="permissions[]" class="form-check-input"  value="2" @php if(in_array(4, $permissions)) echo "checked"; @endphp >
                                  </div>
                               </div>
                           </div>

                          <div class="row mb-10">
                                <div class="col-md-10">
                                  <label class="col-from-label">{{__('admin.export') }}</label>
                               </div>

                                <div class="col-md-2">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input type="checkbox" name="permissions[]" class="form-check-input"  value="3">
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                       <div class="card">
                          {{__('admin.customers') }}
                        <div class="card-body">
                           <div class="row mb-10">
                            <div class="col-md-10">
                                <label class="col-from-label">{{__('admin.create') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="4" @php if(in_array(2, $permissions)) echo "checked"; @endphp >
                              </div>
                           </div>
                       </div>

                      <div class="row mb-10">
                            <div class="col-md-10">
                                <label class="col-from-label">{{__('admin.export') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="5" @php if(in_array(3, $permissions)) echo "checked"; @endphp>

                            </div>
                        </div>
                     </div>
                </div>
             </div>

                   <div class="card">
                          {{__('admin.marketing') }}
                        <div class="card-body">
                        <div class="row mb-10">
                            <div class="col-md-10">
                                <label class="col-from-label">{{__('admin.promocodes') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="6" @php if(in_array(6, $permissions)) echo "checked"; @endphp>
                               </div>
                           </div>
                       </div>
                </div>
             </div>

                 <div class="card">
                          {{__('admin.design') }}
                        <div class="card-body">
                        <div class="row mb-10">
                            <div class="col-md-10">
                                <label class="col-from-label">{{__('admin.frontoptions') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="12" @php if(in_array(12, $permissions)) echo "checked"; @endphp>
                               </div>
                            </div>
                        </div>
                   </div>
                </div>

                   <div class="card">
                         {{__('admin.sales') }}
                        <div class="card-body">
                           <div class="row mb-10">
                              <div class="col-md-10">
                                  <label class="col-from-label">{{__('admin.products') }}</label>
                               </div>

                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="14" @php if(in_array(14, $permissions)) echo "checked"; @endphp >
                                </div>
                            </div>
                       </div>

                         <div class="row mb-10">
                             <div class="col-md-10">
                             <label class="col-from-label">{{__('admin.services') }}</label>
                         </div>
                          <div class="col-md-2">
                             <div class="form-check form-check-custom form-check-solid">
                                  <input type="checkbox" name="permissions[]" class="form-check-input"  value="15" @php if(in_array(15, $permissions)) echo "checked"; @endphp>
                                </div>
                            </div>
                       </div>
                   </div>
               </div>

                    <div class="card">
                          {{__('admin.users_staf') }}
                        <div class="card-body">
                            <div class="row mb-10">
                              <div class="col-md-10">
                                <label class="col-from-label">{{__('admin.adduser') }}</label>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input type="checkbox" name="permissions[]" class="form-check-input"  value="20" @php if(in_array(20, $permissions)) echo "checked"; @endphp>
                                </div>
                            </div>
                        </div>

                            <div class="row mb-10">
                                  <div class="col-md-10">
                                     <label class="col-from-label">{{__('admin.assignpermission') }}</label>
                                 </div>

                                   <div class="col-md-2">
                                        <div class="form-check form-check-custom form-check-solid">
                                             <input type="checkbox" name="permissions[]" class="form-check-input"  value="21" @php if(in_array(21, $permissions)) echo "checked"; @endphp>
                                          </div>
                                      </div>
                                  </div>
                             </div>
                         </div>
                     </div>
                 </div>

                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-sm btn-primary">{{__('admin.save')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
