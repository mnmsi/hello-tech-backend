<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{\Illuminate\Support\Env::get('APP_NAME')}}</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="{{asset('assets/css/login.css')}}" rel="stylesheet">
</head>
<body class="login-form-body">
<div class="login-form">
    <form action="{{route('admin-login-check')}}" method="post">
        @csrf
        @method('POST')
        <div class="avatar">
            <img src="{{asset('img/app-logo.svg')}}" alt="logo" class="img-fluid"/>
        </div>
        <h4 class="modal-title">Welcome Back!</h4>
        <hr/>
{{--        <div class="form-group">--}}
{{--            <label for="loginDropdown">--}}
{{--                Select login method:--}}
{{--            </label>--}}
{{--            <div style="display: flex; align-items: center; gap: 20px;">--}}
{{--                <label style="display: flex;align-items: center;gap: 10px;cursor: pointer;">--}}
{{--                    <input type="radio" name="login_type" style="margin: 0;cursor: pointer;" value="email" checked>--}}
{{--                    Email--}}
{{--                </label>--}}
{{--                <label style="display: flex;align-items: center;gap: 10px;cursor: pointer;">--}}
{{--                    <input type="radio" name="login_type" value="phone" style="margin: 0;cursor: pointer;">--}}
{{--                    Phone--}}
{{--                </label>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="form-group" id="phone_login" style="display: none;">--}}
{{--            <label for="phone">Enter your number:</label>--}}
{{--            <input class="form-control" name="phone" placeholder="Enter number" type="text" id="phone">--}}
{{--        </div>--}}

{{--        <div class="form-group" id="email_login" style="display: none;">--}}
{{--            <label for="email">Enter your email:</label>--}}
{{--            <input class="form-control" placeholder="Enter email" name="email"  type="email" id="email">--}}
{{--        </div>--}}
        <div class="form-group">
            <label for="email">Enter your email:</label>
            <input class="form-control" placeholder="Enter email" value="{{old('email')}}" name="email"  type="email" id="email">
        </div>
        <div class="form-group">
            <label for="password">
                Enter Password:
            </label>
            <input type="password" id="password" value="{{old('password')}}" name="password" class="form-control"
                   placeholder="Enter Password"
                   required="required">
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="submit" class="btn btn-primary btn-block btn-lg" value="Login">
    </form>
</div>
</body>
<script>
    // $(document).ready(function () {
    //     const phoneLoginDiv = document.getElementById('phone_login');
    //     const emailLoginDiv = document.getElementById('email_login');
    //
    //     emailLoginDiv.style.display = 'block';
    //
    //     document.querySelectorAll('input[name="login_type"]').forEach((radio) => {
    //         radio.addEventListener('change', function() {
    //             if (this.value === 'phone') {
    //                 phoneLoginDiv.style.display = 'block';
    //                 emailLoginDiv.style.display = 'none';
    //             } else if (this.value === 'email') {
    //                 phoneLoginDiv.style.display = 'none';
    //                 emailLoginDiv.style.display = 'block';
    //             }
    //         });
    //     });
    // });
</script>
</html>




