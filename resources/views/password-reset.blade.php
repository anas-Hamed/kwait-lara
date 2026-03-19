<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
    <style>
        .pass_show{position: relative}

        .pass_show .ptxt {

            position: absolute;

            top: 50%;

            right: 10px;

            z-index: 1;

            color: #f36c01;

            margin-top: -10px;

            cursor: pointer;

            transition: .3s ease all;

        }

        .pass_show .ptxt:hover{color: #333333;}
    </style>
    <script>
        $(document).ready(function(){
            $('.pass_show').append('<span class="ptxt">Show</span>');
        });


        $(document).on('click','.pass_show .ptxt', function(){

            $(this).text($(this).text() == "Show" ? "Hide" : "Show");

            $(this).prev().attr('type', function(index, attr){return attr == 'password' ? 'text' : 'password'; });

        });
    </script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <form action="{{ url('api/password/reset') }}" method="post">

                <input type="hidden" name="token" value="{{$token}}">
{{--                <input type="hidden" name="email" value="{{$email}}">--}}
{{--                <input type="hidden" name="user_id" value="{{$user_id}}">--}}
                <label>Email</label>
                <div class="form-group ">
                    <input type="email" name="email" value="" class="form-control" placeholder="Your Email">
                </div>
                <label>New Password</label>
                <div class="form-group pass_show">
                    <input type="password" value="" name="password" class="form-control" placeholder="New Password">
                </div>
                <label>Confirm Password</label>
                <div class="form-group pass_show">
                    <input type="password" name="password_confirmation" value=""  class="form-control" placeholder="Confirm Password">
                </div>

                <input type="submit" value="RESET">
            </form>
        </div>
    </div>
</div>
</body>
