<div class="modal fade" id="kt_customer_balances_modal" tabindex="-1" aria-labelledby="kt_import_customers_modal_label" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content" style="width: 700px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="kt_import_customers_modal_label">{{__('محفظة العميل')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!--begin::Wrapper-->
                    <ul class="nav nav-tabs" id="userBalancesTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="balances-list-tab" data-bs-toggle="tab" data-bs-target="#balances-list-content" type="button" role="tab" aria-controls="balances-list-content" aria-selected="true">عمليات المحفظة</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="new-operation-balance-tab" data-bs-toggle="tab" data-bs-target="#new-operation-balance-content" type="button" role="tab" aria-controls="new-operation-balance-content" aria-selected="false">عملية جديدة</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="userBalancesTabContent" style="width: 700px;">
                        <div class="tab-pane fade show active" id="balances-list-content" role="tabpanel" aria-labelledby="balances-list-tab">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">رقم العملية</th>
                                    <th scope="col">نوع العملية</th>
                                    <th scope="col">القيمة</th>
                                    <th scope="col">بواسطة</th>
                                    <th scope="col">تاريخ الإنتهاء</th>
                                    <th scope="col">تاريخ الإنشاء</th>
                                    <th scope="col">ملاحظات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(\App\Models\UserBalance::all() as $balance)
                                    @php
                                    $createdBalanceBy = $balance->createdByData;
                                    @endphp
                                <tr>
                                    <th scope="row">{{$balance->id}}</th>
                                    <td>{{trans('trans_admin.'.$balance->operation_type)}}</td>
                                    <td>{{$balance->value}}</td>
                                    <td>{{$createdBalanceBy?$createdBalanceBy->name:'SYSTEM'}}</td>
                                    <td>{{$balance->expiry_date}}</td>
                                    <td>{{$balance->created_at}}</td>
                                    <td>{{$balance->reason_note}}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="new-operation-balance-content" role="tabpanel" aria-labelledby="contact-tab">
                            <form action="{{route('customers.balance_action', $customer->id)}}" method="POST">
                                @csrf
                                <br/>
                                <div class="col-md-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('قيمة السحب')}}</label>
                                    <div class="mb-5 {{ $errors->has('operation_type') ? 'has-error' : '' }}">
                                        <select name="operation_type" class="form-control form-control-solid">
                                            <option value="plus">{{__('trans_admin.plus')}}</option>
                                            <option value="plus">{{__('trans_admin.minus')}}</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('operation_type') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('القيمة')}}</label>
                                    <div class="mb-5 {{ $errors->has('balance_value') ? 'has-error' : '' }}">
                                        <input type="number" name="balance_value" class="form-control form-control-solid" value="{{old('balance_value')}}" placeholder="{{__('00.00')}}"/>
                                        <span class="text-danger">{{ $errors->first('balance_value') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('تاريخ الانتهاء')}}</label>
                                    <div class="mb-5 {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                                        <input type="date" name="expiry_date" class="form-control form-control-solid" value="{{old('expiry_date')}}"/>
                                        <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                                    </div>
                                </div>
 
                                   <div class="col-md-12">
                                      <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('سبب العملية')}}</label>
                                         <div class="mb-5 {{ $errors->has('balance_reason') ? 'has-error' : '' }}">
                                             <input type="text" name="balance_reason" class="form-control form-control-solid" value="{{old('balance_reason')}}"/>
                                             <span class="text-danger">{{ $errors->first('balance_reason') }}</span>
                                         </div>
                                      </div>
                                       <button type="submit" class="btn btn-sm btn-primary">تنفيذ</button>
                                  </form>
                            </div>
                         </div>
                   </div>
              </div>
        </div>
   </div>
