<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Appeal Lab') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

    </head>
    <style>

        button, optgroup, select, textarea {
            margin: 0;
            height: 58px !important;
        }
        .form-control{
            margin: 0;
            height: 58px !important;
        }

        /*.p{*/
        /*    color: rgb(110, 110, 110)!important;*/
        /*}*/
        .support-text {
            display: flex;
        }

        .mail-to a {
            text-decoration: none !important;
            color: rgb(22, 22, 22);
        }

        /*.fa {*/
        /*    color: rgb(102, 102, 102);*/
        /*}*/

        /*.info-h1 {*/
        /*    font-size: 22px;*/
        /*    font-weight: 400;*/
        /*}*/

        /*.selection-input {*/
        /*    margin: 0px 10px;*/
        /*}*/

        /*#myDIV {*/
        /*    display: none;*/
        /*}*/

        /*.coupon-text {*/
        /*    color: rgb(2, 147, 204);*/
        /*    font-size: 15px;*/
        /*}*/

        /*.coupon-submit {*/
        /*    width: 200px;*/
        /*    font-size: 14px;*/
        /*    padding: 8px 10px;*/
        /*    height: auto;*/
        /*    background-color: #999;*/
        /*    color: #fff !important;*/
        /*    border: none;*/
        /*    margin-left: 10px;*/
        /*}*/

        /*.payment-para {*/
        /*    font-size: 15px;*/
        /*}*/
        .btn-form-submit{
            font-size: 22px;
            padding: 19px 25px;
            width: 100%;
            font-weight: 700;
            color: #fff;
            border: none;
            justify-content: center;
            /*box-shadow: 1px 0px 15px rgb(143, 143, 143);*/
            background-color: #03c6ad;
        }
        .last-sec-heading{
            color:rgb(56, 56, 56);
            background-color: rgb(241, 241, 241);
            text-align: center;
            padding: 10px 0px;
        }
        .three-column{
            padding: 0px 25px;
        }
        .three-col-text{
            color: rgb(168, 168, 168);
        }
        .card-labels{
            font-size: 15px;
            color: rgb(121, 121, 121);
        }
        .placeholder{
            font-size: 15px;
            color: rgb(224, 58, 58);
        }
    </style>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
    </body>
</html>
