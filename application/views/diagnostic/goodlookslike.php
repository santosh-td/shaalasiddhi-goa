<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<style type="text/css">
	body{background-color: #d1c382;font-family: 'Open Sans', sans-serif;font-size:14px;}p{margin:0 0 15px;padding:0;}h4{font-size:16px;margin:0;padding:12px 0 0;}
	.navbar-brand{height: auto;}
	.navbar-brand img {
    height: 73px;
    width: auto;
	}
	h2{font-size: 20px; line-height: 1.3; font-weight: 700; color: #000; padding-top: 20px; margin: 0 0 20px;}
	.statementscrollarea {
	    line-height: 1.4;
	    font-weight: 400;
	    color: #000;
	    /*max-height: 450px;
	   	overflow: hidden;*/
	    overflow-y: auto;
	}
	@media(max-width: 767px){
		.navbar-brand img {
	    height: 50px;	
		}
	}
	
</style>
<body>

<header>
	<div class="container clearfix">
		<div class="navbar-brand logo"><a href="http://localhost/Adhyayan/"><img src="	public/images/logo.png" alt="Logo - Adhyayan"></a></div>
	</div>

</header>
<section>
	<div class="container clearfix">
		<h2>What "good" looks like statements</h2>
		<div class="statementscrollarea">
 	    <?php  

            foreach($res_new as $mostly_statements){?>
                    <?php if($mostly_statements['display_js_txt']==1){?> 
                    <h4><span style="font-weight: bold;"> Core Standard: </span><span><?php echo $mostly_statements['judgement_statement_text1'];?></span></h4>
                    <p><span><span>&#9656; </span><?php echo str_replace(array('*','Mostly:'),array('<br><span>&#9656;</span>',''),$mostly_statements['translation_text']);?></span></p>
                    <?php }else if($mostly_statements['display_js_txt']==0){?>
                    <p> <span><span>&#9656; </span><?php echo str_replace(array('*','Mostly:'),array('<br><span>&#9656;</span>',''),$mostly_statements['translation_text']);?></span></p>
                    <?php   } 
                 
                } 
           ?>

        </div>
	</div>
</section>

</body>
</html>

