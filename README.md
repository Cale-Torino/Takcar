# TAKCAR

[<img src="img/f12.jpg" width="800"/>](img/f12.jpg)

Quick and dirty example of importing **Traccar** `Lon`, `Lat` coordinates into the **FreeTakServer**.

On page load the script automatically begins a loop getting lat,lon from *Traccar* and posting them to *FTS*.

The `Test Service` button just tests to see that you can see a result on *FTS*.

## STEPS

### 1.

Make sure that `Traccar` and `FreeTakServer` are running.

### 2.

Open the `config.php` file and replace the variables with your `Traccar` and `FreeTakServer` variables.

Both the `Traccar` and `FreeTakServer` softwares must be running on the same server computer.

If this is not the case you will need to port forward the appropriate ports to allow the endpoint connections.

The CORS policy will most likely come into play at this point (an easy fix is to run the browser in development mode)

check this link: https://stackoverflow.com/questions/3102819/disable-same-origin-policy-in-chrome

> Open the start menu

> Type `windows`+`R` or open "Run"

> `chrome.exe --user-data-dir="C://Chrome dev session" --disable-web-security`

Note this disables security so run at your own risk.

[<img src="img/config.jpg" width="800"/>](img/config.jpg)

### 3.

Once you have confirmed that the endpoints are communicating you can open `index.php` on your machine via `localhost`

I use the XAMPP system however any system that has `PHP` will suffice.

https://www.apachefriends.org/download.html

### 4.

Now that everything is up and running the `Test Service` button can be clicked to immediately test a `postPresence` position on FTS.

The loop will continue to run as long as the webpage is open.

*getPositions* `Traccar` endpoint example

```HTTP
http://127.0.0.1:8082/api/positions?token=Fm9OhNJS7SShyw80M8kdqKMcSiOuqqhA
```

*postPresence* `FreeTakServer` endpoint example

```HTTP
http://127.0.0.1:19023/ManagePresence/postPresence
```

## Notes

The scripts assume `19023` is the *FTS* API port and that `8082` is the *Traccar* API port.

It is also assumed that the scripts are running on the same machine as *FTS* and *Traccar*.

As long as the page is open the loop will continue to run but will stop on close.
