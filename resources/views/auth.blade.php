<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#00aeea">
    <meta name="msapplication-TileColor" content="#00aeea">
    <meta name="theme-color" content="#00aeea">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env("APP_NAME") }}</title>
    <link href="{{ asset("css/style.css") }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="text-center mb-4">
                        <a href="/"><img src="{{ asset("images/logo-full-colors.svg") }}" style="max-width: 260px;" class="img-fluid" alt=""></a>
                    </div>
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4 text-white">Access Denied</h4>
                                    <form action="{{ route("auth") }}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <input type="password" class="form-control" name="password" placeholder="Password">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-white text-primary btn-block">Enter</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset("vendor/global/global.min.js") }}"></script>
    <script src="{{ asset("vendor/deznav/deznav.min.js") }}"></script>
    <script src="{{ asset("js/custom.min.js") }}"></script>
    <script src="{{ asset("js/deznav-init.js") }}"></script>
</body>
</html>
