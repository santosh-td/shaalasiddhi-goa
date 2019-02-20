<?php

/**
 ** Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage and generate all type of reports
 * 
 */
class customReportController extends controller {

    /** Generate report for aqs type
     * @paerams:$isreportsave,$filename
     */
    function generateAqsReportAction($isreportsave = 0, $filename = '') {
        $objCustom = new customreportModel();
        $assessment_id = $_REQUEST ['assessment_id'];
        $assessor_id = $_REQUEST ['assessor_id'];
        $report_id = $_REQUEST ['report_id'];
        $lang_id = $_REQUEST ['lang_id'];
        $diagnostic_id = $_REQUEST ['diagnostic_id'];
        $pdfDm = new pdfReport($assessment_id, 0, 2);
        $demographic = $pdfDm->generateOutput();
        /* Calling function for evidence Report Start */
        $pdfEv = new pdfReport($assessment_id, 0, 6);
        $evidence = $pdfEv->generateOutput();
        /* Calling function for evidence Report End */
        if ($assessment_id != '' && !empty($assessment_id)) {
            $validDate = '';
            $customModel = new customreportModel();
            $aqsData = $customModel->loadAqsData($assessment_id);
            $client_id = '';
            if (!empty($aqsData)) {

                $client_id = $aqsData['client_id'];
            }
            if (empty($aqsData['valid_until'])) {
                $years = empty($_REQUEST ['years']) ? 0 : $_REQUEST ['years'];
                $months = empty($_REQUEST ['months']) ? 0 : $_REQUEST ['months'];
                $tMonths = $months + ($years * 12);
                if ($aqsData['edate'] == "" || $aqsData['edate'] == "0000-00-00") {
                    $conductedDate = date("M-Y", strtotime($aqsData['create_date']));
                    $validDate = date("M-Y", strtotime("+$tMonths month", strtotime($aqsData['create_date'])));
                } else {
                    $conductedDate = date("M-Y", strtotime($aqsData['edate']));
                    $validDate = date("M-Y", strtotime("+$tMonths month", strtotime($aqsData['edate'])));
                }
            } else {

                $validDate = $aqsData['valid_until'];
            }
            $js = $this->db->array_grouping($customModel->loadJudgementalStatements($assessment_id, $assessor_id, 9, 18), "core_question_instance_id", "judgement_statement_instance_id");
            $addressDetails = $customModel->getAddressDetails($client_id);
            $jsLevels = $customModel->loadJudgementalStatementsWithLevels('', $lang_id, 1, $assessment_id);
            $jsCQ = $customModel->loadCoreQuestions('', $lang_id, 1, $assessment_id);
            $jsKQ = $customModel->loadKeyQuestions('', $lang_id, 1, $assessment_id);
            $jsKpas = $customModel->loadKpas('', $lang_id, $assessment_id, 18);
            $jsBlock = array();
            $level1 = 0;
            $level2 = 0;
            $level3 = 0;
            $kpaWiseRatingData = array();
            $kpaBlockDataArray = array();
            $kpaIndex = 0;
            $numKpa = 0;
            $totalKpa = count($jsKpas);
            $kpaRatingLevels = array("LEVEL-1", "LEVEL-2", "LEVEL-3");
            $levelRating = array();
            $levelCount = 0;
            $totalRating = 0;
            $kpaLevelBlock = array();
            $kpaLevelRating = array();
            $urlArray = array();
            $rankArray = array();
            $url = array("");
            $url_count = 0;
            foreach ($jsKpas as $kpa) {
                $numKpa++;
                $jsBlock['dataHeading'] = $kpa['KPA_name'];
                $level1 = 0;
                $level2 = 0;
                $level3 = 0;
                $level1_1 = 0;
                $level2_1 = 0;
                $level3_1 = 0;
                $l0 = $l1 = $l2 = $l3 = 0;
                $l0 = $l1_2 = $l2_2 = $l3_2 = 0;
                if (isset($jsKQ[$kpa['kpa_instance_id']])) {
                    foreach ($jsKQ[$kpa['kpa_instance_id']] as $keyQ) {
                        $coreQ_count = 0;
                        if (isset($jsCQ[$keyQ['key_question_instance_id']])) {
                            foreach ($jsCQ[$keyQ['key_question_instance_id']] as $coreQ) {
                                foreach ($js[$coreQ['core_question_instance_id']] as $statment) {
                                    if (isset($statment['numericRating']) && $statment['numericRating'] == 1) {
                                        $level3++;
                                        $l3++;
                                    } else if (isset($statment['numericRating']) && $statment['numericRating'] == 2) {
                                        $level2++;
                                        $l2++;
                                    } else if (isset($statment['numericRating']) && $statment['numericRating'] == 3) {
                                        $level1++;
                                        $l1++;
                                    }
                                    if (isset($statment['level2rating']) && $statment['level2rating'] == 3) {
                                        $level1_1++;
                                        $l1_2++;
                                    } else if (isset($statment['level2rating']) && $statment['level2rating'] == 2) {
                                        $level2_1++;
                                        $l2_2++;
                                    } else if (isset($statment['level2rating']) && $statment['level2rating'] == 1) {
                                        $level3_1++;
                                        $l3_2++;
                                    }
                                    if ($statment['numericRating'] == 3) {
                                        $statment['numericRating'] = 1;
                                    } else if ($statment['numericRating'] == 1) {
                                        $statment['numericRating'] = 3;
                                    }
                                    if (isset($statment['level2rating']) && $statment['level2rating'] == 3) {
                                        $statment['level2rating'] = 1;
                                    } else if (isset($statment['level2rating']) && $statment['level2rating'] == 1) {
                                        $statment['level2rating'] = 3;
                                    }
                                    $jsBlock['dataArray'][] = array($statment['judgement_statement_text'], $statment['numericRating'], $statment['level2rating']);
                                }
                                $total = $level1 + $level2 + $level3;
                                $ratingLevel2 = array();
                                if ($numKpa == 1) {
                                    $rating = array($level1, $level2, $level3);
                                    $ratingLevel2 = array($level1_1, $level2_1, $level3_1);
                                } else
                                    $rating = array($level1, $level2, $level3);

                                $kpaLevelRating['dataArray'][] = $rating;
                                if ($numKpa == 1) {
                                    $kpaLevelRating['dataArray'][] = $ratingLevel2;
                                }
                                $kpaLevelRating['total'][] = $total;
                                if ($numKpa == 1) {
                                    $kpaLevelRating['total'][] = $level1_1 + $level2_1 + $level3_1;
                                }
                            }
                        }
                    }
                }
                $jsBlock['levelsTotal'] = array("<b>LEVEL-1</b> = $level1 , <b>LEVEL-2</b> = $level2 , <b>LEVEL-3</b> = $level3");
                $jsBlock['levels2Total'] = array("<b>LEVEL-1</b> = $level1_1 , <b>LEVEL-2</b> = $level2_1 , <b>LEVEL-3</b> = $level3_1");
                $kpaBlockDataArray[$kpaIndex] = $jsBlock;
                $jsBlock = array();
                $level1 = 0;
                $level2 = 0;
                $level3 = 0;
                if ($kpaIndex == 1 && $numKpa != $totalKpa) {
                    $kpaIndex = 0;
                    $kpaWiseRatingData[] = $kpaBlockDataArray;
                    $kpaBlockDataArray = array();
                } else {
                    $kpaIndex++;
                    if ($numKpa == $totalKpa) {
                        $kpaWiseRatingData[] = $kpaBlockDataArray;
                        $kpaBlockDataArray = array();
                    }
                }

                $weightage = 0;
                $weightage = $l1 * 3 + $l2 * 2 + $l3 * 1;
                $kpaName = str_replace(' & ', '_', $kpa['KPA_name']);

                $jsArr[$kpa['kpa_instance_id']] = array('Level-1' => $l1, 'Level-2' => $l2, 'Level-3' => $l3);
                if ($numKpa == 1) {
                    $rankArray["KD" . $numKpa . ' a'] = $weightage;
                    $urlArray["KD" . $numKpa . ' a'] = "KD" . $numKpa . ' a' . "=" . urlencode("KD" . $numKpa . ' a') . ';' . $l0 . ';' . $l1 . ';' . $l2 . ';' . $l3 . '&';
                    $weightage = $l1_2 * 3 + $l2_2 * 2 + $l3_2 * 1;
                    $rankArray["KD" . $numKpa . ' b'] = $weightage;
                    $urlArray["KD" . $numKpa . ' b'] = "KD" . $numKpa . ' b' . "=" . urlencode("KD" . $numKpa . ' b') . ';' . $l0 . ';' . $l1_2 . ';' . $l2_2 . ';' . $l3_2 . '&';
                } else {
                    $rankArray["KD" . $numKpa] = $weightage;
                    $urlArray["KD" . $numKpa] = "KD" . $numKpa . "=" . urlencode("KD" . $numKpa) . ';' . $l0 . ';' . $l1 . ';' . $l2 . ';' . $l3 . '&';
                }
            }
            arsort($rankArray, SORT_NUMERIC);
            //url	

            $isGraphPage1 = 1;
            $k = 0;
            foreach ($urlArray as $key => $val) {
                $k++;

                $url[$url_count] .= $urlArray[$key];
            }
            $this->rankArray = $rankArray;
            $graph = '';
            for ($i = 0; $i <= $url_count; $i++) {
                $file = '' . $url[$i] . '&lang_id=' . $lang_id . '';
                $data = explode("&", $file);
                $result = $this->curlResultAction('' . SITEURL . 'library/stacked.chart_shala_jr.php', $data);
                $graph .= '<div style="page-break-inside:avoid;padding-left:100px; padding-top:2px;"><div style="text-align:center;font-weight:bold;"><span style="font-weight:normal;">Table 1</span>
                                            </div><img width="570" height="400"  src="data:image/png;base64,' . base64_encode($result) . '" /></div>';
            }
            $indexArray = array(
                array('sr_no' => 1, 'value' => 'Rating Performance Data At Key Domain Level', 'page' => 2),
                array('sr_no' => 2, 'value' => 'Overall Summary - Performance across 7 Key Domains', 'page' => 3),
                array('sr_no' => 3, 'value' => 'Individual Key Domain Performance', 'page' => 3),
                array('sr_no' => 4, 'value' => 'School Demographic Details', 'page' => 5),
                array('sr_no' => 5, 'value' => 'Evidence Text Data At Key Domain Level', 'page' => 18),
            );
            include (ROOT . 'application' . DS . 'views' . DS . "customreport" . DS . 'generateaqsreport.php');
        } else
            $this->_is404 = 1;
    }

