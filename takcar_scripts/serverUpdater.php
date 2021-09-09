<?php
//include config
include_once("config.php");

//get the session cookie
$session = json_encode(get_TraccarSession("$Protocol://$TraccarIP:$TraccarPort/api/session?token=$TraccarAPIToken"));

$api_result = json_decode($session, true);
$JSESSIONID = $api_result['JSESSIONID'];

//get the Traccar positions
$positions = get_TraccarPosition("$Protocol://$TraccarIP:$TraccarPort/api/positions?token=$TraccarAPIToken", $JSESSIONID);

$json = json_decode($positions);

//loop through the positions
foreach($json as $key => $item){

//forward the positions to the FTS API endpoint
$result = get_FTSAPI($item->id, $item->latitude, $item->longitude, $Protocol, $FTSIP, $FTSAPIPort, $FTSAPIToken);

$markers[] = json_encode(
    array(
        'result' => 0,
        'guid' => $result,
        'time' => date("Y-m-d h:i:sa")
    )
);
}

$JSON = json_encode(
    array(
        'result' => 0,
        'message' => $markers,
        'time' => date("Y-m-d h:i:sa"),
    )
);
//return the JSON results
echo '{"result":0,"euds":['.$JSON.']}';
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
  
  // Open the file using the HTTP headers set above
  $file = file_get_contents($url, false, stream_context_create($opts));
  return $file;
}

function get_FTSAPI($id, $latitude, $longitude, $Protocol, $FTSIP, $FTSAPIPort, $FTSAPIToken) {

    //generate GUID
    $guid = vsprintf('%s%s-%s-4000-8%.3s-%s%s%s0',str_split(dechex( microtime(true) * 1000 ) . bin2hex( random_bytes(8) ),4));

    //Json data to post
    $postData = array(
        "uid" => $guid,
        "how" => "nonCoT",
        "name" => "ID_".$id,
        "longitude" => $longitude,
        "latitude" => $latitude,
        "role" => "Team Member",
        "team" => "Red"
    );

    $ch = curl_init("$Protocol://$FTSIP:$FTSAPIPort/ManagePresence/postPresence");
    //set cURL options
    curl_setopt(
        $ch, 
        CURLOPT_HTTPHEADER, 
        array(
            'Content-Type: application/json',
            'Authorization: '.$FTSAPIToken
        )
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    //execute cURL call
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
?>