    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
         <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{isset($page_title)?$page_title:null}}</h1>
         <span class="h-20px border-gray-200 border-start mx-4"></span>
            @if(isset($breadcrumb) && is_array($breadcrumb))
         <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
           @foreach($breadcrumb as $row)
             @php
             $url = $row['url']?:'javascript:;';
             @endphp
           @if(!$loop->first)
            <li class="breadcrumb-item">
               <span class="bullet bg-gray-200 w-5px h-2px"></span>
           </li>
           @endif
            <li class="breadcrumb-item">
               <a href="{{$url}}" class="text-hover-primary {{$loop->last?'text-dark':'text-muted'}}">{{$row['name']}}</a>
           </li>
            @endforeach
        </ul>
          @endif
     </div>

