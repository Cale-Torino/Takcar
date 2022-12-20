### TODO
- Add python version of script
- Run .py as cronjob in linux server
- Logs in log file of script dir
- Maybe run script with timer as well
- Allow manual run


### Logic

    - guid maker
    - timer loop
    - log to file
    - Endpoints
            - Traccar
                - http://YOUR_IP:8082/api/session?token=YOUR_TOKEN
                - http://YOUR_IP:8082/api/positions?token=YOUR_TOKEN
                - http://YOUR_IP:8082/api/devices?token=YOUR_TOKEN
            - FTS
                - http://YOUR_IP:19023/ManagePresence/postPresence
    - Sockets XML
```XML
<?xml version="1.0" encoding="utf-8" standalone="yes"?>
<event version="2.0" uid="S-1-5-21-1568504889-667903775-1938598950-'.$id.'_'.$name.'" type="a-f-G-U-C-I" time="'.date("Y-m-d\TH:i:s.000\Z",time()).'" start="'.date("Y-m-d\TH:i:s.000\Z",time()).'" stale="'.date("Y-m-d\TH:i:s.000\Z", strtotime('+1 minutes', time())).'" how="h-g-i-g-o">
	<point lat="'.$latitude.'" lon="'.$longitude.'" hae="0" ce="9999999" le="9999999"/>
	<detail>
		<takv version="4.1.0.231" platform="WinTAK-CIV" os="Microsoft Windows 10 Pro" device="System manufacturer System Product Name"/>
		<contact callsign="callsign_'.$name.'" endpoint="*:-1:stcp"/>
		<uid Droid="Droid_'.$name.'"/>
		<__group name="Red" role="Team Member"/>
		<status battery="100"/>
		<track course="0.00000000" speed="0.00000000"/>
	</detail>
</event>
```
```XML
<?xml version="1.0"?>
<event version="2.0" uid="S-1-5-21-1568504889-667903775-1938598950-'.$id.'_'.$name.'-ping" type="t-x-c-t" time="'.date("Y-m-d\TH:i:s.000\Z",time()).'" start="'.date("Y-m-d\TH:i:s.000\Z",time()).'" stale="'.date("Y-m-d\TH:i:s.000\Z", strtotime('+1 minutes', time())).'" how="m-g">
	<point lat="'.$latitude.'" lon="'.$longitude.'" hae="0.00000000" ce="9999999" le="9999999"/>
	<detail/>
</event>
```
