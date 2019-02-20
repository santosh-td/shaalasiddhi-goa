<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class aqsreportHelper {

    private $skip_subquestions = false;

    function renderKpa($kpaData, $params) {
        
        array_walk($kpaData, array($this, 'renderQuestionTemplate'), $params);
        
    }

    private function renderQuestionTemplate($question, $key, $params) {
        
        //echo "<pre>";print_r($question);
        $level = $params['level'];
        global  $question_num ;
        $this->skip_subquestions = false;
        $main = '';
        if ($level == 1) {
            $main = 'main';
           $question_num = $question_num+1;
        }
        
        $this->questionStartHtmlTags($question, $level,$main);
        /*********************** template design *****************************/
        //name="aqs[referrer_id]" id="aqsf_referrer_id"
        
        $school_profile_id=$question['school_profile_id'];
        
        /*********************** template design *****************************/
        $question_text= str_replace(array('_','NA'),array('<input type="text" value="'.$question['answer'].'" class="required" name="aqs[txt_'.$school_profile_id.'_'.$question['kpa_id'].']" id="aqsf_'.$school_profile_id.'"/>',''),$question['translation_text']);
    
        ?>
        <div class="<?php echo  $question['html_type_id']==2 || $question['html_type_id']==3 ? 'chkHldr' : 'main-question-wrap'; ?>">  
            
        <?php
       
        echo ($level==1? '<span class="question-numbering">'.$question_num . '.</span> ': '');
        echo $this->getFieldByHtmlTypeId($question_text, $question);
        
        ?>
        </div>
        
        <?php

        if(!empty($question['sub_questions']) && !$this->skip_subquestions){
            ?>
            <div class="question-wrap">
            <?php
                $params['level'] = $level + 1;
                array_walk($question['sub_questions'], array($this, 'renderQuestionTemplate'), $params);
            ?>
            </div>
        <?php
        }

        $this->questionEndHtmlTags();
    }


    private function questionStartHtmlTags($question, $level,$main='') {
        $childDependencyClass = '';//echo '<pre>';print_r($question);
        $yesClass='';
        if($question['childtobeactive_ifparentselected']) {
            $childDependencyClass = ' show_child_if_active' . (empty($question['answer'])?' hide_child':'');
        }
        
        if($question['childtobeactive_ifparentselected']==1 && $question['translation_text']=='Yes') {
            $yesClass = ' child_exist';
        }else {
            $yesClass = ' child_notexist';
        }
        
    ?>
        <div id="<?php echo $question['school_profile_id']; ?>" class="<?php   echo $main;echo $childDependencyClass; echo $yesClass;?> question-template question-template-level-<?php echo $level; echo empty($question['translation_text']) ? ' text-empty': ''; ?> question-template-<?php echo $question['html_type_id']; ?> question-id-<?php echo $question['school_profile_id']; ?>">

        <?php
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
                $checkedRadio='';
                if($question['answer'] == 1){
                   $checkedRadio='checked="checked"'; 
                }
                return '<input type="radio" data-parentid='.$question['parent_id'].' class="radioRow parent-id-'.$question['parent_id'].'" '.$checkedRadio.' name="aqs[rd_'.$question['parent_id'].'_'.$question['kpa_id'].']'.$name_suffix.'" value="1-'.$school_profile_id.'" id="aqsf_rd_'.$school_profile_id.'"/>' . ($skip_label ? '' : '<label class="chkF radio"><span>'.$question_text.'</span></label>');

            case 3:
                $checkedCh='';
                if($question['answer'] == 1){
                   $checkedCh='checked="checked"'; 
                }

                return '<input type="checkbox" data-parentid="'.$question['parent_id'].'" class="required_ch checkRow parent-id-'.$question['parent_id'].'" '.$checkedCh.' name="aqs[ch_'.$question['parent_id'].'_'.$question['kpa_id'].'][]" value="1-'.$school_profile_id.'" id="aqsf_ch_'.$school_profile_id.'"/>' . ($skip_label ? '' : '<label class="chkF checkbox"><span>'.$question_text.'</span></label>');

               

            case 4:
                return ($skip_label ? '' : $question_text) . '<input type="text" value="'.$question['answer'].'" class="required" name="aqs[txt_'.$school_profile_id.'_'.$question['kpa_id'].']'.$name_suffix.'" id="aqsf_txt_'.$school_profile_id.'"/>';
            
            case 5:
                return ($skip_label ? '' : $question_text) . '<textarea class="required" cols="80" rows="3" name="aqs[area_'.$school_profile_id.'_'.$question['kpa_id'].']'.$name_suffix.'" id="aqsf_area_'.$school_profile_id.'">'.$question['answer'].'</textarea>';
            
            case 7:
                return $this->renderTabularFields($question_text, $question);

            default:
                return $skip_label ? '' : $question_text;
        }

    }

    function renderTabularFields($question_text, $question) {
        $this->skip_subquestions = true;
        $column_count = count($question['sub_questions']);
        echo $question_text;
        ?>
        <table class="question-template-table">
            <thead>
                <tr>
                <?php
                    foreach($question['sub_questions'] as $sq){
                        echo '<th>'.$sq['translation_text'].'</th>';
                    }
                ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="tabular-question-row">
                <?php
                    foreach($question['sub_questions'] as $sq){
                        echo '<td>'.$this->getFieldByHtmlTypeId($sq['translation_text'], $sq, true, true).'</td>';
                    }
                ?>
                    <td><a href="#" class="add-tabular-question-row">+</a></td>
                </tr>
            </tbody>
        </table>
        <?php
    }
    
    
    

}