    //graph result get from curl
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

    /** cluster report form creation
     * @paerams:
     */
    function clusterReportAction() {
        if (in_array("view_all_assessments", $this->user ['capabilities'])) {

            $networkModel = new networkModel();
            $assessmentModel = new assessmentModel();
            $customreportModel = new customreportModel();
            $this->set("states", $networkModel->getStateList());
            $this->set("zones", $networkModel->getZoneList());
            $this->set("blocks", $networkModel->getNetworkList());
            $this->set("cluster", $networkModel->getProvinceList());
            $this->set("aqsRounds", $assessmentModel->getRounds());
            $this->set("reportType", $customreportModel->getClusterReportType());
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('studentreport.js');
        } else
            $this->_notPermitted = 1;
    }

    /** block report form creation
     * @paerams:
     */
    function blockReportAction() {
        if (in_array("view_all_assessments", $this->user ['capabilities'])) {
            $networkModel = new networkModel();
            $assessmentModel = new assessmentModel();
            $customreportModel = new customreportModel();
            $this->set("states", $networkModel->getStateList());
            $this->set("zones", $networkModel->getZoneList());
            $this->set("blocks", $networkModel->getNetworkList());
            $this->set("cluster", $networkModel->getProvinceList());
            $this->set("aqsRounds", $assessmentModel->getRounds());
            $this->set("reportType", $customreportModel->getClusterReportType('Block Report', 1, 4));
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('studentreport.js');
        } else
            $this->_notPermitted = 1;
    }

