<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" />
        <!--favicon icon-->
        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
        <title> Portal </title>
        <style type="text/css">
            table, tr, td, tbody {
                max-width:100%;
                display:block;
            }
            @media screen and (max-width : 767px) {
                .nps-container {
                    width: 100% !important;
                }
                .nps-container h3 {
                    font-size: 15px !important;
                    max-width: 90% !important;
                }
                .nps-td {
                    display: block !important;
                    width: 100% !important;
                }
                .nps-table {
                    width: 100% !important;
                }
            }
        </style>
    </head>
    <body style="font-family: 'Montserrat', sans-serif;color: #333;">
        <div class="container-fluid">
            Dear User,
            <br>
            <br>
            Please <a href="{{ $details['body'] }}" target="_blank" rel="noopener noreferrer">Click here</a> to verify your Email address and complete your registration.
            
        <div style="height: 30px;clear: both;"></div>
    </body>
</html>