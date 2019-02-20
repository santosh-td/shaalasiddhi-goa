<?php

/**
Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
LICENSE file in the root directory of this source tree.
**/


error_reporting(0);
include ROOT . 'library' . DS . 'tcpdf' . DS . 'tcpdf.php';
ini_set('memory_limit', '50M');
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
            $footer .= '<table style="font-family:Helvetica,Arial,sans-serif"><tr><td>'.$pageNum.'</td></tr><tr><td>Powered by Adhyayan Quality Education Services Private Limited; Advaith Foundation</td></tr></table>';

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
//$pdf->network_report_name = $network_report_name;
$pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
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
$schoolAddress = 'Hub Level Report';
$firstpagehtml = '<table  style="font-family:Helvetica,Arial,sans-serif"><tbody><tr><td  align="center"><div style="font-size: 20px;
font-weight: bold;margin-top: 5px;">' . $schoolAddress . '</div></td></tr></tbody></table>';
$firstpagehtml .= '<br/><br/>';
$firstpagehtml .= '<table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;">';
$firstpagehtml .= '<tr><td>Zone</td><td>' . $schools_arrays[0]['zone_name'] . '</td></tr>';
$firstpagehtml .= '<tr><td>Block</td><td>' . $schools_arrays[0]['block_name'] . '</td></tr>';
$firstpagehtml .= '<tr><td>Hub</td><td>' . $schools_arrays[0]['cluster_name'] . '</td></tr>';
$firstpagehtml .= '</table><br/><br/><br/><br/>';

$firstpagehtml .= '<div style="text-align: center;"><table border="0" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;">';
$firstpagehtml .= '<tr><td></td><td><table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;"><tr><td><b>Key</b></td></tr></table></td><td></td></tr>';
$firstpagehtml .= '<tr><td></td><td><table border="1" style="padding-left: 10px;font-family:Helvetica,Arial,sans-serif;"><tr><td style="background-color: #D12200;"></td><td>Level 1</td></tr><tr><td style="background-color: #D0B122;"></td><td>Level 2</td></tr><tr><td style="background-color: #5E9900;"></td><td>Level 3</td></tr></table></td><td></td></tr>';
$firstpagehtml .= '</table></div><br/><br/><br/><br/>';
$pdf->writeHTMLCell(0, 0, '', '', $firstpagehtml, 0, 1, 0, true, 'J', true);
$pdf->AddPage();
$mainHeadingNum = 1;
// first page end
//Second Page Start
$pdf->SetFont('times', '', 7);
$lvlVt = array();

