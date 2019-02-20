<?php

/**
Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
LICENSE file in the root directory of this source tree.
**/


error_reporting(0);
require_once  ROOT . 'library' . DS . 'tcpdf' . DS . 'tcpdf.php';
ini_set('memory_limit', '-1');
set_time_limit(0);
ini_set('max_execution_time', 0);


if (isset($_REQUEST['custom_report']) && $_REQUEST['custom_report'] == 1) {
    $report_point3 = isset($_REQUEST['report_point3']) ? $_REQUEST['report_point3'] : 0;
    $report_point5 = isset($_REQUEST['report_point5']) ? $_REQUEST['report_point5'] : 0;
} else {
    $report_point3 = 1;
    $report_point5 = 1;
}


$mpdf=new mPDF('utf-8', 'letter', 0, '', 0, 0, 0, 0, 0, 0,'CalibriBold',[
    'setAutoTopMargin' => 'stretch',
    'autoMarginPadding' => 5
]);
$mpdf->autoScriptToLang = true;
$mpdf->baseScript = 1;	// Use values in classes/ucdn.php  1 = LATIN
$mpdf->autoVietnamese = true;
$mpdf->autoArabic = true;
$mpdf->autoLangToFont = true;
if(isset($_REQUEST['report_id']) && $_REQUEST['report_id']==2){
$mpdf->shrink_tables_to_fit=0;
}

 $HeaderHtml = '<table  width="100%" style="font-family:Helvetica,Arial,sans-serif">'
                . '<tr><td align="center" valign="top"  width="33%"  style="padding-top: 20px;padding-left: 00px;"  ><table>'
                . '<tr><td style="padding-left: 80px;" ><img height="50" src="./public/images/advaithlogo-resized.jpg" alt=""></td></tr></table></td>'
                . '<td align="center" width="33%" ><table style="padding-top: 0px;padding-left: 60px;" align="left"><tr><td><img height="140" src="./public/images/Seal_of_Goa-Resized.jpg" alt=""></td></tr></table></td>'
                . '<td align="right" valign="top" width="33%" style="padding-top: 7px;padding-left: 00px;" ><table><tr><td style="padding-right: 45px;"><img height="85" src="./public/images/diagnostic_adhyayan.png" alt=""></td></tr></table></td></tr></table>'
                . '<table><tr><td width="18%"></td><td width="82%" colspan="2" align="left" style="text-align:center;font-weight:bold;font-size:21px;">Systemic School Improvement Program, DoE, GOA </td></tr></table>'
                . '<table style="border-bottom:5px solid  #6c0d10;width:100%;padding-top: -10px;padding-left: 10px;"><tr><td width="100%" colspan="3" ></td></tr></table>';
 $mpdf->SetHTMLHeader($HeaderHtml);  
 $footer .= "<table align='center' width='100%' bgcolor='black' ><tr><td height='15'>$pageNum</td></tr><tr><td height='15' align='center' style='padding-top: -10px; font-size:10px;'><font color='white'>{PAGENO}/{nbpg} <br>Powered by Adhyayan Quality Education Services Private Limited; Advaith Foundation</font></td></tr></table>";
$mpdf->SetHTMLFooter($footer);
$reportName = "schoolevaluationreport-".$aqsData['school_name'];
$mpdf->SetTitle($reportName); 
$schoolAddress = $aqsData['school_name'].", ".$aqsData['school_address'];
$firstpagehtml ='<table width="100%"><tbody><tr><td style="padding-top: 15px;"  align="center"><div style="font-size: 20px;
font-weight: bold;margin-top: 5px;">'.$schoolAddress.'</div>
		<div style="font-size: 16px;line-height: 26px;margin-bottom: 18px;">Evaluation Report based on Shaala Siddhi<br>Conducted on: '.date("M-Y",strtotime($aqsData['edate'])).'<br>Valid until: '.date("M-Y",strtotime($validDate)).' </div></td></tr></tbody></table>' ;
$firstpagehtml .= '<br/><br/>';
$firstpagehtml .= '<table cellpadding="0" cellspacing="0" width="90%" border="1" align="center"><tr><td style="padding-left: 10px;">Name of the Incharge/HM</td><td style="padding-left: 10px;">'.$aqsData['principal_name'].'</td></tr>';
        if(isset($addressDetails['zone_name']) && !empty($addressDetails['zone_name'])){
            $firstpagehtml .=  '<tr><td style="padding-left: 10px;">Zone</td><td style="padding-left: 10px;">'.$addressDetails['zone_name'].'</td></tr>';
        }if(isset($addressDetails['block']) && !empty($addressDetails['block'])){
            $firstpagehtml .=  '<tr><td style="padding-left: 10px;">Block</td><td style="padding-left: 10px;">'.$addressDetails['block'].'</td></tr>';
        }if(isset($addressDetails['cluster']) && !empty($addressDetails['cluster'])){
             $firstpagehtml .=  '<tr><td style="padding-left: 10px;">Hub</td><td style="padding-left: 10px;">'.$addressDetails['cluster'].'</td></tr>';
        }
