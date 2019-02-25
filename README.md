##Prerequisites
Your system should have following:
PHP 7.0 and above
Mysql 5.6
Apache webserver

##Git Repository to Download Project
Download Project code from github or run below command
"git clone https://github.com/adhyayanasia/shaalasiddhi-goa.git"

##Database setup
1. Restore your project db from following location: /db/shaalasiddhi-goa.sql
2. For Database setup, please enter your credentials in config.php
-DB URL
-DB user
-DB password
-DB name

##To upload evidence files on AWS S3 bucket
Add AWS credentials in config_var.php for uploading evidence files 

##Highchart URL
Please set DOWNLOAD_CHART_URL in config.php to create highchart graph.

##Start Project
Start mysql, php and apache if not already running. At last navigate to the url on your browser where you have placed the project code under Apache.
For e.g. if you have placed your code in a sub-directory "yourprojectfolder" under localhost, then the url should be http://localhost/yourprojectfolder

##Use the following credentials to log into the application.
email: supadmin@adhyayan.asia
password: 111111

##License & copyright
Licensed under GNU AFFERO GENERAL PUBLIC LICENSE, as found in the LICENSE file.