    /** cluster report data and generation 
     * @paerams:
     */
    function clusterDataReportAction() {
        //error_reporting(1);
        $report_id = empty($_GET['report_id']) ? 0 : $_GET['report_id'];
        $state_id = empty($_GET['state']) ? 0 : $_GET['state'];
        $zone_id = empty($_GET['zone']) ? 0 : $_GET['zone'];
        $unselectEmptySchool = empty($_GET['wtr']) ? 0 : $_GET['wtr'];
        $block_id = empty($_GET['block']) ? 0 : $_GET['block'];
        $report_name = empty($_GET['name']) ? 0 : $_GET['name'];
        $cluster_id = empty($_GET['centre_id']) ? 0 : $_GET['centre_id'];
        $round = empty($_GET['round_id']) ? 0 : $_GET['round_id'];
        $schools = isset($_GET['batch_id']) ? explode(",", $_GET['batch_id']) : array();
        $diagnosticModel = new diagnosticModel();
        $customreportModel = new customreportModel();
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;

        if ($report_id > 0 && $cluster_id > 0 && $round > 0 && $state_id > 0 && $zone_id > 0 && $block_id > 0 && !empty($report_name) && !empty($schools)) {

            $schools_arrays = array();
            $zone_name = $customreportModel->getZoneName($zone_id);
            $block_name = $customreportModel->getBlockName($block_id);
            $cluster_name = $customreportModel->getClusterName($cluster_id);
            foreach ($schools as $schkey => $schvalue) {
                $data_array = array();
                $data_array1 = array();
                $cluster_res = $customreportModel->getClusterReportData($state_id, $zone_id, $block_id, $cluster_id, $schvalue, $round);
                $school_name = $customreportModel->getClientName($schvalue);

                if (!empty($cluster_res)) {
                    foreach ($cluster_res as $cluster_res_val) {
                        $aqsData = $customreportModel->loadAqsData($cluster_res_val['assessment_id']);
                        $js = $this->db->array_grouping($customreportModel->loadJudgementalStatements($cluster_res_val['assessment_id'], $cluster_res_val['assessor_id'], DEFAULT_LANGUAGE), "core_question_instance_id", "judgement_statement_instance_id");
                        $jsLevels = $customreportModel->loadJudgementalStatementsWithLevels('', DEFAULT_LANGUAGE, 1, $cluster_res_val['assessment_id']);
                        $jsCQ = $customreportModel->loadCoreQuestions('', DEFAULT_LANGUAGE, 1, $cluster_res_val['assessment_id']);
                        $jsKQ = $customreportModel->loadKeyQuestions('', DEFAULT_LANGUAGE, 1, $cluster_res_val['assessment_id']);
                        $jsKpas = $customreportModel->loadKpas('', DEFAULT_LANGUAGE, $cluster_res_val['assessment_id']);
                        $jsBlock = array();
                        $level1 = 0;
                        $level2 = 0;
                        $level3 = 0;
                        $kpaWiseRatingData = array();
                        $kpaBlockDataArray = array();
                        $kpaIndex = 0;
                        $numKpa = 0;
                        $totalKpa = count($jsKpas);
                        $kpaRatingLevels = array("LEVEL-1", "LEVEL-2", "LEVEL-3");
                        $levelRating = array();
                        $levelCount = 0;
                        $totalRating = 0;
                        $kpaLevelBlock = array();
                        $kpaLevelRating = array();
                        foreach ($jsKpas as $kpa) {
                            $numKpa++;
                            $jsBlock['dataHeading'] = $kpa['KPA_name'];
                            $level1 = 0;
                            $level2 = 0;
                            $level3 = 0;
                            $level1_1 = 0;
                            $level2_1 = 0;
                            $level3_1 = 0;
                            if (isset($jsKQ[$kpa['kpa_instance_id']])) {
                                foreach ($jsKQ[$kpa['kpa_instance_id']] as $keyQ) {
                                    $coreQ_count = 0;
                                    if (isset($jsCQ[$keyQ['key_question_instance_id']])) {
                                        foreach ($jsCQ[$keyQ['key_question_instance_id']] as $coreQ) {
                                            foreach ($js[$coreQ['core_question_instance_id']] as $statment) {
                                                if (isset($statment['numericRating']) && $statment['numericRating'] == 1) {
                                                    $level3++;
                                                } else if (isset($statment['numericRating']) && $statment['numericRating'] == 2) {
                                                    $level2++;
                                                } else if (isset($statment['numericRating']) && $statment['numericRating'] == 3) {
                                                    $level1++;
                                                }
                                                if (isset($statment['level2rating']) && $statment['level2rating'] == 3) {
                                                    $level1_1++;
                                                } else if (isset($statment['level2rating']) && $statment['level2rating'] == 2) {
                                                    $level2_1++;
                                                } else if (isset($statment['level2rating']) && $statment['level2rating'] == 1) {
                                                    $level3_1++;
                                                }
                                                if ($statment['numericRating'] == 3) {
                                                    $statment['numericRating'] = 1;
                                                } else if ($statment['numericRating'] == 1) {
                                                    $statment['numericRating'] = 3;
                                                }
                                                if (isset($statment['level2rating']) && $statment['level2rating'] == 3) {
                                                    $statment['level2rating'] = 1;
                                                } else if (isset($statment['level2rating']) && $statment['level2rating'] == 1) {
                                                    $statment['level2rating'] = 3;
                                                }
                                                $jsBlock['dataArray'][] = array($statment['judgement_statement_text'], $statment['numericRating'], $statment['level2rating']);
                                            }
                                            $total = $level1 + $level2 + $level3;
                                            $ratingLevel2 = array();
                                            if ($numKpa == 1) {
                                                $rating = array($level1, $level2, $level3);
                                                $ratingLevel2 = array($level1_1, $level2_1, $level3_1);
                                            } else
                                                $rating = array($level1, $level2, $level3);

                                            $kpaLevelRating['dataArray'][] = $rating;
                                            if ($numKpa == 1) {
                                                $kpaLevelRating['dataArray'][] = $ratingLevel2;
                                            }
                                            $kpaLevelRating['total'][] = $total;
                                            if ($numKpa == 1) {
                                                $kpaLevelRating['total'][] = $level1_1 + $level2_1 + $level3_1;
                                            }
                                        }
                                    }
                                }
                            }
                            $jsBlock['levelsTotal'] = array("<b>LEVEL-1</b> = $level1 , <b>LEVEL-2</b> = $level2 , <b>LEVEL-3</b> = $level3");
                            $jsBlock['levels2Total'] = array("<b>LEVEL-1</b> = $level1_1 , <b>LEVEL-2</b> = $level2_1 , <b>LEVEL-3</b> = $level3_1");
                            $kpaBlockDataArray[$kpaIndex] = $jsBlock;
                            $jsBlock = array();
                            $level1 = 0;
                            $level2 = 0;
                            $level3 = 0;
                            $kpaWiseRatingData[] = $kpaBlockDataArray;
                        }
                        $data_array[] = $kpaLevelRating;
                        $data_array1[] = $kpaWiseRatingData;
                    }
                } else {
                    unset($data_array, $data_array1);
                    $data_array = array();
                    $data_array1 = array();
                }
                $schools_arrays[] = array('id' => $schvalue, 'client_name' => $school_name[0]['client_name'], 'zone_name' => $zone_name[0]['zone_name'], 'block_name' => $block_name[0]['network_name'], 'cluster_name' => $cluster_name[0]['province_name'], 'data1' => $data_array, 'data2' => $data_array1);
            }
            if ($unselectEmptySchool == 1) {
                $schools_arrays = $this->multid_sorts_school_wto_rvw($schools_arrays, 'data2');
                $wtSchoolCt = 0;
            } else {
                $schools_arrays = $this->multid_sort($schools_arrays, 'data2');
                $wtSchoolCt = $this->multid_sorts_count_wto_rvw($schools_arrays, 'data2');
            }
            include (ROOT . 'application' . DS . 'views' . DS . "customreport" . DS . 'generateclsreport.php');
            die('here');
        } else {
            $this->_is404 = 1;
        }
    }

