<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style>
            html * {
                font-family:Arial, Helvetica, sans-serif;
                margin: 0;
                padding: 0;
            }
            body {
                margin-top: 0px;
                margin-left: 40px;
                margin-right: 40px;
            }
            .code {
                position: absolute;
                top: -30; /* 4 cm from the top */
                left: 10; /* 7 cm from the left */
                font-size: 10pt; /* Adjust font size as needed */
                width: 170px;
                /* background-color: red; */
            }
            .received {
                position: absolute;
                top: -17; /* 4 cm from the top */
                left: 10; /* 7 cm from the left */
                font-size: 7pt; /* Adjust font size as needed */
                width: 170px;
            }
            .due {
                position: absolute;
                top: -9; /* 4 cm from the top */
                left: 10; /* 7 cm from the left */
                font-size: 7pt; /* Adjust font size as needed */
                width: 170px;
            }
            .names {
                position: absolute;
                top: 10; /* 4 cm from the top */
                left: -15; /* 7 cm from the left */
                font-size: 7.5pt; /* Adjust font size as needed */
            }
            .breakline {
                position: absolute;
                width: 210px; /* or any desired width */
                top: 5; /* 4 cm from the top */
                left: -21; /* 7 cm from the left */
                border-top: 1px solid black; /* Thickness and color of the line */
            }
            .image {
                position: absolute;
                width: 30;
                height: 30;
                margin-top: -39px; 
                margin-left: -30px;
            }
        </style>
    </head>

    <body>
        <img class="image" src="<?php echo $qrCodeImage; ?>" alt="QR Code"/>
        <div class="code">{{$sample_code}} {{strlen($sample_name)}} @if (strlen($sample_name) <= 10)<span style="font-size: 10px;">({{$sample_name}})</span> @endif</div>
        <div class="received">Received: {{$created_at}}</div>
        <div class="due">Due at: {{$due_at}}</div>
        <div class="breakline"></div>
        <div class="names">
            <ul>
                @foreach($testnames as $index=>$name)
                <li>{{$name}}</li>
                @endforeach
            </ul>
        </div>
    </body>
</html>

