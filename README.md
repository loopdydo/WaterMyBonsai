# Water my Bonsai

Allows users to recieve notifications when their Bonsai needs watering. 
Notifications are weather dependant.

Deploy using vagrant - 'vagrant up' will be sufficient to get up and running. 
www contains the front end, written in php.
The mailserver is responsible for sending mail notifications - both as a literal mailserver,
and running the python script that determines when to send emails. Bonsai.py is scheduled to run
once every 24 hours. 
dbserver runs a mysql server, and is preconfigured with the bonsai database which contains user data
and tree data. dbserver is the only dependancy - webserver and mailserver both require dbserver to be
present, but both webserver and mailserver can be replaced entirely. 

API keys are required for https://openweathermap.org/api/one-call-api and https://locationiq.com/geocoding . 
Place them at the top of bonsai.py.