    /** block report data and generation 
     * @paerams:
     */
    function blockDataReportAction() {
        $report_id = empty($_GET['report_id']) ? 0 : $_GET['report_id'];
        $state_id = empty($_GET['state']) ? 0 : $_GET['state'];
        $zone_id = empty($_GET['zone']) ? 0 : $_GET['zone'];
        $unselectEmptySchool = empty($_GET['wtr']) ? 0 : $_GET['wtr'];
        $block_id = empty($_GET['block']) ? 0 : $_GET['block'];
        $report_name = empty($_GET['name']) ? 0 : $_GET['name'];
        $cluster_id = empty($_GET['centre_id']) ? 0 : $_GET['centre_id'];
        $round = empty($_GET['round_id']) ? 0 : $_GET['round_id'];
        $schools = isset($_GET['batch_id']) ? explode(",", $_GET['batch_id']) : array();
        if ($report_id == 9) {
            $schoolCount = 1;
        } else
            $schoolCount = count($schools);

        $diagnosticModel = new diagnosticModel();
        $customreportModel = new customreportModel();
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        if ($report_id > 0 && $cluster_id > 0 && $round > 0 && $state_id > 0 && $zone_id > 0 && $block_id > 0 && !empty($report_name) && !empty($schoolCount)) {
            $h = 0;
            $schools_arrays = array();
            $zone_name = $customreportModel->getZoneName($zone_id);
            $block_name = $customreportModel->getBlockName($block_id);
            $cluster_name = $customreportModel->getClusterName($cluster_id);
            $blockSchoolData = $this->db->array_grouping($customreportModel->getTotalSchoolsWithAssessment($block_id), 'cluster_id');
            $clusterData = $this->db->array_grouping($customreportModel->getClustersData($block_id), 'cluster_id');
            $ratingData = $this->db->array_grouping($customreportModel->getRatingData($block_id, 'network_name'), 'kpa');
            $ratingDataClusterWise = $this->db->array_grouping($customreportModel->getRatingData($block_id), 'kpa');
            $ratingData2 = array();
            $ratingData3 = array();
            foreach ($ratingData as $key => $data) {
                $ratingData2[$key] = $this->db->array_grouping($data, 'js');
            }
            foreach ($ratingData2 as $kp => $data) {

                foreach ($data as $key => $val)
                    $ratingData3[$kp][$key] = $this->db->array_grouping($val, 'js_rating');
            }
            $ratingDataCluster = array();
            $ratingDataClusterBlock = array();
            $ratingDataClusterPro = array();
            foreach ($ratingDataClusterWise as $key => $data) {
                $ratingDataCluster[$key] = $this->db->array_grouping($data, 'js');
            }
            foreach ($ratingDataCluster as $kp => $data) {

                foreach ($data as $key => $val)
                    $ratingDataClusterBlock[$kp][$key] = $this->db->array_grouping($val, 'province_name');
            }
            foreach ($ratingDataClusterBlock as $kp => $data) {

                foreach ($data as $key => $val) {
                    foreach ($val as $valKey => $valData) {
                        $ratingDataClusterPro[$kp][$key][$valKey] = $this->db->array_grouping($valData, 'js_rating');
                    }
                }
            }
            $jsBlock = array();
            $totalSchools = 0;
            $percentageDataArray = array();
            $percentageKpaDataArray = array();
            $j = 1;
            foreach ($clusterData as $cluster_id => $clusters) {
                $totalSchools += count($clusters);
            }
            include (ROOT . 'application' . DS . 'views' . DS . "customreport" . DS . 'generateblockreport.php');
            die('here');
        } else {
            $this->_is404 = 1;
        }
    }

