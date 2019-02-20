<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class aqsformValidateHelper {

    private $skip_subquestions = false;
    
    private $errorList = [];
    private $dataToBeValidated = [];
    private $errors = [
        'required' => 'This field is required'
    ];
            
    function validateForm($dataToBeValidated, $params = []) {echo "<pre>";print_r($dataToBeValidated);
        $params['level'] = 1;
        $this->errorList = [];
        $this->dataToBeValidated = $dataToBeValidated;
        array_walk($dataToBeValidated, array($this, 'validateQuestion'), $params);
        print_r($this->errorList);
    }

    private function validateQuestion($question, $key, $params) {
        
        
        $level = $params['level'];
        global  $question_num ;
        $this->skip_subquestions = false;
        if ($level == 1) {
           $question_num = $question_num+1;
        }
        
        $this->questionStartHtmlTags($question, $level);
        
        $school_profile_id=$question['school_profile_id'];
        
        
        
        echo $this->getFieldByHtmlTypeId($question_text, $question);
        
        ?>
        </div>
        
        <?php

        if(!empty($question['sub_questions']) && !$this->skip_subquestions){
            ?>
            <div class="question-wrap">
            <?php
                $params['level'] = $level + 1;
                array_walk($question['sub_questions'], array($this, 'validateQuestion'), $params);
            ?>
            </div>
        <?php
        }

        $this->questionEndHtmlTags();
    }

    private function questionEndHtmlTags() {
        ?>
        </div>
        <?php
    }

    function getFieldByHtmlTypeId($question_text, $question, $is_array_type = false, $skip_label = false) {
        $school_profile_id = $question['school_profile_id'];
       // echo"aaaa". $is_array_type;
        $name_suffix= $is_array_type ? '[]' : '';
        switch($question['html_type_id']){
            case 2:
                if(empty($this->dataToBeValidated['rd_'.$question['parent_id'].'_'.$question['kpa_id']])){
                    $this->addError($question['parent_id'], $this->errors['required']);
                }
                return;
            case 3:
                $checkedCh='';
                if($question['answer'] == 1){
                   $checkedCh='checked="checked"'; 
                }

                if(empty($this->dataToBeValidated['ch_'.$question['parent_id'].'_'.$question['kpa_id']])){
                    $this->addError($question['parent_id'], $this->errors['required']);
                }
                return;
               

            case 4:
                if(empty($this->dataToBeValidated['txt_'.$question['parent_id'].'_'.$question['kpa_id']])){
                    $this->addError($question['parent_id'], $this->errors['required']);
                }
                return;
            
            case 5:
                if(empty($this->dataToBeValidated['area_'.$question['parent_id'].'_'.$question['kpa_id']])){
                    $this->addError($question['parent_id'], $this->errors['required']);
                }
                return;
            
        }

    }
    
    function addError($field_id, $errorMsg) {
        if(empty($this->errorList[$field_id])){
            $this->errorList[$field_id] = [$errorMsg];
        } else {
            $this->errorList[$field_id][] = $errorMsg;
        }
    }

}