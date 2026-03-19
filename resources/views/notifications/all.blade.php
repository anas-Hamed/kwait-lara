@extends(backpack_view('blank'))


@section('content')

    <div class="container-fluid">
        <h2>
            <span class="text-capitalize">  @lang('notifications.show_notifications')</span>
        </h2>
        <div class="row justify-content-center align-items-center py-3">
            <div class="col-md-12 border-bottom">
                <button class="btn btn-link float-right mark-all-as-read mt-4">@lang('notifications.mark_as_read')</button>
            </div>
            <div class=" col-12 col-md-8 mt-1 notifications-wrapper py-2 rounded overflow-auto "
                 style="background-color: #fff;height: 80vh">
                <div class="box box-info ">
                </div>
                <ul class="menu px-0 mx-0" style="            max-height: 70vh;
            overflow-y: auto;overflow-x: hidden">
                <li id="loading_item2" class="text-center font-weight-bold p-2" >@lang('notifications.loading')</li>
                <li id="empty_item2" class="text-center font-weight-bold p-2" >@lang('notifications.empty_notifications')</li>
                </ul>
            </div>
        </div>
    </div>
    @push('after_styles')
        <style>
            /*.box-info div.container{*/
            /*    background-color: #fff !important;*/
            /*}*/
            .app-body {
                background: #fff !important;
            }
        </style>
    @endpush
@endsection
@push("after_scripts")
    <script>

        var loading2 = false;
        var isEmpty2 = true;
        var offset2 = 0;
        var limit2 = 10;
        var type = '';

        $(document).ready(function () {
            var $loadingItem = $("#loading_item2");
            var $emptyItem = $("#empty_item2");
            $emptyItem.hide()
            getNotifications()

            $('.notifications-wrapper').on('scroll', function () {
                var scrollTop = $(this).scrollTop();
                 scrollTop = scrollTop.toFixed(0);
                var delta = (Number($(this).prop('scrollHeight')) - $(this).height());
                delta = delta.toFixed(0)

                if ((scrollTop ) >= delta && !loading2) {
                    loading2 = true;

                    getNotifications()
                }
            });

            function getNotifications() {

                $loadingItem.show();
                $emptyItem.hide()
                $.get(`/admin/notifications?offset=${offset2}&limit=${limit2}&type=${type}`, function (response) {
                    $loadingItem.hide();
                    if (response.length !== 0) {
                        offset2 += limit2;
                        isEmpty2 = false;
                        loading2 = false;
                        $('.box-info').append(response);
                    }
                    if (!loading2 && isEmpty2) {
                        $emptyItem.show()
                    }
                });
            }

            $('.notifications-type').on('change', data => {
                resetVars();
                type = data.target.value
                getNotifications();
            })

            function resetVars() {
                loading2 = false;
                isEmpty2 = true;
                offset2 = 0;
                limit2 = 10;
                type = ''
                $('.box-info').html('');

                $loadingItem.show();
            }
            $('.mark-all-as-read').on('click',function () {
                let allCount = Number($("#counter").html())
                let notificationsCount = allCount;
                if(type){
                    notificationsCount = Number($(`.${type} .badge-counter`).html());
                }
                $.get(`/admin/read_all_notifications?type=${type}`).then(data => {

                    if(allCount == notificationsCount){
                        $("#counter").text(0);
                        $("#counter").hide();
                        $(".badge-counter").hide();

                        for(var i = 0; i < $('.notification-con').length ; i ++){
                            $('.notification-con')[i].style.backgroundColor = '#F7F7EA'
                            if($('.notification-con .markAsRead')[i]){
                                $('.notification-con .markAsRead')[i].style.display = 'none'
                            }
                        }
                    }
                    else{
                        $("#counter").text(allCount - notificationsCount);
                        $(`.${type} .badge-counter`).hide();
                        for(let i = 0; i < $(`.${type}-con`).length ; i ++){
                            $(`.${type}-con`)[i].style.backgroundColor = '#F7F7EA'
                            if($(`.${type}-con .markAsRead`)[i]){
                                $(`.${type}-con .markAsRead`)[i].style.display = 'none'
                            }
                        }
                    }

                })
            })
        });
    </script>
@endpush
