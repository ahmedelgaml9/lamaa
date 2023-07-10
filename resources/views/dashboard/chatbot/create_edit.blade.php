@extends('dashboard.layouts.master')
@section('toolbar')
@endsection
@section('content')
    <div class="card card-xl-stretch mb-5 mb-xl-10">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">{{$data->id? __('admin.edit_info'): __('admin.add_new')}}</span>
            </h3>
            <div class="card-toolbar">

            </div>
        </div>

        @if($data->id)
            {!! Form::open(['url' => route('chatbot.answers.update', $data->id), 'method' => 'put', 'files'=>true]) !!}
        @else
            {!! Form::open(['url' => route('chatbot.answers.store'), 'method' => 'post', 'files'=>true]) !!}
        @endif

        <div class="card-body py-3">
            <div class="tab-content">
                        <div class="row">

                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.question_title')}}</label>
                                <div class="mb-5 {{ $errors->has('question_title') ? 'has-error' : '' }}">
                                    <input type="text" name="question_title" class="form-control form-control-solid" value="{{old('question_title',$data->question_title)}}" placeholder="{{__('admin.question_title')}}"/>
                                    <span class="text-danger">{{ $errors->first('question_title') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="input" class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.chatbot_input')}}</label>
                                <div class="mb-5 {{ $errors->has('input') ? 'has-error' : '' }}">
                                    <select id="input" name="input" class="form-select">
                                        <option value="">لا يوجد</option>
                                        @foreach(__('admin.chatbot_inputs_types') as $key => $value)
                                            <option value="{{ $key }}" @if($key == old('input', $data->input)) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('input') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.question_body')}}</label>
                                <div class="mb-5 {{ $errors->has('question_body') ? 'has-error' : '' }}">
                                    <textarea name="question_body" class="form-control form-control-solid" placeholder="{{__('admin.question_body')}}">{{old('question_body',$data->question_body)}}</textarea>
                                    <span class="text-danger">{{ $errors->first('question_body') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.chatbot_answer')}}</label>
                                <div class="mb-5 {{ $errors->has('answer') ? 'has-error' : '' }}">
                                    <textarea name="answer" class="form-control form-control-solid" placeholder="{{__('admin.chatbot_answer')}}">{{old('answer',$data->answer)}}</textarea>
                                    <span class="text-info" dir="ltr">{order_number} | {customer_name} | {order_status}</span>
                                    <span class="text-danger">{{ $errors->first('answer') }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="to_customer_service" class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.to_customer_service')}}</label>
                                <div class="mb-5">
                                    <select id="to_customer_service" name="to_customer_service" class="form-select">
                                        @foreach(__('admin.int_status_options_yes_no') as $key => $value)
                                        <option value="{{ $key }}" @if($key == (int) old('to_customer_service', $data->to_customer_service)) selected @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <label class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.status')}}</label>
                                <div class="mb-5">
                                    <select name="status" class="form-select">
                                        <option value="1" @if($data->status == 1 ) selected @endif>{{__('admin.active')}}</option>
                                        <option value="0" @if($data->status == 0 ) selected @endif>{{__('admin.inactive')}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label
                                    class="form-label fs-6 fw-bolder text-gray-700 mb-3">{{__('admin.sort')}}</label>
                                <div class="mb-5 {{ $errors->has('sort') ? 'has-error' : '' }}">
                                    <input type="number" name="sort" class="form-control form-control-solid" value="{{old('sort',$data->sort)?:$lastSort}}" placeholder="{{__('admin.sort')}}"/>
                                    <span class="text-danger">{{ $errors->first('sort') }}</span>
                                </div>
                            </div>

                        </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2"  onClick="window.location.href=window.location.href">{{__('admin.discard')}}</button>
            <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">{{__('admin.save')}}</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