$firstpagehtml .= '</table>';
$mpdf->WriteHTML($firstpagehtml);
$mainHeadingTxt ='<table border="0" width="100%" style="padding-top:5px;" ><tr>
            <td  width="100%" style="background-color: #c8c8c8;font-weight: bold;font-family: CalibriBold;font-size: 18px;font-color:black;"  align="center" colspan="3" height="30"> <span style="font-stretch:90%;letter-spacing: -0.254mm;">Table Of Content</span></td>
        </tr></table>';
$mainHeadingTxt .= '<div style="padding-top:10px;"><table cellpadding="0" cellspacing="0" width="90%" border="1" align="center">';
           $mainHeadingTxt .=  '<tr><td style="padding-left: 10px;"><a style="text-decoration:none;color:black;" href="#1b21">1. Overall Summary - Performance across 7 Key Domains</a></td><td style="padding-left: 10px;">2</td></tr>';
           $mainHeadingTxt .=  '<tr><td style="padding-left: 10px;"><a style="text-decoration:none;color:black;" href="#1b22">2. Individual Key Domain Performance</a></td><td style="padding-left: 10px;">2</td></tr>';
$mainHeadingTxt .= '</table></div>';
$mpdf->WriteHTML($mainHeadingTxt);
$mpdf->AddPage();
$mainHeadingNum = 1;
$mainHeadingTxt ='<a name="1b21"><table style="padding-top:5px;" border="0" width="100%"><tr>
            <td  width="100%" style="background-color: #c8c8c8;font-weight: bold;font-family: CalibriBold;font-size: 18px;font-color:black;"  align="center" colspan="3" height="30"> <span style="font-stretch:90%;letter-spacing: -0.254mm;"  align="center" colspan="3" height="30"> Overall Summary - Performance across 7 Key Domains</td>
        </tr></table>';

$mpdf->WriteHTML($mainHeadingTxt);
$kpaOverallRatings = '<div style="padding-top:10px;"><table  width="90%"  border="1" cellpadding="0" cellspacing="0" align="center"><tbody><tr><td width="20%" align="center">Domain Levels</td>'
        . '<td  colspan="2" width="20%" align="center">KD1<br><table width="100%" border="1" cellpadding="0" cellspacing="0"><tr><td width="50%" align="center">A &amp; A</td><td width="50%" align="center">Q &amp; U</td></tr></table></td>'
        . '<td width="10%" align="center">KD2</td><td width="10%" align="center">KD3</td><td width="10%" align="center">KD4</td><td width="10%" align="center">KD5</td><td width="10%" align="center">KD6</td><td width="10%" align="center">KD7</td></tr>';
if(!empty($kpaLevelRating)){
    $startLevel = 0;
    $kpaNo = 0;
    foreach($kpaRatingLevels as $levels){
        $kpaOverallRatings .='<tr><td align="center">'.$levels.'</td>';
    foreach($kpaLevelRating['dataArray'] as $data){
        $kpaNo++;        
        $kpaOverallRatings .= '<td align="center">'.$data[$startLevel].'</td>';            
    }
    $kpaOverallRatings .="</tr>";
    $startLevel++;
   }
    $kpaOverallRatings .='<tr><td align="center">Total</td>';
   foreach($kpaLevelRating['total'] as $data){
        $kpaOverallRatings .= '<td align="center">'.$data.'</td>';
   }
   $kpaOverallRatings .="</tr>";
}
$kpaOverallRatings .="</tbody></table></div></a>";
$mpdf->WriteHTML($kpaOverallRatings);
$mainHeadingTxt ='<a name="1b22"><table style="padding-top:5px;" border="0" width="100%" ><tr>
            <td  width="100%" style="background-color: #c8c8c8;font-weight: bold;font-family: CalibriBold;font-size: 18px;font-color:black;"  align="center" colspan="3" height="30"> <span style="font-stretch:90%;letter-spacing: -0.254mm;">Individual Key Domain Performance</span></td>
        </tr></table>';
