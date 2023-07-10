<div class="modal fade" id="change-order-status-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('admin.change_order_status')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div  id="statuses-list-container">
                        Loading .... .
                    </div>
                </div>
            </div>
    </div>
</div>
@push('child_script')
<script>

    $(function () {
        $('body').on('click', '.change-order-status-btn', function (e) {
            var btn = $(this);
            var url = btn.data('url');
        $('#change-order-status-modal').modal('show');
        $.ajax({
            url: url,
            type: "GET",
            success: function (response) {
                $('#statuses-list-container').html(response.data.view);
                },
             error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    });

    $('body').on('change', '#change-order-status-select', function (e) {
        var value = $(this).val();
    $('.hide-show-status-content').hide();
        if(Number(value) === Number({{\App\Models\Order::STATUS_CANCELED}})){
        $('#cancel-reason-input-container').show();
        }else{
            $('.hide-show-status-content').hide();
        }

        $(".date-time-picker").flatpickr({
            enableTime: true,
            minDate: "today",
            position: 'horizontal',
            dateFormat: "Y-m-d H:i",
            onChange: function(selectedDates, dateStr, instance){
                var url = '{{route('orders.available_shifts')}}?date='+dateStr;
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function (response) {
                        $("#available-times-container").html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });

                }
        });
    });
});
    </script>
@endpush
