<?php include_once("config.php");//include config?>
<!-- http://localhost/takcar_scripts/ -->
<!DOCTYPE html>
<html>
<head>
<title>TAKCAR SERVICE</title>
<link rel="shortcut icon" href="img/favicon.ico">
<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- font-awesome -->
<link rel="stylesheet" href="css/font-awesome.min.css">
<!-- Jquery -->
<script src="js/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>
</head>

<body>
<br>
<div class="container-fluid">

<div class="row">
<h1 class="mx-auto" style="width: 200px;"><span style="color:grey">TAK</span>CAR</h1>
</div>

<hr>

<div class="row">
<div class="mx-auto" style="width: 700px;">
<div class="table-responsive">          
  <table class="table table-condensed table-hover">
    <thead>
      <tr>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td></td>
        <td>Quick and dirty example of importing Traccar Lon, Lat coordinates into the FreeTakServer.</td>
      </tr>
    </tbody>
  </table>
  </div>
</div>
</div>

<hr>

<div class="row">
<div class="mx-auto" style="width: 400px;">
<div class="bs-component">
<div class="form-group">
                    <br>
                    <button id="testFunction" type="button" onclick="testFunction()" class="btn btn-primary btn-block">Test Service</button>
                    <p id="data" style="margin:10px;"></p>
                    <br>
                    <a href="tester.php" target="_blank" class="btn btn-primary btn-block">Testing Page</a>
                </div>
        </div>
</div>

</div>

<script>

setInterval(timerTAKCAR, <?php echo$Interval;?>);

function timerTAKCAR() {

  $.ajax({
        type: 'POST',
        url: 'serverUpdater.php',
        //data: data,
        cache:false,
         dataType:'JSON',
        success: function(data) {
              if(data.result == 0){
                $.each(data.euds , function(key , devices){ // 
                  //alert('Success: '+ data.guid+' at time: '+ data.time);
                  var table = new Device(devices);
                //display in table in console
                console.table(table);            
            });
           } else {
              alert('Failure: Could not get devices');
           }
   },
        });

}

function Device(device) {
  this.device = device;
}

function uuidv4() {
  //Generate GUID
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
  );
}

// function to handle success
function success() {
  //var data = JSON.parse(this.responseText); //parse the string to JSON
  //console.log('Success:', data);
  alert('Success: ' + JSON.stringify(this.responseText));
}

// function to handle error
function error(err) {
  console.log('Request Failed', err); //error details will be in the "err" object
  alert('Failed: ' + JSON.stringify(err));
}

var Authorization = "<?php echo$FTSAPIToken;?>";

function testFunction(){
  var xhr = new XMLHttpRequest(); //invoke a new instance of the XMLHttpRequest
  xhr.onload = success; // call success function if request is successful
  xhr.onerror = error;  // call error function if request failed

  var json = {uid: uuidv4(),
    how: "<?php echo$How;?>",
    name: "<?php echo$Name;?>",
    longitude: <?php echo$Longitude;?>,
    latitude: <?php echo$Latitude;?>,
    role: "<?php echo$Role;?>",
    team: "<?php echo$Team;?>"};//JSON object

  xhr.open('POST', '<?php echo$Protocol;?>://<?php echo$FTSIP;?>:<?php echo$FTSAPIPort;?>/ManagePresence/postPresence', true); // open a GET request
  xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');//application/json;charset=UTF-8
  xhr.setRequestHeader('Authorization', Authorization);//application/json;charset=UTF-8
  xhr.send(JSON.stringify(json)); // send the request to the server.
}

</script>
</body>
</html>