#!/usr/bin/env python
import socket
import _thread
import random
import os
import os.path
import subprocess
import re
import glob
import requests
import time
import logging
import sys
import logging.handlers as handlers

#VARS
session = "http://YOUR_IP:8082/api/session?token=YOUR_TOKEN"
positions = "http://YOUR_IP:8082/api/positions?token=YOUR_TOKEN"
devices = "http://YOUR_IP:8082/api/devices?token=YOUR_TOKEN"
postPresence = "http://YOUR_IP:19023/ManagePresence/postPresence"

id = ""
name = ""
date = ""
latitude = ""
longitude = ""

socketsend = (f"<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\"?>"
f"<event version=\"2.0\" uid=\"S-1-5-21-1568504889-667903775-1938598950-{id}_{name}\" type=\"a-f-G-U-C-I\" time=\"{date}\" start=\"{date}\" stale=\"{date}\" how=\"h-g-i-g-o\">"
	f"<point lat=\"{latitude}\" lon=\"{longitude}\" hae=\"0\" ce=\"9999999\" le=\"9999999\"/>"
	f"<detail>"
		f"<takv version=\"4.1.0.231\" platform=\"WinTAK-CIV\" os=\"Microsoft Windows 10 Pro\" device=\"System manufacturer System Product Name\"/>"
		f"<contact callsign=\"callsign_{name}\" endpoint=\"*:-1:stcp\"/>"
		f"<uid Droid=\"Droid_{name}\"/>"
		f"<__group name=\"Red\" role=\"Team Member\"/>"
		f"<status battery=\"100\"/>"
		f"<track course=\"0.00000000\" speed=\"0.00000000\"/>"
	f"</detail>"
f"</event>")

socketping = (f"<?xml version=\"1.0\"?>"
f"<event version=\"2.0\" uid=\"S-1-5-21-1568504889-667903775-1938598950-{id}_{name}-ping\" type=\"t-x-c-t\" time=\"{date}\" start=\"{date}\" stale=\"{date}\" how=\"m-g\">"
	f"<point lat=\"{latitude}\" lon=\"{longitude}\" hae=\"0.00000000\" ce=\"9999999\" le=\"9999999\"/>"
	f"<detail/>"
f"</event>")




#Logger logs to file and console output
timestr = time.strftime("%Y-%m-%d_%H-%M-%S")
logger = logging.getLogger()
logger.setLevel(logging.INFO)
formatter = logging.Formatter('%(asctime)s > %(levelname)s | %(message)s', '%m-%d-%Y %H:%M:%S')
stdout_handler = logging.StreamHandler(sys.stdout)
stdout_handler.setLevel(logging.DEBUG)
stdout_handler.setFormatter(formatter)
#file_handler = logging.FileHandler("SocketServer_" + timestr + ".log")
# 25000000 25 mb
file_handler = handlers.RotatingFileHandler(sys.path[0]+"/SocketServer_" + timestr + ".log", maxBytes=25000000, backupCount=2)
file_handler.setLevel(logging.DEBUG)
file_handler.setFormatter(formatter)
logger.addHandler(file_handler)
logger.addHandler(stdout_handler)

#logger.info("Socket created")
#logger.error("Error : Message " + str(msg), exc_info=True)

_thread.start_new_thread(sendHTTP ,(msgbuffer,ip,))

#########################################################################################################

def sendHTTP(msgbuffer,ip):
    try:
        logger.info("[HTTP REQUEST START]")
        protocol = msgbuffer.split(",")
        head = protocol[0]
        imei = protocol[1]
        # defining the api-endpoint 
        #API_ENDPOINT = "https://techrad.co.za/java/test/java_in_json.php?API_KEY=java1234&USERNAME=java&DATA=Sent"
        API_ENDPOINT = "https://api.telegram.org/bot1726488859:AAGy13koBigjBU0h_MSxzglmaAAbPzqH8DI/sendMessage?chat_id=-268086624&text="+imei+"%0A"+ip
        # data to be sent to api
        data = {'head':head,
                'imei':imei,
                'ip':ip}
        # sending post request and saving response as response object
        r = requests.post(url = API_ENDPOINT, data = data)
        #url = r.text
        #print("The response is:%s"%url)
        logger.info("[HTTP REQUEST END]")
    except Exception as msg:
        logger.error("Error : Message " + str(msg), exc_info=True)

#########################################################################################################