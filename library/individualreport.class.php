<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */

class individualReport extends reportClass {

    protected $subAssessmentType;
    protected $awardBreakdown;
    protected $actionPlanId;
    protected $actionPlanKpKQ;
    protected $actionPlanActivities;
    protected $actionPlanStatements;
    protected $actionStatements;
    protected $actionPlannedActivities;
    protected $assessmentIdRound2;
    protected $sameRatingKey = 0;
    protected $improvedRatingKey = 0;
    protected $decreasedRatingKey = 0;
    protected $sameRatingCore = 0;
    protected $improvedRatingCore = 0;
    protected $decreasedRatingCore = 0;
    protected $sameRatingSt = 0;
    protected $improvedRatingSt = 0;
    protected $decreasedRatingSt = 0;
    protected $judgementStatementLevels;

    function __construct($assessment_id, $subAssessmentType, $report_id = '', $conductedDate = '', $validDate = '') {
        $this->assessmentId = $assessment_id;
        $this->subAssessmentType = $subAssessmentType;
        $this->sameRatingPercentage = 0;
        parent::__construct($report_id, $conductedDate, $validDate);
    }

    function actionPlanOutput($action_plan_id = '', $start_date = '', $end_date = '', $palnnedDate = array(), $datesrange = "", $details = array(), $rating_date = '', $file_name = '', $source = 0) {
        $this->reportId = 0;
        $this->actionPlanId = $action_plan_id;
        $this->loadAqsData();
        $this->loadKpas();
        $this->loadActionPlanKpaData();
        $this->loadActionPlanActivitiesData($start_date, $end_date);
        if (isset($palnnedDate['fromDate']) && isset($palnnedDate['endDate'])) {
            $this->loadPlannedActivitiesData($palnnedDate['fromDate'], $palnnedDate['endDate']);
        }
        $this->loadimpactStatementData($start_date, $end_date);
        $this->loadStatementData($start_date, $end_date);
        $pdf = new reporttcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->assessemnt_type = 'plan';
        $pdf->report_type = 1;
        $pdf->source = 0;
        if ($source == 1) {
            $pdf->source = 1;
        }
        //$pdf->SetMargins(15, $pdf->top_margin, 15);
        $pdf->footer_text = $this->config['coverAddress'];
        $pdf->other_footer_text = ' Action Planning report:' . $this->aqsData['school_name'] . ' ' . $this->schoolCity . ' ' . $this->schoolState . ' ' . $this->schoolCountry;
        $pdf->footerBG = $this->config['footerBG'];
        $pdf->footerColor = $this->config['footerColor'];
        $pdf->footerHeight = $this->config['footerHeight'];
        $pdf->pageNoBarHeight = $this->config['pageNoBarHeight'];
        $pdf->SetTitle('Action Plan Report');
        $pdf->SetHeaderData('', '', 'Action PlannloadAqsDataing Report');
        $pdf->setHeaderFont(Array(
            PDF_FONT_NAME_MAIN,
            '',
            PDF_FONT_SIZE_MAIN
        ));
        $pdf->setFooterFont(Array(
            PDF_FONT_NAME_DATA,
            '',
            9
        ));
        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(5, 42, 5);
        $pdf->SetAutoPageBreak(TRUE, 33);
        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once (dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // set font
        $pdf->SetFont('helvetica', '', 10);
        $pdf->addPage();
        $firstpagehtml = '';
        $textAlign = 'align="center"';
        $firstpagehtml .= <<<EOD
		<table><tr><td width="100%" style="border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b>Action Planning Report</b></td></tr></table>
EOD;
        $pdf->writeHTML($firstpagehtml, true, false, true, false, '');
        $schoolDetailsHtml = '<br>';
        $schoolDetailsHtml .= '<table style="align:center;padding:3px;font-size:15px;" ><tr><td><b>School:</b> ' . $this->aqsData['school_name'] . '</td></tr>'
                . '<tr><td><b>Principal: </b>' . $this->aqsData['principal_name'] . '</td></tr>'
                . '<tr><td><b>Review Date: </b>' . ChangeFormat($rating_date, "d F Y", "") . '</td></tr>'
                . '<tr><td><b>Report time period: </b>' . ChangeFormat($start_date, "d F Y", "") . ' to ' . ChangeFormat($end_date, "d F Y", "") . '</td></tr>'
                . '</table>';
        $pdf->writeHTMLCell(0, 0, '', '', $schoolDetailsHtml, 0, 1, 0, true, 'J', true);
        $kpaDetailsHtml = '<br><br><style>th{text-align:center;}</style><table><tr><td width="100%" style="border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b> Area selected for Action Planning </b></td></tr></table><br><br>';
        $kpaDetailsHtml .= '<style>th{text-align:center;}</style><table width="100%" border="1"  style="padding:5px;"><thead>'
                . '<tr>'
                . '<th width="50%"><b>KD Name</b></th>'
                . '<th width="50%"><b>Core Standard</b></th>'
                . '</tr></thead><tbody>';

        $kqs = array();
        $cqs = array();
        $js = array();
        if (!empty($this->actionPlanKpKQ['kq_text'])) {

            $kqs = explode('@#@$', $this->actionPlanKpKQ['kq_text']);
        }
        if (!empty($this->actionPlanKpKQ['cq_text'])) {

            $cqs = explode('@#@$', $this->actionPlanKpKQ['cq_text']);
        }
        if (!empty($this->actionPlanKpKQ['js_text'])) {

            $js = explode('@#@$', $this->actionPlanKpKQ['js_text']);
        }
        if (!empty($this->actionPlanKpKQ['kpa'])) {

            $kps = explode('@#@$', $this->actionPlanKpKQ['kpa_text']);
            // print_r($kqs);
            foreach ($kps as $key => $val) {
                $kpaDetailsHtml .= '<tr nobr="true"><td width="50%" align="left">' . $val . '</td>'
                        . '<td width="50%">' . $js[$key] . '</td></tr>';
            }
        }

        $kpaDetailsHtml .= '</tbody></table>';
        $pdf->writeHTMLCell(0, 0, '', '', $kpaDetailsHtml, 0, 1, 0, true, 'J', true);

        //echo $details['text_data'];
        $extRecommendationHtml = '<br><br><style>th{text-align:center;}</style><table border="0"><tr><td width="19%" valign="top" style="font-size: 15px;font-family: CalibriRegular;"><b>Recommendation:</b></td><td style=" font-size: 15px;font-family: CalibriRegular;padding-bottom:1px;" align="left" valign="bottom" width="*" >' . $details['text_data'] . '</td></tr></table><br><br>';
        $pdf->writeHTMLCell(0, 0, '', '', '', 0, 1, 0, true, 'J', true);

        $activityDateRange = '';
        if (isset($start_date) && isset($end_date)) {
            $activityDateRange = "(" . ChangeFormat($start_date, "d F Y", "") . " To " . ChangeFormat($end_date, "d F Y", "") . ")";
        }


        $timeLineHtml = '<br><style>th{text-align:center;}</style><table nobr="true"><tr><td width="100%" style="border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b>Your journey towards improvement </b></td></tr><br>';
        // $pdf->writeHTMLCell ( 0, 0, '', '', $timeLineHtml, 0, 1, 0, true, 'J', true );

        $file_name = $this->assessmentId . "_" . $this->actionPlanId;
        if (!empty($datesrange)) {
            $file_name .= "_" . $start_date . "_" . $end_date;
        }
        $file_name .= '.png';
        $file_path = UPLOAD_URL . 'charts/' . $file_name;
        $timeLineHtml .= '<tr><td align="center" width="100%" style="border:0px soild #000000;"><img src="' . $file_path . '"></td></tr>'
                . '<tr><td><span style="color:green;">C-</span>Activities completed.'
                . '  <span style="color:rgba(255,140,0,1);">P-</span>Activities planned or in progres.'
                . '  <span style="color:red;">Ex-</span>Activities expired due to lack of action.</td></tr>'
                . '</table>';

        $pdf->writeHTMLCell(0, 0, '', '', $timeLineHtml, 0, 1, 0, true, 'J', true);
        $pdf->addPage();
        $activityHeadDetailsHtml = '<style>th{text-align:center;}</style><table><tr><td width="100%" style="border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b>Action taken by the School ' . $activityDateRange . ' </b></td></tr></table><br><br>';
        $pdf->writeHTML($activityHeadDetailsHtml, true, false, true, false, '');

        $activityDetailsHtml = '<style>th{text-align:center;}</style><table width="100%" border="1"  style="padding:5px;"><thead><tr>'
                . '<th width="20%"><b>Stakeholder</b></th>'
                . '<th width="12%"><b>Type of Activity</b></th>'
                . '<th width="14%"><b>Activity Details</b></th>'
                . '<th width="10%"><b>Status</b></th>'
                . '<th width="12%"><b>Planned date</b></th>'
                . '<th width="12%"><b>Date of Completion</b></th>'
                . '<th width="20%"><b>Comments</b></th>'
                . '</tr></thead>';
        if (!empty($this->actionPlanActivities)) {

            $activityDetailsHtml .= '<tbody>';
            foreach ($this->actionPlanActivities as $key => $val) {
                // echo "<pre>";print_r($val);
                $activityDetailsHtml .= '<tr nobr="true"><td width="20%">' . $val['designation'] . '</td>'
                        . '<td width="12%">' . $val['activity'] . '</td>'
                        . '<td width="14%">' . $val['activity_details'] . '</td>'
                        . '<td width="10%">' . $val['activity_status_name'] . '</td>'
                        . '<td width="12%">' . ChangeFormat($val['activity_date'], "d-m-Y", "") . '</td>'
                        . '<td width="12%">' . ChangeFormat($val['activity_actual_date'], "d-m-Y", "") . '</td>'
                        . '<td width="20%">' . $val['activity_comments'] . '</td></tr>';
            }
            $activityDetailsHtml .= '</tbody>';
        } else {
            $activityDetailsHtml .= '<tr><td colspan="7" align="centre" color="red">No activities undertaken in the given time period</td></tr>';
        }
        $activityDetailsHtml .= '</table><br><br>';
        $pdf->writeHTMLCell(0, 0, '', '', $activityDetailsHtml, 0, 1, 0, true, 'J', true);

        //----------plan activity details--------------------
        $dateRange = '';
        if (isset($palnnedDate['fromDate']) && isset($palnnedDate['endDate'])) {
            $dateRange = "(" . ChangeFormat($palnnedDate['fromDate'], "d F Y", "") . " To " . ChangeFormat($palnnedDate['endDate'], "d F Y", "") . ")";
        }
        $planActivityDetailsHtml = '<br><br><table><tr><td width="100%" style="border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b>Next Plan ' . $dateRange . '</b></td></tr></table><br><br>';
        $pdf->writeHTMLCell(0, 0, '', '', $planActivityDetailsHtml, 0, 1, 0, true, 'J', true);
        $planActivityDetailsHtml = '<style>th{text-align:center;}</style><table width="100%" border="1"  style="padding:5px;"><thead><tr>'
                . '<th width="40%"><b>Stakeholder</b></th>'
                . '<th width="15%"><b>Type of Activity</b></th>'
                . '<th width="30%"><b>Activity Details</b></th>'
                . '<th width="15%"><b>Planned date</b></th>'
                . '</tr></thead><tbody>';
        if (!empty($this->actionPlannedActivities)) {

            foreach ($this->actionPlannedActivities as $key => $val) {
                $planActivityDetailsHtml .= '<tr nobr="true"><td width="40%">' . $val['designation'] . '</td>'
                        . '<td width="15%">' . $val['activity'] . '</td>'
                        . '<td width="30%">' . $val['activity_details'] . '</td>'
                        . '<td width="15%">' . ChangeFormat($val['activity_date'], "d-m-Y", "") . '</td></tr>';
            }
        } else {
            $planActivityDetailsHtml .= '<tr><td colspan="4" align="centre" color="red">No activities planned for next time period</td></tr>';
        }
        $planActivityDetailsHtml .= '</tbody></table><br><br>';
        $pdf->writeHTMLCell(0, 0, '', '', $planActivityDetailsHtml, 0, 1, 0, true, 'J', true);
        if (!empty($this->actionStatements) && !empty($this->actionPlanStatements)) {
            $impactMethodDetailsHtml = '<table width="100%"  style="padding:5px 0px 0px 0px;color:blue;font-text:5px;"><thead><tr><th align="left"><b>Methods performed:</b></th></tr></thead></table>';
            $impactMethodDetailsHtml .= '<style>th{text-align:center;}</style><table width="100%" border="1"  style="padding:5px;"><thead><tr>'
                    . '<th width="25%"><b>Learning walk</b></th>'
                    . '<th width="25%"><b>Class Observations</b></th>'
                    . '<th width="25%"><b>Book Look</b></th>'
                    . '<th width="25%"><b>Interactions</b></th>'
                    . '</tr></thead>';
            $impactMethodValueHtml = '';
            $impactStatementHtml = '<table><tr><td width="100%" style="padding-left:0px;border:1px solid #c0c0c0;line-height:30px; background-color: #c8c8c8;text-align: center;font-size: 15px;font-family: CalibriRegular;color: #474343;"><b>Impact Monitoring</b></td></tr></table><h4>Below methods were used to monitor the impact.</h4>';
            $pdf->writeHTMLCell(0, 0, '', '', $impactStatementHtml, 0, 1, 0, true, 'J', true);

            $impactStatementHtmlHead = '';
            $statementCount = 1;
            foreach ($this->actionStatements as $key => $data) {

                $impactDetailsHtml = '';
                if (($key + 1) > 1) {
                    $impactStatementHtmlHead = '<br><br>';
                }
                $impactStatementHtmlHead .= '<table width="100%"  style="padding:8px 0px 0px 0px; font-text:5px;"><thead><tr><th><b>Impact statement ' . ($statementCount) . ': </b>' . $data['impact_statement'] . '</th></tr></thead></table>';
                //}

                $impactDetailsHtml = '<style>th{text-align:center;}</style><table width="100%" border="1"  style="padding:5px;"><thead><tr>'
                        . '<th width="25%"><b>Date</b></th>'
                        . '<th width="25%"><b>Method</b></th>'
                        . '<th width="25%"><b>Classes/Stakeholder</b></th>'
                        . '<th width="25%"><b>Comments</b></th>'
                        . '</tr></thead>';
                if (!empty($this->actionPlanStatements[$data['assessor_action1_impact_id']])) {
                    $pdf->writeHTMLCell(0, 0, '', '', $impactStatementHtmlHead, 0, 1, 0, true, 'J', true);
                    $impactDetailsHtml .= '<tbody>';
                    $lw = $co = $bl = $intr = 0;
                    $statementCount++;
                    foreach ($this->actionPlanStatements[$data['assessor_action1_impact_id']] as $key => $val) {
                        if ($val['activity_method_id'] == 1) {
                            $lw++;
                        } else if ($val['activity_method_id'] == 2) {
                            $co++;
                        } else if ($val['activity_method_id'] == 3) {
                            $bl++;
                        } else if ($val['activity_method_id'] == 4) {
                            $intr++;
                        }
                        $impactDetailsHtml .= '<tr nobr="true"><td width="25%">' . ChangeFormat($val['date'], "d-m-Y", "") . '</td>' . '<td width="25%">' . trim($val['method']) . '</td>';
                        if (!empty($val['activity_method_id']) && $val['activity_method_id'] == 2) {
                            $impactDetailsHtml .= '<td width="25%">' . $val['class_name'] . '</td>'
                                    . '<td width="25%" align="left">' . $val['class_comments'] . '</td>';
                        } else if (!empty($val['activity_method_id']) && $val['activity_method_id'] == 4) {
                            $impactDetailsHtml .= '<td width="25%" align="left">' . $val['designation'] . '</td>'
                                    . '<td width="25%">' . $val['stak_comments'] . '</td>';
                        } else if ($val['activity_method_id'] == 1 || $val['activity_method_id'] == 3) {
                            $impactDetailsHtml .= '<td width="25%" ></td>'
                                    . '<td width="25%">' . $val['comments'] . '</td>';
                        } else {
                            $impactDetailsHtml .= '<td></td><td></td>';
                        }
                        $impactDetailsHtml .= '</tr>';
                    }
                    if (!empty($this->actionPlanStatements[$data['assessor_action1_impact_id']])) {
                        $impactMethodValueHtml = $impactMethodDetailsHtml . '<tbody><tr><td width="25%" align="centre">' . $lw . '</td>'
                                . '<td width="25%" align="centre">' . $co . '</td>'
                                . '<td width="25%" align="centre">' . $bl . '</td>'
                                . '<td width="25%" align="centre">' . $intr . '</td></tr><tbody></table><br><br>';
                        $impactDetailsHtml .= '</tbody>';
                        $pdf->writeHTMLCell(0, 0, '', '', $impactMethodValueHtml, 0, 1, 0, true, 'J', true);
                        $impactMethodValueHtml = '';
                    }
                    $impactDetailsHtml .= '</table>';
                    $pdf->writeHTMLCell(0, 0, '', '', $impactDetailsHtml, 0, 1, 0, true, 'J', true);
                }
            }
        }
        $reportName = 'ActionPlanningReport-' . $this->aqsData['school_name'] . '-' . ChangeFormat($start_date, "d-m-Y", "") . "-" . ChangeFormat($end_date, "d-m-Y", "");

        $file_type = 'I';
        $filelocation = $reportName;
        if ($source == 1) {
            $file_type = "F";
            $filelocation = ROOT . "/" . UPLOAD_NOTIFICATION_PDF_PATH . $reportName;
        }
        $pdf->Output($filelocation . '.pdf', $file_type);
        if ($source == 1) {
            return $reportName;
        }
    }

    protected function getCreateNetType() {
        $sql = "select a.diagnostic_id,d.iscreateNet from d_assessment a INNER JOIN d_diagnostic d ON"
                . " a.diagnostic_id = d.diagnostic_id where a.assessment_id=? AND d.iscreateNet = ?";
        $res = $this->db->get_row($sql, array($this->assessmentId, 1));
        return $res['iscreateNet'] > 0 ? $res['iscreateNet'] : 0;
    }

    protected function loadAqsData() {

        if (empty($this->aqsData)) {
            $sql = "select z.zone_name,n.network_name as block,p.province_name as cluster,a.school_name,ctr.country_name,st.state_name,cty.city_name,sr.region_name,a.principal_name,a.school_address,a.principal_phone_no,STR_TO_DATE(a.school_aqs_pref_end_date, '%d-%m-%Y') as school_aqs_pref_end_date,DATE(b.create_date) as create_date,b.award_scheme_id,date(c.publishDate) as publishDate,date(valid_until) as valid_until,b.tier_id,b.client_id,b.review_criteria
				from d_AQS_data a
				inner join d_assessment b on a.id = b.aqsdata_id
				inner join d_client dc on dc.client_id = b.client_id
                                left join h_client_zone cz on cz.client_id=dc.client_id 
                                left join  d_zone z on cz.zone_id=z.zone_id 
                                left join h_client_network cn on cn.client_id=dc.client_id 
                                left join d_network n on cn.network_id=n.network_id 
                                left join h_client_province cp on cp.client_id=dc.client_id 
                                left join d_province p on cp.province_id=p.province_id
				left join d_countries ctr on ctr.country_id = dc.country_id
				left join d_states st on dc.state_id = st.state_id and st.country_id = dc.country_id
				left join d_cities cty on cty.city_id = dc.city_id and cty.state_id = dc.state_id
				left join h_assessment_report c on b.assessment_id = c.assessment_id and c.report_id= $this->reportId
				left join d_school_region sr on sr.region_id = a.school_region_id			
				where b.assessment_id = ?  
				group by a.id;";

            $this->aqsData = $this->db->get_row($sql, array($this->assessmentId));
            //echo "<pre>";print_r($this->aqsData);

            if ((isset($this->aqsData) && empty($this->aqsData['school_name'])) || !isset($this->aqsData)) {
                $sql = "select z.zone_name,n.network_name as block,p.province_name as cluster,dc.client_name as school_name,du.name as principal_name,CONCAT(COALESCE(`street`,''),' ',COALESCE(`addressLine2`,''),', ',COALESCE(cty.city_name,''),', ',COALESCE(st.state_name,'')) as school_address,ctr.country_name,st.state_name,cty.city_name,DATE(b.create_date) as create_date,b.award_scheme_id,date(c.publishDate) as publishDate,date(valid_until) as valid_until,b.tier_id,b.client_id,b.review_criteria
				from d_assessment b
				inner join d_client dc on dc.client_id = b.client_id
                                 left join h_client_zone cz on cz.client_id=dc.client_id 
                                left join  d_zone z on cz.zone_id=z.zone_id 
                                left join h_client_network cn on cn.client_id=dc.client_id 
                                left join d_network n on cn.network_id=n.network_id 
                                left join h_client_province cp on cp.client_id=dc.client_id 
                                left join d_province p on cp.province_id=p.province_id
				left join d_countries ctr on ctr.country_id = dc.country_id
				left join d_states st on dc.state_id = st.state_id and st.country_id = dc.country_id
				left join d_cities cty on cty.city_id = dc.city_id and cty.state_id = dc.state_id
				left join h_assessment_report c on b.assessment_id = c.assessment_id and c.report_id= $this->reportId 
                                left join d_user du on dc.client_id = du.client_id    
                                left join h_user_user_role dur on du.user_id = dur.user_id && dur.role_id=6
				where  dur.role_id=6 && b.assessment_id = ?  
				group by b.assessment_id;";
                $this->aqsData = $this->db->get_row($sql, array($this->assessmentId));
            }

            if (isset($this->aqsData['school_name']) && empty($this->aqsData['school_name'])) {
                $this->aqsData['school_name'] = "{School Name}";
            } else if (!isset($this->aqsData['school_name'])) {
                $this->aqsData['school_name'] = "{School Name}";
            }

            if (isset($this->aqsData['principal_name']) && empty($this->aqsData['principal_name'])) {
                $this->aqsData['principal_name'] = "{Principal Name}";
            } else if (!isset($this->aqsData['principal_name'])) {
                $this->aqsData['principal_name'] = "{Principal Name}";
            }

            if (isset($this->aqsData['school_address']) && empty($this->aqsData['school_address'])) {
                $this->aqsData['school_address'] = "{School Address}";
            } else if (!isset($this->aqsData['school_address'])) {
                $this->aqsData['school_address'] = "{School Address}";
            }

            if (isset($this->aqsData['region_name']) && empty($this->aqsData['region_name'])) {
                $this->aqsData['region_name'] = "";
            } else if (!isset($this->aqsData['region_name'])) {
                $this->aqsData['region_name'] = "";
            }

            if (isset($this->aqsData['city_name']) && empty($this->aqsData['city_name'])) {
                $this->aqsData['city_name'] = "";
            } else if (!isset($this->aqsData['city_name'])) {
                $this->aqsData['city_name'] = "";
            }

            if (isset($this->aqsData['state_name']) && empty($this->aqsData['state_name'])) {
                $this->aqsData['state_name'] = "";
            } else if (!isset($this->aqsData['state_name'])) {
                $this->aqsData['state_name'] = "";
            }

            if (isset($this->aqsData['country_name']) && empty($this->aqsData['country_name'])) {
                $this->aqsData['country_name'] = "";
            } else if (!isset($this->aqsData['country_name'])) {
                $this->aqsData['country_name'] = "";
            }

            if (isset($this->aqsData['principal_phone_no']) && empty($this->aqsData['principal_phone_no'])) {
                $this->aqsData['principal_phone_no'] = "";
            } else if (!isset($this->aqsData['principal_phone_no'])) {
                $this->aqsData['principal_phone_no'] = "";
            }

            //$this->conductedDate=(empty($this->aqsData['school_aqs_pref_end_date']) || $this->aqsData['school_aqs_pref_end_date']=="0000-00-00")?$this->conductedDate:implode("-",array_reverse(explode("-",substr($this->aqsData['school_aqs_pref_end_date'],0,7))));
            //$this->validDate=empty($this->aqsData['valid_until'])?$this->validDate:implode("-",array_reverse(explode("-",substr($this->aqsData['valid_until'],0,7))));
            $this->conductedDate = (empty($this->aqsData['school_aqs_pref_end_date']) || $this->aqsData['school_aqs_pref_end_date'] == "0000-00-00") ? $this->conductedDate : date("M-Y", strtotime($this->aqsData['school_aqs_pref_end_date']));
            $this->validDate = empty($this->aqsData['valid_until']) ? $this->validDate : date("M-Y", strtotime($this->aqsData['valid_until']));

            $this->schoolLocation = $this->aqsData['region_name'];
            $this->schoolCity = $this->aqsData['city_name'];
            $this->schoolState = $this->aqsData['state_name'];
            $this->schoolCountry = $this->aqsData['country_name'];
        }
    }

    protected function loadJudgementalStatements($type = 0, $lang_id = DEFAULT_LANGUAGE, $assessor_id) {
        if (empty($this->judgementStatement)) {

            $sql = "SELECT role,fs.level2rating, if(fs.score_id is NULL,cqjs.judgement_statement_instance_id,fs.score_id)"
                    . " as groupId, GROUP_CONCAT( CONCAT(f.file_id,'|',f.file_name) SEPARATOR '||') as files,"
                    . " fs.score_id,fs.level2rating,js.judgement_statement_id,hlt.translation_text judgement_statement_text,"
                    . "fs.evidence_text,fs.evidence_text_lw,fs.evidence_text_co,fs.evidence_text_in,evidence_text_bl, "
                    . "cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,r.rating,hls.rating_level_order "
                    . "as numericRating,rls.rating_level_scheme_id as scheme_id FROM `d_judgement_statement` js "
                    . "inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id "
                    . "inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id "
                    . "inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id "
                    . "inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id "
                    . "inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id "
                    . "inner join h_assessment_user au on au.assessment_id=a.assessment_id "
                    . "inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id "
                    . "left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id "
                    . "and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1 "
                    . "left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id "
                    . "left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id "
                    . "and fs.rating_id=hls.rating_id and hls.rating_level_id=4 left join d_rating_level rl "
                    . "on rl.rating_level_id = hls.rating_level_id left join "
                    . "(select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt "
                    . "INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id WHERE hlt.language_id=?) r "
                    . "on fs.rating_id = r.rating_id and hls.rating_id=r.rating_id left join h_score_file sf "
                    . "on sf.score_id=fs.score_id left join d_file f on sf.file_id=f.file_id where a.assessment_id=? "
                    . "and au.user_id=? and hlt.translation_type_id=4 and hlt.language_id=? group by groupId "
                    . "order by cqjs.`js_order` asc ";
            $this->judgementStatement = $this->db->array_grouping($this->db->get_results($sql, array($lang_id, $this->assessmentId, $assessor_id, $lang_id)), "core_question_instance_id", "judgement_statement_instance_id");
        }
    }

    protected function loadCoreQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->coreQuestions) || $round == 2) {

            $whrCond = 'a.assessment_id=? and';
            //$columnCond = '';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {

                $whrCond = 'a.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
                // $columnCond = 'g.assessment_id';
            }
            $params[] = $lang_id;
            $sql = "select a.assessment_id,c.key_question_instance_id,a.core_question_instance_id,hlt.translation_text as core_question_text,r.rating,role,hls.rating_level_order as numericRating
					 from h_cq_score a
					 inner join h_kq_cq c on a.core_question_instance_id = c.core_question_instance_id
					 inner join d_core_question d on d.core_question_id = c.core_question_id
                                         inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					 inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					 inner join d_assessment g on a.assessment_id = g.assessment_id
					 inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id and i.kpa_order <=7
					 inner join h_kpa_kq j on i.kpa_instance_id = j.kpa_instance_id and c.key_question_instance_id = j.key_question_instance_id
					 inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					 inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=3
                                        inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					 inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=? ) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id		
					 where $whrCond hlt.language_id=?
					 order by c.`cq_order` asc;";
            if ($round == 2) {
                return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "core_question_instance_id", "key_question_instance_id");
            } else {
                $this->coreQuestions = $this->get_section_Array($this->db->get_results($sql, $params), "core_question_instance_id", "key_question_instance_id");
            }
            //echo "<pre>";print_r($this->coreQuestions);
        }
    }

    protected function loadKeyQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->keyQuestions) || $round == 2) {

            $whrCond = 'g.assessment_id=? and';
            //$columnCond = '';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {

                $whrCond = 'g.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
                // $columnCond = 'g.assessment_id';
            }
            $params[] = $lang_id;
            $sql = "select g.assessment_id,c.kpa_instance_id,a.key_question_instance_id,hlt.translation_text as key_question_text,r.rating,role,hls.rating_level_order as numericRating
					from h_kq_instance_score a
					inner join h_kpa_kq c on a.key_question_instance_id = c.key_question_instance_id
					inner join d_key_question d on d.key_question_id = c.key_question_id	
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					inner join d_assessment g on a.assessment_id = g.assessment_id
					inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id and i.kpa_instance_id=c.kpa_instance_id and i.kpa_order <=7
					inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=2
				    inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=?) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id	
					where $whrCond hlt.language_id=?
					order by c.`kq_order` asc;";
            if ($round == 2) {
                return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "key_question_instance_id", "kpa_instance_id");
            } else {
                $this->keyQuestions = $this->get_section_Array($this->db->get_results($sql, $params), "key_question_instance_id", "kpa_instance_id");
            }
            //echo "<pre>";print_r($this->keyQuestions);
        }
    }

    protected function loadKpas($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE) {
        if (empty($this->kpas)) {
            $sql = "select a.kpa_instance_id,hlt.translation_text as KPA_name,r.rating,role,hls.rating_level_order as numericRating
					from h_kpa_instance_score a
					inner join h_kpa_diagnostic c on a.kpa_instance_id = c.kpa_instance_id 
					inner join d_kpa d on d.kpa_id = c.kpa_id
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					inner join d_assessment g on a.assessment_id = g.assessment_id
					inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=1
				        inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=?) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id
					where a.assessment_id = ? and hlt.language_id=?
					order by c.`kpa_order` asc;";
            $this->kpas = $this->get_section_Array($this->db->get_results($sql, array($lang_id, $this->assessmentId, $lang_id)), "kpa_instance_id");
        }
    }

    protected function loadActionPlanKpaData($lang = DEFAULT_LANGUAGE) {
        if (empty($this->actionPlanKpKQ)) {

            $sql = "select xyz1.*,GROUP_CONCAT(kk) as kk1,GROUP_CONCAT(js SEPARATOR '@#@$') as js_text,GROUP_CONCAT(cq SEPARATOR '@#@$') as cq_text,GROUP_CONCAT(kq SEPARATOR '@#@$') as kq_text,GROUP_CONCAT(kpa SEPARATOR '@#@$') as kpa_text  from (select xyz.*,js_o.show_text,concat('KPA-',kpa_order_f,'/','KQ-',kq_order_f,'/','SQ-',cq_order_f,'/','JS-',js_o.show_text) as kk from (SELECT a.*,f.kpa_order_f,e.kq_order_f,d.cq_order_f,c.js_order_f,((e.kq_order_f-1)*9+(d.cq_order_f-1)*3+c.js_order_f) as final_js_order,hlt.translation_text as js,hlt_cq.translation_text as cq,hlt_kq.translation_text as kq,hlt_kpa.translation_text as kpa FROM assessor_key_notes a 
                      inner join h_assessor_key_notes_js b on a.id=b.assessor_key_notes_id
                      inner join (select *,if(((js_order%3)=0),3,js_order%3) as js_order_f from h_cq_js_instance) c on b.rec_judgement_instance_id=c.judgement_statement_instance_id
                      inner join d_judgement_statement djs on djs.judgement_statement_id=c.judgement_statement_id
                      inner join h_lang_translation hlt on djs.equivalence_id=hlt.equivalence_id && hlt.language_id='" . $lang . "'
                      inner join (select *,if(((cq_order%3)=0),3,cq_order%3) as cq_order_f from h_kq_cq) d on c.core_question_instance_id=d.core_question_instance_id
                      inner join d_core_question dcq on dcq.core_question_id=d.core_question_id
                      inner join h_lang_translation hlt_cq on dcq.equivalence_id=hlt_cq.equivalence_id && hlt_cq.language_id='" . $lang . "'
                      inner join (select *,if(((kq_order % 3)=0),3,kq_order % 3) as kq_order_f from h_kpa_kq) e on e.key_question_instance_id=d.key_question_instance_id
                      
                      inner join d_key_question dkq on dkq.key_question_id=e.key_question_id
                      inner join h_lang_translation hlt_kq on dkq.equivalence_id=hlt_kq.equivalence_id && hlt_kq.language_id='" . $lang . "'
                      
                      inner join (select *,kpa_order as kpa_order_f from h_kpa_diagnostic) f on f.kpa_instance_id=e.kpa_instance_id
                      
                      inner join d_kpa dka on dka.kpa_id=f.kpa_id
                      inner join h_lang_translation hlt_kpa on dka.equivalence_id=hlt_kpa.equivalence_id && hlt_kpa.language_id='" . $lang . "'
                       
                      where a.id=?  order by a.rec_type,f.kpa_order,e.kq_order,d.cq_order,c.js_order) xyz 
                      left join d_js_order js_o on xyz.final_js_order=js_o.order_id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f) xyz1 group by id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f";

            $this->actionPlanKpKQ = $this->db->get_row($sql, array($this->actionPlanId));
        }
    }

    protected function loadActionPlanActivitiesData($start_date, $end_date, $lang = DEFAULT_LANGUAGE) {
        if (empty($this->actionPlanActivities)) {

            $sql = "select GROUP_CONCAT(a2.activity_stackholder) as activity_stackholder,a.activity_details,a.activity_comments,act.activity_status_name,group_concat(des.designation) as designation,a.activity_date,at.activity,a.activity_actual_date from h_review_action2_activity a "
                    . "INNER JOIN h_review_action2_activity_stackholder a2 ON a2.h_review_action2_activity_id = a.h_review_action2_activity_id"
                    . " INNER JOIN h_assessor_action1 a1 ON a1.h_assessor_action1_id = a.h_assessor_action1_id"
                    . " INNER JOIN d_action_activity_status act ON a.activity_status = act.activity_status"
                    . " INNER JOIN d_designation des ON a2.activity_stackholder = des.designation_id"
                    . " INNER JOIN d_activity at ON a.activity = at.activity_id WHERE a1.assessor_key_notes_id=? AND a.activity_date BETWEEN ? AND ? GROUP BY a2.h_review_action2_activity_id";
            $this->actionPlanActivities = $this->db->get_results($sql, array($this->actionPlanId, $start_date, $end_date));

            //print_r($this->actionPlanActivities);
        }
    }

    protected function loadPlannedActivitiesData($start_date, $end_date, $lang = DEFAULT_LANGUAGE) {
        if (empty($this->actionPlannedActivities)) {
            //echo $start_date; 
            $sql = "select GROUP_CONCAT(a2.activity_stackholder) as activity_stackholder,a.activity_details,a.activity_comments,act.activity_status_name,group_concat(des.designation) as designation,a.activity_date,at.activity,a.activity_actual_date from h_review_action2_activity a "
                    . "INNER JOIN h_review_action2_activity_stackholder a2 ON a2.h_review_action2_activity_id = a.h_review_action2_activity_id"
                    . " INNER JOIN h_assessor_action1 a1 ON a1.h_assessor_action1_id = a.h_assessor_action1_id"
                    . " INNER JOIN d_action_activity_status act ON a.activity_status = act.activity_status"
                    . " INNER JOIN d_designation des ON a2.activity_stackholder = des.designation_id"
                    . " INNER JOIN d_activity at ON a.activity = at.activity_id WHERE a1.assessor_key_notes_id=? AND a.activity_date BETWEEN ? AND ? AND act.activity_status=? GROUP BY a2.h_review_action2_activity_id";
            $this->actionPlannedActivities = $this->db->get_results($sql, array($this->actionPlanId, $start_date, $end_date, 0));

            //echo "<pre>"; print_r($this->actionPlannedActivities);
        }
    }

    protected function loadimpactStatementData($start_date, $end_date, $lang = DEFAULT_LANGUAGE) {
        if (empty($this->actionPlanStatements)) {

            $sql = "SELECT im.statement_id,imt.method,im.activity_method_id,im.comments,cs.class_name,cs.class_comments,ids.stak_comments,ids.designation,im.date
                    FROM h_impact_statement im
                    INNER JOIN d_impact_method imt ON imt.id = im.activity_method_id LEFT JOIN (
                    SELECT c.class_name,ic.impact_statement_id,ic.comments as class_comments FROM h_impact_statement_classes ic LEFT JOIN d_school_class c ON c.class_id = ic.class_id ) cs ON cs.impact_statement_id = im.id
                    LEFT JOIN (
                    SELECT ds.designation,st.impact_statement_id,st.comments as stak_comments FROM h_impact_statement_stakeholders st LEFT JOIN d_designation ds ON ds.designation_id = st.designation_id ) ids ON ids.impact_statement_id = im.id
                    WHERE  im.action_plan_id = ? AND im.date BETWEEN ? AND ? ORDER BY im.date  ";
            $res = $this->db->get_results($sql, array($this->actionPlanId, $start_date, $end_date));
            if (!empty($res)) {
                foreach ($res as $data) {
                    $this->actionPlanStatements[$data['statement_id']][] = $data;
                }
            }
            // echo "<pre>"; print_r($this->actionPlanStatements);
        }
    }

    protected function loadStatementData($start_date, $end_date, $lang = DEFAULT_LANGUAGE) {
        if (empty($this->actionStatements)) {
            $sql = "SELECT d.designation,im.impact_statement,im.assessor_action1_impact_id,im.assessor_action1_id,im.designation_id FROM h_assessor_action1_impact im
                 INNER JOIN h_assessor_action1 a on a.h_assessor_action1_id=im.assessor_action1_id
                 INNER JOIN d_designation d on d.designation_id=im.designation_id
                 
                 WHERE a.assessor_key_notes_id = ?
                 ";
            $this->actionStatements = $this->db->get_results($sql, array($this->actionPlanId));
        }
    }

    protected function loadAqsTeam() {
        if (empty($this->aqsTeam)) {
            $sql = "(select name,c.designation,email,isInternal
				from d_AQS_data ad
				inner join d_assessment a on a.aqsdata_id=ad.id
				inner join d_AQS_team b on b.AQS_data_id = ad.id
                                left join d_designation c on b.designation_id = c.designation_id
				where a.assessment_id = ? and isInternal=1
				group by b.id)
                                union
                                (select name,'Adhyayan Assessor' as designation,u.email as email,0 as isInternal from 
                                h_assessment_user hu inner join d_user u on hu.user_id=u.user_id 
                                where role=4 and assessment_id=?)
                                union
                                (select name,'Adhyayan Assessor' as designation,u.email as email,0 as isInternal from 
                                h_assessment_external_team ext left join d_user u on u.user_id=ext.user_id
                                left join d_user_sub_role sr on sr.sub_role_id = ext.user_sub_role
                                 where assessment_id =? order by sub_role_order)
				;";
            $this->aqsTeam = $this->db->get_results($sql, array($this->assessmentId, $this->assessmentId, $this->assessmentId));
        }
    }

    protected function getGraphHTML($valuesArray, $steps, $maxValue, $minValue = 0, $barDes = array(), $infoBelowGraph = "", $infoOnYAxis = "") {
        $lnth = count($valuesArray);
        if ($lnth == 0)
            return '';
        else if ($lnth < 5) {
            $to = 5 - $lnth;
            $emptyArray = array();
            for ($i = 0; $i < count($valuesArray[0]['values']); $i++)
                $emptyArray[] = 0;
            for ($i = 0; $i < $to; $i++) {
                $valuesArray[] = array("values" => $emptyArray, "empty" => 1);
            }
            $lnth = count($valuesArray);
        }
        $cols = count($valuesArray[0]["values"]);
        if ($cols == 0)
            return '';
        $extraTopSpaceInGraph = 44;
        $oneStepHeight = 21 * 4; //21 is the difference in 2 lines in image and we are adding 4 lines in one step
        $graphHeight = $oneStepHeight * ($maxValue - $minValue) + $extraTopSpaceInGraph; // we are adding 2 line space as buffer
        $bottomBarHeight = 50;
        $topBarHeight = 30;
        $totalHeight = $topBarHeight + $bottomBarHeight + $graphHeight;
        $html = '<div class="graphWrap">
		<div style="height:' . $totalHeight . 'px" class="stepDesc">';
        foreach ($steps as $k => $step) {
            $html .= '<div style="top:' . (($maxValue - $k) * $oneStepHeight + $topBarHeight + $extraTopSpaceInGraph - 15) . 'px;" class="graphSteps"><span class="score-' . $k . '">' . $step . '</span></div>';
        }
        $html .= '
			<div class="infoOnYAxis" style="margin-top:' . ($totalHeight / 2) . 'px;">' . $infoOnYAxis . '</div>
		</div>
		<div style="height:' . $totalHeight . 'px" class="theBarGraph">
			<table style="margin-top:' . $topBarHeight . 'px;height:' . $graphHeight . 'px" class="theBarGraphTbl">
		';
        $barNamesTbl = '';
        $addBarNames = false;

        $widthOfColumn = 100 / ($cols * $lnth + $lnth - 1); //total no. of columns + no. of space columns (in %)
        $widthOfBarNameCol = floor(10000 / $lnth) / 100;
        for ($i = 0; $i < $lnth; $i++) {
            if ($i > 0) {
                $html .= '<td style="width:' . $widthOfColumn . '%;"></td>';
                //$barNamesTbl.='<div class="barNameCol" style="width:'.$widthOfColumn.'%;">&nbsp;</div>';
            }
            for ($j = 0; $j < $cols; $j++) {
                $height = $oneStepHeight * (isset($valuesArray[$i]['values'][$j]) ? $valuesArray[$i]['values'][$j] - $minValue : 0);
                $html .= '<td ' . (isset($valuesArray[$i]['empty']) && $valuesArray[$i]['empty'] ? 'class="emptyBar"' : '') . ' style="width:' . $widthOfColumn . '%;">
					<div class="barStyle-' . $j . ' gScore-' . (isset($valuesArray[$i]['values'][$j]) ? $valuesArray[$i]['values'][$j] : 0) . '">
						<div class="barBody">
							<div class="barBodyLeft">
								<div class="barBodyRight" style="height:' . ($height > 0 ? $height + 1 : 0) . 'px;">
									<div class="barHead">
										<div class="barHeadLeft">
											<div class="barHeadRight"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</td>';
            }
            $barNamesTbl .= '<div class="barNameCol" style="width:' . $widthOfBarNameCol . '%;">';

            if (isset($valuesArray[$i]['name']) && $valuesArray[$i]['name'] != "") {
                $addBarNames = true;
                $barNamesTbl .= $valuesArray[$i]['name'];
            }
            $barNamesTbl .= '</div>';
        }

        $html .= '
			</table>
			' . ($addBarNames ? '<div style="margin-left:-' . ($widthOfColumn / 2) . '%;margin-right:-' . ($widthOfColumn / 2) . '%;" class="barNameWrap">' . $barNamesTbl . '<div class="clear"></div></div>' : '') . '
			<div class="infoBelowGraph">' . $infoBelowGraph . '</div>
		</div>
		<div class="barDesc" style="margin-top:' . ($totalHeight / 2) . 'px">
		';

        for ($i = 0; $i < count($barDes); $i++) {
            $html .= '
			<div class="barDesc-' . $i . ' barDescItem">' . $barDes[$i] . '</div>
			';
        }

        $html .= '
		</div>
			<div style="clear:both;"></div>
		</div>';
        return $html;
    }

    public function curlResultAction($url, $data) {

        $params = '';
        foreach ($data as $key => $value)
            $params .= $value . '&';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
