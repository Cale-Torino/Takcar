<?php
//include config
include_once("config.php");

//get the session cookie
$session = json_encode(get_TraccarSession("$TraccarProtocol://$TraccarIP:$TraccarPort/api/session?token=$TraccarAPIToken"));

$api_result = json_decode($session, true);
$JSESSIONID = $api_result['JSESSIONID'];

//get the Traccar positions
$positions = get_TraccarPosition("$TraccarProtocol://$TraccarIP:$TraccarPort/api/positions?token=$TraccarAPIToken", $JSESSIONID);

$json = json_decode($positions);

//loop through the positions
$devices = array();
foreach($json as $key => $item){

//forward the positions to the FTS API endpoint
//$result = get_FTSAPI($item->id, $item->latitude, $item->longitude, $FTSProtocol, $FTSIP, $FTSAPIPort, $FTSAPIToken);
if (in_array($item->id, $devices)) {
    //forward the positions to the FTS TCP port
    $result = get_FTSSOCKETPING($item->id, $item->latitude, $item->longitude, $FTSIP, $FTSPort);
}else {
    //forward the positions to the FTS TCP port
    $result = get_FTSSOCKET($item->id, $item->latitude, $item->longitude, $FTSIP, $FTSPort);
    $devices[] = $item->id;
}

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

function get_FTSAPI($id, $latitude, $longitude, $FTSProtocol, $FTSIP, $FTSAPIPort, $FTSAPIToken) {

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

    $ch = curl_init("$FTSProtocol://$FTSIP:$FTSAPIPort/ManagePresence/postPresence");
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

//https://www.latlong.net/
function get_FTSSOCKET($id, $latitude, $longitude, $FTSIP, $FTSPort) {
        //generate GUID
        //$guid = vsprintf('%s%s40-%s%s3-%s%s43-%s1',str_split(dechex( microtime(true) * 1000 ) . bin2hex( random_bytes(8) ),4));
        $data = '<?xml version="1.0" encoding="utf-8" standalone="yes"?><event version="2.0" uid="S-1-5-21-1568504889-667903775-1938598950-'.$id.'" type="a-f-G-U-C-I" time="'.date("Y-m-d\TH:i:s.000\Z",time()).'" start="'.date("Y-m-d\TH:i:s.000\Z",time()).'" stale="'.date("Y-m-d\TH:i:s.000\Z", strtotime('+1 minutes', time())).'" how="h-g-i-g-o"><point lat="'.$latitude.'" lon="'.$longitude.'" hae="0" ce="9999999" le="9999999"/><detail><takv version="4.1.0.231" platform="WinTAK-CIV" os="Microsoft Windows 10 Pro" device="System manufacturer System Product Name"/><contact callsign="callsign_'.$id.'" endpoint="*:-1:stcp"/><uid Droid="Droid_'.$id.'"/><__group name="Red" role="Team Member"/><status battery="100"/><track course="0.00000000" speed="0.00000000"/></detail></event>';
        $SocketConnection = fsockopen($FTSIP, $FTSPort, $errno, $errstr);
        if (!$SocketConnection) {
            return "Failed_no_Socket_Connection";
        } else {
             fwrite($SocketConnection, $data);//PHP_EOL
             if(!feof($SocketConnection))
             {
                return "SuccessSent";
             }else
             {
                return "Failed_NotSent";
             }
             fclose($SocketConnection);
        }
        //sleep(0.5);
    return "Done";
}

function get_FTSSOCKETPING($id, $latitude, $longitude, $FTSIP, $FTSPort) {
        //generate GUID
        //S-1-5-21-1568504889-667903775-1938598950-1001-ping
        //$guid = vsprintf('%s%s40-%s%s3-%s%s43-%s1',str_split(dechex( microtime(true) * 1000 ) . bin2hex( random_bytes(8) ),4));
        $data = '<?xml version="1.0"?><event version="2.0" uid="S-1-5-21-1568504889-667903775-1938598950-'.$id.'-ping" type="t-x-c-t" time="'.date("Y-m-d\TH:i:s.000\Z",time()).'" start="'.date("Y-m-d\TH:i:s.000\Z",time()).'" stale="'.date("Y-m-d\TH:i:s.000\Z", strtotime('+1 minutes', time())).'" how="m-g"><point lat="'.$latitude.'" lon="'.$longitude.'" hae="0.00000000" ce="9999999" le="9999999"/><detail/></event>';
        $SocketConnection = fsockopen($FTSIP, $FTSPort, $errno, $errstr);
        if (!$SocketConnection) {
            return "Failed_no_Socket_Connection";
        } else {
             fwrite($SocketConnection, $data);//PHP_EOL
             if(!feof($SocketConnection))
             {
                return "SuccessSent";
             }else
             {
                return "Failed_NotSent";
             }
             fclose($SocketConnection);
        }
        //sleep(0.5);
    return "Done";
}
?>