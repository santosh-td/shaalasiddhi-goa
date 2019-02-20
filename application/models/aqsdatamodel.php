<?php
 /** Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class aqsDataModel extends Model{
	
	function getReferrerList(){
		$res=$this->db->get_results("select * from d_referrer where referrer_id>0;");
		return $res?$res:array();
	}
	
	function getBoardList($country_id = '',$aqs = 0,$val = ''){
                $sql = "select * from d_board WHERE ";
                if($aqs == 1) {
                    
                    $sql .= " LOWER(board) = '$val'";
                }else {
                    $sql .= " board_id>0";
                    if($country_id != '202') {

                        $sql.= " AND board NOT IN ('CAPS','IEB') ";
                    }
                }
		$res=$this->db->get_results($sql);
		return $res?$res:array();
	}
	
	function getSchoolTypeList($aqs = 0,$val = ''){
                
                $sql = "select * from d_school_type where ";
                if($aqs == 1) {
                    
                     $sql .= " LOWER(school_type) IN ($val);";
                }else {
                     $sql .= " school_type_id>0 AND school_type NOT IN ('Minority','Unrecognised');";
                }
                //echo $sql;
		$res=$this->db->get_results($sql);
		return $res?$res:array();
	}
	function getSchoolRegionList($aqs = 0,$val = ''){
            
                $sql = "select * from d_school_region where ";
                if($aqs == 1) {
                     $sql .= " region_id = '$val'";
                }else 
                    $sql .= ' region_id>0';
		$res = $this->db->get_results($sql);
		return $res?$res:array();
	}
	
	function getSchoolItSupportList(){
		$res=$this->db->get_results("select * from d_school_it_support;");
		return $res?$res:array();
	}
	
	function getSchoolClassList($aqs=0,$val=''){
                
                $sql = "select * from d_school_class where ";
                if($aqs == 1) {
                    
                    $sql .= " LOWER(class_name) = '$val'";
                }else {
                    $sql .= ' class_id>0;';
                }
		$res=$this->db->get_results($sql);
		return $res?$res:array();
	}
	
	function getSchoolLevelList(){
		$res=$this->db->get_results("select * from d_school_level where dept_type=0;");
		return $res?$res:array();
	}
	
	function getStudentTypeList($aqs = 0,$val = ''){
                
                $sql = "select * from d_student_type where ";
                if($aqs == 1) {
                    $sql .= " student_type_id  = '$val';";
                }else {
                        $sql .= "student_type_id>0;";
                }
		$res=$this->db->get_results($sql);
		return $res?$res:array();
	}
	
	function getAqsData($assmntId_or_grpAssmntId,$assessment_type_id){
		$res=$this->db->get_row("select ad.*, group_concat(distinct it.itsupport_id) as it_support_ids, group_concat(concat(t.school_level_id,'#|',t.id,'#|',t.start_time,'#|',t.end_time) SEPARATOR '#|#') as school_timing,group_concat(distinct st.school_type_id) as school_type_ids
		from d_AQS_data ad
        ".(($assessment_type_id==1 || $assessment_type_id==5)?"inner join d_assessment a on a.aqsdata_id=ad.id and a.assessment_id=?":"inner join (select aa.* from d_assessment aa inner join h_assessment_ass_group ag on aa.assessment_id=ag.assessment_id and ag.group_assessment_id=? limit 1)  a on a.aqsdata_id=ad.id")."
		left join h_aqsdata_itsupport it on ad.id=it.aqs_id
		left join h_AQS_school_level t on t.AQS_data_id=ad.id
                left join h_assessment_school_type st on st.assessment_id=a.assessment_id
		group by ad.id",array($assmntId_or_grpAssmntId));
		if($res){
                        $res['school_type_ids']=$res['school_type_ids']!=""?explode(",",$res['school_type_ids']):array();
			$res['it_support_ids']=$res['it_support_ids']!=""?explode(",",$res['it_support_ids']):array();
			$res['school_timing']=$res['school_timing']!=""?explode("#|#",$res['school_timing']):array();
			$tmp=array();
			foreach($res['school_timing'] as $itm){
				$t=explode("#|",$itm);
				$tmp[$t[0]]=array("school_level_id"=>$t[0],"id"=>$t[1],"start_time"=>$t[2],"end_time"=>$t[3]);
			}
			$res['school_timing']=$tmp;
			return $res;
		}else
			return array();
	}
	function getAqsAdditionalData($aqsData_id,$tableName){
		$sql = "Select aaq.*,
 				(select group_concat(school_community_id) from h_aqs_school_communities where aqsdata_id=aaq.aqs_data_id) school_community_id ,
 				(select group_concat(review_medium_instrn_id) from h_aqs_medium_instruction where aqsdata_id=aaq.aqs_data_id) review_medium_instrn_id 
 				from $tableName aaq
 				where aqs_data_id=?";
		$res = $this->db->get_row($sql,array($aqsData_id));
		return $res?$res:array();
	}
	function getAqsAdditionalRefTeam($aqsData_id){
		$sql = "SELECT * FROM d_aqs_additional_references where aqsdata_id=?";
		$res = $this->db->get_results($sql,array($aqsData_id));
		return $res?$res:array();
	}

	
	function updateAqsData($AQS_data_id,$data){
		return $this->db->update("d_AQS_data",$data,array("id"=>$AQS_data_id));
	}
	
	function insertAqsData1($data){
		if($this->db->insert("d_AQS_data",$data)){
			return $this->db->get_last_insert_id();
		}else
			return false;
	}
        
        function saveAqsStatus($assessment_id,$submit=0,$totalPercentage=0,$aqsStatusId=0){

            if($assessment_id>0){
                if($aqsStatusId){
                    return $this->db->update("h_school_profile_status",array('assessment_id'=>$assessment_id,'perComplete'=>$totalPercentage,'submit_date'=>date("Y-m-d"),'status'=>$submit),array("id"=>$aqsStatusId));
                }else{
                     $this->db->delete("f_school_profile_data", array('assessment_id'=>$assessment_id));
                    if($this->db->insert("h_school_profile_status",array('assessment_id'=>$assessment_id,'perComplete'=>$totalPercentage,'submit_date'=>date("Y-m-d"),'status'=>$submit))){
                            return $this->db->get_last_insert_id();
                    }else
                            return false;
                }

            }
        }
        
        function saveAqsFormData($data,$assessment_id,$submit=0){
                
                //echo "<pre>";print_r($data);die;
                $condition = array('assessment_id' => $assessment_id);
                $params = array();
                $this->db->delete("f_school_profile_data", $condition);
                $sql = 'INSERT INTO f_school_profile_data (school_profile_id,assessment_id,answer, group_id) VALUES';
                $sqlValues = '';
                $complete=0;$inComplete=0;$total=0;
                foreach($data as $key=>$val){
                   
                    $keyData = explode("_",$key);
                    if(isset($keyData[0]) && $keyData[0] == 'txt' ){
                        if(is_array($val)){
                            
                            foreach($val as $key=>$areaData){
                                 //echo $areaData;
                                //$sqlValues .= '('. $keyData[1].",".$assessment_id.",'".$areaData."'),";
                                 if(!empty($areaData)){
                                    $sqlValues .= '(?,?,?,?),';
                                    $complete++;
                                    array_push($params,$keyData[1],$assessment_id,$areaData,1);
                                 }else
                                     $inComplete++;
                             }
                        }else{
                            if(isset($val)){
                                $sqlValues .= '(?,?,?,?),';
                                $complete++;
                                array_push($params,$keyData[1],$assessment_id,$val,1);
                            }else{
                                $inComplete++;
                            }
                        }
                    }else if(isset($keyData[0]) && $keyData[0] == 'area' ){
                        
                           
                         if(is_array($val)){ 
                              //echo "<pre>";print_r($val);
                            //echo $keyData[1];
                             foreach($val as $key=>$areaData){
                                 //echo $areaData;
                                //$sqlValues .= '('. $keyData[1].",".$assessment_id.",'".$areaData."'),";
                                 if(!empty($areaData)){
                                    $sqlValues .= '(?,?,?,?),';
                                    $complete++;
                                    $key1=$key+1;
                                    array_push($params,$keyData[1],$assessment_id,$areaData,$key1);
                                 }else
                                     $inComplete++;
                             }
                             
                         }else{
                           // $sqlValues .= '('. $keyData[1].",".$assessment_id.",'".$val."'),";
                            if(isset($val)){
                                $sqlValues .= '(?,?,?,?),';
                                $complete++;
                                array_push($params,$keyData[1],$assessment_id,$val,1);
                            }else{
                                $inComplete++;
                            }
                         }
                    }else if(isset($keyData[0]) && $keyData[0] == 'ch'){
                        
                        if(is_array($val)){  
                        foreach($val as $optionDataCh){
                            
                           $optionDataVal = explode("-",$optionDataCh); 
                           if(!empty($optionDataVal)) {
                              // $sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                               $sqlValues .= '(?,?,?,?),';
                               $complete++;
                               array_push($params,$optionDataVal[1],$assessment_id,$optionDataVal[0],1);
                           }else
                               $inComplete++;
                        }
                        }else{
                            $optionDataVal = explode("-",$val); 
                           if(isset($optionDataVal)) {
                              // $sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                               $sqlValues .= '(?,?,?,?),';
                               $complete++;
                               array_push($params,$optionDataVal[1],$assessment_id,$optionDataVal[0],1);
                           }else
                               $inComplete++;
                        }
                    }else if(isset($keyData[0]) && $keyData[0] == 'rd'){
                        
                        if(is_array($val)){                        
                            foreach($val as $optionDataRd){

                               $optionDataVal = explode("-",$optionDataRd); 
                               if(!empty($optionDataVal)) {
                                   //$sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                                   $sqlValues .= '(?,?,?,?),';
                                   $complete++;
                                   array_push($params,$optionDataVal[1],$assessment_id,$optionDataVal[0],1);
                               }else
                               $inComplete++;
                            }
                        }else{
                            
                           $optionDataVal = explode("-",$val); 
                           if(isset($optionDataVal)) {
                              // $sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                               $sqlValues .= '(?,?,?,?),';
                               $complete++;
                               array_push($params,$optionDataVal[1],$assessment_id,$optionDataVal[0],1);
                           }else
                               $inComplete++;
                        }
                        
                    }
                   
                }
                
                if(!empty($sqlValues)){
                     $sqlValues = trim($sqlValues,",");
                     $sql .= $sqlValues;
                     //echo "in".$inComplete;
                   //  echo"c". $complete;
                     $total = $inComplete+$complete;
                    // echo"sss". $percentage = round(($complete*100)/($total*7),2);
                     //echo "<pre>";print_r($params);
                     if($this->db->query($sql,$params)){
                         
			return $this->db->get_last_insert_id();
                    }else
			return false;
                }
                 return false;
        
               
                
	}
        function validateAqsFormData($data,$assessment_id){
                
                //echo "<pre>";print_r($data);die;
                $condition = array('assessment_id' => $assessment_id);
                $this->db->delete("f_school_profile_data", $condition);
                $sql = 'INSERT INTO f_school_profile_data (school_profile_id,assessment_id,answer) VALUES';
                $sqlValues = '';
                foreach($data as $key=>$val){
                   
                    $keyData = explode("_",$key);
                    if(isset($keyData[0]) && $keyData[0] == 'txt' && !empty($val)){
                        
                       $sqlValues .= '('. $keyData[1].",".$assessment_id.",'".$val."'),";
                    }else if(isset($keyData[0]) && $keyData[0] == 'area' && !empty($val)){
                        $sqlValues .= '('. $keyData[1].",".$assessment_id.",'".$val."'),";
                    }else if(isset($keyData[0]) && $keyData[0] == 'ch' && !empty($val)){
                        foreach($val as $optionDataCh){
                            
                           $optionDataVal = explode("-",$optionDataCh); 
                           if(!empty($optionDataVal)) {
                               $sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                           }
                        }
                    }else if(isset($keyData[0]) && $keyData[0] == 'rd' && !empty($val)){
                        foreach($val as $optionDataRd){
                            
                           $optionDataVal = explode("-",$optionDataRd); 
                           if(!empty($optionDataVal)) {
                               $sqlValues .= '('. $optionDataVal[1].",".$assessment_id.",".$optionDataVal[0].'),';
                           }
                        }
                        
                    }
                   
                }
                
                if(!empty($sqlValues)){
                     $sqlValues = trim($sqlValues,",");
                     $sql .= $sqlValues;
                     if($this->db->query($sql)){
			return $this->db->get_last_insert_id();
                    }else
			return false;
                }
                 return false;
        
               
                
	}
	function insertSchoolType($ass_id, $data) {
            $condition = array('assessment_id' => $ass_id);
            $this->db->delete("h_assessment_school_type", $condition);
            if (!empty($data)) {
                $values = $this->prepareValues($ass_id, $data);
                $sql_school_type = "INSERT INTO h_assessment_school_type (assessment_id,school_type_id ) VALUES $values";
                if ($this->db->query($sql_school_type)) {
                    return $this->db->get_last_insert_id();
                } else
                    return false;
            }
        }

    function prepareValues($ass_id,$resresult){
        
            $query_values = '';
            foreach ($resresult as $data) {

                $query_values .= "(" . $ass_id . ',' . $data . "),";
            }
            return rtrim($query_values,',');
    }
	
	function getAQSTeam($AQS_data_id){
		$res=$this->db->get_results("select * from d_AQS_team where AQS_data_id=?;",array($AQS_data_id));
//		if($res){
//			$res=$this->db->array_grouping($res,"isInternal");
//			return array("school"=>isset($res[1])?$res[1]:array(),"adhyayan"=>isset($res[2])?$res[2]:array());
//		}else
//			return array("school"=>array(),"adhyayan"=>array());
                if($res){
			$res=$this->db->array_grouping($res,"isInternal");
			return array("school"=>isset($res[1])?$res[1]:array());
		}else
			return array("school"=>array());
                
	}
        
	function getAqsSchoolType($AQS_id){
            
		return $this->db->get_results("select school_type_id from h_assessment_school_type where assessment_id=?;",array($AQS_id));
	}
	
	function addItSupport($aqsData_id,$itsupport_id){
		if($this->db->insert("h_aqsdata_itsupport",array('aqs_id'=>$aqsData_id,'itsupport_id'=>$itsupport_id))){
			return $this->db->get_last_insert_id();
		}else
			return false;
	}
	
	function removeItSupport($aqsData_id,$itsupport_id=0){
		$condition=array('aqs_id'=>$aqsData_id);
		if($itsupport_id>0)
			$condition['itsupport_id']=$itsupport_id;
		return $this->db->delete("h_aqsdata_itsupport",$condition);
	}
	
	function addSchoolTiming($aqsData_id,$school_level_id,$start_time,$end_time){
		if($this->db->insert("h_AQS_school_level",array('AQS_data_id'=>$aqsData_id,'school_level_id'=>$school_level_id,'start_time'=>$start_time,"end_time"=>$end_time))){
			return $this->db->get_last_insert_id();
		}else
			return false;
	}
	
	function removeSchoolTiming($aqsData_id,$school_level_id=0){
		$condition=array('AQS_data_id'=>$aqsData_id);
		if($school_level_id>0)
			$condition['school_level_id']=$school_level_id;
		return $this->db->delete("h_AQS_school_level",$condition);
	}
	
	function addAqsTeam($aqsData_id,$name,$designation,$lang_id,$email,$mobile,$isSchoolteam=1,$country_code){
		$teamType=$isSchoolteam?1:2;
                
                 if(!empty($mobile)) {
                                                    
                    $mobile = "(+".$country_code.")". $mobile;
                 }
             
		if($this->db->insert("d_AQS_team",array('AQS_data_id'=>$aqsData_id,'name'=>$name,'designation_id'=>$designation,"lang_id"=>$lang_id,"email"=>$email,"mobile"=>$mobile,"isInternal"=>$teamType))){
			return $this->db->get_last_insert_id();
		}else
			return false;
	}
	
	function removeAqsTeam($aqsData_id,$removeSchoolTeam=1,$id=0){//remove school team=0 is for removing adhyayan team
		$condition=array('AQS_data_id'=>$aqsData_id);
		$condition['isInternal']=$removeSchoolTeam>0?1:2;
		if($id>0)
			$condition['id']=$id;
		return $this->db->delete("d_AQS_team",$condition);
	}
	function addAdditionalRefTeam($aqsData_id,$name,$phone,$email,$role_stakeholder){		
		if($this->db->insert("d_aqs_additional_references",array('aqsdata_id'=>$aqsData_id,'name'=>$name,'phone'=>$phone,"email"=>$email,"role_stakeholder"=>$role_stakeholder))){
			return $this->db->get_last_insert_id();
		}else
			return false;
	}
	function removeAdditionalRefTeam($aqsData_id){
		$condition=array('aqsdata_id'=>$aqsData_id);				
		return $this->db->delete("d_aqs_additional_references",$condition);
	}
	function removeAdditionalSchoolCommunity($aqsData_id){
		$condition =array('aqsdata_id'=>$aqsData_id);
		return $this->db->delete("h_aqs_school_communities",$condition);
	}
	function removeAdditionalMediumInstruction($aqsData_id){
		$condition =array('aqsdata_id'=>$aqsData_id);
		return $this->db->delete("h_aqs_medium_instruction",$condition);
	}
	function addAdditionalSchoolCommunity($aqsData_id,$community_id){
		return $this->db->insert("h_aqs_school_communities",array("aqsdata_id"=>$aqsData_id,"school_community_id"=>$community_id));
	}
	function addAdditionalMediumInstruction($aqsData_id,$medium_instruction_id){
		return $this->db->insert("h_aqs_medium_instruction",array("aqsdata_id"=>$aqsData_id,"review_medium_instrn_id"=>$medium_instruction_id));
	}
	function insertAdditionalQuestionsData($data){
		if($this->db->insert("d_aqs_additional_questions",$data))
			return $this->db->get_last_insert_id();
	}
	function updateAdditionalQuestionsData($aqsData_id,$data){
		return $this->db->update("d_aqs_additional_questions",$data,array("aqs_data_id"=>$aqsData_id));
	}

	
        function aqsFormValidation($aqsData,$checkRequired=1){
		$aqsFields=array(	
			"txt_125_1"=>array("type"=>"string","isRequired"=>1) //txt
                        //"aqs[ch_29]"=>array("type"=>"int","isRequired"=>1,"name"=>"This field is required!"), //ch
                        //"aqs[rd_17]"=>array("type"=>"int","isRequired"=>1,"name"=>"This field is required!") //rd
                       	);
		$errors=array();
		
		foreach($aqsFields as $k=>$f){echo '<pre>'; print_r($aqsData[$k]);
			$val=isset($aqsData[$k])?trim($aqsData[$k]):"";
                        //echo $val;echo '<br>';
                        //echo $checkRequired;echo '<br>';
			if($checkRequired && $f["isRequired"] && empty($val)){
				$errors[$k]="This field is required";
                        }
			
		}
		return array("errors"=>$errors);
	}
	
	
	function getPreferredLanguages($active=1){  
              //$res=$this->db->get_results("select * from preferred_language where lang_id>0 and active=? ;",array($active));  
              $res=$this->db->get_results("select language_id as lang_id,language_name as lang_name  from d_language where language_id>0;",array());
              return $res?$res:array();
        }
        
        function getDesignations($active=1){
        
            $res=$this->db->get_results("select designation_id,designation  from d_designation where designation_id>0 and active=? ORDER BY rank ASC ;",array($active));
            return $res?$res:array();
        }
        
        function getActivity($active=1){
        
            $res=$this->db->get_results("select activity_id,activity,symbol  from d_activity where  active=? ORDER BY activity  ASC ;",array($active));
            return $res?$res:array();
        }
    
	function getAqsTeamHtmlRow($sn,$isSchoolRow=1,$name,$designation,$lang_id,$email,$mobile,  $attrbutes='',$addDelete=1,$c_code = 91){
		global $pLangList;
		if(empty($pLangList))
			$pLangList=$this->getPreferredLanguages();
                
                $pDesigList=$this->getDesignations();
                $clientModel=new clientModel();	
                $country_code_list =  $clientModel->getCountryWithCode();
		$type=$isSchoolRow?'schoolTeam':'adhyayanTeam';
		$ret= '<tr class="team_row">
				<td class="s_no">'.$sn.'</td> 
				<td><input type="text"  class="tableTxtFld" id="aqsf_'.$type.'_'.($sn-1).'_name" autocomplete="off" name="'.$type.'[name][]" value="'.$name.'" '.$attrbutes.'></td>'; 
				
                                //<td><input type="text" class="tableTxtFld" id="aqsf_'.$type.'_'.($sn-1).'_designation" autocomplete="off" name="'.$type.'[designation][]" value="'.$designation.'" '.$attrbutes.'></td>
				$ret.= '<td><select class="tableDdFld selectpicker form-control" id="aqsf_'.$type.'_'.($sn-1).'_designation" autocomplete="off" name="'.$type.'[designation][]" '.$attrbutes.'><option value=""> - Select designation - </option>';
		foreach($pDesigList as $desig)
			$ret.='<option value="'.$desig['designation_id'].'" '.($desig['designation_id']==$designation?'selected="selected"':'').'>'.$desig['designation'].'</option>';
		$ret.='</select></td>';
                
                                $ret.= '<td><select class="tableDdFld selectpicker form-control" id="aqsf_'.$type.'_'.($sn-1).'_language" autocomplete="off" name="'.$type.'[lang_id][]" '.$attrbutes.' data-size="10"><option value=""> - Select language - </option>';
		foreach($pLangList as $lang)
			$ret.='<option value="'.$lang['lang_id'].'" '.($lang['lang_id']==$lang_id?'selected="selected"':'').'>'.$lang['lang_name'].'</option>';
		$ret.='</select></td>
				<td><input type="email" class="tableTxtFld" id="aqsf_'.$type.'_'.($sn-1).'_email" autocomplete="off" name="'.$type.'[email][]" value="'.$email.'" '.$attrbutes.'></td> ';
               
                
                $ret.= '<td style="width: 250px;"><select class="tableDdFld selectpicker related form-control w90" id="aqsf_'.$type.'_'.($sn-1).'_c_code" autocomplete="off" name="'.$type.'[c_code][]" '.$attrbutes.'>';
		foreach($country_code_list as $country)
			$ret.='<option value="'.$country['phonecode'].'" '.($country['phonecode']==$c_code?'selected="selected"':'').'>'."(+".$country['phonecode'].") ".'</option>';
		$ret.='</select>';
                
		$ret.=	'<input type="text" class="tableTxtFld w90 aqs_ph" id="aqsf_'.$type.'_'.($sn-1).'_mobile" autocomplete="off" name="'.$type.'[mobile][]" value="'.$mobile.'" '.$attrbutes.'></td>
				<td >'.($addDelete>0?'<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>':'').'</td>
			</tr>';
		return $ret;
	}
	function getAqsAdditonalRefHtmlRow($sn,$name,$phone,$email,$role_stakeholder,$addDelete,$c_code = 91){	
            
                $clientModel=new clientModel();	
                $country_code_list =  $clientModel->getCountryWithCode();
		$ret = '<tr class="team_row">
				<td class="s_no">'.$sn.'</td>
				<td><input type="text" class="tableTxtFld" id="additional_team_name_'.($sn-1).'" name="additional_ref[name][]" value="'.$name.'"></td>';
                $ret.= '<td style="width: 250px;"><select class="tableDdFld selectpicker related form-control w90" id="additional_team_ccode_'.($sn-1).'" autocomplete="off" name="additional_ref[c_code][]]" >';
                                foreach($country_code_list as $country)
                                        $ret.='<option value="'.$country['phonecode'].'" '.($country['phonecode']==$c_code?'selected="selected"':'').'>'."(+".$country['phonecode'].") ".'</option>';
                                $ret.='</select>';
				$ret.= '<input type="text" class="tableTxtFld aqs_ph w90" id="additional_team_phone_'.($sn-1).'" name="additional_ref[phone][]" value="'.$phone.'"></td>
				<td><input type="email" class="tableTxtFld" id="additional_team_email_'.($sn-1).'" name="additional_ref[email][]" value="'.$email.'"></td>
				<td><input type="text" class="tableTxtFld" id="additional_team_role_stake_'.($sn-1).'" name="additional_ref[role_stakeholder][]" value="'.$role_stakeholder.'"></td>	
				<td>'.($addDelete>0?'<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>':'').'</td>		
				</tr>';		
				return $ret;
	}
	
	function isValidDate($date){
		$t=explode("-",$date);
		if(strpos($date,".")===false && count($t)==3 && $t[1]<=12 && $t[0]<=31 && $t[2]>2000)
			return true;
		else
			return false;
	}
        function isValidAQSDate($date){
                //print_r($date);
		$t=array_map('trim',explode("/",$date));
                $t  = array_filter($t);
                //print_r($t);
                $validFlag = 1;
                if(count($t) ==3) {
		if( !count($t)==3 ) {
                    $validFlag = 0;
                }if(!(preg_match('/^[0-9][0-9]*$/', $t[0]) && $t[0]>2016))
                     $validFlag = 0;
                if(!(preg_match('/^[0-9][0-9]*$/', $t[1]) && $t[1]>=1 && $t[1]<=12))
                     $validFlag = 0;
                if(!(preg_match('/^[0-9][0-9]*$/', $t[2]) && $t[2]>=1 && $t[2]<=31))
                     $validFlag = 0;
                if($validFlag == 1)
                    return true;
		else
			return false;
                }return false;
	}

	
}