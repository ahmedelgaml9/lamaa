<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    <div class="btn-group" role="group">
        <button id="btnGroupDrop{{$loop->index}}" type="button" class="btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="dropdown" aria-expanded="false">
            {{__('admin.actions')}}
            <span class="svg-icon svg-icon-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24" /><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" /></g></svg></span><!--end::Svg Icon-->
        </button>
        <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" aria-labelledby="btnGroupDrop{{$loop->index}}">
            @foreach($actions as $action)
                <li class="menu-item px-3">
                @if(isset($action['ajax']) && $action['ajax'])
                   <a href="javascript:void(0);" class="dropdown-item menu-link px-3 {{isset($action['class'])?$action['class']:'load-ajax-modal'}}" data-url="{!! $action['url'] !!}">{{$action['label']}}</a>
                @else
                    <a href="{!! $action['url'] !!}" class="dropdown-item menu-link px-3 {{isset($action['class'])??''}}" target="{{$action['target']??'_self'}}">{{$action['label']}}</a>
                @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>
