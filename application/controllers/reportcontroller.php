<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage report for all action plan 
 * 
 */
class reportController extends controller {


    /** Show the action plan list row
     */

    function actionPlanAction() {
        error_reporting(0);
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $group_assessment_id = empty($_GET['group_assessment_id']) ? 0 : $_GET['group_assessment_id'];
        $action_plan_id = empty($_GET['id_c']) ? 0 : $_GET['id_c'];
        $datesrange = empty($_GET['datesrange']) ? 0 : $_GET['datesrange'];
        $actionModel = new actionModel();
        $details = $actionModel->getDetailsofAssessment($action_plan_id);
        $date = (isset($details['from_date']) && $details['from_date'] != "0000-00-00") ? $details['from_date'] : '';
        $date_start_real = (isset($details['from_date']) && $details['from_date'] != "0000-00-00") ? $details['from_date'] : '';
        $date_end_real = (isset($details['to_date']) && $details['to_date'] != "0000-00-00") ? $details['to_date'] : date("Y-m-d");
        $end_date = $date_end_real;
        $array_dates = array();
        $ii = 0;
        if (!empty($date)) {
            $ii = 0;
            while (strtotime($date) <= strtotime($end_date)) {
                $sdate = date("Y-m-d", strtotime($date));
                $dateex = date("Y-m-d", strtotime("" . $details['frequency_days'] . "", strtotime($date)));
                $date = date("Y-m-d", strtotime("-1 day", strtotime($dateex)));
                if ($date > $date_end_real) {
                    $dateex = date("Y-m-d", strtotime("+1 day", strtotime($date_end_real)));
                    $date = date("Y-m-d", strtotime("-1 day", strtotime($dateex)));
                    $array_dates[$ii]['fromDate'] = $sdate;
                    $array_dates[$ii]['endDate'] = $date_end_real;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date_end_real)));
                    $ii++;
                    break;
                } else {
                    $array_dates[$ii]['fromDate'] = $sdate;
                    $array_dates[$ii]['endDate'] = $date;
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                $ii++;
            }
        }


        if ($ii == 0) {
            $sdate = date("Y-m-d", strtotime($date));
            $dateex = date("Y-m-d", strtotime($end_date));
            $array_dates[$ii]['fromDate'] = $sdate;
            $array_dates[$ii]['endDate'] = $dateex;
            $date = date("Y-m-d", strtotime($dateex));
            $nextDate = date("d-m-Y", strtotime($date));
        } else {
            $date = date("Y-m-d", strtotime("-1 day", strtotime($dateex)));
            $nextDate = date("d-m-Y", strtotime($date));
            if ($date < date("Y-m-d")) {
                $nextDate = "Last Date is over";
            }
        }
        $array_dates_f = array();
        foreach ($array_dates as $key => $val) {

            $array_dates_f[] = $val;
        }
        $reportDates = array();
        if (!empty($datesrange)) {
            $reportDates = explode('/', $datesrange);
            $start_date = $reportDates[0];
            $end_date = $reportDates[1];
        } else {
            $start_date = $date_start_real;
            $end_date = $date_end_real;
        }
        $palnnedDate = array();
        if (!empty($datesrange)) {
            if (!empty($array_dates_f)) {

                $palnnedDateKey = -1;
                foreach ($array_dates_f as $key => $val) {
                    if ($val['fromDate'] == $start_date && $val['endDate'] == $end_date) {
                        $palnnedDateKey = $key;
                        break;
                    }
                }if ($palnnedDateKey != -1) {
                    $palnnedDate['fromDate'] = $array_dates_f[$palnnedDateKey + 1]['fromDate'];
                    $palnnedDate['endDate'] = $array_dates_f[$palnnedDateKey + 1]['endDate'];
                }
            }
        } else {
            foreach ($array_dates_f as $key => $val) {

                if ($val['endDate'] <= date("Y-m-d")) {
                    $end_date = $val['endDate'];
                } else {
                    $planned_start_date = $val['fromDate'];
                    $palnnedDate['fromDate'] = $planned_start_date;
                    $palnnedDate['endDate'] = $date_end_real;
                    break;
                }
            }
        }
        $diagnosticModel = new diagnosticModel();
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        if ($group_assessment_id == 0 && $assessment_id == 0) {
            $this->_is404 = 1;
        } else if ($assessment_id > 0) {
            $assessment = $diagnosticModel->getAssessmentById($assessment_id);
            $group_asmt_external = empty($assessment['userIdByRole'][4]) ? 0 : $assessment['userIdByRole'][4];
            $group_asmt_internal = empty($assessment['userIdByRole'][3]) ? 0 : $assessment['userIdByRole'][3];
            $rating_date = '';
            if (!empty($assessment)) {
                if ($assessment['assessment_type_id'] == 1 && $assessment['subAssessmentType'] == 1) {
                    $rating_date = !empty($assessment['rating_date']) ? date("d-m-Y", strtotime($assessment['rating_date'])) : '';
                } else {
                    $rating_date = !empty($assessment['rating_date1']) ? date("d-m-Y", strtotime($assessment['rating_date1'])) : '';
                }
            }
            $this->_render = false;
            $reportObject = null;
            $tMonths = $pMonths + ($pYears * 12);
            if ($assessment['school_aqs_pref_end_date'] == "" || $assessment['school_aqs_pref_end_date'] == "0000-00-00") {
                $conductedDate = date("m-Y", strtotime($assessment['create_date']));
                $validDate = date("m-Y", strtotime("+$tMonths month", strtotime($assessment['create_date'])));
            } else {
                $conductedDate = date("m-Y", strtotime($assessment['school_aqs_pref_end_date']));
                $validDate = date("m-Y", strtotime("+$tMonths month", strtotime($assessment['school_aqs_pref_end_date'])));
            }
            $reportObject = new individualReport($assessment_id, 0);
            $reportObject->actionPlanOutput($action_plan_id, $start_date, $end_date, $palnnedDate, $datesrange, $details, $rating_date);
        } else {
            $this->_is404 = 1;
        }
    }

}
