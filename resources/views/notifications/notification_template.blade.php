<div dir="rtl"
     class="container p-1 px-3  text-right my-1 notification-con "
     style="cursor: pointer;{{$notification->read_at == null ? "background-color: #F0F3F9":"background-color: #F7F7EA"}}">
    <div class="row mx-0  ">
        @if(isset($notification->data["image"]) && $notification->data["image"] != null)
            <div class='col-2' onclick="location.href='/admin/notification/{{$notification->id}}">
            <img
                style="width : 65px;height:65px;border-radius:50%"
                src='{{$notification->data['image']}}' />
            </div>
        @endif
        <div class="col" onclick="location.href='/admin/notification/{{$notification->id}}'">
            <h6 class="py-1 my-0"><b>{{ $notification->data["title"] }}</b></h6>
            <p class="p-1 my-0">
                {{ $notification->data["body"] }}
            </p>
            <?php echo $notification->data['icon'] ?? "<i class='fa fa-cube  text-info'></i>" ?>
            <small>{{$notification->created_at->diffForHumans()}}</small>
        </div>
        <div class="col-2 justify-content-center align-items-center d-flex">
            @if($notification->read_at == null)
                <div title='تمييز كمقروء'
                     id="<?php echo $notification->id?>"
                     onclick='{$.get("/admin/read_notification/<?php echo $notification->id?>").then(data => {
                         if(data){
                         $(this).hide()
                         $(this).parents()[2].style.backgroundColor = "#F7F7EA"
                         $("#counter").text(Number($("#counter").text()) - 1)
                         if($("#counter").text()  == 0){
                         $("#counter").hide()
                         }
                         }
                         })}' class='p-3 markAsRead'>
                    <div style='width: 10px;height: 10px;border-radius: 10px;background-color: #dadada'>

                    </div>
                </div>
            @endif

        </div>
    </div>


</div>

{{--</a>--}}

