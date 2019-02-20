<?php

/* * HTML listing for adding in ui for action laning 
 * */
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */

class kpajsHelper {

    private $db;

    function __construct() {
        $this->db = db::getInstance();
    }

    /*     * Fetch and show list of all  kpa and judgement statements
     * $sno:serial no,
     * $assessment_id:id of assessment,
     * $addDelete:delete flag
     * $viewdata:
     */

    static function getAction1HTMLNew($sno, $assessment_id, $addDelete = 1, $viewdata = array(), $lang_id = DEFAULT_LANGUAGE) {
        $obj = new actionModel();
        $kpas = $obj->getKpasForAssessmentNew($assessment_id, $lang_id);
        $kpaOpt = '';
        $kqOpt = '';
        $cqOpt = '';
        $jsOpt = '';
        $recOpt = '';
        foreach ($kpas as $kpa) {
            $kpaOpt .= '<option value="' . $kpa['kpa_instance_id'] . '" ' . (isset($viewdata['kpa_instance_id']) && $viewdata['kpa_instance_id'] == $kpa['kpa_instance_id'] ? 'selected="selected"' : "") . ' >' . $kpa['kpa_name'] . '</option>';
        }
        if (!empty($viewdata['kpa_instance_id'])) {
            $kqs = $obj->getKeyQuestionsForAssessment($assessment_id, $assessor_id, $viewdata['kpa_instance_id'], $lang_id);
            foreach ($kqs as $kq) {
                $kqOpt .= '<option value="' . $kq['key_question_instance_id'] . '" ' . (isset($viewdata['key_question_instance_id']) && $viewdata['key_question_instance_id'] == $kq['key_question_instance_id'] ? 'selected="selected"' : "") . ' >' . $kq['key_question_text'] . '</option>';
            }
            $cqs = $obj->getCoreQuestionsForKQAssessment($assessment_id, $assessor_id, $viewdata['key_question_instance_id'], $lang_id);
            foreach ($cqs as $cq) {
                $cqOpt .= '<option value="' . $cq['core_question_instance_id'] . '" ' . (isset($viewdata['core_question_instance_ids']) && in_array($cq['core_question_instance_id'], explode(',', $viewdata['core_question_instance_ids'])) ? 'selected="selected"' : "") . ' >' . $cq['core_question_text'] . '</option>';
            }
        }

        $uniqId = uniqid();
        $html = '<tr class="prow">';
        $html .= '<td class="s_no">' . $sno . '</td>';
        $html .= '<td><select name="kpa[' . $uniqId . ']" class="form-control kpa selectpicker required" required data-width="150px"><option value="">--Key Domain--</option>' . $kpaOpt . '</select></td>';
        $html .= '<td><select name="js[' . $uniqId . ']" class="form-control js selectpicker" required  data-width="150px"><option value="">--Core Standard--</option>' . $jsOpt . '</select></td>';
        $html .= '<td>' . ($addDelete > 0 ? '<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>' : '') . '</td>';
        $html .= '</tr>';

        return $html;
    }

    /*     * action team new row add
     * $sno:serial no,
     * $val:array of teams members
     */

    static function getActionTeamHTMLRow($sn, $val = array()) {
        $aqsDataModel = new aqsDataModel();
        $designations = $aqsDataModel->getDesignations();
        $row = '<tr class="teamrow2">';
        $row .= '<td class="s_no">' . $sn . '</td>';
        $row .= '<td><input type="hidden" name="team_designation1[]" value=""><select class="selectpicker dholder" name="team_designation[]"><option value="">--Designation--</option>';
        foreach ($designations as $key_desig => $val_desig) {
            $row .= '<option value="' . $val_desig['designation_id'] . '" ';
            if (isset($val['team_designation']) && $val['team_designation'] == $val_desig['designation_id'])
                $row .= ' selected="selected" ';
            $row .= '>' . $val_desig['designation'] . '</option>';
        }
        $row .= '</select>
            
            </td>';


        $row .= '<td>
                                                        <input type="text" name="team_member_name[]" class="form-control tholder" value="' . (isset($val['team_member_name']) ? $val['team_member_name'] : '') . '">
                                                    </td>';
        if ($sn != 1) {
            $row .= '<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>';
        } else {
            $row .= '<td></td>';
        }

        $row .= '</tr>';

        return $row;
    }

