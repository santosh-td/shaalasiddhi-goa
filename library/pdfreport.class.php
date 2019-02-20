<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */

class pdfReport extends reportClass {

    protected $subAssessmentType;
    protected $awardBreakdown;
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

    function __construct($assessment_id, $subAssessmentType, $report_id, $conductedDate = '', $validDate = '') {
        $this->assessmentId = $assessment_id;
        $this->subAssessmentType = $subAssessmentType;
        $this->sameRatingPercentage = 0;
        parent::__construct($report_id, $conductedDate, $validDate);
    }

    public function generateOutput($lang_id = DEFAULT_LANGUAGE, $round = 1) {
        $this->config['isChangeMaker'] = $this->isChangeMaker();
        switch ($this->reportId) {
            case 1:$diagId = $this->getDiagnosticId();
                $diagType = $this->getDiagnosticType();
                $this->config['isCollobrative'] = $this->isCollobrative();
                if ($diagType == 1) {
                    $this->config['isChildProt'] = 1 && $this->config['childProtImg'] = '.' . DS . 'public' . DS . 'images' . DS . 'diagnostic_adhyayan.png'; //for Don-Bosco show logo in reports  
                } else
                    $diagId == 1 ? ($this->config['isCoBranded'] = 1 && $this->config['coBrandedImg'] = '' . UPLOAD_URL_DIAGNOSTIC . 'diagnostic_image_' . $diagId . '.jpg') : '';
                return $this->generateAqsOutput($lang_id);
                break;
            case 2:$diagId = $this->getDiagnosticId();
                $diagType = $this->getDiagnosticType();
                $this->config['isDemographic'] = 1;
                if ($diagType == 1) {
                    $this->config['isChildProt'] = 1 && $this->config['childProtImg'] = '.' . DS . 'public' . DS . 'images' . DS . 'diagnostic_adhyayan.png';
                } else
                    $diagId == 1 ? ($this->config['isCoBranded'] = 1 && $this->config['coBrandedImg'] = '' . UPLOAD_URL_DIAGNOSTIC . 'diagnostic_image_' . $diagId . '.jpg') : ''; //for Don-Bosco show logo in reports

                return $this->generateSchoolProfileOutput();
                break;

            case 3:$diagId = $this->getDiagnosticId();
                $diagId == 1 ? ($this->config['isCoBranded'] = 1 && $this->config['coBrandedImg'] = '' . UPLOAD_URL_DIAGNOSTIC . 'diagnostic_image_' . $diagId . '.jpg') : '';

                return $this->generateRecommendationOutput();
                break;

            case 6: $diagId = $this->getDiagnosticId();
                $diagType = $this->getDiagnosticType();
                $this->config['isDemographic'] = 1;
                if ($diagType == 1) {
                    $this->config['isChildProt'] = 1 && $this->config['childProtImg'] = '.' . DS . 'public' . DS . 'images' . DS . 'diagnostic_adhyayan.png';
                    $this->config['childProtImg2'] = '.' . DS . 'public' . DS . 'images' . DS . 'Seal_of_Goa.jpg';
                    $this->config['childProtImg3'] = '.' . DS . 'public' . DS . 'images' . DS . 'advaithlogo.jpg';
                } else
                    $diagId == 1 ? ($this->config['isCoBranded'] = 1 && $this->config['coBrandedImg'] = '' . UPLOAD_URL_DIAGNOSTIC . 'diagnostic_image_' . $diagId . '.jpg') : '';

                return $this->generateEvidenceOutput($lang_id);
                break;
        }
    }

    protected function generateAqsOutput($lang_id = DEFAULT_LANGUAGE, $round = 1) {
        $isCollobrative = $this->config['isCollobrative'];
        $this->loadAqsData();
        $this->loadJudgementalStatements('', $lang_id);
        $this->loadJudgementalStatementsWithLevels('', $lang_id);
        $this->loadCoreQuestions('', $lang_id);
        $this->loadKeyQuestions('', $lang_id);
        $this->loadKpas('', $lang_id);

        $this->config['getCreateNetType'] = $this->getCreateNetType();
        $diagnosticLabels = array();
        $languageLabels = $this->getDiagnosticLabels($lang_id);
        foreach ($languageLabels as $data) {
            $diagnosticLabels[$data['label_key']] = $data['label_text'];
        }
        if ($isCollobrative == 1) {
            $this->aqsInfo = '<div class="bigBold">{schoolName}, {schoolAddress}</div>
		<div class="reportInfo">Evaluation Report based on Shaala Siddhi<br>' . str_replace("&", '{conductedOn}', $diagnosticLabels['Conducted_On']) . '<br>' . str_replace("&", '{validTill}', $diagnosticLabels['Valid_Until']) . ' </div>';
        } else {
            if ($lang_id == 9) {
                $this->aqsInfo = '<div class="bigBold">{schoolName}, {schoolAddress}</div>
		<div class="reportInfo">' . $diagnosticLabels['Compilation_Scores'] . '<br>' . $diagnosticLabels['School_Self_Review_Evaluation_title'] . ($this->subAssessmentType != 1 ? '<br>' . $diagnosticLabels['And'] . '<br>' . $diagnosticLabels['School_External_Review_Evaluation_title'] : '') . '<br>' . str_replace("&", '{conductedOn}', $diagnosticLabels['Conducted_On']) . '<br>' . str_replace("&", '{validTill}', $diagnosticLabels['Valid_Until']) . ' </div>';
            } else {
                $this->aqsInfo = '<div class="bigBold">{schoolName}, {schoolAddress}</div>
		<div class="reportInfo">' . $diagnosticLabels['School_Self_Review_Evaluation_title'] . ($this->subAssessmentType != 1 ? '<br>' . $diagnosticLabels['And'] . '<br>' . $diagnosticLabels['School_External_Review_Evaluation_title'] : '') . '<br>' . $diagnosticLabels['Compilation_Scores'] . '<br>' . str_replace("&", '{conductedOn}', $diagnosticLabels['Conducted_On']) . '<br>' . str_replace("&", '{validTill}', $diagnosticLabels['Valid_Until']) . ' </div>';
            }
        }

        $this->config['reportTitle'] = $diagnosticLabels['Adhyayan_Report_Card_Title'];
        $this->config["footerText"] = "Powered by Adhyayan Quality Education Services Private Limited & Advaith Foundation";
        $this->sectionArray = array();
        $this->indexArray = array();
        $this->generateSection_ScoreCardForKPALevels('', $lang_id, $diagnosticLabels);
        $this->generateIndexAndCover($diagnosticLabels);
        $output = array(
            "config" => $this->config,
            "data" => $this->sectionArray
        );

        return $output;
    }

