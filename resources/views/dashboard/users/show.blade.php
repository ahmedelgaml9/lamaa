@extends('dashboard.layouts.master')
@section('content')

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                {{__('admin.list')}}
            </div>
            <div class="card-toolbar">
                
            </div>
        </div>
      
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed fs-6 gy-5">
                 <thead>
                     <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                         <th>#</th>
                         <th>{{__('admin.user_name')}}</th>
                         <th>{{__('admin.action')}}</th>
                         <th>{{__('admin.created_at')}}</th>
                         <th>{{__('admin.url')}}</th>
                         <th>{{__('admin.ip')}}</th>
                      </tr>
                    </thead>
                  <tbody class="text-gray-600 fw-bold">
                     @if($logs->count())
		         	 @foreach($logs as $key => $log)
                     <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $log->user_name }}</td>
                    <td>{{ $log->subject }}</td>
                    <td>{{$log->created_at->format('Y-m-d')}}
                     <div class="fw-bold fs-6 text-gray-400"><i class="fa fa-clock"></i> {{$log->created_at->format('h:i A')}}</div>
                    </td>
                    <td class="text-success"><a href="{{ $log->url }}">{{ $log->url }}</a></td>
                    <td class="text-warning">{{ $log->ip }}</td>
                    {{--<td><button class="btn btn-danger btn-sm">Delete</button></td>--}}
                 </tr>
		          	@endforeach
	            	@endif
                 </tbody>
            </table>
              {!! $logs->links('dashboard.partials.paginator', ['disableJS' => true]) !!}
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
     
         
    </script>
@endsection
