# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
    # For a complete reference, please see the online documentation at
    # https://docs.vagrantup.com.
  
    # Every Vagrant development environment requires a box.
    config.vm.box = "ubuntu/xenial64"
   
   config.vm.define "dbserver" do |dbserver|
      dbserver.vm.hostname = "dbserver"
      #set IP
      dbserver.vm.network "private_network", ip: "192.168.2.12"
      
      dbserver.vm.provision "shell", inline: <<-SHELL
         # update apt-get
         apt-get update
         # set mysql setup options (change password for production use)
         debconf-set-selections <<< 'mysql-server mysql-server/root_password password admin'
         debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password admin'
         # install mysql-server
         apt-get -y install mysql-server
         # set for use below password
         export MYSQL_PWD='admin'
         # run bonsai.sql instructions to set up database
         mysql -u root < /vagrant/bonsaisql.txt

         # set mysql to accept connections from any network interface
         sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

         # restart to make config change
         systemctl restart mysql
      SHELL
   end

   # defined after dbserver, so apache can restart to enable php to connect to dbserver 
   config.vm.define "webserver" do |webserver|
      # These are options specific to the webserver VM
      webserver.vm.hostname = "webserver"
      
      # Forward guests port 80 (http) to host port 8080.
      webserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
      
      # Set up a private network that our VMs will use to communicate
      # with each other. 
      webserver.vm.network "private_network", ip: "192.168.2.11"
  
  
    # Provision webserver
      webserver.vm.provision "shell", inline: <<-SHELL
         # update apt-get
         apt-get update  
         # install apache, php, and php-mysql      
         apt-get install -y apache2 php libapache2-mod-php php-mysql
         # Change VM's webserver's configuration to use shared folder.
         cp /vagrant/bonsai-site.conf /etc/apache2/sites-available/
         # activate our website configuration ...
         a2ensite bonsai-site
         # ... and disable the default website provided with Apache
         a2dissite 000-default


         systemctl restart apache2
      SHELL
   end
        
   config.vm.define "mailserver" do |mailserver|
      mailserver.vm.hostname = "mailserver"
      #set IP
      mailserver.vm.network "private_network", ip: "192.168.2.13"
      #forward mail port
      mailserver.vm.network "forwarded_port", guest: 25, host: 25
      
      mailserver.vm.provision "shell", inline: <<-SHELL
         # update apt-get
         apt-get update
         # set postfix installation options
         debconf-set-selections <<< "postfix postfix/mailname string ''"
         debconf-set-selections <<< "postfix postfix/main_mailer_type string 'Internet Site'"
         # install postfix
         apt-get install -y postfix

         # install python packages
         apt-get install -y python3-pip
         pip3 install requests
         pip3 install mysql-connector-python
         pip3 install flask

         # import crontab to run bonsai.py every 24 hours
         crontab /vagrant/crontab.bak
     SHELL
  end

end
  
