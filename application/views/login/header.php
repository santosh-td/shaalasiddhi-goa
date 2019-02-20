<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php header("Content-Security-Policy: default-src 'none';script-src 'self' code.jquery.com fonts.googleapis.com fonts.gstatic.com;style-src 'self' fonts.googleapis.com fonts.gstatic.com;img-src 'self' fonts.googleapis.com fonts.gstatic.com;font-src 'self' fonts.googleapis.com fonts.gstatic.com;object-src 'self' fonts.googleapis.com fonts.gstatic.com;frame-ancestors 'self';"); ?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In: Adhyayan</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600' rel='stylesheet' type='text/css'>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT" crossorigin="anonymous"></script>
    <?php echo $addToHeader; ?>
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
    <link href="<?php echo SITEURL;?>public/css/login.css" rel="stylesheet" type="text/css">
  </head>
  <body class="nuiLogInBody">
	<header class="nuiLogInHdrOuter">
	  <div class="nuiLogInHdr">
              <a href=""><img src="<?php echo SITEURL;?>public/images/login/logo_login.png" alt="" class="img-height"></a>
	  </div>
  </header>
  <section class="nuiLogBoxBody">