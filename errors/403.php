<?php require "../error/getHost/getHost.php"; $host = (new getHost())->host() ?>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>403 ERROR</title>
    </head>
    <style>
        .error {
            padding-top: 150px;
            text-align: center;
            font-family: Arial, Helvetica, sans-serif
        }
        
        h1 {
            font-size: 120px;
            line-height: 1px;
            font-weight: bold;
        }
        
        p {
            line-height: 1px;
            font-size: 30px;
        }
        
        a {
            text-decoration: none;
            background-color: #4169E1;
            padding: 7px 30px;
            border-radius: 10px;
            color: #fff;
        }
    </style>
    <body>
        <div class="error">
            <h1>FORBIDDEN 403</h1>
            <p>You don't have permission to access...</p>
            <a href="<?=$host; ?>">Go Back</a>
        </div>
    </body>
</html>