    function multid_sort($arr, $index) {
        $b = array();
        $c = array();
        foreach ($arr as $key => $value) {
            $b[$key] = $value[$index];
        }
        arsort($b);
        foreach ($b as $key => $value) {
            $c[] = $arr[$key];
        }
        return $c;
    }

    function multid_sorts_count_wto_rvw($arr, $index) {
        $b = array();
        $c = array();
        foreach ($arr as $key => $value) {
            $b[$key] = $value[$index];
        }
        arsort($b);
        foreach ($b as $key => $value) {
            if (empty($b[$key]))
                $c[] = $arr[$key];
        }
        return count($c);
    }

    function multid_sorts_school_wto_rvw($arr, $index) {
        $b = array();
        $c = array();
        foreach ($arr as $key => $value) {
            $b[$key] = $value[$index];
        }
        arsort($b);
        foreach ($b as $key => $value) {
            if (!empty($b[$key]))
                $c[] = $arr[$key];
        }
        return $c;
    }

    //list of all network list for custom report
    function networkreportlistAction() {
        if (in_array("view_all_assessments", $this->user ['capabilities'])) {
            $customreportModel = new customreportModel();
            $assessmentModel = new assessmentModel();
            $networkModel = new networkModel();
            $cPage = empty($_POST['page']) ? 1 : $_POST['page'];
            $order_type = empty($_POST['order_type']) ? "desc" : $_POST['order_type'];
            $order_by = empty($_POST['order_by']) ? "create_date" : $_POST['order_by'];
            $assessment_type_id = empty($_POST['assessment_type_id']) ? 0 : $_POST['assessment_type_id'];
            $report_id = empty($_POST['report_id']) ? 0 : $_POST['report_id'];
            $param = array(
                "report_name_like" => empty($_POST['report_name']) ? "" : $_POST['report_name'],
                "report_id" => empty($_POST['report_type']) ? 0 : $_POST['report_type'],
                "assessment_type_id" => empty($_POST['assessment_type_id']) ? 0 : $_POST['assessment_type_id'],
                "network_id" => empty($_POST['network_id']) ? 0 : $_POST['network_id'],
                "province_id" => empty($_POST['province_id']) ? 0 : $_POST['province_id'],
                "client_id" => empty($_POST['client_id']) ? 0 : $_POST['client_id'],
                "round_id" => empty($_POST['round_id']) ? 0 : $_POST['round_id'],
                "page" => $cPage,
                "order_by" => $order_by,
                "order_type" => $order_type,
            );
            $this->set("filterParam", $param);
            $this->set("cPage", $cPage);
            $this->set("orderBy", $order_by);
            $this->set("orderType", $order_type);
            $this->set('networkReportList', $customreportModel->getNetworkReportsList($param));
            $this->set("pages", $customreportModel->getPageCount());
            $report_type = $assessmentModel->getAssessmentTypes();
            $report_type_array = array();
            foreach ($report_type as $key => $val) {
                $report_type_array[] = array_map('ucfirst', $val);
            }
            $this->set("report_type", $report_type_array);
            $this->set("networks", $networkModel->getNetworkList());
            $this->set("aqsRounds", $assessmentModel->getRounds());
            $this->set("reportType", $customreportModel->getStuReportType());
            $this->set("provinces", empty($_POST['network_id']) ? array() : $networkModel->getProvinces($_POST['network_id']));
            $this->set("clients", empty($_POST['province_id']) ? array() : $networkModel->getSchools($_POST['province_id']));

            $etc = ($assessment_type_id == 4) ? "" : "style='display:none'";
            $etc1 = ($report_id == "12") ? " disabled='disabled'" : "";
            $etc2 = ($report_id == "11" || $report_id == "12") ? " disabled='disabled'" : "";
            $this->set("etc", $etc);
            $this->set("etc1", $etc1);
            $this->set("etc2", $etc2);
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('studentreport.js');
        } else
            $this->_notPermitted = 1;
    }

}
