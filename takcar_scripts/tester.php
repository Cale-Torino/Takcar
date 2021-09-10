<?php include_once("config.php");//include config?>
<!-- http://localhost/takcar_scripts/tester.php -->
<!DOCTYPE html>
<html>
<head>
<title>TAKCAR TESTER</title>
<link rel="shortcut icon" href="img/favicon.ico">
<!-- Optional theme https://bootswatch.com/darkly/ -->
<link rel="stylesheet" href="css/bootstrap.css">
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
<h1 class="mx-auto" style="width: 400px;">TAKCAR <span style="color:red">TESTER</span></h1>
</div>

<hr>

<div class="row">
<div class="mx-auto" style="width: 800px;">
<div class="table-responsive">          
  <table class="table table-condensed table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Endpoint</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td><code>http://YOUR_IP:8082/api/session?token=YOUR_TOKEN</code></td>
        <td>Get the Traccar Jsession coockie</td>
      </tr>
      <tr>
        <td>2</td>
        <td><code>http://YOUR_IP:8082/api/positions?token=YOUR_TOKEN</code></td>
        <td>Get the Traccar positions via</td>
      </tr>
      <tr>
        <td>3</td>
        <td><code>http://YOUR_IP:19023/ManagePresence/postPresence</code></td>
        <td>Post the Traccar positions to FreeTakServer</td>
      </tr>
    </tbody>
  </table>
  </div>
</div>
</div>

<hr>

<div class="row">
<div class="mx-auto" style="width: 800px;">
  <p>Example images of the setup files</p>
  <div class="row">
    <div class="col-md-4">
      <div class="thumbnail">
        <a href="img/config.jpg" target="_blank">
          <img src="img/config.jpg" alt="" style="width:100%">
          <div class="caption">
            <p>Config.php [only edit this file]</p>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="thumbnail">
        <a href="img/f12.jpg" target="_blank">
          <img src="img/f12.jpg" alt="" style="width:100%">
          <div class="caption">
            <p>Console example image</p>
          </div>
        </a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="thumbnail">
        <a href="img/traccar.jpg" target="_blank">
          <img src="img/traccar.jpg" alt="" style="width:100%">
          <div class="caption">
            <p>Generate a token in Traccar</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
</div>

<hr>

<div class="row">
<div class="mx-auto" style="width: 600px;">
<div class="bs-component">
<div class="form-group">
                    <br>
                    <button style="width: 600px;" id="testFunction" type="button" onclick="testFunction()" class="btn btn-primary btn-block">Start Test</button>
                    <p id="data" style="margin:10px;"></p>
                    <br>
                    <div class="mx-auto" style="width: 600px;">
                      <!-- toast start-->
                      <div id="testToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                          <strong class="me-auto"><span style="color:green">Testing Complete</span></strong>
                          <small>Now</small>
                            <span aria-hidden="true"></span>
                          </button>
                        </div>
                        <div class="toast-body">
                          Test complete, please see the results
                        </div>
                      </div>
                      </div>
                    <!-- toast end -->
                    <br>
                    <!-- table start-->
                    <div class="table-responsive">          
                      <table id="testTable" class="table table-condensed table-hover">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Server_Type</th>
                            <th>Result</th>
                          </tr>
                        </thead>
                        <tbody>
                           <tr>
                            <td>1</td>
                            <td>Page_load_Test</td>
                            <td><span class="badge bg-success">Success</span></td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                      <!-- table end-->
                </div>
        </div>
</div>

</div>

<hr>
<script>

function uuidv4() {
  //Generate GUID
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16));
}
var c = 1;
// function to handle success
function success() {
  c++;
  //remove \
  var j = JSON.stringify(this.responseText).replace(/\\/g, '');
  var json = j.substring(1,j.length-1);

  console.log(json);
  // Get a reference to the table
  var tableRef = document.getElementById("testTable");

  // Insert a row at the end of the table
  var newRow = tableRef.insertRow(-1);
  if (json) 
  {
        // Append a text node to the cell
        switch(c) 
        {
          case 2:
            // Append a text node to the cell
            var data = JSON.parse(json);
            newRow.insertCell(0).appendChild(document.createTextNode(c));
            newRow.insertCell(1).appendChild(document.createTextNode("Traccar\xa0Test"));
            newRow.insertCell(2).appendChild(document.createTextNode("Token => " + data.token));
            break;
          case 3:
            // Append a text node to the cell
            newRow.insertCell(0).appendChild(document.createTextNode(c));
            newRow.insertCell(1).appendChild(document.createTextNode("FreeTakServer\xa0Test"));
            newRow.insertCell(2).appendChild(document.createTextNode("GUID => " + json));
              c = 3;
            break;
          default:
            c = 3;
        }
  } else {
          // Append a text node to the cell
          newRow.insertCell(0).appendChild(document.createTextNode(c));
          newRow.insertCell(1).appendChild(document.createTextNode("Error"));
          // Append a text node to the cell
          newRow.insertCell(2).appendChild(document.createTextNode("Error"));
  }
}

// function to handle error
function error(err) {
  console.log('Request Failed', err); //error details will be in the "err" object
  alert('Failed: ' + JSON.stringify(err));
}

function testFunction(){
    xhrRequestTraccar("<?php echo$TraccarProtocol;?>://<?php echo$TraccarIP;?>:<?php echo$TraccarPort;?>/api/session?token=<?php echo$TraccarAPIToken;?>");
    xhrRequest("<?php echo$FTSProtocol;?>://<?php echo$FTSIP;?>:<?php echo$FTSAPIPort;?>/ManagePresence/postPresence");
    $("#testToast").toast({animation: true, autohide: true, delay: 5000});
    $("#testToast").toast('show');
}

function xhrRequest(url){
  var xhr = new XMLHttpRequest(); //invoke a new instance of the XMLHttpRequest
  xhr.onload = success; // call success function if request is successful
  xhr.onerror = error;  // call error function if request failed

  var json = {uid: uuidv4(),
    how: "nonCoT",
    name: "Test Point",
    longitude: 18.49681,
    latitude: -34.35697,
    role: "Team Member",
    team: "Green"};//JSON object

  xhr.open('POST', url, true); // open a GET request
  xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');//application/json;charset=UTF-8
  xhr.setRequestHeader('Authorization', "<?php echo$FTSAPIToken;?>");//application/json;charset=UTF-8
  xhr.send(JSON.stringify(json)); // send the request to the server.
}

function xhrRequestTraccar(url){
  var xhr = new XMLHttpRequest(); //invoke a new instance of the XMLHttpRequest
  xhr.onload = success; // call success function if request is successful
  xhr.onerror = error;  // call error function if request failed

  xhr.open('GET', url, true); // open a GET request
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');//application/json;charset=UTF-8
  xhr.send(); // send the request to the server.
}
</script>
</body>
</html>