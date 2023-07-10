<div class="btn-group" role="group" aria-label="Button group with nested dropdown">
      <div class="btn-group" role="group">
          <button id="btnGroupDrop{{$loop->index}}" type="button" class="btn btn-sm btn-icon btn-color-light-dark btn-active-light-primary" data-bs-toggle="dropdown" aria-expanded="false">
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
           </button>
          <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" aria-labelledby="btnGroupDrop{{$loop->index}}">
              @foreach($actions as $action)
                 <li class="menu-item px-3">
                      @if(isset($action['ajax']) && $action['ajax'])
                         <a href="javascript:void(0);" class="dropdown-item menu-link px-3 {{isset($action['class'])??''}}" data-url="{!! $action['url'] !!}">{{$action['label']}}</a>
                       @else
                          <a href="{!! $action['url'] !!}" class="dropdown-item menu-link px-3 {{isset($action['class'])??''}}"  target="{{$action['target']??'_self'}}">{{$action['label']}}</a>
                      @endif
                  </li>
              @endforeach
          </ul>
    </div>
</div>