foreach ($schools_arrays as $schools_array_key => $schools_array_val) {
    foreach ($schools_array_val['data2'] as $schoolskey => $schoolsVals) {
        foreach ($schoolsVals as $schoolskey => $schoolsVal) {
            //////////////Start Key Domain 1///////////
            if ($schoolskey == 0) {
                //For First Key Availability and Adequacy Start
                $mainHeadingTxt = 'Domain ' . ($schoolskey + 1) . ' : ' . $schoolsVal[0]['dataHeading'] . ' - Availability and Adequacy';
                $kpaOverallRatings .= '<div><table width="100%"  border="1" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                $kpaOverallRatings .= '<thead><tr nobr="true"><td colspan="' . (count($schoolsVal[0]['dataArray']) + 1) . '" bgcolor="#C8C8C8" align="center" style="line-height: 1.3;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxt . '</font></b></td></tr>';

                $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>Core Standards</b></td>';
                foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                    $kpaOverallRatings .= '<td style="text-align: center;"><div style="padding: 5px;"><b>' . $jsvalue[0] . '</b></div></td>';
                }
                $kpaOverallRatings .= "</tr></thead><tbody>";
                ///////////////////////////////////////////////////////////////////////
                for ($i = 0; $i <= count($schools_arrays) - 1; $i++) {
                    $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>' . $schools_arrays[$i]['client_name'] . '</b></td>';
                    if (empty($schools_arrays[$i]['data2'])) {
                        foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                            $kpaOverallRatings .= '<td style="text-align: center;">0</td>';
                        }
                    } else {
                        foreach ($schools_arrays[$i]['data2'][0][$schoolskey][0]['dataArray'] as $jskey => $jsvalue) {
                            if ($jsvalue[1] == 1)
                                $bgcl = "#D12200";
                            elseif ($jsvalue[1] == 2)
                                $bgcl = "#D0B122";
                            elseif ($jsvalue[1] == 3)
                                $bgcl = "#5E9900";

                            $kpaOverallRatings .= '<td style="background-color: ' . $bgcl . ';text-align: center;">' . $jsvalue[1] . '</td>';
                            $lvlVt[$i][$jskey] = $jsvalue[1];
                        }
                    }
                    $kpaOverallRatings .= "</tr>";
                }


                if (count($lvlVt) > 0) {
                    foreach ($lvlVt as $k => $v) {
                        for ($m = 0; $m <= count($v) - 1; $m++) {
                            $lvt[$m] = array_column($lvlVt, $m);
                        }
                        break;
                    }
                }

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 1</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[1] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 2</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[2] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 3</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[3] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";
                /////////////////////////////////////////////////////////////////////
                $kpaOverallRatings .= '</tbody></table></div>';
                $pdf->writeHTMLCell(0, 5, '', '', $kpaOverallRatings, 0, 1, 0, true, '', true);
                unset($kpaOverallRatings, $lvlVt, $lvt);
                //For First Key Availability and Adequacy Ends
                //For First Key Quality and Usability Start
                $mainHeadingTxt = 'Domain ' . ($schoolskey + 1) . ' : ' . $schoolsVal[0]['dataHeading'] . ' - Quality and Usability';
                $kpaOverallRatings .= '<div><table width="100%"  border="1" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                $kpaOverallRatings .= '<thead><tr nobr="true"><td colspan="' . (count($schoolsVal[0]['dataArray']) + 1) . '" bgcolor="#C8C8C8" align="center" style="line-height: 1.5;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxt . '</font></b></td></tr>';

                $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>Core Standards</b></td>';
                foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                    $kpaOverallRatings .= '<td style="text-align: center;"><b>' . $jsvalue[0] . '</b></td>';
                }
                $kpaOverallRatings .= "</tr></thead><tbody>";
                ///////////////////////////////////////////////////////////////////////
                for ($i = 0; $i <= count($schools_arrays) - 1; $i++) {
                    $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>' . $schools_arrays[$i]['client_name'] . '</b></td>';
                    if (empty($schools_arrays[$i]['data2'])) {
                        foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                            $kpaOverallRatings .= '<td style="text-align: center;">0</td>';
                        }
                    } else {
                        foreach ($schools_arrays[$i]['data2'][0][$schoolskey][0]['dataArray'] as $jskey => $jsvalue) {
                            if ($schoolskey == 0) {
                                if ($jsvalue[2] == 1)
                                    $bgcl = "#D12200";
                                elseif ($jsvalue[2] == 2)
                                    $bgcl = "#D0B122";
                                elseif ($jsvalue[2] == 3)
                                    $bgcl = "#5E9900";

                                $kpaOverallRatings .= '<td style="background-color: ' . $bgcl . ';text-align: center;">' . $jsvalue[2] . '</td>';
                                $lvlVt[$i][$jskey] = $jsvalue[2];
                            }else {
                                if ($jsvalue[1] == 1)
                                    $bgcl = "#D12200";
                                elseif ($jsvalue[1] == 2)
                                    $bgcl = "#D0B122";
                                elseif ($jsvalue[1] == 3)
                                    $bgcl = "#5E9900";

                                $kpaOverallRatings .= '<td style="background-color: ' . $bgcl . ';text-align: center;">' . $jsvalue[1] . '</td>';
                                $lvlVt[$i][$jskey] = $jsvalue[1];
                            }
                        }
                    }
                    $kpaOverallRatings .= "</tr>";
                }


                if (count($lvlVt) > 0) {
                    foreach ($lvlVt as $k => $v) {
                        for ($m = 0; $m <= count($v) - 1; $m++) {
                            $lvt[$m] = array_column($lvlVt, $m);
                        }
                        break;
                    }
                }

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 1</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[1] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 2</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[2] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 3</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[3] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";
                /////////////////////////////////////////////////////////////////////
                $kpaOverallRatings .= '</tbody></table></div>';
                $pdf->writeHTMLCell(0, 5, '', '', $kpaOverallRatings, 0, 1, 0, true, '', true);
                unset($kpaOverallRatings, $lvlVt, $lvt);

                //For First Key Quality and Usability Ends
                //////////////Ends Key Domain 1///////////
            } else {

                $mainHeadingTxt = 'Domain ' . ($schoolskey + 1) . ' : ' . $schoolsVal[0]['dataHeading'];
                $kpaOverallRatings .= '<div><table width="100%"  border="1" cellpadding="2" cellspacing="0"  style="font-family:Helvetica,Arial,sans-serif">';
                $kpaOverallRatings .= '<thead><tr nobr="true"><td colspan="' . (count($schoolsVal[0]['dataArray']) + 1) . '" bgcolor="#C8C8C8" align="center" style="line-height: 1.5;"><b><font size="10" style="line-height: 1.3;">' . $mainHeadingTxt . '</font></b></td></tr>';

                $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>Core Standards</b></td>';
                foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                    $kpaOverallRatings .= '<td style="text-align: center;"><b>' . $jsvalue[0] . '</font></td>';
                }
                $kpaOverallRatings .= "</tr></thead><tbody>";
                ///////////////////////////////////////////////////////////////////////
                for ($i = 0; $i <= count($schools_arrays) - 1; $i++) {
                    $kpaOverallRatings .= '<tr nobr="true"><td style="text-align: center;"><b>' . $schools_arrays[$i]['client_name'] . '</b></td>';
                    if (empty($schools_arrays[$i]['data2'])) {
                        foreach ($schoolsVal[0]['dataArray'] as $jskey => $jsvalue) {
                            $kpaOverallRatings .= '<td style="text-align: center;">0</td>';
                        }
                    } else {
                        foreach ($schools_arrays[$i]['data2'][0][$schoolskey][0]['dataArray'] as $jskey => $jsvalue) {
                            if ($jsvalue[1] == 1)
                                $bgcl = "#D12200";
                            elseif ($jsvalue[1] == 2)
                                $bgcl = "#D0B122";
                            elseif ($jsvalue[1] == 3)
                                $bgcl = "#5E9900";

                            $kpaOverallRatings .= '<td style="background-color: ' . $bgcl . ';text-align: center;">' . $jsvalue[1] . '</td>';
                            $lvlVt[$i][$jskey] = $jsvalue[1];
                        }
                    }
                    $kpaOverallRatings .= "</tr>";
                }


                if (count($lvlVt) > 0) {
                    foreach ($lvlVt as $k => $v) {
                        for ($m = 0; $m <= count($v) - 1; $m++) {
                            $lvt[$m] = array_column($lvlVt, $m);
                        }
                        break;
                    }
                }

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 1</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[1] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 2</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[2] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";

                $kpaOverallRatings .= '<tr style="background-color: #C8C8C8;text-align: center;" nobr="true"><td><b>Level 3</b></td>';
                foreach ($lvt as $k1 => $v1) {
                    $n = array_count_values($lvt[$k1]);
                    $kpaOverallRatings .= '<td style="text-align: center;">' . round(($n[3] * 100) / (count($schools_arrays) - $wtSchoolCt), 2) . '%</td>';
                }
                $kpaOverallRatings .= "</tr>";
                /////////////////////////////////////////////////////////////////////
                
                
                    $kpaOverallRatings .= '</tbody></table></div>';
                    $pdf->writeHTMLCell(0, 5, '', '', $kpaOverallRatings, 0, 1, 0, true, '', true);
                    unset($kpaOverallRatings, $lvlVt, $lvt);
            }
        }
        break;
    }
    break;
}

$path = ROOT . 'reports';
// Close and output PDF document
$pdf->Output($reportName . '.pdf', 'I');