    /*     * action activity new row add
     * $sno:serial no,
     * $val:array of teams members
     */

    static function getActionActivityHTMLRow($sn, $val = array()) {
        $aqsDataModel = new aqsDataModel();
        $designations = $aqsDataModel->getDesignations();
        $activities = $aqsDataModel->getActivity();
        $row = '<tr class="teamrowac2"  data-id="' . (isset($val['h_review_action2_activity_id']) ? $val['h_review_action2_activity_id'] : '') . '">';
        if (isset($val['postponed_ids']) && explode(",", $val['postponed_ids']) > 0) {

            $row .= '<td class="s_no" style="vertical-align:top;"><span class="collapseGA vtip fa fa-plus-circle" title="View Postponed"></span></td>';
        } else {
            $row .= '<td class="s_no" style="vertical-align:top;"></td>';
        }

        $row .= '<td class="tdcaret" style="vertical-align:top;"><input type="hidden" name="activity_old_id[]" value="' . (isset($val['h_review_action2_activity_id']) ? $val['h_review_action2_activity_id'] : '') . '"><input type="hidden" name="activity_stackholder_check[' . $sn . '][]" value=""><select class="form-control aholder" name="activity_stackholder[' . $sn . '][]" multiple="multiple">';
        //$row.='<option value="">--Stackholder--</option>';                   
        foreach ($designations as $key_desig => $val_desig) {
            $row .= '<option value="' . $val_desig['designation_id'] . '" ';

            if (isset($val['activity_stackholder_ids']) && in_array($val_desig['designation_id'], explode(",", $val['activity_stackholder_ids'])))
                $row .= ' selected="selected" ';

            $row .= '>' . $val_desig['designation'] . '</option>';
        }
        $row .= '</select>
            
            </td>';

        $row .= '<td  style="vertical-align:top;">';

        $row .= '<select name="activity[]" class="selectpicker act">';
        $row .= '<option value="">--Activity--</option>';
        foreach ($activities as $key_a => $val_a) {

            $row .= '<option value="' . $val_a['activity_id'] . '" ';
            if (isset($val['activity']) && $val['activity'] == $val_a['activity_id'])
                $row .= ' selected="selected" ';
            $row .= '>' . $val_a['activity'] . '</option>';
        }
        $row .= '</select>';
        $row .= '</td>';

        $row .= '<td style="vertical-align:top;"> 
                                                        <textarea  class="form-control ad areasize" name="activity_details[]">' . (isset($val['activity_details']) ? $val['activity_details'] : '') . '</textarea>

                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <select class="selectpicker astatus" name="activity_status[]">
                                                            <option value="">--Status--</option>
                                                            <option value="0" ' . ((isset($val['activity_status']) && $val['activity_status'] == "0") ? "Selected='Selected'" : "") . '>Not Started</option>
                                                            <option value="1" ' . ((isset($val['activity_status']) && $val['activity_status'] == "1") ? "Selected='Selected'" : "") . '>Started</option>
                                                            <option value="2"  ' . ((isset($val['activity_status']) && $val['activity_status'] == "2") ? "Selected='Selected'" : "") . '>Completed</option>
                                                            <option value="3"  ' . ((isset($val['activity_status']) && $val['activity_status'] == "3") ? "Selected='Selected'" : "") . '>Postponed</option>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <div class="datePicker1 adate"><input type="text" class="form-control date  adateda" placeholder="dd-mm-yyyy"  name="activity_date[]" value="' . ((isset($val['activity_date']) && $val['activity_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['activity_date'])) : "") . '">
                                                          
                                                      </div> 
                                                      <div class="date-show"  style="display:none;"><span>Earlier Date :</span> <span class="arealdate"></span></div> 
                                                    </td>
                                                    
                                                    <td style="vertical-align:top;">
                                                        <div class="datePicker1 fdate"><input type="text" class="form-control date " placeholder="dd-mm-yyyy" name="activity_actual_date[]" value="' . ((isset($val['activity_actual_date']) && $val['activity_actual_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['activity_actual_date'])) : "") . '"></div>                                                
                                                    </td>
                                                    
                                                    <td style="vertical-align:top;">
                                                       <textarea  class="form-control acomments areasize" name="activity_comments[]">' . (isset($val['activity_comments']) ? $val['activity_comments'] : '') . '</textarea>
                                                       
                                                       <a href="Javascript:void(0);" class="activity_comments-show vtip" title="" style="display:none;">view comment</a>   
                                                    </td>';
        $row .= '<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>';

        $row .= '</tr>';

        return $row;
    }

