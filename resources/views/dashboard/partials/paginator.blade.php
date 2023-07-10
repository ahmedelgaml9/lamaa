@if ($paginator->lastPage() > 1)
<div class="d-flex flex-stack flex-wrap pt-10">
    <div class="fs-6 fw-bold text-gray-700">{{__('admin.show_number_of_total_data', ['current' => $paginator->count(), 'total' => $paginator->total()])}}</div>
      <ul class="pagination">
          @if ($paginator->onFirstPage())
            <li class="page-item previous disabled">
                <a href="javascript:void(0);" class="page-link page-prev-next-btn" disabled="">
                    &laquo;
                </a>
            </li>
           @else
            <li class="page-item previous">
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link page-prev-next-btn" disabled="">
                    &laquo;
                </a>
            </li>
           @endif
            @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a href="javascript:void(0)" class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
               @endif
               @endforeach
               @if ($paginator->hasMorePages())
              <li class="page-item next">
                   <a href="{{ $paginator->nextPageUrl() }}" class="page-link page-prev-next-btn">
                      &raquo;
                   </a>
              </li>
              @else
              <li class="page-item next">
                 <a href="javascript:void(0);" class="page-link page-prev-next-btn">
                     &raquo;
                 </a>
             </li>
             @endif
         </ul>
    </div>
    @if(isset($disableJS) && $disableJS)
    @else
    @push('child_script')
 <script>
    $(function () {
        $('body').on('click', '.page-item a', function (e) {
            e.preventDefault();
            let btn = $(this);
            let url = btn.attr('href');

            {{--$('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="{{asset('dashboard/dist/assets/images/loading.gif')}}" />');--}}
            getPaginationItem(btn);
            window.history.pushState("", "", url);
        });

        function getPaginationItem(btn) {
            let url = btn.attr('href');
            $.ajax({
                url: url,
                cache: false,
            }).done(function (data) {
                btn.closest('.pagination-content').html(data);
            }).fail(function () {
                alert('Items could not be loaded.');
            });
        }
    });
</script>
    @endpush
@endif
@endif
