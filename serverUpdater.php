<?php
// set headers
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

//get the session cookie
$session = json_encode(get_TraccarSession("http://127.0.0.1:8082/api/session?token=hdbtrsy6576hyw84567cSiOuqqhA"));

$api_result = json_decode($session, true);
$JSESSIONID = $api_result['JSESSIONID'];

//get the Traccar positions
$positions = get_TraccarPosition("http://127.0.0.1:8082/api/positions?token=hdbtrsy6576hyw84567cSiOuqqhA",$JSESSIONID);

$json = json_decode($positions);

//loop through the positions
foreach($json as $key => $item){
//     $markers[] = json_encode(
//     array(
//         'id' => $item->id,
//         'latitude' => $item->latitude,
//         'longitude' => $item->longitude,
//         'batteryLevel' => $item->attributes->battery,
//         'Time' => date("Y-m-d h:i:sa")
//     )
// );

//forward the positions to the FTS API endpoint
$result = get_FTSAPI($item->id, $item->latitude, $item->longitude);

$markers[] = json_encode(
    array(
        'result' => 0,
        'guid' => $result,
        'Time' => date("Y-m-d h:i:sa")
    )
);
}

$JSON = json_encode(
    array(
        'result' => 0,
        'message' => $markers,
        'Time' => date("Y-m-d h:i:sa"),
    )
);
//return the JSON results
echo "data: {\"result\": 0,\"eud\":[{$JSON}]}\n\n";
flush();
die();


function get_TraccarSession($url) {
    file_get_contents($url);

    $cookies = array();
    //search for all cookies and return; we only need `JSESSIONID`
    foreach ($http_response_header as $hdr) {
        if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
            parse_str($matches[1], $tmp);
            $cookies += $tmp;
        }
    }
    return $cookies;
}

function get_TraccarPosition($url,$JSESSIONID) {
//set the headers and cookie
$opts = array(
    'http'=>array(
      'method'=>"GET",
      'header'=>"Accept-language: en\r\n" .
                "Cookie: JSESSIONID=$JSESSIONID\r\n"
    )
  );
  
  $context = stream_context_create($opts);
  
  // Open the file using the HTTP headers set above
  $file = file_get_contents($url, false, $context);
  return $file;
}

function get_FTSAPI($id, $latitude, $longitude) {

    //http://127.0.0.1:19023/ManagePresence/putPresence
    //http://127.0.0.1:19023/ManagePresence/postPresence
    $url = "http://127.0.0.1:19023/ManagePresence/postPresence";
    //FTS Bearer token
    $token = "Bearer token";
    //generate GUID
    $guid = vsprintf('%s%s-%s-4000-8%.3s-%s%s%s0',str_split(dechex( microtime(true) * 1000 ) . bin2hex( random_bytes(8) ),4));

    //Json data to post
    $postData = array(
        "uid" => $guid,
        "how" => "nonCoT",
        "name" => "id".$id,
        "longitude" => $longitude,
        "latitude" => $latitude,
        "role" => "Team Member",
        "team" => "Red"
    );

    // for sending data as json type
    $fields = json_encode($postData);

    $ch = curl_init($url);
    //set cURL options
    curl_setopt(
        $ch, 
        CURLOPT_HTTPHEADER, 
        array(
            'Content-Type: application/json',
            'Authorization: '.$token
        )
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    //execute cURL call
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
?>