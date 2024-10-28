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
            .image {
                position: absolute;
                width: 155;
                height: 155;
                margin-top: -39px; 
                margin-left: -30px;
            }
        </style>
    </head>

    <body>
        <img class="image" src="<?php echo $qrCodeImage; ?>" alt="QR Code"/>
    </body>
</html>