    /*     * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */

    static function getActionImpactStmntHTMLRow($sn, $val = array(), $impactStmntId, $designations = array(), $classes = array(), $statementData = array(), $methods = array()) {


        $row = '<tr class="teamrowac2">';
        $row .= '<td class="s_no" style="vertical-align:top;">' . $sn . '</td>';
        $row .= '<td style="vertical-align:top;"><div class="datePicker1 impact_date1"><input type="text"  class="form-control date" placeholder="dd-mm-yyyy" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'date' . ']"></div></td>';

        $row .= '<td style="vertical-align:top;">';

        $row .= '<select  class="selectpicker methodType impact_activity_method" id="' . $sn . '-' . $impactStmntId . '" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'activity_method' . ']">
                                <option value="">--Activity Method--</option>';
        foreach ($methods as $key => $val) {

            $row .= "<option value=" . $val['id'] . ">" . $val['method'] . "</option>";
        }
        $row .= '</select></td>';

        $row .= '<td style="vertical-align:top;">';


        $row .= '<div class="inlContBox fullW"><div id="actopt-' . $sn . '-' . $impactStmntId . '" class="inlCBItm cmntsDD" style=" display: none;">'
                . '<select class="selectpicker impact_activity_option" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'activity_option' . ']"><option value="">--Class--</option>';
        foreach ($classes as $key_class => $val_class) {
            $row .= '<option value="' . $val_class['class_id'] . '" ';
            if (isset($stmntData['class_id']) && $stmntData['class_id'] == $val_class['class_id'])
                $row .= ' selected="selected" ';
            $row .= '>' . $val_class['class_name'] . '</option>';
        }
        $row .= '</select></div>';
        $row .= '<div id="stake-' . $sn . '-' . $impactStmntId . '" class="inlCBItm cmntsDD " style=" display: none;">'
                . '<select class="selectpicker impact_stakeholder" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'stakeholder' . ']"><option value="">--Stakeholder--</option>';
        foreach ($designations as $key_desig => $val_desig) {
            $row .= '<option value="' . $val_desig['designation_id'] . '" ';
            if (isset($val['activity_stackholder']) && $val['activity_stackholder'] == $val_desig['designation_id'])
                $row .= ' selected="selected" ';
            $row .= '>' . $val_desig['designation'] . '</option>';
        }
        $row .= '</select></div>';
        $row .= '<textarea  class="form-control ad impact_comments areasize" id="cmnt-' . $sn . '-' . $impactStmntId . '" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'comments' . ']">' . (isset($val['activity_details']) ? $val['activity_details'] : '') . '</textarea>
                            </div></td>
                            <td style="vertical-align:top;">
                            <dd class="judgementS" style="background-color: transparent;">
                                <div class="upldHldr">
                                    <div class="fileUpload btn btn-primary mr0 vtip" title="Only jpeg, png, gif, jpg, avi, mp4, mov, doc, docx, txt, xls, xlsx, pdf, cvs, xml, pptx, ppt, cdr, mp3, wav type of files are allowed">
                                        <i class="glyphicon glyphicon-folder-open"></i> <span>Attach File</span>  
                                        <input type="file" autocomplete="off" id="' . $impactStmntId . '-' . $sn . '" title="" class="upload uploadImpactStmntBtn">
                                    </div>                                    
                                    <div class="filesWrapper" style="margin-top: 10px;">                                               

                                    </div>
                                </div>                                        

                            </dd>
                            </td>';
        $row .= '<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>';

        $row .= '</tr>';

        return $row;
    }

    /*     * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */

    static function getActionImpactStmntDataRow($sn, $val = array(), $impactStmntId, $designations, $classes, $statementData = array(), $methods = array()) {
        $row = '';
        $sn = 1;
        foreach ($statementData[$impactStmntId] as $key => $stmntData) {

            $comments = '';
            if (isset($stmntData['activity_method_id']) && $stmntData['activity_method_id'] == 2) {
                $comments = isset($stmntData['comments']) ? $stmntData['comments'] : '';
            } else if (isset($stmntData['activity_method_id']) && $stmntData['activity_method_id'] == 4) {
                $comments = isset($stmntData['stk_comments']) ? $stmntData['stk_comments'] : '';
            } else {
                $comments = isset($stmntData['im_comments']) ? $stmntData['im_comments'] : '';
            }
            $files = '';
            if (isset($stmntData['files'])) {
                $files = $stmntData['files'];
            }
            $files = diagnosticModel::decodeFileArray($files);
            $name = " impactStmnt[files][" . $impactStmntId . '][' . $sn . '][]';
            $row .= '<tr class="teamrowac2">';
            $row .= '<td class="s_no" style="vertical-align:top;">' . $sn . '</td>';
            $row .= '<td style="vertical-align:top;"><div class="datePicker1 impact_date1"><input type="text"  class="form-control date" placeholder="dd-mm-yyyy" value="' . date("d-m-Y", strtotime($stmntData['date'])) . '" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'date' . ']"></div></td>';

            $row .= '<td style="vertical-align:top;">';

            $row .= '<select  class="selectpicker methodType impact_activity_method" id="' . $sn . '-' . $impactStmntId . '" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'activity_method' . ']">
                                <option  value="">--Activity Method--</option>';
            foreach ($methods as $key => $val) {
                $row .= ' <option value="' . $val['id'] . '" ' . (isset($stmntData['activity_method_id']) && $stmntData['activity_method_id'] == $val['id'] ? "selected='selected'" : '') . '>' . $val['method'] . '</option>';
            }
            $row .= '</select>';

            $row .= '</td>';

            $row .= '<td style="vertical-align:top;">';


            $row .= '<div class="inlContBox fullW"><div id="actopt-' . $sn . '-' . $impactStmntId . '" class="inlCBItm cmntsDD" style=" ' . (isset($stmntData['activity_method_id']) && $stmntData['activity_method_id'] == 2 ? "" : 'display: none') . '">'
                    . '<select class="selectpicker impact_stakeholder" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'activity_option' . ']"> <option  value="">--Class--</option>';
            foreach ($classes as $key_class => $val_class) {
                $row .= '<option value="' . $val_class['class_id'] . '" ';
                if (isset($stmntData['class_id']) && $stmntData['class_id'] == $val_class['class_id'])
                    $row .= ' selected="selected" ';
                $row .= '>' . $val_class['class_name'] . '</option>';
            }
            $row .= '</select></div>';
            $row .= '<div id="stake-' . $sn . '-' . $impactStmntId . '" class="inlCBItm cmntsDD" style=" ' . (isset($stmntData['activity_method_id']) && $stmntData['activity_method_id'] == 4 ? "" : 'display: none') . '">'
                    . '<select class="selectpicker impact_activity_option" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'stakeholder' . ']"> <option  value="">--Stakeholder--</option>';
            foreach ($designations as $key_desig => $val_desig) {
                $row .= '<option value="' . $val_desig['designation_id'] . '" ';
                if (isset($stmntData['designation_id']) && $stmntData['designation_id'] == $val_desig['designation_id'])
                    $row .= ' selected="selected" ';
                $row .= '>' . $val_desig['designation'] . '</option>';
            }
            $row .= '</select></div>';
            $row .= '<textarea class="form-control ad impact_comments areasize" id="cmnt-' . $sn . '-' . $impactStmntId . '" name="impactStmnt[' . $impactStmntId . '][' . $sn . '][' . 'comments' . ']">' . $comments . '</textarea>
                            </div></td>
                            <td style="vertical-align:top;">
                            <dd class="judgementS" style="background-color: transparent;">
                                <div class="upldHldr">
                                    <div class="fileUpload btn btn-primary mr0 vtip" title="Only jpeg, png, gif, jpg, avi, mp4, mov, doc, docx, txt, xls, xlsx, pdf, cvs, xml, pptx, ppt, cdr, mp3, wav type of files are allowed">
                                        <i class="glyphicon glyphicon-folder-open"></i> <span>Attach File</span>  
                                        <input type="file" autocomplete="off" id="' . $impactStmntId . '-' . $sn . '" title="" class="upload uploadImpactStmntBtn">
                                    </div>
                                    <div class="filesWrapper" style="margin-top: 10px;">  ';


            foreach ($files as $file_id => $file_name) {
                $row .= '<div class="filePrev uploaded vtip ext-' . diagnosticModel::getFileExt($file_name) . '" id="file-' . $file_id . '" title="' . $file_name . '">' . '<span class="delete fa"></span><div class="inner"><a href="' . UPLOAD_URL . '' . $file_name . '" target="_blank"> </a></div><input type="hidden" name="' . $name . '" value="' . $file_id . '"></div>';
            }

            $row .= "</div></div> </dd>
                            </td>";
            $row .= '<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>';
            $row .= '</tr>';
            $sn++;
        }
        return $row;
    }
    
    
    /*     * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */
    static function getExternalImpactTeamHTMLRow($sn,$id_c,$dropval="",$textval="",$alreadyexists_id=0,$disabled=0){
            
            $aqsDataModel = new aqsDataModel();
            $designations=$aqsDataModel->getDesignations();
            $disabled_text=$disabled?'disabled="disabled"':'';
            $row = '<tr class="teamrow">';
            $row.='<td><input type="hidden" name="assessor_action1_impact_id['.$id_c.'][]" value="'.$alreadyexists_id.'"><select class="selectpicker sholder" name="stackholder['.$id_c.'][]" data-width="200px" ><option value="">--Stakeholder--</option>';
            foreach($designations as $key_desig=>$val_desig ){
                $row.='<option value="'.$val_desig['designation_id'].'" ';
                if($dropval==$val_desig['designation_id']) $row.=' selected="selected" ';
                $row.='>'.$val_desig['designation'].'</option>';
            }
                        $row.='</select>
            
            </td>
                        <td style="width:75%;"><textarea class="form-control iholder" name="stackholderimpact['.$id_c.'][]" style="resize: both;" placeholder="Enter Impact Statement" >'.$textval.'</textarea></td>';
            if($sn!=1 && $disabled==0){           
            $row.='<td style="width:4%"><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>';
            }else{
            $row.='<td style="width:4%"></td>';    
            }
            $row .= '</tr>';
            
            return $row;
        }
        /*     * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */
    static function getActionActivityViewRow($val = array()) {


        $row = '<tr class="teamrowac2 ga-rows-' . $val['h_review_action2_activity_id'] . '">';

        $row .= '<td class="s_no"></td>';


        $row .= '<td>' . $val['designation'] . '</td>';

        $row .= '<td>';
        $row .= $val['activity'];
        $row .= '</td>';
        $row .= '<td>' . nl2br($val['activity_details']) . '</td>';
        $row .= '<td>Postponed</td>';

        if ($val['activity_date'] != "0000-00-00" && !empty($val['activity_date'])) {
            $row .= '<td>' . date("d-m-Y", strtotime($val['activity_date'])) . '</td>';
        } else {
            $row .= '<td></td>';
        }

        if ($val['activity_actual_date'] != "0000-00-00" && !empty($val['activity_actual_date'])) {
            $row .= '<td>' . date("d-m-Y", strtotime($val['activity_actual_date'])) . '</td>';
        } else {
            $row .= '<td></td>';
        }

        $row .= '<td>' . nl2br($val['activity_comments']) . '</td>';



        $row .= '<td></td>';
        $row .= '</tr>';

        return $row;
    }
