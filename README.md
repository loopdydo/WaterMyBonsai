# Water my Bonsai

Allows users to recieve notifications when their Bonsai needs watering. 
Notifications are weather dependant.

## Usage

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

## Why does this exist?
Mostly, as a project for my CS course. That's why there is no commit history. The original private
repo contains some course related guff and api keys (not particularly sensitive, but keys nonetheless)
so I decided to recreate the repo uploading edited files. There is however a wee story that explains
why this is the project I chose...

My flatmate keeps several bonsai trees. They live outdoors, where she emphatically informs me they are meant to live (indoors is no place for a tree). Often, I am responsible for their care while she is away. This primarily consists of watering them. I set a reminder on my phone, but sometimes the reminder will go off and it will be raining out. Water my Bonsai attempts to solve this (enormous) problem. 
