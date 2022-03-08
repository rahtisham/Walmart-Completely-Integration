<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .wrapper {
        width: 400px;
        height: 400px;
        background: #FC5B3F;
        margin: 10px auto;
        border-radius: 50%;
        overflow: hidden;
        position: relative;
        transform: rotate(108deg);
    }
    .wrapper .d1 {
        width: 800px;
        height: 800px;
        position: absolute;
        top: -200px;
        left: -200px;
        transform: rotate(0deg);
    }
    .wrapper .d1 div {
        width: 800px;
        height: 800px;
    }
    .wrapper .d1 div:after {
        content: '';
        width: 0;
        height: 0;
        display: block;
        border: solid transparent;
        border-width: 400px;
        border-top-color: #FC5B3F;
        position: relative;
        transform: scaleX(-3.07768);
    }
    .wrapper .d1 div span {
        display: block;
        width: 100%;
        position: absolute;
        left: 0;
        top: 34%;
        font-size: 12px;
        text-align: center;
        z-index: 100;
        color: #fff;
        transform: rotate(-108deg);
    }
    .wrapper .d2 {
        width: 800px;
        height: 800px;
        position: absolute;
        top: -200px;
        left: -200px;
        transform: rotate(147.6deg);
    }
    .wrapper .d2 div {
        width: 800px;
        height: 800px;
    }
    .wrapper .d2 div:after {
        content: '';
        width: 0;
        height: 0;
        display: block;
        border: solid transparent;
        border-width: 400px;
        border-top-color: #FCB03C;
        position: relative;
        transform: scaleX(0.82727);
    }
    .wrapper .d2 div span {
        display: block;
        width: 100%;
        position: absolute;
        left: 0;
        top: 34%;
        font-size: 12px;
        text-align: center;
        z-index: 100;
        color: #fff;
        transform: rotate(-255.6deg);
    }
    .wrapper .d3 {
        width: 800px;
        height: 800px;
        position: absolute;
        top: -200px;
        left: -200px;
        transform: rotate(201.6deg);
    }
    .wrapper .d3 div {
        width: 800px;
        height: 800px;
    }
    .wrapper .d3 div:after {
        content: '';
        width: 0;
        height: 0;
        display: block;
        border: solid transparent;
        border-width: 400px;
        border-top-color: #6FB07F;
        position: relative;
        transform: scaleX(0.25676);
    }
    .wrapper .d3 div span {
        display: block;
        width: 100%;
        position: absolute;
        left: 0;
        top: 34%;
        font-size: 12px;
        text-align: center;
        z-index: 100;
        color: #fff;
        transform: rotate(-309.6deg);
    }
    .wrapper .d4 {
        width: 800px;
        height: 800px;
        position: absolute;
        top: -200px;
        left: -200px;
        transform: rotate(228.6deg);
    }
    .wrapper .d4 div {
        width: 800px;
        height: 800px;
    }
    .wrapper .d4 div:after {
        content: '';
        width: 0;
        height: 0;
        display: block;
        border: solid transparent;
        border-width: 400px;
        border-top-color: #068587;
        position: relative;
        transform: scaleX(0.22353);
    }
    .wrapper .d4 div span {
        display: block;
        width: 100%;
        position: absolute;
        left: 0;
        top: 34%;
        font-size: 12px;
        text-align: center;
        z-index: 100;
        color: #fff;
        transform: rotate(-336.6deg);
    }
    .wrapper .d5 {
        width: 800px;
        height: 800px;
        position: absolute;
        top: -200px;
        left: -200px;
        transform: rotate(246.6deg);
    }
    .wrapper .d5 div {
        width: 800px;
        height: 800px;
    }
    .wrapper .d5 div:after {
        content: '';
        width: 0;
        height: 0;
        display: block;
        border: solid transparent;
        border-width: 400px;
        border-top-color: #1A4F63;
        position: relative;
        transform: scaleX(0.09453);
    }
    .wrapper .d5 div span {
        display: block;
        width: 100%;
        position: absolute;
        left: 0;
        top: 34%;
        font-size: 12px;
        text-align: center;
        z-index: 100;
        color: #fff;
        transform: rotate(-354.6deg);
    }
</style>
<body>
<div class="wrapper">
    <div class="d1"><div><span>{{ $registredNotification['name'] }}</span></div></div>
    <div class="d2"><div><span>22%</span></div></div>
    <div class="d3"><div><span>8%</span></div></div>
    <div class="d4"><div><span>7%</span></div></div>
    <div class="d5"><div><span>3%</span></div></div>
</div>
</body>
</html>
