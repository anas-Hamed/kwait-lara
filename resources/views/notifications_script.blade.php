<?php
$count = backpack_user()->unreadNotifications()->count();
?>
<li class="nav-item d-md-down-none notifications-menu x">
    <a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#">
        <i class="la la-bell"></i>
        @if($count > 0)
            <span id="counter" class="badge badge-pill badge-danger">{{ $count }}</span>
        @endif
    </a>
    <ul class="dropdown-menu  " style="background-color: #fff;width: 500px">
        @if($count  >  0 )
            <li class="header">
            </li>
        @endif
        <li>
            <div class="card-header text-right py-0 pb-1 px-1">
                <span class="float-right px-3" style="padding-left: 5px !important;">
                    <span onclick="readAll()" class="mx-2" style="font-size: 12px;cursor: pointer;color: #467fd0;font-weight: bold"  >@lang('notifications.mark_as_read')</span>
                </span>
                <h5> @lang('notifications.notifications')</h5>
            </div>
            <ul class="menu px-0 mx-0" style="            max-height: 70vh;
            overflow-y: auto;overflow-x: hidden">
                <li id="loading_item" class="text-center font-weight-bold p-2" >@lang('notifications.loading')</li>
                <li id="no_more_item" class="text-center font-weight-bold p-2" >@lang('notifications.finish')</li>
                <li id="empty_item" class="text-center font-weight-bold p-2" >@lang('notifications.empty_notifications')</li>
            </ul>
        </li>

        <li class="footer text-center py-2">
            <b><a class="mx-2" style="font-size: 12px" href="{{backpack_url("allnotifications")}}">@lang('notifications.show_all')</a></b>
        </li>


    </ul>
</li>
<style>
    html[dir='ltr'] .dropdown-menu{
        left: unset;
        right: 0;
    }
</style>
@push("after_scripts")

    <script>
        function readAll() {
            $.get("<?php echo backpack_url('read_all_notifications');?>").then(data => {
                $("#counter").text(0)
                $("#counter").hide()
                $(".badge-counter").hide()
                for(var i = 0; i < $('.notification-con').length ; i ++){
                    $('.notification-con')[i].style.backgroundColor = '#F7F7EA'
                    if($('.notification-con .markAsRead')[i]){
                        $('.notification-con .markAsRead')[i].style.display = 'none'
                    }
                }
            })
        }
        var loading = false;
        var isEmpty = true;
        var offset = 0;
        var limit = 10;
        $(document).ready(function () {

            var $loadingItem = $("#loading_item");
            var $emptyItem = $("#empty_item");
            var $noMoreItem = $("#no_more_item");
            $emptyItem.hide()
            $.get("<?php echo backpack_url('notifications');?>?offset=" + offset + "&limit=" + limit, function (response) {
                $loadingItem.hide();
                $noMoreItem.hide();
                if (response.length !== 0) {
                    isEmpty = false;
                    offset += limit;
                    loading = false;
                    $loadingItem.before(response);
                }
                if(!loading && isEmpty){
                    $emptyItem.show()
                }
            });

            $(".notifications-menu .menu").on('scroll', function () {
                console.info('test scrol')
                var scrollTop = $(this).scrollTop();
                var delta = (Number($(this).prop('scrollHeight')) - $(this).height());
                delta = delta.toFixed(0)
                if ((scrollTop + 50) >= delta && !loading){
                    console.info('test scrol')
                    loading = true;
                    $loadingItem.show();
                    $.get("<?php echo backpack_url('notifications');?>?offset=" + offset + "&limit=" + limit, function (response) {
                        $loadingItem.hide();
                        if (response.length !== 0) {
                            offset += limit;
                            loading = false;
                            $loadingItem.before(response);
                        }
                        else{
                            $noMoreItem.show();
                        }
                    });
                }
            });
        });
    </script>
@endpush
