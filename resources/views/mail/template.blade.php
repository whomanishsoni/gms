<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #333;
        }

        .note-card {
            /* border: 1px solid #ddd; */
            /* border-radius: 8px; */
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafafa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .note-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .note-text {
            margin-bottom: 10px;
            color: #555;
        }

        .attachment-info {
            font-size: 14px;
            color: #888;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer a {
            text-decoration: none;
        }

        .footer button {
            background-color: #EA6B00;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: -webkit-fill-available;
        }

        .footer button:hover {
            background-color: #333;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <?php
            
            $imagePath = public_path('general_setting/' . getLogoSystem());
            $imageData = base64_encode(file_get_contents($imagePath));
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            $imageSrc = "data:image/{$imageType};base64,{$imageData}";
            ?>
            <img src="{{ $imageSrc }}" alt="Logo" width="40%">
            <h2>{{ getNameSystem() }}</h2>
            <h4>{{ $notification_label }}</h4>
        </div>

        <div class="note-card">
            <div><?php echo $email_content; ?></div>
        </div>

        <div class="footer">
            <a href="{!! $redirect_url !!}"><button>{{ trans('message.Open In Garage') }}</button></a>
        </div>
    </div>
</body>

</html>