    protected function generateSection_ScoreCardForKPALevels($skipComparisonSection = 0, $lang_id, $diagnosticLabels = array()) {
        $totalKpas = count($this->kpas);
        $schemeId = ($this->reportId == 5 || $this->reportId == 9) ? 2 : 1;
        $comparisonSection = array();
        $isCollobrative = $this->config['isCollobrative'];

        $kpa_count = 0;
        $kpaCountArray = array("Domain Levels");
        foreach ($this->kpas as $kpa) {
            $kpa_count++;
            $cspan = 0;
            $group = '';
            if ($kpa_count == 1) {
                $cspan = 2;
                $group = '<table border="0" width="100%"><tr><td width="50%">A & A</td><td width="50%">Q & U</td></tr></table>';
            }
            $kpaCountArray[] = array("text" => "KD" . $kpa_count . $group, "cSpan" => $cspan);
        }
        if ($skipComparisonSection == 0 && $this->config['getCreateNetType'] != 1) {
            if ($isCollobrative == 1) {
                $kpaNum = 'Overall Summary - Performance across ' . $totalKpas . ' Key Domains';
            } else {
                $kpaNum = ($totalKpas) > 1 ? str_replace('&', $totalKpas, $diagnosticLabels['kpa_performance_area_title']) : str_replace('&', '', $diagnosticLabels['kpa_performance_area_title']);
            }

            $indexKey = $this->addIndex($kpaNum);
            $comparisonSection = array("sectionHeading" => array("text" => "1. $kpaNum", "style" => "greyHead"), "sectionBody" => array(), "indexKey" => $indexKey);
            $indexKey = $this->addIndex('Individual Key Domain Performance');
        }

        if ($isCollobrative == 1) {
            $kpaLevelBlock = array(
                "blockHeading" => array(
                    "data" => $kpaCountArray
                ),
                "blockBody" => array(
                    "dataArray" => array()
                ),
                "style" => "bordered comparisonBlock",
            );
        }
        $kpa_count = 0;
        $kpaSectionArray = array();
        $numberToAlpha = array(1 => "a", 2 => "b", 3 => "c", 4 => "d");
        $kpaValuesForGraph = array();
        $kpaRatingLevels = array("LEVEL-1", "LEVEL-2", "LEVEL-3");
        $levelRating = array();
        $levelCount = 0;
        $totalRating = 0;
        $ratingSum = array('Total');
        foreach ($kpaRatingLevels as $levelData) {
            $levelCount++;
            $levelRating = array();
            $levelRating[] = $levelData;
            $level2 = 0;
            foreach ($this->judgementStatementLevels as $jsData) {
                $levelRating[] = $jsData['level' . $levelCount];
                $level2++;
                if ($level2 == 1) {
                    $levelRating[] = $jsData['level_2_' . $levelCount];
                }
                if ($levelCount == 3) {
                    $ratingSum[] = $jsData['level1'] + $jsData['level2'] + $jsData['level3'];
                    $sumLevel2 = $jsData['level_2_1'] + $jsData['level_2_2'] + $jsData['level_2_3'];
                    if ($sumLevel2 >= 1) {
                        $ratingSum[] = $jsData['level_2_1'] + $jsData['level_2_2'] + $jsData['level_2_3'];
                    }
                }
            }
            $kpaLevelBlock['blockBody']['dataArray'][] = $levelRating;
        }
        $kpaLevelBlock['blockBody']['dataArray'][] = $ratingSum;
        $comparisonSection['sectionBody'][] = $kpaLevelBlock;
        $kpaHeadingCount = 0;
        $kpaNameArray = array();
        $graphBlock = array(
            "blockHeading" => array(
                "data" => array(),
            ),
            "GridBlock" => array(
                "dataArray" => array(),),
            "style" => "grid-block",
            "width" => "100"
        );
        $block = array(
            "blockHeading" => array(
                "data" => array()
            ),
            "GridBlockLevels" => array(
                "dataArray" => array()
            ),
            "style" => "kpaLevel"
        );
        foreach ($this->kpas as $kpa) {
            $kpa_count++;
            $kpaHeadingCount++;
            $kpaNameArray[] = array("text" => "<span><b>" . $diagnosticLabels['KEY_DOMAIN'] . $kpa_count . "-" . $kpa['KPA_name'] . '</b></span>', "cWidth" => '50%', "grid" => 1, "gridHeading" => array('Core Standard', 'Levels'), 'gridWidth' => "50%");
            if ($kpaHeadingCount == 2) {

                $kpaHeadingCount = 0;
                $graphBlock['GridBlock']['dataArray'][] = $kpaNameArray;
                $kpaNameArray = array();
            } else if ($kpa_count == count($this->kpas)) {
                $graphBlock['GridBlock']['dataArray'][] = $kpaNameArray;
            }

            $jsBlock = array(
                "blockHeading" => array(
                    "data" => array()
                ),
                "GridBlockData" => array(
                    "dataArray" => array()
                ),
                "style" => "bordered comparisonBlock"
            );

            $keyQ_count = 0;
            $coreQsInKPA = 0;
            $level1 = $level2 = $level3 = 0;
            if (isset($this->keyQuestions[$kpa['kpa_instance_id']])) {
                foreach ($this->keyQuestions[$kpa['kpa_instance_id']] as $keyQ) {
                    $coreQ_count = 0;
                    if (isset($this->coreQuestions[$keyQ['key_question_instance_id']])) {
                        foreach ($this->coreQuestions[$keyQ['key_question_instance_id']] as $coreQ) {
                            $coreQ_count++;
                            $coreQsInKPA++;
                            $satatement_count = 0;
                            foreach ($this->judgementStatement[$coreQ['core_question_instance_id']] as $statment) {

                                if (isset($statment['externalRating']['score']) && $statment['externalRating']['score'] == 1) {
                                    $level3++;
                                } else if (isset($statment['externalRating']['score']) && $statment['externalRating']['score'] == 2) {
                                    $level2++;
                                } else if (isset($statment['externalRating']['score']) && $statment['externalRating']['score'] == 3) {
                                    $level1++;
                                }
                                $jsBlock['GridBlockData']['dataArray'][] = array($statment['judgement_statement_text'], $statment['externalRating']['score']);
                            }
                        }
                    }

                    $jsBlock['GridBlockLevels']['dataArray'][] = array("<b>LEVEL-1</b> = $level1 , <b>LEVEL-2</b> = $level2 , <b>LEVEL-3</b> = $level3");
                    $comparisonSection['sectionBody'][] = $jsBlock;
                }
            }
        }
        $comparisonSection['sectionBody'][] = $graphBlock;
        if ($skipComparisonSection == 0 && $this->config['getCreateNetType'] != 1) {
            $this->sectionArray = array_merge($this->sectionArray, array($comparisonSection));
        } else {
            if ($this->config['getCreateNetType'] == 1) {
                $this->sectionArray = array_merge($this->sectionArray, array($comparisonSection));
            }
            if ($this->reportId == 1 && $this->subAssessmentType != 1) {
                $this->sectionArray = array_merge($this->sectionArray, $judgedistance);
                $this->sectionArray = array_merge($this->sectionArray, $ratingperformance);
            }
            $this->sectionArray = array_merge($this->sectionArray, $kpaSectionArray);
        }
    }

