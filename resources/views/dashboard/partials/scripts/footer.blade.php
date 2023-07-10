<script src="{{asset('dashboard/dist/assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/scripts.bundle.js')}}"></script>

<script src="{{asset('dashboard/dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>

<script src="{{asset('dashboard/dist/assets/js/custom/widgets.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/custom/apps/chat/chat.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/custom/modals/create-app.js')}}"></script>
<script src="{{asset('dashboard/dist/assets/js/custom/modals/upgrade-plan.js')}}"></script>

<script>

    $(function (){
        $('body').tooltip({selector: '[data-bs-toggle="tooltip"]'});
        $('body').on('click', '.load-ajax-modal', function (){
            $("#loadAjaxDataModal").modal('show');
            var url = $(this).data('url');
            $.ajax({
                url: url,
                cache: false
            }).done(function (data) {
                $('#loadAjaxDataModalContent').html(data.content);
            }).fail(function () {
                alert('Items could not be loaded.');
            });
        })
    })
</script>
