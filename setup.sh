#!/bin/bash

# Check apache
if ! command -v apache2 &> /dev/null; then
    echo "Apache is not installed. Installing..."

    # Install apache
    apt update
    apt install -y apache2

    echo "Apache has been installed successfully."
else
    echo "Apache is already installed on the system."
fi

# Check PHP
if ! command -v php &> /dev/null; then
    echo "PHP is not installed. Installing..."

    # Install PHP
    apt update
    apt install -y php

    echo "PHP has been installed successfully."
else
    echo "PHP is already installed on the system."
fi

# Check MySQL
if ! dpkg -l | grep -q mysql-server; then
    echo "MySQL is not installed. Installing..."

    apt update
    apt install -y mysql-server
    apt install -y php-mysql
    # Start MySQL service
    sudo systemctl start mysql
    echo "MySQL has been installed successfully."
else
    echo "MySQL is already installed on the system."
    sudo service mysql start
fi

# Check Git
if ! command -v git &> /dev/null; then
    echo "Git is not installed. Installing..."

    # Install Git
    apt update
    apt install -y git

    echo "Git has been installed successfully."
else
    echo "Git is already installed on the system."
fi


web_root="/var/www/html"
repository_url="https://github.com/TITANs1506/DBS401"
web_folder="DBS401"

# Check if the root web directory already exists
if [ ! -d "$web_root" ]; then
    echo "The root web directory does not exist. Please check the path again."
    exit 1
fi

# Check if the web directory for the PHP website already exists
if [ -d "$web_root/$web_folder" ]; then
    echo "The folder $web_folder already exists in the root web folder. Please choose another folder name."
    exit 1
fi

# Clone repository from GitHub
echo "Downloading website from GitHub..."
git clone "$repository_url" "$web_root/$web_folder"

# Check if the clone was successful or not
if [ $? -eq 0 ]; then
    echo "The website was successfully downloaded to the $web_root/$web_folder folder."
else
    echo "An error occurred while downloading the website from GitHub."
    exit 1
fi

# Replace apache2.conf
DEST_CONFIG="/etc/apache2/apache2.conf"
SOURCE_CONFIG="/var/www/html/DBS401/apache2.conf"
cp $DEST_CONFIG $DEST_CONFIG.bak
cp $SOURCE_CONFIG $DEST_CONFIG 

# Create database
# MySQL configuration information
mysql_user="DBS"
mysql_password="quangdeptrai"
database_name="DBS_CMS"

# Check if the database exists or not
echo "Mysql's default password will be empty, so spam enter xD"
if mysql -u"root" -p -e "use $database_name;" &> /dev/null; then
    echo "Database $database_name already exists."
    
   # Check if the user already exists or not
   if mysql -u"root" -p -e "SELECT User FROM mysql.user WHERE User='$mysql_user';" | grep -q "$mysql_user"; then
   	 echo "The user $mysql_user already exists in MySQL."
   else
   	 # Create users and grant permissions on the database
   	 echo "User $mysql_user does not exist in MySQL yet. Creating new user..." 
	 sudo mysql -u"root" -p -e "CREATE USER '$mysql_user'@'localhost' IDENTIFIED BY '$mysql_password';"
   	 sudo mysql -u"root" -p -e "GRANT ALL PRIVILEGES ON $database_name.* TO '$mysql_user'@'localhost';"
    	 sudo mysql -u"root" -p -e "FLUSH PRIVILEGES;"
   fi 
   echo "insert data to database...."
   mysql -u "$mysql_user" -p"$mysql_password" DBS_CMS < "$web_root/$web_folder/databse/dbs_cms.sql" 
else
    echo "Database $database_name does not exist yet. Create database..."

    # Create database
    mysql -u"root" -p -e "CREATE DATABASE $database_name;"

    # Check if the database has been created successfully
    if [ $? -eq 0 ]; then
        echo "Database $database_name was successfully created."
    if mysql -u"root" -p -e "SELECT User FROM mysql.user WHERE User='$mysql_user';" | grep -q "$mysql_user"; then
         echo "The user $mysql_user already exists in MySQL."
    else  
         # Create users and grant permissions on the database
         echo "User $mysql_user does not exist in MySQL yet. Creating new user..." 
         sudo mysql -u"root" -p -e "CREATE USER '$mysql_user'@'localhost' IDENTIFIED BY '$mysql_password';"
         sudo mysql -u"root" -p -e "GRANT ALL PRIVILEGES ON $database_name.* TO '$mysql_user'@'localhost';"
         sudo mysql -u"root" -p -e "FLUSH PRIVILEGES;"
    fi
	
	echo "Insert data to database...."
        mysql -u "$mysql_user" -p"$mysql_password" DBS_CMS < "$web_root/$web_folder/database/dbs_cms.sql" 
    else
        echo "An error occurred while creating database $database_name."
        exit 1
    fi
fi

# Restart apache2, mysql
# Restart the Apache service
echo "Restart apache,mysql"

# Restart the Apache service
sudo service apache2 restart

# Check if the reboot was successful or not
if [ $? -eq 0 ]; then
    echo "The Apache service has been restarted successfully."
else
    echo "An error occurred while restarting the Apache service."
    exit 1
fi

# Restart the mysql service
sudo service mysql restart

# Check if the reboot was successful or not
if [ $? -eq 0 ]; then
    echo "Mysql service has been restarted successfully."
else
    echo "n error occurred during restart of the Mysql service."
    exit 1
fi

# Link to website
sudo chmod -R 757 /var/www/html/DBS401
echo "Visit the following link to website: http://localhost/DBS401"