<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */   
 /* CAT:Stacked chart */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT1', dirname(__FILE__).DS);
include '..'.DS.'config'.DS.'config.php';
include ROOT1 . 'pData.class.php';
include ROOT1 . 'pDraw.class.php';
include ROOT1 . 'pImage.class.php';

 /* Create and populate the pData object */
 $MyData = new pData();  
 $request = $_GET;
 $labels = array();
 $outstandingPoints = array();
 $goodPoints = array();
 $variablePoints = array();
 $needsAttentionPoints = array();
 $sameRating = array();
 //$num_of_schools = $_GET['num_of_schools'];
 $max_num = 0;

 foreach($request as $k=>$r){ 	
 	/* if($k=='num_of_schools')
 		continue; */
 	$Variable = "0";
 	$Good = "0";
 	$Outstanding = "0";
 	$NeedsAttention = "0";
 	$values = explode(';',$r);
 	$name = substr($values[0],strpos($values[0],'_')+1);
 	$num = count($values);
 	for($i=1;$i<$num;$i++){
 		//$var_name = str_replace(" ","_",substr($values[$i],0,strpos($values[$i],'_')));
 		$var_name = str_replace(" ","_",substr($values[$i],0,strpos($values[$i],'_')));
 			
 		$$var_name = substr($values[$i],strpos($values[$i],'_')+1);		
 	}
 	 $break = 15;
 	 $tempName =str_split($name,$break);
 	//$tempName = explode(PHP_EOL,wordwrap($name,$break,PHP_EOL,TRUE));
 	/* array_walk($tempName,function(&$val) use($break){
 		$val = str_pad($val,$break,' ',STR_PAD_RIGHT);
 	});  */
 	$name = $tempName[0].(!empty($tempName[1])?PHP_EOL.'-'.$tempName[1]:''); 	
 	$labels[] = $name;
 	$max_num=($Outstanding + $Good + $Variable + $NeedsAttention)>$max_num?($Outstanding + $Good + $Variable + $NeedsAttention):$max_num;
 	array_push($outstandingPoints, $Outstanding>0?$Outstanding:0);
	array_push($goodPoints, $Good>0?$Good:0);
 	array_push($variablePoints, $Variable>0?$Variable:0);
 	array_push($needsAttentionPoints, $NeedsAttention>0?$NeedsAttention:0);
 }
 $MyData->addPoints($outstandingPoints,"Outstanding");
 $MyData->addPoints($goodPoints,"Good");
 $MyData->addPoints($variablePoints,"Variable");
 $MyData->addPoints($needsAttentionPoints,"Needs Attention");
 
 $MyData->setAxisName(0,"Number of Schools");
 $MyData->addPoints($labels,"Labels");
 $MyData->setSerieDescription("Labels","KPAs");
 $MyData->setAbscissa("Labels");
 
 $MyData->setPalette("Outstanding",array("R"=>48,"G"=>122,"B"=>206,"Alpha"=>100));
 $MyData->setPalette("Good",array("R"=>94,"G"=>153,"B"=>0,"Alpha"=>100));
 $MyData->setPalette("Variable",array("R"=>208,"G"=>177,"B"=>34,"Alpha"=>100));
 $MyData->setPalette("Needs Attention",array("R"=>209,"G"=>34,"B"=>0,"Alpha"=>100));
 $MyData->setSerieWeight("Outstanding",11);
 $MyData->setSerieWeight("Good",8);
 $MyData->setSerieWeight("Variable",5);
 $MyData->setSerieWeight("Needs Attention",2);

 /* Normalize all the data series to 100% */
 //$MyData->normalize(100,"");

 /* Create the pChart object */
 $myPicture = new pImage(217,210,$MyData,FALSE);
 
 /* Set the default font properties */
 $myPicture->setFontProperties(array("FontName"=>"../public/fonts/HelveticaLTStd-LightCond.ttf","FontSize"=>9));

 /* Draw the scale and the chart */
 $myPicture->setGraphArea(30,6,210,136);
 //$myPicture->drawRectangle(30,2,200,170,array("R"=>0,"G"=>0,"B"=>0));


 $myPicture->drawScale(array("DrawSubTicks"=>TRUE,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>array(0=>array("Min"=>0,"Max"=>$max_num)),"Factors"=>array(1),"LabelRotation"=>50,"SubTickR"=>0,"SubTickG"=>0,"SubTickB"=>0));
 //$myPicture->drawScale(array("GridR"=>200,"GridG"=>0,"GridB"=>0,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>array(0=>array("Min"=>0,"Max"=>$max_num)),"Factors"=>array(1)));
 //$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 //$myPicture->drawStackedBarChart(array("DisplayOrientation"=>ORIENTATION_HORIZONTAL,"DisplayValues"=>true,"DisplayColor"=>DISPLAY_AUTO,"Gradient"=>FALSE,"GradientMode"=>GRADIENT_EFFECT_CAN));
 //$myPicture->drawStackedBarChart(array("DisplayValues"=>true,"DisplayColor"=>DISPLAY_AUTO,"Gradient"=>FALSE,"GradientMode"=>GRADIENT_EFFECT_CAN));
 $myPicture->drawStackedBarChart(array("DisplayValues"=>true,"Surrounding"=>-15,"InnerSurrounding"=>15));
 $myPicture->setShadow(FALSE);

 /* Write the chart legend */
 //$myPicture->drawLegend(10,0,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawStackedBarChart.can.png");
?>