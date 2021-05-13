import mysql.connector
import requests
import smtplib

# Put your keys here
location_key =
weather_key = 

# connect to database (change password for production)
bonsaidb = mysql.connector.connect(
    host="192.168.2.12",
    user="webuser",
    password="web",
    database="bonsai"
)

# uses postfix
smtpObj = smtplib.SMTP('localhost') 
# smptpObj.login(user, password)

cursor = bonsaidb.cursor()

# get users from database
cursor.execute("SELECT username, city FROM users")
users = cursor.fetchall()

# number of emails sent
emails = 0 

#iterate through each user
for user in users:
    # for the email notifications
    email = ""

    #use geocoding to turn users city into lat+lon
    location = requests.get('https://us1.locationiq.com/v1/search.php?key='+ location_key 
    + '&q=' + user[1] + '&format=json')
    location = location.json()[0]

    # get the weather forecast at the users location
    weather = requests.get('https://api.openweathermap.org/data/2.5/onecall?lat=' 
    + location['lat'] + '&lon=' + location['lon'] + '&exclude=current,minutely,hourly&appid=' 
    + weather_key)
    weather = weather.json()['daily'][0]

    # get all of the users trees from database
    cursor.execute("SELECT name, water_freq, id, since_watered FROM trees WHERE username='" + user[0]+"'")
    trees = cursor.fetchall()

    #iterate through trees
    for tree in trees:
        # would prefer to use precipitation volume, but api does not 
        # provide it with any consistency, so using chance of precipitation
        # instead
        if weather['pop'] > 0.5:
            # looks like rain! The tree will get watered today
            cursor.execute("UPDATE trees SET since_watered = 1 WHERE id = " + str(tree[2]))
        else:
            # one more day without water
            cursor.execute("UPDATE trees SET since_watered = " + str(tree[3]+1) + " WHERE id = " + str(tree[2]))
        if tree[3] >= tree[1]:
            # its time to water the tree
            email = email + ("Your bonsai " + tree[0] + " will need watering today.")
            # boldly assume that they watered it
            cursor.execute("UPDATE trees SET since_watered = 1 WHERE id = " + str(tree[2]))

    bonsaidb.commit()

    # send email notification
    if email != "":
        smtpObj.sendmail("alert@watermybonsai.com", user[0], email)          
