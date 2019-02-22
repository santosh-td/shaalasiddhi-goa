<?php

define("DEVELOPMENT_ENVIRONMENT",true);

if(DEVELOPMENT_ENVIRONMENT){
        define("DB_USER","xyz_user");  // Please set your database USERNAME   eg: xyz_user 
        define("DB_PASSWORD","xyz_pass"); // Please set your database PASSWORD  eg: xyz_pass   
        define("DB_NAME","xyz_dbname"); // Please set your database NAME  eg:  xyz_dbname
        define("DB_HOST","127.0.0.1"); // Please set your database HOST   eg: 127.0.0.1       
	define("SITEURL","https://localhost/XYZ/"); // Please set your website url eg: https://localhost/XYZ/
}

define("TOKEN_LIFE",12000); //Users session expires time(in Seconds)
define("DEFAULT_TIER",3); // Default award is state award(3=state)
define("DEFAULT_AWARD_STANDARD",1); // Default standard award is international award(1=international)
define("DEFAULT_AQS_ROUND",1); // Default aqs round is 1
define("RATING_OTHERS_ID",39); // Match rating-id to validate
define('KEY_BEHAVIOURS','Objectivity,Positivity,Maintaining confidentiality'); // KEY_BEHAVIOURS text
define('CLASSROOM_OBSERVATION','Quietly find a seat or space at the back of the room to observe~Spend between 5 to 7 minutes in a class'); //CLASSROOM_OBSERVATION text
define('STAKEHOLEDER','Introduce yourself, the purpose of the review and share that all responses will be confidential~Ask for examples and documentation to verify the information~Adapt language to suit the stakeholder being interviewed'); //STAKEHOLEDER text
define('SCHOOL_RATING','The meetings are irregular~There is some contribution to events from the teachers, and there is some documented evidence of opportunities to meet.~The meeting minutes indicate that meetings are directed by the Principal');// SCHOOL_RATING text
date_default_timezone_set("Asia/Kolkata"); // Default timezone
define('OFFLINE_STATUS', FALSE); // Status to manage application is using online or offline
define("DIAGNOSTIC_LANG",'hi,en,ma'); // Diagnostic can be created in these languages (hi=hindi,en=english,ma=marathi)
define("DEFAULT_LANGUAGE",'9'); // Default lanuage is 9 (9=English)
define("DOWNLOAD_CHART_URL","https://www.highcharts.com/"); // Please set your Highchart url

