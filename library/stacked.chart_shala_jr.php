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
function multiCol($string, $numcols)
{
	$collength = ceil(strlen($string) / $numcols) + 3;
	$retval = explode("\n", wordwrap(strrev($string), $collength));
	if(count($retval) > $numcols) {
		$retval[$numcols-1] .= " " . $retval[$numcols];
		unset($retval[$numcols]);
	}
	$retval = array_map("strrev", $retval);
	return array_reverse($retval);
}

 
 $data = $_REQUEST;
 //sortArray($data);
 
 //echo "<pre>";print_r($data);die;
 //die;
 //echo"<pre>";print_r($data);die;
 $clients = array();
 $agreements = array();
 $disagree1 = array();
 $disagree2 = array();
 $disagree3 = array();
 $total=4;
 $kpasData = array();
 $sortKpaData = array();
 
 $lang_id=(isset($data['lang_id']) && $data['lang_id']!="")?$data['lang_id']:DEFAULT_LANGUAGE;
 unset($data['lang_id']);
 
 foreach($data as $row){
 	$r = explode(';',$row); 
       $kpasData[$r[0]] = array_slice($r, 1);
      //  $kpasData[$r[0]] = $r;
 }
 //echo "<pre>";print_r($data);die;
//usort($kpasData, 'compareMid');
 //array_multisort($kpasData,SORT_DESC);
 foreach($kpasData as $key=>$val) {
     $val[]=$key;
     $sortKpaData[$key] = $val;
 }
 //echo "<pre>";print_r($sortKpaData);die;
 unset($sortKpaData[1]);
 $sortKpaData = array_reverse($sortKpaData, true);
 foreach($sortKpaData as $r){
        
 	//$scl = str_replace('  ',' ',urldecode($r[0]));
 	$scl = urldecode($r[4]);
 //	echo "<pre>",$scl,"</pre>";
 	$scl = preg_replace('/\s+/',' ',trim($scl));  	
 	$break = 29; 
 	//$scl = explode(PHP_EOL,wordwrap($scl,$break,PHP_EOL));	
 	//$scl = implode(PHP_EOL.'-', str_split($scl, $break)); 	
 	//echo "<pre>",sprintf("%-{$break}s",$scl),strlen($scl),"</pre>";
 	//$clients[] = sprintf("%-{$break}s",$scl);
 	//$clients[] = str_pad($scl[0],$break,' ',STR_PAD_RIGHT).(!empty($scl[1])?str_pad($scl[1],$break,' ',STR_PAD_RIGHT):'');
 	//$clients[] = str_pad($scl,$break,' ',STR_PAD_RIGHT);
        if($lang_id==8){
        $array = explode(PHP_EOL,wordwrap($scl));    
        }else{
 	$array = explode(PHP_EOL,wordwrap($scl,$break,PHP_EOL));
        }
        if($lang_id==8){
            
        }else{
 	array_walk($array,function(&$val) use($break){
 		$val = str_pad($val,$break,' ',STR_PAD_RIGHT); 		
 	});
        }
 	$clients[] = $array[0].(!empty($array[1])?PHP_EOL.$array[1]:'').(!empty($array[2])?PHP_EOL.$array[2]:'');
 	//echo $r[1];
        //echo "<pre>";print_r($r);die;
 	$level1[] = @number_format($r[1],1);
 	$level2[] = @number_format($r[2],1);
 	$level3[] = @number_format($r[3],1);
 		
 };
 $MyData->addPoints($level1,"Level-1");
 $MyData->addPoints($level2,"Level-2");
 $MyData->addPoints($level3,"Level-3");
 //$MyData->addPoints($rarely,"Rarely");
 
 //$MyData->setAxisName(0,"Percentage");
 if($lang_id==8){
$MyData->setAxisName(0,"प्रतिशत");    
}else{
$MyData->setAxisName(0,"Percentage");     
}


$clients = array_map('trim',$clients);;

//echo "<pre>";print_r($labels);die;
 $MyData->addPoints($clients,"Labels");
 //$MyData->setSerieDescription("Labels","Schools");
 $MyData->setAbscissa("Labels");

 /*$MyData->setSeriePicture("Agreements","../public/images/reports/leg_outstanding.png");
 $MyData->setSeriePicture("Disagreements by 1","../public/images/reports/leg_good.png");
 $MyData->setSeriePicture("Disagreements by 2","../public/images/reports/leg_variable.png");
 $MyData->setSeriePicture("Disagreements by 3","../public/images/reports/leg_attn.png");
 $MyData->setSerieWeight("Agreements",7);
 */
 
 $MyData->setPalette("Level-1",array("R"=>255,"G"=>0,"B"=>0,"Alpha"=>100));
 $MyData->setPalette("Level-2",array("R"=>208,"G"=>177,"B"=>34,"Alpha"=>100));
 $MyData->setPalette("Level-3",array("R"=>94,"G"=>153,"B"=>0,"Alpha"=>100));
 //$MyData->setPalette("Rarely",array("R"=>255,"G"=>0,"B"=>0,"Alpha"=>100));
 
 $MyData->setSerieWeight("Level-1",80);
 $MyData->setSerieWeight("Level-2",50);
 $MyData->setSerieWeight("Level-3",110);
 //$MyData->setSerieWeight("Rarely",2);

 $num_clients = count($clients);
 $addHeight = 0;
 if($num_clients>10)
	 $addHeight = $num_clients*20;
 /* Normalize all the data series to 100% */
 $MyData->normalize(100,"");

 /* Create the pChart object */
 $myPicture = new pImage(770,470+$addHeight,$MyData);
 
 /* Set the default font properties */
 //$myPicture->setFontProperties(array("FontName"=>"../public/fonts/calibrib-webfont.ttf","FontSize"=>8));
 if($lang_id==8){
 $myPicture->setFontProperties(array("FontName"=>"../public/fonts/MANGAL.TTF","FontSize"=>11));
 }else{
 $myPicture->setFontProperties(array("FontName"=>"../public/fonts/calibrib-webfont.ttf","FontSize"=>11));    
 }
 /* Draw the scale and the chart */
 $myPicture->setGraphArea(90,38,750,440+$addHeight);
 $myPicture->drawRectangle(90,38,750,440+$addHeight,array("R"=>0,"G"=>0,"B"=>0));

 $myPicture->drawScale(array("Floating"=>TRUE,"GridR"=>200,"GridG"=>0,"GridB"=>0,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE,"Pos"=>SCALE_POS_TOPBOTTOM,"Mode"=>SCALE_MODE_MANUAL,"ManualScale"=>array(0=>array("Min"=>0,"Max"=>100))));
 //$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->drawStackedBarChart(array("DisplayOrientation"=>ORIENTATION_HORIZONTAL,"DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO,"Gradient"=>FALSE,"GradientMode"=>GRADIENT_EFFECT_CAN));
 $myPicture->setShadow(FALSE);
 $myPicture->setFontProperties(array("FontName"=>"../public/fonts/calibrib-webfont.ttf","FontSize"=>8)); 
 /* Write the chart legend */
 $myPicture->drawLegend(350,450+$addHeight,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL,"Margin"=>10,'XSpacing'=>20,'FontSize'=>11));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.drawStackedBarChart.can.png");
?>