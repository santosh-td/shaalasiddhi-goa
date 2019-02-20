<?php

/**
Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
LICENSE file in the root directory of this source tree.
**/


error_reporting(0);
include ROOT . 'library' . DS . 'tcpdf' . DS . 'tcpdf.php';
ini_set('memory_limit', '-1');
set_time_limit(0);
ini_set('max_execution_time', 0);

if ($_REQUEST['custom_report'] == 1) {
    $report_point3 = isset($_REQUEST['report_point3']) ? $_REQUEST['report_point3'] : 0;
    $report_point5 = isset($_REQUEST['report_point5']) ? $_REQUEST['report_point5'] : 0;
} else {
    $report_point3 = 1;
    $report_point5 = 1;
}

class TOC_TCPDF extends TCPDF {

    public $top_margin = 12;
    public $network_report_name = '';

    /**
     * Overwrite Header() method.
     * @public
     */
    public function Header() {
        
        $html12 = '<table style="font-family:Helvetica,Arial,sans-serif"><tr><td align="center"><table  style="padding-top: 25px;padding-left: 5px;">'
                . '<tr><td><img height="45" src="./public/images/advaithlogo-resized.jpg" alt=""></td></tr></table></td>'
                . '<td align="center"><table style="padding-top: 0px;padding-left: 60px;" align="left"><tr><td><img height="130" src="./public/images/Seal_of_Goa-Resized.jpg" alt=""></td></tr></table></td>'
                . '<td align="center"><table style="padding-top: 10px;padding-left: 10px;"><tr><td><img height="75" style="height:57px;" src="./public/images/diagnostic_adhyayan.png" alt=""></td></tr></table></td></tr></table>'
                . '<table><tr><td width="10%"></td><td width="90%" colspan="2" align="left" style="text-align:center;20px;font-weight:bold;font-size:20px;">Systemic School Improvement Program, DoE, GOA </td></tr></table>'
                . '<table style="padding-top: -10px;padding-left: 10px;"><tr><td width="100%" colspan="3" style="border-bottom:5px solid  #6c0d10;width:100%"></td></tr></table>';
        
        $this->writeHTMLCell($w = 0, $h = 1, $x = '', $y = '', $html12, $border = 0, $ln = 0, $fill = 0, $reseth = true, $align = '', $autopadding = false);
        
    }

    /**
     * Overwrite Footer() method.
     * @public
     */
    public function Footer() {

         {
            // *** replace the following parent::Footer() with your code for normal pages
            $pageNum = 'Page ' . $this->getAliasNumPage();
            $footer = '<table style="font-family:Helvetica,Arial,sans-serif"><tr><td>'.$pageNum.'</td></tr><tr><td>Powered by Adhyayan Quality Education Services Private Limited; Advaith Foundation</td></tr></table>';

            $this->SetY(-7);
            $headerText = 'Powered by Adhyayan Quality Education Services Private Limited; Advaith Foundation';
            $this->SetTextColor(144, 128, 118);
            $this->WriteHtmlCell(0, 0, '', '', $footer, 0, 0, 1, true, 'C');
            $this->WriteHtmlCell(0, 0, '', '', $footer, 1, 1, 1, true, 'C');
            parent::Footer();
        }
    }

}

// end of class
// create new PDF document
$pdf = new TOC_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetPrintHeader(true);

// set header and footer fonts
$pdf->setHeaderFont(Array(
    PDF_FONT_NAME_MAIN,
    '',
    PDF_FONT_SIZE_MAIN
));
$pdf->setFooterFont(Array(
    PDF_FONT_NAME_DATA,
    '',
    PDF_FONT_SIZE_DATA
));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 50, 10);

// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, 40 );
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once (dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
// set font
$pdf->SetFont('times', '', 10);
// ---------------------------------------------------------
// create some content ...
// add a page
// first page start
$pdf->addPage();
$reportName = $report_name;
$pdf->SetTitle($reportName);
$schoolAddress = 'Block Level Report';
$firstpagehtml = '<table  style="font-family:Helvetica,Arial,sans-serif"><tbody><tr><td  align="center"><div style="font-size: 20px;
font-weight: bold;margin-top: 5px;">' . $schoolAddress . '</div></td></tr></tbody></table>';
$firstpagehtml .= '<br/><br/>';
$firstpagehtml .= '<table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;">';
$firstpagehtml .= '<tr><td>Zone</td><td>' . $zone_name[0]['zone_name'] . '</td></tr>';
$firstpagehtml .= '<tr><td>Block</td><td>' . $block_name[0]['network_name'] . '</td></tr>';
$firstpagehtml .= '<tr><td>Total no. of schools </td><td>' . $totalSchools . '</td></tr>';
$firstpagehtml .= '</table><br/><br/><br/><br/>';
$firstpagehtml .= '<table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;">';
$firstpagehtml .= '<tr><th><b>Hub</b></th><th><b>No. of Schools(Assessment Completed)</b></th></tr>';
$numOfSchools = 0;
$blockWiseSchools = array();
foreach($blockSchoolData as $cluster_id =>$clusters){
    $numOfSchools = $numOfSchools+count($clusters);
    $firstpagehtml .= '<tr><td>'.$clusters[0]['province_name'].'</td><td>'.count($clusters).'</td></tr>';
    $blockWiseSchools[$clusters[0]['province_name']] = count($clusters);
}
$firstpagehtml .= '</table><br/><br/><br/><br/>';

$firstpagehtml .= '</table></div><br/>';
$firstpagehtml .= '<div style="text-align: center;"><table border="0" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;">';
$firstpagehtml .= '<tr><td></td><td><table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;"><tr><td><b>Key</b></td></tr></table></td><td></td></tr>';
$firstpagehtml .= '<tr><td></td><td><table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;"><tr><td style="background-color: #D12200;"></td><td>Level 1</td></tr><tr><td style="background-color: #D0B122;"></td><td>Level 2</td></tr><tr><td style="background-color: #5E9900;"></td><td>Level 3</td></tr></table></td><td></td></tr>';
$firstpagehtml .= '</table></div><br/>';
$pdf->writeHTMLCell(0, 0, '', '', $firstpagehtml, 0, 1, 0, true, 'J', true);
$pdf->AddPage();
$mainHeadingNum = 1;
$pdf->SetFont('times', '', 7);
$lvlVt = array();
$kpaNum=1;
$colspn = 2;
 
$jsTitle1 = ' Availability and Adequacy'; $jsTitle2 = ' Quality and Usability';

$cumulativeDataString='';
$kpaOverallRatings = '';
$levelHead = '<tr><td><table border="1" width="100%"><tbody><tr><td  width="50%"><b>A & A</b></td><td width="50%"><b>Q & U</b></td></tr></tbody></table></td></tr>';
 foreach ($ratingData3 as $kpaKey=>$kpa) {
                if($kpaNum != 1) {
                  $levelHead = ''; 
                  $colspn = 1;
                  $jsTitle1 = '';
                  $jsTitle2 = '';
                } 
                $cumulativeDataStringPart2='';
                $mainHeadingTxtPart1 =  'Key Domain '.$kpaNum .'-'. $kpaKey;
                if($kpaNum == 1){
                    $mainHeadingTxtPart1 .= "-".$jsTitle1;
                }
                $cumulativeDataString .= '<div><table nobr="true"  width="100%"  border="0" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                $cumulativeDataString .= '<tr ><td  bgcolor="#C8C8C8" align="center" style="line-height: 1.7;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxtPart1 . '</font></b></td></tr>';
                $level1Row = $level2Row = $level3Row = $level1_1Row = $level2_1Row = $level3_1Row = '';
                
                $cumulativeDataString .= '<tr ><td><table border="1" style="padding-left: 3px" ><tr><td><b><font size="8" style="line-height: 1.3;">Core Standards</font></b></td>';
                $cumulativeDataStringPart3 = '';
                $cumulativeDataStringLevel1 = '';
                $cumulativeDataStringLevel2 = '';
                $cumulativeDataStringLevel3 = '';
                $cumulativeDataStringLevel1_1 = '';
                $cumulativeDataStringLevel2_1 = '';
                $cumulativeDataStringLevel3_1 = '';
                $level1=$level2=$level3=0;
                foreach($kpa as $jsKey=>$js){
                     $level1=$level2=$level3=0;
                   $cumulativeDataStringPart3 .= '<td ><b><font size="8" style="line-height: 1.3;">'.$jsKey.'</font></b></td>';
                   $cumulativeDataStringLevel1 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($js['Level-1'][0]['sum_rating']/$numOfSchools)*100,2).'</font></td>';
                   $cumulativeDataStringLevel2 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($js['Level-2'][0]['sum_rating']/$numOfSchools)*100,2).'</font></td>';
                   $cumulativeDataStringLevel3 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($js['Level-3'][0]['sum_rating']/$numOfSchools)*100,2).'</font></td>';
                   if($kpaNum == 1){
                       $kpaLevel1Rating = explode(",",$js['Level-1'][0]['kpa1level2']);
                       $kpaLevel2Rating = explode(",",$js['Level-2'][0]['kpa1level2']);
                       $kpaLevel3Rating = explode(",",$js['Level-3'][0]['kpa1level2']);
                       $final_values=array_merge($kpaLevel1Rating,$kpaLevel2Rating,$kpaLevel3Rating);
                        $level2Rating = array_count_values($final_values);
                        $level3 = isset($level2Rating[3])?$level2Rating[3]:0;
                        $level2 = isset($level2Rating[2])?$level2Rating[2]:0;
                        $level1 = isset($level2Rating[1])?$level2Rating[1]:0;
                       $cumulativeDataStringLevel1_1 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($level3/$numOfSchools)*100,2).'</font></td>';
                       $cumulativeDataStringLevel2_1 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($level2/$numOfSchools)*100,2).'</font></td>';
                       $cumulativeDataStringLevel3_1 .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($level1/$numOfSchools)*100,2).'</font></td>';
                 
                   }
                }
                $cumulativeDataString .= $cumulativeDataStringPart3;
               
                 $cumulativeDataString .= '</tr><tr><td align="center" style="background-color: #D12200;"><b><font size="8" style="line-height: 1.3;">Level 1</font></b></td>'.$cumulativeDataStringLevel1.'</tr>';
                 $cumulativeDataString .= '<tr><td align="center" style="background-color: #D0B122;"><b><font size="8" style="line-height: 1.3;">Level 2</font></b></td>'.$cumulativeDataStringLevel2.'</tr>';
                 $cumulativeDataString .= '<tr><td align="center" style="background-color: #5E9900;"><b><font size="8" style="line-height: 1.3;">Level 3</font></b></td>'.$cumulativeDataStringLevel3.'</tr>';
                 $cumulativeDataString .= '</table>';
               
                $cumulativeDataString .= '</td></tr>';
                 $cumulativeDataString .= '</table></div>';
                 if($kpaNum == 1) {
                    $mainHeadingTxtPart1 = 'Key Domain '.$kpaNum .'-'. $kpaKey."-".$jsTitle2;
                    $cumulativeDataStringPart2 .= '<div><table nobr="true" width="100%"  border="0" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                    $cumulativeDataStringPart2 .= '<tr ><td bgcolor="#C8C8C8" align="center" style="line-height: 1.7;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxtPart1 . '</font></b></td></tr>';
                    $cumulativeDataStringPart2 .= '<tr ><td><table border="1" style="padding-left: 3px"><tr><td><b><font size="8" style="line-height: 1.3;">Core Standards</font></b></td>'.$cumulativeDataStringPart3.'</tr>';
                    $cumulativeDataStringPart2 .= '<tr><td align="center" style="background-color: #D12200;"><b><font size="8" style="line-height: 1.3;">Level 1</font></b></td>'.$cumulativeDataStringLevel1_1.'</tr>';
                    $cumulativeDataStringPart2 .= '<tr><td align="center" style="background-color: #D0B122;"><b><font size="8" style="line-height: 1.3;">Level 2</font></b></td>'.$cumulativeDataStringLevel2_1.'</tr>';
                    $cumulativeDataStringPart2 .= '<tr><td align="center" style="background-color: #5E9900;"><b><font size="8" style="line-height: 1.3;">Level 3</font></b></td>'.$cumulativeDataStringLevel3_1.'</tr>';
                    $cumulativeDataStringPart2 .= '</table></td></tr></table></div>';
                  $cumulativeDataString .= $cumulativeDataStringPart2;
                 
                }
     $kpaNum++;
 }
  $kpaOverallRatingsCumulative  = '<table  style="font-family:Helvetica,Arial,sans-serif"><tbody><tr><td  align="center"><div style="font-size: 20px;
