# TAKCAR

[<img src="img/f12.jpg" width="800"/>](img/f12.jpg)

Quick and dirty example of importing **Traccar** `Lon`, `Lat` coordinates into the **FreeTakServer**.

On page load the script automatically begins a loop getting lat,lon from *Traccar* and posting them to *FTS*.

The `Test Service` button just tests to see that you can see a result on *FTS*.

## STEPS

Once downloaded copy the folder `takcar_scripts` to your `htdocs` or equivalent folder.

Open the web page via `http://localhost/takcar_scripts/`.

### 1.

Make sure that `Traccar` and `FreeTakServer` are running.

Make sure that all your EUD's are connected to the FreeTakServer.

Make sure you have an *Apache distribution* I use the XAMPP system however any system that has `PHP` will suffice.

https://www.apachefriends.org/download.html

[<img src="img/index.jpg" width="800"/>](img/index.jpg)

### 2.

Open the `config.php` file and replace the variables with your `Traccar` and `FreeTakServer` variables.

Both the `Traccar` and `FreeTakServer` softwares must be running.

If you are running the servers over the internet you will need to port forward the appropriate ports to allow the endpoint connections.

The current `XMLHttpRequest();` should not show any general errors or **CORS** policy errors.

[<img src="img/config.jpg" width="800"/>](img/config.jpg)

### 3.

Open the testing page and click on the `Start Test` button to test the endpoints.

[<img src="img/tester.jpg" width="800"/>](img/tester.jpg)

### 4.

Once you have confirmed that the endpoints are communicating you can open `index.php` on your machine via `localhost`.

### 5.

Now that everything is up and running the `Test Service` button can be clicked to immediately test a `postPresence` position on FTS.

The loop will continue to run as long as the webpage is open.

*getPositions* `Traccar` endpoint example.

```HTTP
http://127.0.0.1:8082/api/positions?token=Your_Token_Here
```

*postPresence* `FreeTakServer` endpoint example.

```HTTP
http://127.0.0.1:19023/ManagePresence/postPresence
```

## Notes

The scripts assume `19023` is the *FTS* API port and that `8082` is the *Traccar* API port.

It is also assumed that the scripts are running on the same machine as *FTS* and *Traccar*.

As long as the page is open the loop will continue to run but will stop on close.

## Traccar JSON examples

[<img src="img/session.jpg" width="800"/>](img/session.jpg)

[<img src="img/positions.jpg" width="800"/>](img/positions.jpg)

> FreeTAKTeam https://github.com/FreeTAKTeam

> Checkout my tutorials https://tutorials.techrad.co.za/2021/04/13/freetakserver-manager

> Video demonstration https://www.youtube.com/watch?v=KvzcrZlr9bU

> Source code https://github.com/Cale-Torino/Takcar