    protected function loadJudgementalStatementsWithLevels($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->judgementStatementLevels) || $round == 2) {
            $whrCond = 'a.assessment_id=? and';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {
                $whrCond = 'a.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
            }
            $params[] = $lang_id;
            $sql = "
				select count(if(hls.rating_level_order = 3,hls.rating_level_order,null)) as level1,
                                count(if(hls.rating_level_order = 2,hls.rating_level_order,null)) as level2,
                                count(if(hls.rating_level_order = 1,hls.rating_level_order,null)) as level3,
                                count(if(a.level2rating = 3,a.level2rating,null)) as level_2_1,
                                count(if(a.level2rating = 2,a.level2rating,null)) as level_2_2,
                                count(if(a.level2rating = 1,a.level2rating,null)) as level_2_3,
                                group_concat(hls.rating_level_order),group_concat(a.level2rating),i.kpa_instance_id,a.assessment_id,c.core_question_instance_id,
                                c.judgement_statement_instance_id,
                                hlt.translation_text as judgement_statement_text,role,r.rating,hls.rating_level_order as 
                                numericRating from f_score a 
                                inner join h_assessment_user b on a.assessor_id = b.user_id 
                                and a.assessment_id = b.assessment_id 
                                inner join h_cq_js_instance c on
                                 a.judgement_statement_instance_id = c.judgement_statement_instance_id 
                                 inner join d_judgement_statement d 
                                 on d.judgement_statement_id = c.judgement_statement_id 
                                 inner join d_assessment g on a.assessment_id = g.assessment_id 
                                 inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
                                 inner join h_kpa_diagnostic h on h.diagnostic_id = g.diagnostic_id and h.kpa_order <=7 
                                 inner join h_kpa_kq i on h.kpa_instance_id = i.kpa_instance_id 
                                 inner join h_kq_cq j on i.key_question_instance_id = j.key_question_instance_id 
                                 and j.core_question_instance_id = c.core_question_instance_id 
                                 inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
                                 inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id 
                                 and a.rating_id=hls.rating_id and hls.rating_level_id=4 
                                 inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
                                 inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt 
                                 INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id 
                                 where hlt.language_id=? ) r on a.rating_id = r.rating_id and hls.rating_id=r.rating_id 
                                 where a.isFinal = 1 and role=4 and $whrCond hlt.language_id=?
					 group by i.kpa_instance_id order by i.key_question_instance_id asc ;";
            if ($round == 2) {

                return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "judgement_statement_instance_id", "core_question_instance_id");
            } else {
                $this->judgementStatementLevels = $this->get_section_Array($this->db->get_results($sql, $params), "kpa_instance_id");
            }
        }
    }

    protected function createComparisionPieChart($sameRatingKey, $improvedRatingKey, $decreasedRatingKey, $file_name) {
        $size = 250;
        $character = '{"options":'
                . '{"chart":{"plotBackgroundColor":null,"plotBorderWidth":null,"plotShadow":false,"type":"pie","width":430,"height":300},'
                . '"title":{"text":""},'
                . '"credits":{"enabled":false},'
                . '"legend": {"fontSize": "15px","fontWeight":"bold","align":"right","layout":"vertical","verticalAlign":"top","x": 15,"y": 110},'
                . '"tooltip":{"pointFormat":"{series.name}: <b>{point.percentage:.1f}%</b>"},'
                . '"series":[{"name":"Browser share","data":[{"name":"Same Ratings","y":' . $sameRatingKey . ',"sliced":false,"selected":true},'
                . '["Improve Rating",' . $improvedRatingKey . '],["Decreased Rating",' . $decreasedRatingKey . ']]}],'
                . '"colors":["#4F81BD", "#9BBB59", "#C0504D"],'
                . '"plotOptions":{"pie":{"size":' . $size . ',"allowPointSelect":true,"cursor":"pointer",'
                . '"dataLabels":{"enabled":true,"format":"{point.percentage:.1f} %","distance":-50,'
                . '"filter":{"property":"percentage","operator":">","value":4}},"showInLegend":false}}}}';
        $url_new = DOWNLOAD_CHART_URL;
        $ch = curl_init($url_new);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $character);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "cache-control: no-cache",
            "content-type: application/json",
        ));
        if (curl_errno($ch)) {
            return false;
        } else {
            curl_exec($ch);
            $fileName = curl_exec($ch);
            $s3_upload_url = '';
            $upload_url = ROOT . UPLOAD_PATH . "charts/" . $file_name;
            $s3_upload_url = "" . UPLOAD_PATH . "charts/" . $file_name;
            file_put_contents($upload_url, $fileName);
            if (upload_file($s3_upload_url, $upload_url)) {
                @unlink($upload_url);
            }
        }
    }

    protected function generateRecommendationOutput() {

        $diagnosticLabels = array();
        $languageLabels = $this->getDiagnosticLabels();
        foreach ($languageLabels as $data) {
            $diagnosticLabels[$data['label_key']] = $data['label_text'];
        }
        $this->loadAqsData();
        $this->loadJudgementalStatements();
        $this->loadCoreQuestions();
        $this->loadKeyQuestions();
        $this->loadKpas();
        $this->loadAqsTeam();
        $this->loadAssessorKeyNotes();
        $this->loadRecommendations();
        $this->aqsInfo = '
		<table class="recomCoverBlock"><tr><td>
			<div class="bigText">Adhyayan Quality Standard Report</div><br><br><br><br>
			<div class="mediumText">Recommendations for School Improvement following the award of </div><br><br>
			<div class="mBigText">{awardName}</div><br>
			<div class="">Valid until: {validTill}</div><br><br><br>
			<div class="mediumText">To</div><br><br><br>
			<div class="mBigText">{schoolName}, {schoolAddress}</div><br>
			<div class="">Based on Adhyayan\'s School External Review and Evaluation (SERE)</div>
		</td></tr></table>';

        $this->config['reportTitle'] = "ADHYAYAN QUALITY STANDARD REPORT";
        $this->config["footerText"] = "Adhyayan Quality Standard award report for {schoolName}, {dateToday} (generated on)";
        $this->sectionArray = array();
        $this->indexArray = array();
        $this->assessorKeyNotes();
        $this->keyForReadingRecommendationReport();
        $this->recommendationsOnKpa();
        $this->generateIndexAndCover();
        $output = array(
            "config" => $this->config,
            "data" => $this->sectionArray
        );
        return $output;
    }

    protected function loadAqsData() {
        if (empty($this->aqsData)) {
            $sql = "
				select a.school_name,ctr.country_name,st.state_name,cty.city_name,sr.region_name,a.principal_name,a.school_address,a.principal_phone_no,STR_TO_DATE(a.school_aqs_pref_end_date, '%d-%m-%Y') as school_aqs_pref_end_date,b.award_scheme_id,date(c.publishDate) as publishDate,date(valid_until) as valid_until,b.tier_id,b.client_id,b.review_criteria
				from d_AQS_data a
				inner join d_assessment b on a.id = b.aqsdata_id
				inner join d_client dc on dc.client_id = b.client_id
				left join d_countries ctr on ctr.country_id = dc.country_id
				left join d_states st on dc.state_id = st.state_id and st.country_id = dc.country_id
				left join d_cities cty on cty.city_id = dc.city_id and cty.state_id = dc.state_id
				left join h_assessment_report c on b.assessment_id = c.assessment_id and c.report_id= $this->reportId
				left join d_school_region sr on sr.region_id = a.school_region_id			
				where b.assessment_id = ?  
				group by a.id;";
            $this->aqsData = $this->db->get_row($sql, array($this->assessmentId));

            if ((isset($this->aqsData) && empty($this->aqsData['school_name'])) || !isset($this->aqsData)) {
                $sql = "select dc.client_name as school_name,du.name as principal_name,CONCAT(COALESCE(`street`,''),' ',COALESCE(`addressLine2`,''),', ',COALESCE(cty.city_name,''),', ',COALESCE(st.state_name,'')) as school_address,ctr.country_name,st.state_name,cty.city_name,DATE(b.create_date) as create_date,b.award_scheme_id,date(c.publishDate) as publishDate,date(valid_until) as valid_until,b.tier_id,b.client_id,b.review_criteria
				from d_assessment b
				inner join d_client dc on dc.client_id = b.client_id
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

            $this->conductedDate = (empty($this->aqsData['school_aqs_pref_end_date']) || $this->aqsData['school_aqs_pref_end_date'] == "0000-00-00") ? $this->conductedDate : date("M-Y", strtotime($this->aqsData['school_aqs_pref_end_date']));
            $this->validDate = empty($this->aqsData['valid_until']) ? $this->validDate : date("M-Y", strtotime($this->aqsData['valid_until']));

            $this->schoolLocation = $this->aqsData['region_name'];
            $this->schoolCity = $this->aqsData['city_name'];
            $this->schoolState = $this->aqsData['state_name'];
            $this->schoolCountry = $this->aqsData['country_name'];
        }
    }

    protected function isChangeMaker() {
        $sql = "select count(*) as num from d_assessment where assessment_id=? and diagnostic_id=32";
        $res = $this->db->get_row($sql, array($this->assessmentId));
        return $res['num'] > 0 ? 1 : 0;
    }

    protected function loadJudgementalStatements($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->judgementStatement) || $round == 2) {
            $whrCond = 'a.assessment_id=? and';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {

                $whrCond = 'a.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
            }
            $params[] = $lang_id;
            $sql = "
				select a.assessment_id, c.core_question_instance_id,c.judgement_statement_instance_id,hlt.translation_text as judgement_statement_text,role,r.rating,hls.rating_level_order as numericRating
					 from f_score a                                         	
					 inner join h_assessment_user b on a.assessor_id = b.user_id and a.assessment_id = b.assessment_id 
					 inner join h_cq_js_instance c on a.judgement_statement_instance_id = c.judgement_statement_instance_id
					 inner join d_judgement_statement d on d.judgement_statement_id = c.judgement_statement_id					 
					 inner join d_assessment g on a.assessment_id = g.assessment_id
                                         inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					 inner join h_kpa_diagnostic h on h.diagnostic_id = g.diagnostic_id 
					 inner join h_kpa_kq i on h.kpa_instance_id = i.kpa_instance_id
					 inner join h_kq_cq j on i.key_question_instance_id = j.key_question_instance_id and j.core_question_instance_id = c.core_question_instance_id					 
					 inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					 inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.rating_id=hls.rating_id and hls.rating_level_id=4
                                         inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					 inner join  (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=? ) r on a.rating_id = r.rating_id and hls.rating_id=r.rating_id			
					 where a.isFinal = 1 and $whrCond hlt.language_id=?
					 order by c.`js_order` asc ;";
            if ($round == 2) {

                return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "judgement_statement_instance_id", "core_question_instance_id");
            } else {
                $this->judgementStatement = $this->get_section_Array($this->db->get_results($sql, array($lang_id, $this->assessmentId, $lang_id)), "judgement_statement_instance_id", "core_question_instance_id");
            }
        }
    }

    protected function loadCoreQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->coreQuestions) || $round == 2) {

            $whrCond = 'a.assessment_id=? and';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {

                $whrCond = 'a.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
            }
            $params[] = $lang_id;
            $sql = "select a.assessment_id ,c.key_question_instance_id,a.core_question_instance_id,hlt.translation_text as core_question_text,r.rating,role,hls.rating_level_order as numericRating
					 from h_cq_score a
					 inner join h_kq_cq c on a.core_question_instance_id = c.core_question_instance_id
					 inner join d_core_question d on d.core_question_id = c.core_question_id
                                         inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					 inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					 inner join d_assessment g on a.assessment_id = g.assessment_id
					 inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id 
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
                $this->coreQuestions = $this->get_section_Array($this->db->get_results($sql, array($lang_id, $this->assessmentId, $lang_id)), "core_question_instance_id", "key_question_instance_id");
            }
        }
    }

    protected function loadKeyQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1) {
        if (empty($this->keyQuestions) || $round == 2) {

            $whrCond = 'g.assessment_id=? and';
            $params = array($lang_id, $this->assessmentId);
            if ($round == 2) {

                $whrCond = 'g.assessment_id in(?,?) and';
                $params[] = $this->assessmentIdRound2;
            }
            $params[] = $lang_id;
            $sql = "select a.assessment_id,c.kpa_instance_id,a.key_question_instance_id,hlt.translation_text as key_question_text,r.rating,role,hls.rating_level_order as numericRating
					from h_kq_instance_score a
					inner join h_kpa_kq c on a.key_question_instance_id = c.key_question_instance_id
					inner join d_key_question d on d.key_question_id = c.key_question_id	
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					inner join d_assessment g on a.assessment_id = g.assessment_id
					inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id and i.kpa_instance_id=c.kpa_instance_id 
					inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=2
				    inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=?) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id	
					where $whrCond hlt.language_id=?
					order by c.`kq_order` asc;";
            if ($round == 2) {
                return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "key_question_instance_id", "kpa_instance_id");
            } else {
                $this->keyQuestions = $this->get_section_Array($this->db->get_results($sql, array($lang_id, $this->assessmentId, $lang_id)), "key_question_instance_id", "kpa_instance_id");
            }
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

    protected function loadAssessorKeyNotes() {
        if (empty($this->keyNotes)) {
            $sql = "SELECT * FROM assessor_key_notes where assessment_id=? ;";
            $this->keyNotes = $this->db->array_grouping($this->db->get_results($sql, array($this->assessmentId)), "kpa_instance_id");
        }
    }

    protected function loadRecommendations() {
        if (empty($this->recommendationText)) {
            $sql = "select c.judgement_statement_instance_id,hlt.translation_text as recommendation_text,a.rating_id
				from f_score a
				inner join h_assessment_user b on a.assessor_id = b.user_id and a.assessment_id = b.assessment_id and role = 4 
				inner join h_cq_js_instance c on a.judgement_statement_instance_id = c.judgement_statement_instance_id
				inner join d_judgement_statement d on d.judgement_statement_id = c.judgement_statement_id				
				inner join d_assessment g on a.assessment_id = g.assessment_id
				inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
				inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.rating_id=hls.rating_id and hls.rating_level_id=4
				inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
				inner join d_rating r on a.rating_id = r.rating_id and hls.rating_id=r.rating_id	
				inner join h_jstatement_recommendation h on h.rating_id = a.rating_id and h.judgement_statement_id = d.judgement_statement_id and h.isActive=1
				inner join d_recommendation i on i.recommendation_id = h.recommendation_id
                                inner join h_lang_translation hlt on i.equivalence_id = hlt.equivalence_id
				where a.isFinal = 1 and a.assessment_id = $this->assessmentId
				order by c.`js_order` asc;";
            $this->recommendationText = $this->db->array_grouping($this->db->get_results($sql, array($this->assessmentId)), "judgement_statement_instance_id");
        }
    }

    protected function loadAqsTeam() {
        if (empty($this->aqsTeam)) {
            $sql = "select name,c.designation,email,isInternal
				from d_AQS_data ad
				inner join d_assessment a on a.aqsdata_id=ad.id
				inner join d_AQS_team b on b.AQS_data_id = ad.id
                                left join d_designation c on b.designation_id = c.designation_id
				where a.assessment_id = $this->assessmentId
				group by b.id
				;";
            $this->aqsTeam = $this->db->get_results($sql);
        }
    }

    protected function generateIndexAndCover($diagnosticLabels = array()) {
        $sections = array();
        $coverSection = array("sectionBody" => array());
        $keysToReplace = array("{schoolName}", "{schoolAddress}", "{conductedOn}", "{validTill}", "{awardName}", "{dateToday}", "{schoolLocation}", "{schoolCity}", "{schoolState}", "{schoolCountry}", "{teacherName}");
        if ($this->reportId == 9) {
            $valuesToReplace = array($this->aqsData['school_name'], $this->aqsData['school_address'], $this->conductedDate, $this->validDate, $this->awardName, date("d-m-Y"), $this->schoolLocation, $this->schoolCity, $this->schoolState, $this->schoolCountry, isset($this->studentInfo['name']['value']) ? $this->studentInfo['name']['value'] : '');
        } else {
            $valuesToReplace = array($this->aqsData['school_name'], $this->aqsData['school_address'], $this->conductedDate, $this->validDate, $this->awardName, date("d-m-Y"), $this->schoolLocation, $this->schoolCity, $this->schoolState, $this->schoolCountry, isset($this->teacherInfo['name']['value']) ? $this->teacherInfo['name']['value'] : '');
        }
        $aqsinfo = str_replace($keysToReplace, $valuesToReplace, $this->aqsInfo);
        $this->config["footerText"] = str_replace($keysToReplace, $valuesToReplace, $this->config["footerText"]);
        $aqsBlock = array(
            "blockBody" => array(
                "dataArray" => array(
                    array($aqsinfo)
                )
            ),
            "style" => "coverInfoBlock"
        );
        $coverSection['sectionBody'][] = $aqsBlock;

        $indexBlock = array(
            "blockHeading" => array(
                "data" => array(
                    array("text" => $diagnosticLabels['INDEX'], "style" => "greyHead", "cSpan" => 3)
                )
            ),
            "blockBody" => array(
                "dataArray" => array(
                    array($diagnosticLabels["SR_No"], $diagnosticLabels['PARTICULARS'], $diagnosticLabels["PAGE_NO"])
                )
            ),
            "style" => "bordered reportIndex"
        );
        foreach ($this->indexArray as $k => $v) {
            $indexBlock["blockBody"]["dataArray"][] = array($k + 1, $v, '<span id="indexKey-' . ($k + 1) . '"></span>');
        }

        switch ($this->reportId) {
            case 1:
            case 2:
                if (empty($this->aqsData['review_criteria'])) {
                    $awardBlock = array(
                        "blockBody" => array(
                            "dataArray" => array(
                                array($diagnosticLabels["Principal_Name"], array("text" => $this->aqsData['principal_name'], "style" => "textBold")),
                                array($diagnosticLabels["Adhyayan_Award"], array("text" => $this->awardName, "style" => "blueColor textBold"))
                            )
                        ),
                        "style" => "bordered awardBlock"
                    );
                } else {
                    $awardBlock = array(
                        "blockBody" => array(
                            "dataArray" => array(
                                array($diagnosticLabels["Principal_Name"], array("text" => $this->aqsData['principal_name'], "style" => "textBold")),
                                array($diagnosticLabels["Adhyayan_Award"], array("text" => $this->awardName, "style" => "blueColor textBold")),
                                array('Review Criteria', array("text" => $this->aqsData['review_criteria'], "style" => "textBold"))
                            )
                        ),
                        "style" => "bordered awardBlock"
                    );
                }
                $coverSection['sectionBody'][] = $awardBlock;
                $coverSection['sectionBody'][] = $indexBlock;
                $sections[] = $coverSection;
                break;

            case 3:
                $sections[] = $coverSection;
                $section = array("sectionHeading" => array("text" => "Adhyayan Quality Standard Award Report"), "sectionBody" => array());
                $indexBlock["style"] .= " recomIndex";
                $section['sectionBody'][] = $indexBlock;

                if (count($this->aqsTeam)) {
                    $internalHtml = '';
                    $externalHtml = "";
                    foreach ($this->aqsTeam as $member) {
                        $row = '<tr><td>' . $member['name'] . '</td><td>' . $member['designation'] . '</td></tr>';
                        if ($member['isInternal'] == 1)
                            $internalHtml .= $row;
                        else
                            $externalHtml .= $row;
                    }
                    $block = array(
                        "blockBody" => array(
                            "dataArray" => array(
                                array(
                                    '<div class="team-head">SSRE Team Member/s:</div><table class="bordered"><thead><tr><td>Name</td><td>Designation</td></tr></thead>' . $internalHtml . '</table>',
                                    '<div class="team-head">SERE Team Member/s:</div><table class="bordered"><thead><tr><td>Name</td><td>Designation</td></tr></thead>' . $externalHtml . '</table>'
                                )
                            )
                        ),
                        "style" => "border-outer aqsTeam"
                    );
                    $section['sectionBody'][] = $block;
                }
                $sections[] = $section;
                break;
        }

        $this->sectionArray = array_merge($sections, $this->sectionArray);
    }

    protected function recommendationsOnKpa() {
        $kpa_count = 0;
        foreach ($this->kpas as $kpa) {
            $kpa_count++;

            $section = array("sectionHeading" => array("text" => $kpa['KPA_name'], "config" => array("repeatHead" => 1)), "sectionBody" => array());
            $recomSection = array("sectionBody" => array());

            if ($kpa_count == 1) {
                $indexKey = $this->addIndex("Key Performance Area 1 to " . count($this->kpas));
                $section['indexKey'] = $indexKey;
            }

            $textBlock['score_1'] = array(
                "blockHeading" => array("data" => array(array("text" => 'Recommendations for areas that <span class="color-red italic">needs attention</span>', "style" => "brownHead"))),
                "blockBody" => array(
                    "dataArray" => array(
                    )
                ),
                "style" => "rTextblock"
            );
            $textBlock['score_2'] = array(
                "blockHeading" => array("data" => array(array("text" => 'Recommendations for areas that <span class="color-yellow italic">are variable</span>', "style" => "brownHead"))),
                "blockBody" => array(
                    "dataArray" => array(
                    )
                ),
                "style" => "rTextblock"
            );
            $textBlock['score_3'] = array(
                "blockHeading" => array("data" => array(array("text" => 'Recommendations for areas that <span class="color-green italic">are good</span>', "style" => "brownHead"))),
                "blockBody" => array(
                    "dataArray" => array(
                    )
                ),
                "style" => "rTextblock"
            );

            $keyQ_count = 0;
            $coreQsInKPA = 0;
            if (isset($this->keyQuestions[$kpa['kpa_instance_id']])) {
                $numberToAlpha = array(1 => "a", 2 => "b", 3 => "c", 4 => "d");
                foreach ($this->keyQuestions[$kpa['kpa_instance_id']] as $keyQ) {
                    $keyQ_count++;

                    $keyQBlock = array(
                        "blockBody" => array(
                            "dataArray" => array(
                                array("Key Question $keyQ_count", array("text" => "&nbsp;", "rSpan" => 6), array("text" => $keyQ['key_question_text'], "cSpan" => isset($this->coreQuestions[$keyQ['key_question_instance_id']]) ? count($this->coreQuestions[$keyQ['key_question_instance_id']]) : 0, "style" => (isset($keyQ['externalRating']) ? "score-bg-" . $keyQ['externalRating']['score'] . " scoreB-" . $keyQ['externalRating']['score'] : ""))),
                                array('Sub Questions'),
                                array(array("text" => "Outstanding", "style" => "score-4")),
                                array(array("text" => "Good", "style" => "score-3")),
                                array(array("text" => "Variable", "style" => "score-2")),
                                array(array("text" => "Needs Action", "style" => "score-1"))
                            )
                        ),
                        "style" => "keyQblock",
                        "config" => array("minRows" => 6)
                    );

                    $coreQ_count = 0;
                    if (isset($this->coreQuestions[$keyQ['key_question_instance_id']])) {
                        foreach ($this->coreQuestions[$keyQ['key_question_instance_id']] as $coreQ) {
                            $coreQ_count++;
                            $coreQsInKPA++;
                            $keyQBlock['blockBody']['dataArray'][1][] = array("text" => '<span class="min-h">' . $coreQsInKPA . ". " . $coreQ['core_question_text'] . '</span>', "style" => (isset($coreQ['externalRating']) ? "scoreB-" . $coreQ['externalRating']['score'] : ""));
                            $satatement_count = 0;
                            $values = array(1 => array(), 2 => array(), 3 => array(), 4 => array());
                            foreach ($this->judgementStatement[$coreQ['core_question_instance_id']] as $statment) {
                                $satatement_count++;
                                if (isset($statment['externalRating'])) {
                                    $values[$statment['externalRating']['score']][] = $coreQsInKPA . $numberToAlpha[$satatement_count];
                                }

                                if (isset($this->recommendationText[$statment['judgement_statement_instance_id']])) {
                                    $recm = '<b>' . $coreQsInKPA . $numberToAlpha[$satatement_count] . '. ' . $statment['judgement_statement_text'] . '</b><div class="italic">Recommendation for improvement - </div>';

                                    $rows = array(1 => "", 2 => "", 3 => "");
                                    foreach ($this->recommendationText[$statment['judgement_statement_instance_id']] as $text) {
                                        if ($text['rating_id'] < 4)
                                            $rows[$text['rating_id']] .= '<div class="recmText">&#8226; ' . $text['recommendation_text'] . "</div>";
                                    }
                                    foreach ($rows as $k => $row) {
                                        if ($row != "")
                                            $textBlock['score_' . $k]['blockBody']['dataArray'][][] = $recm . $row;
                                    }
                                }
                            }
                            foreach ($values as $k => $v)
                                if ($k > 0)
                                    $keyQBlock['blockBody']['dataArray'][6 - $k][] = array("text" => implode(", ", $v), "style" => "score-bg-" . $k);
                        }
                    }
                    $section['sectionBody'][] = $keyQBlock;
                }
                foreach ($textBlock as $blk) {
                    if (count($blk['blockBody']['dataArray']))
                        $recomSection['sectionBody'][] = $blk;
                }
            }
            $this->sectionArray[] = $section;
            if (count($recomSection['sectionBody']))
                $this->sectionArray[] = $recomSection;
        }
    }

    protected function assessorKeyNotes() {
        if (count($this->keyNotes) == 0)
            return;
        $indexKey = $this->addIndex("Assessor Key Notes");
        $section = array("sectionHeading" => array("text" => "Assessor Key Notes for Celebrations & Improvements across " . count($this->kpas) . " Key Performance Areas (KPAs) "), "sectionBody" => array(), 'indexKey' => $indexKey);

        foreach ($this->kpas as $kpa) {
            if (isset($this->keyNotes[$kpa['kpa_instance_id']])) {
                $block = array(
                    "blockHeading" => array(
                        "data" => array(array("text" => $kpa['KPA_name'], "cSpan" => 2))
                    ),
                    "blockBody" => array(
                        "dataArray" => array()
                    ),
                    "style" => "bordered keyNotesBlock"
                );
                $kn_count = 0;
                foreach ($this->keyNotes[$kpa['kpa_instance_id']] as $kn) {
                    $kn_count++;
                    $block['blockBody']['dataArray'][] = array($kn_count . ".", $kn['text_data']);
                }
                $section['sectionBody'][] = $block;
            }
        }
        if (count($section['sectionBody']))
            $this->sectionArray[] = $section;
    }

    protected function keyForReadingRecommendationReport() {
        $indexKey = $this->addIndex("Key for Reading the KPA Report");
        $section = array("sectionHeading" => array("text" => "Key for Reading the Report"), "sectionBody" => array(), 'indexKey' => $indexKey);

        $block = array(
            "blockBody" => array(
                "dataArray" => array(
                    array("This colour coding system below, which is used throughout this document, is designed to highlight areas for both celebration and improvement.")
                )
            ),
            "style" => "onlytext"
        );
        $section['sectionBody'][] = $block;

        $block = array(
            "blockHeading" => array(
                "data" => array("Code", "Performance Level", "What this means:")
            ),
            "blockBody" => array(
                "dataArray" => array(
                    array(array("text" => "&nbsp;", "style" => "score-bg-4"), "Outstanding", "Best practice is consistently and visibly embedded in the culture of the school, is documented well and known to all stakeholders."),
                    array(array("text" => "&nbsp;", "style" => "score-bg-3"), "Good", "There are consistently visible examples of good practice that have become part of the school's culture and are known to all stakeholders. The leadership and management ensures secure system and processes."),
                    array(array("text" => "&nbsp;", "style" => "score-bg-2"), "Variable", "Some examples of good practice are visible. These are not embedded in school culture and are known or practiced by only a few."),
                    array(array("text" => "&nbsp;", "style" => "score-bg-1"), "Needs Attention", "Actions need to be taken immediately. There is little or no evidence of good practice in the school.")
                )
            ),
            "style" => "bordered keysForRecmBlock"
        );
        $section['sectionBody'][] = $block;

        $this->sectionArray[] = $section;
    }

    protected function getDiagnosticId() {
        $sql = "select diagnostic_id from d_assessment where assessment_id=?";
        $res = $this->db->get_row($sql, array($this->assessmentId));
        return $res['diagnostic_id'] > 0 ? $res['diagnostic_id'] : 0;
    }

    protected function isCollobrative() {
        $sql = "select * from d_assessment where assessment_id=?";
        $res = $this->db->get_row($sql, array($this->assessmentId));
        return $res['iscollebrative'] > 0 ? 1 : 0;
    }

    protected function getDiagnosticType() {
        $sql = "select a.diagnostic_id,d.diagnostic_type from d_assessment a INNER JOIN d_diagnostic d ON"
                . " a.diagnostic_id = d.diagnostic_id where a.assessment_id=? AND d.diagnostic_type = ?";
        $res = $this->db->get_row($sql, array($this->assessmentId, 1));
        return $res['diagnostic_type'] > 0 ? $res['diagnostic_type'] : 0;
    }

    protected function getCreateNetType() {
        $sql = "select a.diagnostic_id,d.iscreateNet from d_assessment a INNER JOIN d_diagnostic d ON"
                . " a.diagnostic_id = d.diagnostic_id where a.assessment_id=? AND d.iscreateNet = ?";
        $res = $this->db->get_row($sql, array($this->assessmentId, 1));
        return $res['iscreateNet'] > 0 ? $res['iscreateNet'] : 0;
    }

    protected function getDiagnosticLabels($language_id = DEFAULT_LANGUAGE) {
        if (isset($this->lang)) {
            $language_id = $this->lang;
        }
        $sql = "select d.label_name,d.label_key,a.label_text
			from d_assessment_labels d 
			inner join h_assessment_labels a on d.id=a.label_id				
			where a.language_id=?  ";
        return $this->db->get_results($sql, array($language_id));
    }

    protected function getDiagnosticGraphkeys($language_id = DEFAULT_LANGUAGE) {
        if (isset($this->lang)) {
            $language_id = $this->lang;
        }
        $sql = "select a.rating_id,hlt.translation_text from d_rating a inner join h_lang_translation hlt on a.equivalence_id=hlt.equivalence_id				
			where hlt.language_id=? && rating_id IN (5,6,7,8)  order by rating_id desc";
        $res = $this->db->get_results($sql, array($language_id));
        $array_return = array();
        $k = 4;
        foreach ($res as $key => $val) {
            $array_return[$k] = $val['translation_text'];
            $k--;
        }

        return $array_return;
    }

    /*     * *****************************School Profile Data Start ******************************************** */

    protected function generateSchoolProfileOutput($lang_id = DEFAULT_LANGUAGE) {
        $diagnosticLabels = array();
        $languageLabels = $this->getDiagnosticLabels($lang_id);
        foreach ($languageLabels as $data) {
            $diagnosticLabels[$data['label_key']] = $data['label_text'];
        }
        $this->config["footerText"] = "Powered by Adhyayan Quality Education Services Private Limited & Advaith Foundation";
        $this->sectionArray = array();
        $this->indexArray = array();
        $this->generateSection_SchoolProfileDataForKDs('', $lang_id, $diagnosticLabels);
        $output = array(
            "config" => $this->config,
            "data" => $this->sectionArray
        );
        return $output;
    }

    protected function generateSection_SchoolProfileDataForKDs($skipComparisonSection = 0, $lang_id, $diagnosticLabels = array()) {
        $kdDetailsQuestions = $this->db->array_grouping($this->loadSchoolProfileData($this->assessmentId, $lang_id), 'kpa_id');
        $allKpaQuestionsData = array();
        foreach ($kdDetailsQuestions as $key => $kpaData) {
            $questionHierarchy1 = array();
            $this->sort_questions_hierarchicaly($kpaData, $questionHierarchy1, 0, $key);
            $allKpaQuestionsData[$key] = $questionHierarchy1;
        }
        $question_num = 0;
        $totalKpas = count($this->kpas);
        if ($skipComparisonSection == 0 && $this->config['getCreateNetType'] != 1) {
            $kpaNum = ($totalKpas) > 1 ? str_replace('&', $totalKpas, $diagnosticLabels['kpa_performance_area_title']) : str_replace('&', '', $diagnosticLabels['kpa_performance_area_title']);
            $indexKey = $this->addIndex($kpaNum);
            $comparisonSection = array("sectionHeading" => array("text" => "1.", "style" => "greyHead"), "sectionBody" => array(), "indexKey" => $indexKey);
        }
        $kpa_count = 0;
        $kpaSectionArray = array();
        $sql = "SELECT a.client_id,a.client_name FROM d_client a 
                        INNER JOIN d_assessment b ON a.client_id=b.client_id
                        WHERE b.assessment_id=?";
        $res = $this->db->get_results($sql, array($this->assessmentId));
        foreach ($allKpaQuestionsData as $kpa_id => $kpaData) {//echo '<pre>';print_r($kpaData);
            $diagnosticModel = new diagnosticModel();
            $keyDomainName = $diagnosticModel->getKeyDomainName($kpa_id);
            $section = array();
            if ($kpa_id == 1) {
                $section = array('sectionHeading' => array('text' => '<table width="100%" border="0" style="" cellpadding="4px;"><tr><td align="center" height="28" class="greyHead" style="border-color:#ffffff;background-color:#c8c8c8;font-size:16px;"><strong>School Demographic Details - ' . $res[0]['client_name'] . '</strong></td></tr><br/><tr><td align="left" style="background-color:#ffffff;color:#600109;font-weight:bold;font-size:14px;"><u>Key Domain ' . $kpa_id . ': ' . $keyDomainName[0]['translation_text'] . '<br/></u></td></tr></table>', 'style' => ''), 'sectionBody' => array(), 'indexKey' => $indexKey);
            } else {
                $section = array('sectionHeading' => array('text' => '<table width="100%" border="0" style=""><tr><td align="left" style="background-color:#ffffff;color:#600109;font-family:Helvetica,Arial,sans-serif;font-weight:bold;font-size:14px;"><u><br/>Key Domain ' . $kpa_id . ': ' . $keyDomainName[0]['translation_text'] . '<br/></u></td></tr></table>', 'style' => ''), 'sectionBody' => array(), 'indexKey' => $indexKey);
            }
            $num = 1;
            $space1 = '&nbsp;&nbsp;&nbsp;&nbsp;';
            $space2 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $space3 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $space4 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $space5 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $space6 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            foreach ($kpaData as $kpa_id1 => $kpaData1) {

                $tableshow = '';


                if ($kpaData1['translation_text'] != '') {
                    $tableshow = '<table WIDTH="100%" border="0" style="line-height:19px;font-family:Helvetica,Arial,sans-serif;font-size:12px;">';
                    if ($kpaData1['html_type_id'] == 5) {

                        $tableshow .= '<tr nobr="true"><td><strong>' . $num . '. ' . $kpaData1['translation_text'] . '</strong>';
                        $tableshow .= '</td></tr>';
                        if ($kpaData1['answer'] != '' || $kpaData1['answer'] == 0) {
                            $tableshow .= '<tr nobr="true"><td>' . $kpaData1['answer'] . '</td></tr>';
                        }


                        foreach ($kpaData1['sub_questions'] as $kpa_id2 => $kpaData2) {

                            $tableshow .= '<tr nobr="true"><td style="padding-left:10px;">' . $kpaData2['translation_text'] . '';

                            if ($kpaData2['answer'] != '' || $kpaData2['answer'] == 0) {
                                $tableshow .= ' ' . $space4 . $kpaData2['answer'] . ' ';
                            }

                            $tableshow .= '</td></tr>';

                            foreach ($kpaData2['sub_questions'] as $kpa_id3 => $kpaData3) {
                                $tableshow .= '<tr nobr="true"><td  style="padding-left:20px;">' . $kpaData3['translation_text'] . '';
                                if ($kpaData3['answer'] != '' || $kpaData3['answer'] == 0) {
                                    $tableshow .= ' ' . $kpaData3['answer'] . ' ';
                                }
                                $tableshow .= '</td></tr>';
                            }
                        }
                    } else {
                        $tableshow .= '<tr nobr="true"><td><strong>' . $num . '. ' . $kpaData1['translation_text'] . '</strong>';

                        if ($kpaData1['answer'] != '' || $kpaData1['answer'] == 0) {
                            $tableshow .= ' ' . $kpaData1['answer'] . ' ';
                        }

                        $tableshow .= '--radiovalue--</td></tr>';

                        $valshow = '';
                        if ($kpaData1['html_type_id'] != 7) {

                            foreach ($kpaData1['sub_questions'] as $kpa_id2 => $kpaData2) {


                                if ($kpaData2['html_type_id'] == 2) {

                                    if ($kpaData2['answer'] != '') {


                                        $valshow .= ' ' . $kpaData2['translation_text'] . ' ';
                                    }
                                } else {
                                    if ($kpaData2['answer'] != '' || $kpaData2['answer'] == 0) {

                                        if ($kpaData2['html_type_id'] == 6) {

                                            $tableshow .= '<tr nobr="true"><td >' . $space1 . $kpaData2['translation_text'] . '</td></tr>';
                                        } else if ($kpaData2['html_type_id'] == 2 || $kpaData2['html_type_id'] == 3) {



                                            if ($kpaData2['answer'] != '') {

                                                $tableshow .= '<tr nobr="true"><td > ' . $space1 . $kpaData2['translation_text'] . '</td></tr>';
                                            } else {
                                                $tableshow .= '';
                                            }
                                        } else if ($kpaData2['html_type_id'] == 4) {

                                            if ($kpaData2['answer'] != '') {

                                                $tableshow .= '<tr nobr="true"><td >' . $space1 . $kpaData2['translation_text'] . ' ';
                                                $tableshow .= '' . $kpaData2['answer'] . '</td></tr>';
                                            } else {
                                                $tableshow .= '';
                                            }
                                        } else {
                                            $tableshow .= ' ' . $kpaData2['answer'] . ' ';
                                        }
                                    }
                                    foreach ($kpaData2['sub_questions'] as $kpa_id3 => $kpaData3) {

                                        $tableshow .= '<tr nobr="true"><td  style="padding-left:20px;">';

                                        if ($kpaData3['answer'] != '' || $kpaData3['answer'] == 0) {
                                            if ($kpaData3['html_type_id'] == 4 && $kpaData3['answer'] != '') {

                                                $tableshow .= ' ' . $space4 . $kpaData3['translation_text'] . ' ';
                                                $tableshow .= ' ' . $kpaData3['answer'] . ' ';
                                            } else {

                                                $tableshow .= ' ' . $kpaData3['answer'] . ' ';
                                            }
                                        }
                                        $tableshow .= '</td></tr>';
                                    }
                                }

                                /*                                 * ******** */
                                foreach ($kpaData2['sub_questions'] as $kpa_id3 => $kpaData3) {
                                    if ($kpaData3['html_type_id'] == 5) {
                                        if ($kpaData2['answer'] != '' && $kpaData2['childtobeactive_ifparentselected'] == 1) {
                                            $valshow .= '<br><strong>' . $space1 . $kpaData3['translation_text'] . '</strong>';
                                            $valshow .= '<br>' . $space1 . $kpaData3['answer'] . '';
                                        }
                                    } else if ($kpaData2['html_type_id'] == 2 && $kpaData3['html_type_id'] == 4) {
                                        if ($kpaData3['answer'] != '') {
                                            $valshow .= '<br>' . $space1 . $kpaData3['translation_text'] . '';
                                            $valshow .= ' ' . $kpaData3['answer'] . '';
                                        } else {
                                            $tableshow .= '';
                                        }
                                    }
                                }
                                /*                                 * ******* */
                            }
                        }

                        $tableshow = str_replace('--radiovalue--', $valshow, $tableshow);
                    }
                } else {
                    $tableshow = '<table WIDTH="100%" border="0" style="line-height:19px;font-size:13px;font-family:Helvetica,Arial,sans-serif;">';
                    $ki = 0;
                    foreach ($kpaData1['sub_questions'] as $kpa_id2 => $kpaData2) {
                        if ($kpaData1['translation_text'] != '') {
                            $style = 'padding-left:10px;';
                        } else if ($kpaData1['translation_text'] == '') {
                            $style = '';
                        }
                        $count = '';
                        if ($ki == 0) {
                            $count = '<strong>' . $num . '. </strong>';
                        } else {
                            if (strlen($num) == 1) {
                                $style = 'padding-left:17px;';
                            } else if (strlen($num) == 2) {
                                $style = 'padding-left:23px;';
                            }
                        }
                        if (strlen($num) == 1) {
                            $style_txt = 'padding-left:10px;';
                        } else if (strlen($num) == 2) {
                            $style_txt = 'padding-left:5px;';
                        }
                        if ($kpaData2['html_type_id'] == 5) {
                            $tableshow .= '<tr nobr="true"><td >' . $count . ' <span style="' . $space3 . '"><strong>' . $kpaData2['translation_text'] . '</strong></span>';
                        } else {
                            $tableshow .= '<tr nobr="true"><td >' . $count . ' <span style="' . $space5 . '"><strong>' . $kpaData2['translation_text'] . '</span></strong>';
                        }

                        if ($kpaData2['answer'] != '' || $kpaData2['answer'] == 0) {
                            if ($kpaData2['html_type_id'] == 5) {
                                $tableshow .= '<div style="padding-left:23px;">' . $space5 . $kpaData2['answer'] . ' </div>';
                            } else {
                                $tableshow .= ' ' . $kpaData2['answer'] . ' ';
                            }
                        }
                        $tableshow .= '--radiovalue2--</td></tr>';
                        $ki++;
                        $valshow2 = '';
                        foreach ($kpaData2['sub_questions'] as $kpa_id3 => $kpaData3) {

                            if ($kpaData3['html_type_id'] == 2) {

                                if ($kpaData3['answer'] != '') {


                                    $valshow2 .= ' ' . $kpaData3['translation_text'] . ' ';
                                }
                            } else {
                                if ($kpaData3['answer'] != '' || $kpaData3['answer'] == 0) {
                                    if ($kpaData3['html_type_id'] == 6) {
                                        $tableshow .= '<tr nobr="true"><td>' . $space1 . $kpaData3['translation_text'] . '</td></tr>';
                                    } else if ($kpaData3['html_type_id'] == 2 || $kpaData3['html_type_id'] == 3) {
                                        if ($kpaData3['answer'] != '') {
                                            $tableshow .= '<tr nobr="true"><td>' . $space5 . $kpaData3['translation_text'] . '</td></tr>';
                                        } else {
                                            $tableshow .= '';
                                        }
                                    } else if ($kpaData3['html_type_id'] == 4) {
                                        if ($kpaData3['answer'] != '') {
                                            $tableshow .= '<tr nobr="true"><td>' . $kpaData3['translation_text'] . '';
                                            $tableshow .= ' ' . $kpaData3['answer'] . '</td></tr>';
                                        } else {
                                            $tableshow .= '';
                                        }
                                    } else {
                                        $tableshow .= '<tr nobr="true"><td>' . $space1 . $kpaData3['answer'] . '</td></tr>';
                                    }
                                }
                            }

                            foreach ($kpaData3['sub_questions'] as $kpa_id4 => $kpaData4) { //KD3-Ques-7
                                $tableshow .= '<tr nobr="true"><td  style="padding-left:25px;">';
                                if ($kpaData4['html_type_id'] == 1 && $kpaData3['answer'] != '') {
                                    $tableshow .= ' ' . $kpaData4['translation_text'] . '';
                                } else if ($kpaData4['html_type_id'] == 4) {
                                    if ($kpaData4['answer'] != '') {
                                        $tableshow .= ' ' . $space6 . $kpaData4['translation_text'] . ' ' . $kpaData4['answer'];
                                    }
                                }
                                $tableshow .= '</td></tr>';

                                foreach ($kpaData4['sub_questions'] as $kpa_id5 => $kpaData5) { //KD3-Ques-7
                                    if ($kpaData5['html_type_id'] == 2) {
                                        if ($kpaData5['answer'] != '') {
                                            $tableshow .= '<tr nobr="true"><td  style="padding-left:25px;"> ' . $kpaData5['translation_text'] . '</td></tr> ';
                                        } else {
                                            $tableshow .= '';
                                        }
                                    }
                                    foreach ($kpaData5['sub_questions'] as $kpa_id6 => $kpaData6) { //KD3-Ques-7
                                        $tableshow .= '<tr nobr="true"><td  style="padding-left:25px;">';
                                        if ($kpaData6['html_type_id'] == 4) {
                                            if ($kpaData6['answer'] != '') {
                                                $tableshow .= ' ' . $kpaData6['translation_text'] . ' ' . $kpaData6['answer'] . "";
                                            } else {
                                                $tableshow .= '';
                                            }
                                        }
                                        $tableshow .= '</td></tr>';
                                    }
                                }
                            }
                        }

                        $tableshow = str_replace('--radiovalue2--', $valshow2, $tableshow);
                    }
                }

                $tableshow .= '</table>';
                if ($kpaData1['translation_text'] != '') {
                    if (($kpaData1['html_type_id'] == 5)) {
                        $qaBlock = array(
                            "blockBody" => array(
                                "dataArray" => array(
                                    array($tableshow)
                                )
                            )
                        );
                    } else {
                        $qaBlock = array(
                            "blockBody" => array(
                                "dataArray" => array(
                                    array($tableshow)
                                )
                            )
                        );
                    }
                } else {
                    $qaBlock = array(
                        "blockBody" => array(
                            "dataArray" => array(
                                array($tableshow)
                            )
                        )
                    );
                }

                $section['sectionBody'][] = $qaBlock;

                $tableHeading = array();
                $tabledataArray = array();

                foreach ($kpaData1['sub_questions'] as $kpa_id2 => $kpaData2) {
                    if ($kpaData1['html_type_id'] == 7) {

                        $tableHeading[] = '' . $kpaData2['translation_text'] . '';


                        $tabledataArray[] = $kpaData2['answer'];
                    }
                }

                if (count($tableHeading) > 0) {
                    $tabledataArray_f = array();
                    foreach ($tabledataArray as $key => $val) {
                        $val_f = explode("@#@$", $val);
                        $i = 0;
                        foreach ($val_f as $key1 => $val1) {
                            $tabledataArray_f[$i][$key] = $val1;

                            $i++;
                        }
                    }
                    $childqaBlock = array(
                        "blockHeading" => array(
                            "data" => $tableHeading
                        ),
                        "blockBody" => array(
                            "dataArray" => $tabledataArray_f
                        ),
                        "style" => "bordered comparisonBlock"
                    );
                    $section['sectionBody'][] = $childqaBlock;
                    $tableHeading = array();
                }


                $num++;
            } //end heading loop

            $kpaSectionArray[] = $section; //echo '<pre>';print_r($kpaSectionArray);
        }

        $this->sectionArray = array_merge($this->sectionArray, $kpaSectionArray);
    }

    protected function loadSchoolProfileData($assessment_id, $lang_id = DEFAULT_LANGUAGE) {

        $sql = "select group_concat(sd.answer SEPARATOR '@#@$') as answer,a.*,hlt.translation_text
                from d_school_profile a                
                inner join h_lang_translation hlt on a.equivalence_id = hlt.equivalence_id 
                left join f_school_profile_data sd
                on sd.school_profile_id = a.school_profile_id and sd.assessment_id =?
                where  hlt.language_id=? group by a.school_profile_id order by a.school_profile_id,a.display_order asc";
        $res = $this->db->get_results($sql, array($assessment_id, $lang_id));
        return $res ? $res : array();
    }

    protected function sort_questions_hierarchicaly(Array &$questions, Array &$into, $parentId = 0, $key) {
        foreach ($questions as $i => $question) {
            if ($question['parent_id'] == $parentId) {
                $into[$question['school_profile_id']] = $question;
                unset($questions[$i]);
            }
        }
        foreach ($into as $index => $topQuestion) {
            $into[$index]['sub_questions'] = array();
            $this->sort_questions_hierarchicaly($questions, $into[$index]['sub_questions'], $topQuestion['school_profile_id'], $key);
        }
    }

    /*     * ********************************** School Profile Data End******************************** */

    /*     * ***************** Generate Evidence Report Starts********* */

    protected function generateEvidenceOutput($lang_id = DEFAULT_LANGUAGE) {
        $assessmentId = $this->assessmentId;
        $assessorID = $_REQUEST['assessor_id'];
        $this->loadCoreQuestions('', $lang_id);
        $this->loadKeyQuestions('', $lang_id);
        $this->loadKpas('', $lang_id);
        $diagnosticLabels = array();
        $languageLabels = $this->getDiagnosticLabels($lang_id);
        foreach ($languageLabels as $data) {
            $diagnosticLabels[$data['label_key']] = $data['label_text'];
        }
        $this->config['reportTitle'] = $diagnosticLabels['Adhyayan_Report_Card_Title'];
        $this->config["footerText"] = "Powered by Adhyayan Quality Education Services Private Limited & Advaith Foundation";
        $this->sectionArray = array();
        $this->indexArray = array();
        $this->generateSection_ScoreCardForEvidenceText('', $lang_id, $diagnosticLabels, $assessmentId, $assessorID);
        $output = array(
            "config" => $this->config,
            "data" => $this->sectionArray
        );

        return $output;
    }

    protected function getJudgementalStatementsForAssessment($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $external = 0, $userKpas = array(), $isLeadAssessorKpa = 0) {
        $cond = '';
        $score = ' fs.score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = "SELECT fs.level2rating, 
			if(fs.score_id is NULL,cqjs.judgement_statement_instance_id,fs.score_id) as groupId, GROUP_CONCAT(  CONCAT(f.file_id,'|',f.file_name) SEPARATOR '||') as files, $score,fs.level2rating,js.judgement_statement_id,hlt.translation_text judgement_statement_text,fs.evidence_text,fs.evidence_text_lw,fs.evidence_text_co,fs.evidence_text_in,evidence_text_bl, cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_judgement_statement` js
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        if ($external == 1) {
            $sql .= ' inner join h_assessment_external_team au on au.assessment_id=a.assessment_id';
            if (!empty($userKpas))
                $cond = " and kd.kpa_instance_id IN (" . implode(",", $userKpas) . ")";
        } else
            $sql .= ' inner join h_assessment_user au on au.assessment_id=a.assessment_id';

        $sql .= " inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 
                        
			left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1
                       
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                        left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and fs.rating_id=hls.rating_id  and hls.rating_level_id=4
                        left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on fs.rating_id = r.rating_id and hls.rating_id=r.rating_id 
			left join h_score_file sf on sf.score_id=fs.score_id
                        left join d_file f on sf.file_id=f.file_id
			where a.assessment_id=? $cond and au.user_id=? and hlt.translation_type_id=4 and hlt.language_id=?";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= "
			group by groupId
			order by cqjs.`js_order` asc";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    public function generateSection_ScoreCardForEvidenceText($skipComparisonSection = 0, $lang_id, $diagnosticLabels = array(), $assessmentId, $assessorID) {
        $totalKpas = count($this->kpas);
        $comparisonSection = array();
        $kpa_count = 0;
        $kpaSectionArray = array();
        $kpaHeadingCount = 0;
        $kpaNameArray = array();
        $numToAlph = array(1 => "a", 2 => "b", 3 => "c", 4 => "d", 5 => "e", 6 => "f", 7 => "g", 8 => "h", 9 => "i", 10 => "j", 11 => "k", 12 => "l", 13 => "m", 14 => "n", 15 => "o", 16 => "p");
        $sql = "SELECT a.client_id,a.client_name FROM d_client a 
                        INNER JOIN d_assessment b ON a.client_id=b.client_id
                        WHERE b.assessment_id=?";
        $res = $this->db->get_results($sql, array($assessmentId));
        foreach ($this->kpas as $kpa) {
            $kpa_count++;
            $kpaHeadingCount++;
            $comparisonSection = array();
            $kpaname_arr = array();
            $kpaname_arr[] = "Key Domain " . $kpa_count . " - " . $kpa['KPA_name'];
            if ($kpa_count == 1) {
                $comparisonSection = array('sectionHeading' => array('text' => '<table width="100%" border="0" style="" cellpadding="4px;"><tr><td align="center" class="greyHead" style="border-color:#ffffff;background-color:#c8c8c8;font-size:16px;"><strong>Evidence Text Data At Key Domain Level - ' . $res[0]['client_name'] . '</strong></td></tr><tr><td style="font-family:Helvetica, Arial, sans-serif;color:#600109;font-weight: bold;text-decoration: bold;font-size:14px;"><u>' . $kpaname_arr[0] . '</u></td></tr></table>', 'style' => 'whiteHead'), 'sectionBody' => array(), 'indexKey' => 1);
            } else {
                $comparisonSection = array('sectionHeading' => array('text' => '<br><table width="100%" border="0" style=""><tr><td style="font-family:Helvetica, Arial, sans-serif;color:#600109;font-weight: bold;margin-top:10px;margin-bottom:10px;text-decoration: bold;font-size:14px;"><u>' . $kpaname_arr[0] . '</u></td></tr></table>', 'style' => 'whiteHead'), 'sectionBody' => array(), 'indexKey' => 1);
            }
            $keyQ_count = 0;
            $coreQsInKPA = 0;
            if (isset($this->keyQuestions[$kpa['kpa_instance_id']])) {
                foreach ($this->keyQuestions[$kpa['kpa_instance_id']] as $keyQ) {
                    $coreQ_count = 0;
                    if (isset($this->coreQuestions[$keyQ['key_question_instance_id']])) {
                        foreach ($this->coreQuestions[$keyQ['key_question_instance_id']] as $coreQ) {
                            $coreQ_count++;
                            $coreQsInKPA++;
                            $satatement_count = 0;
                            $userKpas = array();
                            $this->judgementStatement = $this->db->array_grouping($this->getJudgementalStatementsForAssessment($assessmentId, $assessorID, 0, $lang_id, 0, '', 0), "core_question_instance_id", "judgement_statement_instance_id", "level");
                            $table_evidence = "";
                            $js_no = 0;
                            foreach ($this->judgementStatement[$coreQ['core_question_instance_id']] as $statment) {
                                $js_no++;
                                $evidence_text_lw = str_replace(array('
'), array('<br/>'), $statment['evidence_text_lw']);
                                $evidence_text_co = str_replace(array('
'), array('<br/>'), $statment['evidence_text_co']);
                                $evidence_text_in = str_replace(array('
'), array('<br/>'), $statment['evidence_text_in']);
                                $evidence_text_bl = str_replace(array('
'), array('<br/>'), $statment['evidence_text_bl']);

                                if ($evidence_text_lw != '' || $evidence_text_co != '' || $evidence_text_in != '' || $evidence_text_bl != '') {

                                    $table_evidence = '<table width="100%"  class="SchReport QuesAnsText" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:16px;">';

                                    $table_evidence .= '<tr nobr="true"><td colspan="2" style=""><strong><br/>' . $kpa_count . $numToAlph[$js_no] . '. ' . $statment["judgement_statement_text"] . '<br/></strong></td></tr>';


                                    $table_evidence .= '<tr nobr="true"><td style="border:1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="19%" >' . 'Learning Walk:</td><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="79%">' . str_replace('
', '<br/>', $statment['evidence_text_lw']) . '</td></tr>';
                                    $table_evidence .= '<tr nobr="true"><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="19%" >' . 'Class Observations:</td><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="79%">' . str_replace('
', '<br/>', $statment['evidence_text_co']) . '</td></tr>';
                                    $table_evidence .= '<tr nobr="true"><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="19%" >' . 'Interactions:</td><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="79%">' . str_replace('
', '<br/>', $statment['evidence_text_in']) . '</td></tr>';
                                    $table_evidence .= '<tr nobr="true"><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="19%" >' . 'Book Look:</td><td style="border: 1px solid #1d1b1b;padding:6px 10px;vertical-align:top;" width="79%" >' . str_replace('
', '<br/>', $statment['evidence_text_bl']) . '</td></tr>';

                                    $table_evidence .= '</table>';
                                } else {

                                    $table_evidence = '<table width="100%"  class="SchReport QuesAnsText" style="font-family:Helvetica, Arial, sans-serif;font-size:12px;line-height:14px;">';

                                    $table_evidence .= '<tr nobr="true"><td colspan="2" style=""><strong>' . $kpa_count . $numToAlph[$js_no] . '. ' . $statment["judgement_statement_text"] . '</strong></td></tr>';

                                    $table_evidence .= '<tr><td style="padding:1px 5px;vertical-align:top;" width="79%" >NA</td></tr>';
                                    $table_evidence .= '</table>';
                                }
                                $qaBlock = array(
                                    "blockBody" => array(
                                        "dataArray" => array(
                                            array($table_evidence)
                                        )
                                    )
                                );
                                $comparisonSection['sectionBody'][] = $qaBlock;
                            }
                        }
                    }
                }
            }
            $kpaSectionArray[] = $comparisonSection;
        }
        if ($skipComparisonSection == 0 && $this->config['getCreateNetType'] != 1) {
            $this->sectionArray = array_merge($this->sectionArray, $kpaSectionArray);
        }
    }

    /*     * **************Generate Evidence Report Ends************ */
}
