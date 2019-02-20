<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage all global constants for project
 **/
define("MESSAGE_GUEST",'This is a guest log-in and we have provided access to only 1 Key Performance Area for your reference.Contact School Review Programme Lead, Poonam Choksi on 9773187331 for more details. You can even email her at poonam.choksi@adhyayan.asia');

//Please enter vrsion of AWS latest
define("AWS_VERSION","latest");
//Please enter vrsion of AWS Resion
define("AWS_REGION","RESION");

if(DEVELOPMENT_ENVIRONMENT){
    //Set cookie settings variables
    define("COOKIE_GEN",1);
    //Set cookie secure flag
    define("COOKIE_SECURE",0);
    //Set cookie host prefix variables
    define("COOKIE_HTTPONLY",0);
    //Set cookie domain of your website
    define("COOKIE_DOMAIN","DOMAIN");
    
    //Please enter AWS Key
    define("AWS_KEY",'YOUR_KEY');
    //Please enter AWS Secret
    define("AWS_SECRET",'YOUR_SECRET');
    //Please enter vrsion of AWS Bucket name
    define("AWS_BUCKET",'YOUR_BUCKET_NAME');
    //Please enter folder where you want to upload files on server
    define ("UPLOAD_PATH","uploads/"); //also for pre-signed path 
    
    define ("IS_S3",TRUE);
    //Please enter folder for aws files upload
    define ("UPLOAD_URL","https://s3.".AWS_REGION.".amazonaws.com/".AWS_BUCKET."/uploads/");
    //Please enter folder url  upload files for diagonastic
    define ("UPLOAD_PATH_DIAGNOSTIC","diagnostic/");
    //Please enter folder  url of diagonastic files 
    define ("UPLOAD_URL_DIAGNOSTIC","https://s3.".AWS_REGION.".amazonaws.com/".AWS_BUCKET."/diagnostic/");
   //Please enter folder  url of diagonastic files 

    define ("UPLOAD_URL_DIAGNOSTIC_PRESIGNED","diagnostic/"); //s3 pre-signed path
    
    define("TIMESTAMP","+50 seconds");
    define("DOWNLOAD_DIAGNOSTIC","uploads/wordFile/");
    define("CHART_URL_GENERATE","../node-export-server/tmp/"); //URL to delete highcharts image
}
//Token Refresh time
define("TOKEN_LIFE_REFRESH",((TOKEN_LIFE/2)-2)); //in Seconds
//It is private key for generate key token for login and api 
//Please change it for security purpose
$privateKey= '5885c57df30a69747b1230a40d2ff1069e8a2ced251af6bfe5611b80c15eg5hwe7868dwq';
define ("PRIVATEKEY",$privateKey);
define ("RATINGS","1,2,3,4");
