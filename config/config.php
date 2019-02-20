<?php

define("DEVELOPMENT_ENVIRONMENT",true);

if(DEVELOPMENT_ENVIRONMENT){
        define("DB_USER","xyz_user");  // Please set your database USERNAME   eg: xyz_user 
        define("DB_PASSWORD","xyz_pass"); // Please set your database PASSWORD  eg: xyz_pass   
        define("DB_NAME","xyz_dbname"); // Please set your database NAME  eg:  xyz_dbname
        define("DB_HOST","127.0.0.1"); // Please set your database HOST   eg: 127.0.0.1       
	define("SITEURL","https://localhost/XYZ/"); // Please set your website url eg: https://localhost/XYZ/
}

define("TOKEN_LIFE",12000); //in Seconds
define("INDIAN_SCHOOL_BORAD",101); 
define("DEFAULT_COUNTRY_ID",101); 
define("DEFAULT_DIAGNOSTIC",2); 
define("DEFAULT_TIER",3); 
define("DEFAULT_AWARD_STANDARD",1);
define("DEFAULT_AQS_ROUND",1);
define("RATING_OTHERS_ID",39);
define('KEY_BEHAVIOURS','Objectivity,Positivity,Maintaining confidentiality');
define('CLASSROOM_OBSERVATION','Quietly find a seat or space at the back of the room to observe~Spend between 5 to 7 minutes in a class');
define('STAKEHOLEDER','Introduce yourself, the purpose of the review and share that all responses will be confidential~Ask for examples and documentation to verify the information~Adapt language to suit the stakeholder being interviewed');
define('SCHOOL_RATING','The meetings are irregular~There is some contribution to events from the teachers, and there is some documented evidence of opportunities to meet.~The meeting minutes indicate that meetings are directed by the Principal');

date_default_timezone_set("Asia/Kolkata");
define("DEFAULT_STUDENT_PROFILE_ATTRIBUTE",49);
define('OFFLINE_STATUS', FALSE); 
define("FEEDBACK_ROLES",'8,1,2');
define("DIAGNOSTIC_LANG",'hi,en,ma');
define("DEFAULT_LANGUAGE",'9');
define("Vikas",1);
define("DOWNLOAD_CHART_URL","http://13.127.82.199:7801/");