font-weight: bold;margin-top: 5px;">Categorization of schools at different level  - %</div></td></tr></tbody></table><br><br>';
 $pdf->writeHTMLCell(0, 5, '', '', $kpaOverallRatingsCumulative, 0, 1, 0, true, '', true);
  $pdf->writeHTMLCell(0, 5, '', '', $cumulativeDataString, 0, 1, 0, true, '', true);
  $pdf->AddPage();
$cumulativeDataString = '';
$kpaNum = 1;
$jsTitle1 = ' Availability and Adequacy'; $jsTitle2 = ' Quality and Usability';
foreach ($ratingDataClusterPro as $kpaKey=>$kpa) {
                if($kpaNum != 1) {
                  $levelHead = ''; 
                  $colspn = 1;
                  $jsTitle1 = '';
                  $jsTitle2 = '';
                } 
                $cumulativeDataStringPart2='';
               
                $mainHeadingTxtPart1 =  'Key Domain '.$kpaNum .'-'. $kpaKey;
                if($kpaNum == 1){
                    $mainHeadingTxtPart1 .= "-".$jsTitle1;
                }
                if($pdf->getPageHeight() == '800'){
                    $pdf->AddPage();
                }
                $level2JsString='';
                $cumulativeDataString .= '<br><br><table  width="100%"  border="0" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                $cumulativeDataString .= '<tr nobr="true"><td  bgcolor="#C8C8C8" align="center" style="line-height: 1.7;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxtPart1 . '</font></b></td></tr></table>';
                 $alphaCount = 'a';
                foreach($kpa as $jsKey=>$jsData){
                    
                    $cumulativeDataString .= '<table  nobr="true" style="padding-top:10px;"><tr nobr="true" ><td height="30"><b><font size="9" >'.$kpaNum.$alphaCount." ".$jsKey.'</font></b></td></tr>';
                    $cumulativeDataString .= '<tr ><td><table  border="1" ><tr><td colspan="4"><table style="padding-top:3px;" border="1"><tr><td align="center" height="20" style="padding-top:10px;" ><b><font size="8">Hub Name</font></b></td><td height="20" align="center" style="background-color: #D12200;"><b><font size="8" >Level 1</font></b></td><td height="20" align="center" style="background-color: #D0B122;"><b><font size="8">Level 2</font></b></td><td height="20" align="center" style="background-color: #5E9900;"><b><font size="8">Level 3</font></b></td></tr></table></td></tr>';
                    if($kpaNum == 1){
                        $level2JsString .= '<table  nobr="true" style="padding-top:10px;"><tr nobr="true" ><td height="30"><b><font size="9" >'.$kpaNum.$alphaCount." ".$jsKey.'</font></b></td></tr>';
                        $level2JsString .= '<tr ><td><table  border="1" ><tr><td colspan="4"><table style="padding-top:3px;" border="1"><tr><td align="center" height="20" style="padding-top:20px;" ><b><font size="8">Hub Name</font></b></td><td height="20" align="center" style="background-color: #D12200;"><b><font size="8" >Level 1</font></b></td><td height="20" align="center" style="background-color: #D0B122;"><b><font size="8">Level 2</font></b></td><td height="20" align="center" style="background-color: #5E9900;"><b><font size="8">Level 3</font></b></td></tr></table></td></tr>';
                    }
                    $alphaCount++;
                    foreach($jsData as $blockKey=>$blockData){
                        $level1=$level2=$level3=0;
                       
                        $kpaLevel1Rating = isset($blockData['Level-1'][0]['kpa1level2'])?explode(",",$blockData['Level-1'][0]['kpa1level2']):array();
                        $kpaLevel2Rating = isset($blockData['Level-2'][0]['kpa1level2'])?explode(",",$blockData['Level-2'][0]['kpa1level2']):array();
                        $kpaLevel3Rating = isset($blockData['Level-3'][0]['kpa1level2'])?explode(",",$blockData['Level-3'][0]['kpa1level2']):array();
                        $final_values=array_merge($kpaLevel1Rating,$kpaLevel2Rating,$kpaLevel3Rating);
                        $level2Rating = array_count_values($final_values);
                       
                        $level3 = isset($level2Rating[3])?$level2Rating[3]:0;
                        $level2 = isset($level2Rating[2])?$level2Rating[2]:0;
                        $level1 = isset($level2Rating[1])?$level2Rating[1]:0;
                        $level2JsString.= '<tr><td align="center"><font size="8"  style="line-height: 1.3;">'.$blockKey.'</font></td>';
                        $level2JsString .= '<td align="center"><font size="8"  style="line-height: 1.3;">'.round(($level3/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $level2JsString .= '<td align="center"><font size="8"  style="line-height: 1.3;">'.round(($level2/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $level2JsString .= '<td align="center"><font size="8"  style="line-height: 1.3;">'.round(($level1/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $level2JsString .= '</tr>';
                        
                        $cumulativeDataString .= '<tr><td align="center"><font size="8" style="line-height: 1.3;">'.$blockKey.'</font></td>';
                        $cumulativeDataString .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($blockData['Level-1'][0]['sum_rating']/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $cumulativeDataString .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($blockData['Level-2'][0]['sum_rating']/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $cumulativeDataString .= '<td align="center"><font size="8" style="line-height: 1.3;">'.round(($blockData['Level-3'][0]['sum_rating']/$blockWiseSchools[$blockKey])*100,2).'</font></td>';
                        $cumulativeDataString .= '</tr>';
                    }
                    if($kpaNum == 1) {
                     $level2JsString.= '</table></td></tr></table>';
                    }
                    $cumulativeDataString .= '</table></td></tr></table>';
            
                   
                }
                 if($kpaNum == 1) {
                    $mainHeadingTxtPart1 = 'Key Domain '.$kpaNum .'-'. $kpaKey."-".$jsTitle2;
                    $cumulativeDataStringPart2 .= '<br><br><table nobr="true" width="100%"  border="0" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                    $cumulativeDataStringPart2 .= '<tr nobr="true"><td bgcolor="#C8C8C8" align="center" style="line-height: 1.7;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxtPart1 . '</font></b></td></tr></table>';
                    $cumulativeDataStringPart2 .= $level2JsString;
                   
                  $cumulativeDataString .= $cumulativeDataStringPart2;
                 
                }
     $kpaNum++;
 }
 $kpaOverallRatingsCumulative  = '<table  style="font-family:Helvetica,Arial,sans-serif"><tbody><tr><td  align="center"><div style="font-size: 20px;
font-weight: bold;margin-top: 5px;">Categorization of schools at different level  - in number</div></td></tr></tbody></table><br><br>';
 $pdf->writeHTMLCell(0, 5, '', '', $kpaOverallRatingsCumulative, 0, 1, 0, true, '', true);
  $pdf->writeHTMLCell(0, 5, '', '', $cumulativeDataString, 0, 1, 0, true, '', true);


$path = ROOT . 'reports';
// Close and output PDF document
$pdf->Output($reportName . '.pdf', 'I');
