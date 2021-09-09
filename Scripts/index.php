<?php include_once("config.php");//include config?>

<!DOCTYPE html>
<html>
<head>
<title>TAKCAR SERVICE</title>
<!-- Optional theme -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- Jquery -->
<script src="js/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>
</head>

<body>
<br>
<div class="container-fluid">

<div class="row">
<h1 class="col-md-12">TAKCAR</h1>
</div>

<div class="row">
<h6 class="col-md-12">Quick and dirty example of importing Traccar Lon, Lat coordinates into the FreeTakServer.</h6>
</div>

<div class="row">
<div class="col-lg-6">
<div class="bs-component">
<div class="form-group">
                    <br>
                    <button id="testFunction" type="button" onclick="testFunction()" class="btn btn-primary">Test Service</button>
                    <p id="data" style="margin:10px;"></p>
                    <br>
                    <!-- <table id="table" class="table table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Result</th>
                          <th scope="col">Time</th>
                          <th scope="col">Data</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td style="color:;"></td>
                          <td></td>
                          <td></td>
                        </tr>
                      </tbody>
                    </table> -->
                </div>
        </div>
</div>



</div>

<script>

setInterval(timerTAKCAR, <?php echo$Interval;?>);

function timerTAKCAR() {

  $.ajax({
        type:'POST',
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