/*     * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */
    static function getExternalReviewTeamHTMLRow($sn, $ty = 'all') {

        $clientModel = new clientModel();

        if ($ty == "college") {
            $clients = $clientModel->getClients(array("client_institution_id" => 2, "max_rows" => -1, 'school_ids' => array("'Adhyayan'", "'Independent Consultant'")));
        } else if ($ty == "school") {
            $clients = $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1));
        } else {
            $clients = $clientModel->getClients(array("max_rows" => -1));
        }

        $assessmentModel = new assessmentModel();

        $externalReviewRoles = $assessmentModel->getReviewerSubRoles(4);

        $row = '<tr class="team_row">

					<td class="s_no">' . $sn . '</td>';

        $row .= '<td><select class="form-control team_external_client_id" id="team_external_client_id' . $sn . '" required name="externalReviewTeam[clientId][]">';
        if ($ty == "college") {
            $row .= '<option value=""> - Select College - </option>';
        } else {
            $row .= '<option value=""> - Select School - </option>';
        }

        foreach ($clients as $client)
            $row .= "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";



        $row .= '</select></td>';



        $row .= '<td><select class="form-control " name="externalReviewTeam[role][]" required>

						<option value=""> - Select Role - </option>						

						';

        foreach ($externalReviewRoles as $externalReviewer)
            $row .= $externalReviewer['sub_role_id'] == '1' ? '' : "<option value=\"" . $externalReviewer['sub_role_id'] . "\">" . $externalReviewer['sub_role_name'] . "</option>";





        $row .= '</select></td>

					<td><select class="form-control team_external_assessor_id" name="externalReviewTeam[member][]" id="team_external_assessor_id' . $sn . '" required>

						<option value=""> - Select Member - </option>

						</select>

					</td>

					<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>

				</tr>';

        return $row;
    }
    /* * action impact new row add
     * $sno:serial no,
     * $val:array of teams members
     */
    static function getFacilitatorReviewTeamHTMLRow($sn, $ty = 'school') {

        $clientModel = new clientModel();

        if ($ty == "college") {
            $clients = $clientModel->getClients(array("client_institution_id" => 2, "max_rows" => -1, 'school_ids' => array("'Adhyayan'", "'Independent Consultant'")));
        } else if ($ty == "school") {
            $clients = $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1));
        } else {
            $clients = $clientModel->getClients(array("max_rows" => -1));
        }

        $assessmentModel = new assessmentModel();

        $externalReviewRoles = $assessmentModel->getReviewerSubRoles(4);

        $row = '<tr class="facilitator_row">

					<td class="s_no">' . $sn . '</td>';

        $row .= '<td><select class="form-control team_facilitator_client_id" id="team_facilitator_client_id' . $sn . '" required name="facilitatorReviewTeam[clientId][]">';

        if ($ty == "college") {
            $row .= '<option value=""> - Select College - </option>';
        } else {
            $row .= '<option value=""> - Select School - </option>';
        }

        foreach ($clients as $client)
            $row .= "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";



        $row .= '</select></td>';



        $row .= '<td><select class="form-control " name="facilitatorReviewTeam[role][]" required>

						<option value=""> - Select Role - </option>						

						';

        foreach ($externalReviewRoles as $externalReviewer)
            $row .= $externalReviewer['sub_role_id'] == '1' || $externalReviewer['sub_role_id'] == '2' ? '' : "<option value=\"" . $externalReviewer['sub_role_id'] . "\">" . $externalReviewer['sub_role_name'] . "</option>";





        $row .= '</select></td>

					<td><select class="form-control team_external_facilitator_id" name="facilitatorReviewTeam[member][]" id="team_external_facilitator_id' . $sn . '" required>

						<option value=""> - Select Member - </option>

						</select>

					</td>

					<td><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a></td>

				</tr>';

        return $row;
    }
    

    
    /* 
    * @Purpose : Function to add network row html
    * @Params: $network_id,$client
    */
    static function getEditSchoolsInnetworkRowHtml($network_id,$client){
            $address=$client['street']; 
            $address.=($address==""?"":", ").$client['city'];
            $address.=($address==""?"":", ").$client['state'];
            return '
                    <tr>
                            <td>'.$client['client_name'].'</td>
                            <td>'.$address.'</td>
                            <td><a href="javascript:void(0)" data-id="'.$client['client_id'].'" data-nid="'.$network_id.'" class="unlinkClient"><i class="vtip glyphicon glyphicon-remove" title="Remove School from network"></i></a></td>
                    </tr>';
    }
    
    
        /* 
        * @Purpose : Function to add province row html
        * @Params: $province_id,$client
        */
	static function getEditSchoolsInnetworkProvinceRowHtml($province_id,$client){
		$address=$client['street'];
		if(($client['city'] !="" && $address !="")){
                    $address.= ", ".$client['city'];
                }
		if( (($client['city'] !="" || $address !="") && $client['state'] !="")){
                    $address.= ", ".$client['state'];
                }
		return '<tr>
                            <td>'.$client['client_name'].'</td>
                            <td>'.$address.'</td>
                            <td><a href="javascript:void(0)" data-id="'.$client['client_id'].'" data-pid="'.$province_id.'" class="unlinkClientFromProvince"><i class="vtip glyphicon glyphicon-remove" title="Remove School from province"></i></a></td>
			</tr>';
        }
    
        /* 
        * @Purpose : Function to add recommendation row html
        * @Params: $type,$instance_id,$sno,$text
        */
    static function getRecommendationRow($type,$instance_id,$sno=1,$text=''){
		$removeBtn = $sno>1?'<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>':'';
		$html = "<tr class='recRow'>";
		$html .= "<td class='s_no'>$sno</td>
		<td><textarea class='form-control' name='recommendations_".$type."[".$instance_id."][]' id='recommendation_".$type."_".$sno."' placeholder='Please enter your recommendation here' required>".$text."</textarea></td>
				<td>".$removeBtn."</td>";
		$html .= "</tr>";
		return $html;

	}
        
        /* 
        * @Purpose : Function to add assessor-key-note row html
        * @Params: $kpa7,$kpa_id, $akn_id, $text, $type, $attrbutes, $addDelete, $type_q, $jsDrop, $jsselected
        */
        public static function getAssessorKeyNoteHtmlRow($kpa7 = '', $kpa_id, $akn_id, $text, $type, $attrbutes = '', $addDelete = 1, $type_q = "", $jsDrop = array(), $jsselected = array()) {
        $akn_id = $akn_id == 'new' ? 'new_' . uniqid() : $akn_id;
        $options = "";
        if ($type_q == "kpa") {
            $options .= 'Choose a core standard : 
                   <select name="js[' . $type . '][' . $kpa_id . '][' . $akn_id . '][]" id="js_' . $type . '_' . $kpa_id . '_' . $akn_id . '"  class="form-control rec_dropdown" required multiple>';
            foreach ($jsDrop as $key => $val) {
                $options .= '<option value="' . $val['judgement_statement_instance_id'] . '"';
                if (in_array($val['judgement_statement_instance_id'], $jsselected))
                    $options .= ' selected="selected" ';
                $options .= ' >' . $val['show_text'] . '. ' . $val['judgement_statement_text'] . '</option>';
            }
            $selected_js_id = implode(',', $jsselected);
            $good = "'good'";

            $options .= '</select>';

            if ($kpa7 != 1) {
                $options .= '<span><a href="#" class="good_stmnt" id="js_' . $type . '_' . $kpa_id . '_' . $akn_id . '">&nbsp;&nbsp;<span style="color:#000000;">Click here to view </span><span style="color:blue;">What ' . $good . ' looks like?</span></a></span>';
                $options .= '<input type="hidden" id="goodstatementurl" value="' . SITEURL . 'index.php?controller=diagnostic&action=goodlookslike&type=kpa&instance_id=">';
            }
        }

        if (!empty($type)) {
            return '<dl class="fldList keynote-wrap ' . $type . '" id="kn-id-' . $akn_id . '"><dd class="ml0" style="background:none;">
                        
                        ' . $options . '
                    
                       <textarea cols="20" rows="4" name="data[' . $type . '][' . $kpa_id . '][' . $akn_id . ']" class="form-control keynotes-text" ' . $attrbutes . ' autocomplete="off" placeholder="Enter text" required>' . $text . '</textarea>' . ($addDelete > 0 ? '<span class="deleteKeyNote"><i class="fa fa-remove"></i></span>' : '') . '</dd></dl>';
        }

        return '<dl class="fldList keynote-wrap" id="kn-id-' . $akn_id . '"><dd class="ml0" style="background:none;"><textarea cols="20" rows="4" name="data[' . $kpa_id . '][' . $akn_id . ']" class="form-control keynotes-text" ' . $attrbutes . ' autocomplete="off" placeholder="Assessor Key Recommendations" required>' . $text . '</textarea>' . ($addDelete > 0 ? '<span class="deleteKeyNote"><i class="fa fa-remove"></i></span>' : '') . '</dd></dl>';
    }

}



?>