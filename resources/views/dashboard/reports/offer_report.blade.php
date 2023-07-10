@extends('dashboard.layouts.master')
@section('content')
 
        <div class="card">
                <div class="card-header border-0 pt-5">
                <div class="card-title">
                   {{__('admin.offers_reports')}}

                 </div>
                <div class="card-toolbar">
                   {{--<div class="row">
                     <div class="col-lg-6">
                       {!! Form::open(array('route'=>'export_returned','method'=>'POST','class'=>'form-inline')) !!}                         
                         <label  class="col-form-label  fw-bold fs-6"></label><br><br>
                         <button type="submit" class="btn btn-success"><i class="fas fa-file-excel"></i></button>
                         {!! Form::close() !!}

                     </div>
                     <div class="col-lg-6">
                         <label  class="col-form-label  fw-bold fs-6"></label><br><br>
                         <a href="{{url('admin/returnedorders/pdf')}}" class="btn btn-danger"><i class="fas fa-file-pdf"></i></a>
                     </div>--}}
                  </div>
              </div>


           <div class="card-body pt-0">
              <table class="table align-middle table-row-dashed fs-6 gy-5" id="db_table">
                  <thead>
                      <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">#</th>
                        <th class="min-w-125px">{{__('admin.name')}}</th>
                        <th class="min-w-125px">{{__('admin.take')}}</th>
                        <th class="min-w-125px">{{__('admin.get')}}</th>
                        <th class="min-w-125px">{{__('admin.status')}}</th>
                        <th class="text-end min-w-100px">{{__('admin.actions')}}</th>
                 </tr>
                </thead>
                 <tbody class="text-gray-600 fw-bold">
                     @foreach($offers  as $offer)
                  <tr>
                    <td>{{ $offer->offer->id }}</td>
                    <td>{{ $offer->offer->name }}</td>
                    <td>{{ $offer->offer->take }}</td>
                    <td>{{ $offer->offer->get }}</td>
                    <td>
                        {!! intStatusLabel($offer->offer->status) !!}
                    </td>
                    @endforeach
                 </tbody>
            </table>
        </div>
    </div>

@endsection
@section('after_content')
@endsection
@section('css')

<link href="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>

@endsection
@section('script')
<script src="{{asset('dashboard/dist/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
 <script>

 $(function (){
     $('#db_table').dataTable({
      "ordering": false
     });
})

$("#kt_datepicker_1").flatpickr();
$("#kt_datepicker_2").flatpickr();

</script>
@endsection
