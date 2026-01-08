<div class="con">
    <div style="width: 100%;text-align: center">
        <img width="200" src="{{asset('images/logo.png')}}" alt="دليل الكويت">
    </div>
    <h2 style="text-align: center">استعادة كلمة المرور</h2>
    <div class="content-card">
        <p>لقد طلبت تغيير كلمة مرور حسابك في دليل الكويت ، لهذا أنت تتلقى هذا البريد</p>
        <a href="{{$url}}">استعادة كلمة المرور</a>
        <div style="margin-top: 32px">
            <small>يمكنك استخدام الرمز التالي في التطبيق</small>
        </div>
        <code>{{$token}}</code>
        <div style="margin-top: 32px">
            <small>
                إذا لم تقم بطلب استعادة كلمة المرور يمكنك تجاهل هذا البريد
            </small>
        </div>

    </div>
</div>


<style>
    .con{
        padding-top: 24px;
        background-color: #ececec;
        min-height: 600px;
    }
    .content-card{
        margin: auto 16px;
        padding: 48px;
        border-radius: 0.5rem;
        background-color: white;
        text-align: center;
        font-size: 18px;
        border: 1px solid #ddd;
    }
    a{
        all: unset;
        background-color: #0b4d75;
        padding: 8px 16px;
        color: white;
        margin: 16px;
        cursor: pointer;
        border-radius: 0.2rem;
        font-size: 13px;
    }
</style>
