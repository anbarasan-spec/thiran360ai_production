<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiran360AI - Login</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

    <style>
        /* Blue gradient background */
        .login-block{
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            width:100%;
            padding:50px 0;
        }

        /* Container */
        .container{
            background:#fff;
            border-radius:10px;
            box-shadow:15px 20px 0 rgba(0,0,0,0.1);
        }

        /* Left login form */
        .login-sec{
            padding:50px 30px;
            position:relative;
        }
        .login-sec h2{
            margin-bottom:30px;
            font-weight:800;
            font-size:30px;
            color:#2575fc;
        }
        .login-sec h2:after{
            content:"";
            width:100px;
            height:5px;
            background:#6a11cb;
            display:block;
            margin-top:20px;
            border-radius:3px;
            margin-left:auto;
            margin-right:auto;
        }
        .login-sec .copy-text{
            position:absolute;
            width:80%;
            bottom:20px;
            font-size:13px;
            text-align:center;
        }
        .login-sec .copy-text i{color:#2575fc;}
        .login-sec .copy-text a{color:#2575fc;}
        .btn-login{
            background: #2575fc;
            color:#fff;
            font-weight:600;
        }

        /* Right static image */
        .banner-sec{
            min-height:500px;
            border-radius:0 10px 10px 0;
            padding:0;
            background:url('https://tse1.mm.bing.net/th/id/OIP.PZILx14ggGjFB-sftkBvnQHaBV?pid=Api&P=0&h=180') no-repeat center center;
            background-size:contain;
        }

        @media (max-width:768px){
            .banner-sec{display:none;}
        }
    </style>
</head>
<body>
<section class="login-block">
    <div class="container">
        <div class="row">
            <!-- Login Form -->
            <div class="col-md-4 login-sec">
                <h2 class="text-center">Thiran360AI</h2>

                {{-- Show login error --}}
                @if($errors->has('email'))
                    <div class="alert alert-danger py-2">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="text-uppercase">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="you@example.com">
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="text-uppercase">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                        @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>

                    <button type="submit" class="btn btn-login float-right">Sign In</button>
                </form>

                <div class="copy-text">Created with <i class="fa fa-heart"></i> by <a href="http://grafreez.com">Thiran360AI</a></div>
            </div>

            <!-- Right static image -->
            <div class="col-md-8 banner-sec"></div>
        </div>
    </div>
</section>
</body>
</html>