$mpdf->WriteHTML($mainHeadingTxt);
if(!empty($kpaWiseRatingData)){
        $kpaIndex = 0;
        $pageBreak=0;
        $i=0;
        $kpaOverallRatings = '';
        $color = '';
      
   $levelSumHtml='';
   $kpaNum = 0;
   foreach($kpaWiseRatingData as $kpaRatingData){
        
       $kpaSrNum=1;
       foreach($kpaRatingData as $data){
           $kpaIndex++;
            $kpaOverallRatings1='';
            $leftTitle='';
            $rightTitle='';
            $dataHeading = explode("-#", $data['dataHeading']);
            if($kpaSrNum == 1){ 
                $leftTitle = '<span><b>KEY DOMAIN '.$kpaIndex.'-'.$dataHeading[0]; 
                if(!empty($dataHeading[1])){
                $leftTitle .=  "<br>". $dataHeading[1].'</b></span>';
                }
                
            }else{
               $rightTitle = '<span><b>KEY DOMAIN '.$kpaIndex.'-'.$dataHeading[0];
               if(!empty($dataHeading[1])){
                $rightTitle .=   "<br>".$dataHeading[1].'</b></span>';
                }
            }
            $kpaOverallRatings = '<div ><table style="page-break-inside:avoid;padding-top:10px;" cellpadding="0" cellspacing="0"  width="90%" align="center"><tbody>';            
            $kpaOverallRatings .= '<tr><td  width="45%" align="center">'.$leftTitle.'</td><td  width="45%" align="center" >'.$rightTitle.'</td>';
           
            $kpaOverallRatings .= '</tr>';
            $kpaNum++;            
            $widthCol1 = 'width="80%"';
            $widthCol2 = 'width="20%"';
            $colspan = 'colspan="0"';            
            $kpaOverallRatings .= '<tr>';
            $pageBreak++;
            $levelSumText = '';
            if($kpaIndex == 1){
                $levelSumText = '<b>A & A</b> -';
            }
            if(isset($data['levelsTotal'])){
              $levelSumHtml .= ' '.$levelSumText.$data['levelsTotal'][0];
            }
            if(isset($data['levels2Total']) && $kpaIndex == 1){
              $levelSumHtml .= '<br><b>Q & U </b>- '.$data['levels2Total'][0];
            }
            
            $kpaOverallRatings1 = '<td width="50%" align="left"   ><table width="100%"  border="0" ><tr><td align="center" >'.$levelSumHtml.'</td></tr></table></td>';
            
            if($kpaSrNum == 2){                
               $kpaOverallRatings = $kpaOverallRatings.$kpaOverallRatings1;
            }
            $kpaOverallRatings .='<td width="50%"><table cellpadding="0" cellspacing="0" width="100%"  border="1"  >';
            
            if($kpaNum == 1){
                $widthCol1 = 'width="60%"';
                $widthCol2 = 'width="40%"';
                $colspan = 'colspan="2"';
                $kpaOverallRatings .='<tr ><td '.$widthCol1.' align="center">Core Standard</td><td '.$colspan.' align="center" '.$widthCol2.' align="center"  >Level<br><table width="100%" border="1" cellpadding="0" cellspacing="0" ><tbody><tr><td width="50%" align="center"  >A &amp; A</td><td width="50%" align="center">Q &amp; U</td></tr></tbody></table></td></tr>';
            }else{             
                $kpaOverallRatings .= '<tr><td '.$widthCol1.' align="center">Core Standard</td><td align="center" width="20%" align="center">Level</td></tr>';
            }
            foreach($data['dataArray'] as $kpaRatingData){
                $dataHeading = explode("-#", $kpaRatingData[0]);
                $lang2Text = '<br>';
                if(!empty($dataHeading[1])) {
                    $lang2Text .= "".$dataHeading[1]."";
                }
                $kpaOverallRatings .= '<tr >
               <td  style="padding-left: 10px;"'.$widthCol1.' >'.$dataHeading[0].$lang2Text.'</td>
               <td width="20%" align="center" >  '.$kpaRatingData[1].'</td>';
               if($kpaNum == 1){
                 $kpaOverallRatings .='<td width="20%" align="center" style="padding-left: 10px;"> '.$kpaRatingData[2].'</td>';  
               }
              $kpaOverallRatings .='</tr>';               
            }
            $kpaOverallRatings .= '</table></td>';
            $kpaOverallRatings22='';
            if($kpaSrNum == 1){
                $kpaOverallRatings =  $kpaOverallRatings.$kpaOverallRatings1;
            }      
            $kpaOverallRatings .= '</tr>';
            $kpaSrNum++;
            $levelSumHtml='';
            $kpaOverallRatings .= '</tbody></table></div></a>';
            $mpdf->SetAutoPageBreak('true',40);
            $mpdf->WriteHTML($kpaOverallRatings);
            }
   } 
   
}
$path = ROOT . 'reports';
// Close and output PDF document

if(isset($isreportsave) && $isreportsave==1){
    $mpdf->Output('uploads/download_pdf/'.$filename. '.pdf', 'F');
}else{

$mpdf->Output($reportName . '.pdf', 'I');
}

