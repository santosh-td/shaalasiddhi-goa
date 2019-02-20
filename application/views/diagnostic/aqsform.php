<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php
$isReadOnly = empty($isReadOnly) ? 0 : 1;
$readOnlyText = $isReadOnly ? 'readonly="readonly"' : "";
$disabledText = $isReadOnly ? 'disabled="disabled"' : "";
$aqsFilled = 1;
$isSelfReview = isset($assessment['subAssessmentType']) && $assessment['subAssessmentType'] == 1 ? 1 : 0;
$isTeacherReview = isset($assessment['assessment_type_id']) && ($assessment['assessment_type_id'] == 2 || $assessment['assessment_type_id'] == 4) ? 1 : 0;
$isSchoolReview = isset($assessment['assessment_type_id']) && $assessment['assessment_type_id'] == 1 ? 1 : 0;
$isStudentReview = isset($assessment['assessment_type_id']) && ($assessment['assessment_type_id'] == 4) ? 1 : 0;
$isCollegeReview = isset($assessment['assessment_type_id']) && $assessment['assessment_type_id'] == 5 ? 1 : 0;$i = 0;
?>
<form id="aqsFormWrapper" name="aqsFormWrapper" class="<?php echo $isReadOnly ? 'isReadOnly' : 'isEditable'; ?>" method="post" onsubmit="return false;">
    <div class="row">
        <div class="fl">
            <h1 class="page-title">
                <a href="<?php
                $args = array("controller" => "assessment", "action" => "assessment");
                echo createUrl($args);
                ?>">
                    <i class="fa fa-chevron-circle-left vtip" title="Back"></i> Manage MyReviews
                </a> &rarr;
                <?php 
                $diagnosticModel = new diagnosticModel();
                $client = $diagnosticModel->getClientNameByAssessmentId($assmntId_or_grpAssmntId);
                echo $client[0]['client_name']; ?>
            </h1>
        </div>
    </div>

    <div id="aqsForm" class="feedForm">
        <div class="ylwRibbonHldr">
            <a href="javascript:void(0);" class="navIcon collapsed" data-toggle="collapse" data-target="#tab4_Toggle" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
            <div class="collapse navbar-collapse tabitemsHldr" id="tab4_Toggle">
                <ul class="yellowTab nav nav-tabs"> 
                    <li class="item active"><a href="#aqs-step1" data-toggle="tab" class="vtip" title="Key Domain 1">Key Domain <?php echo ++$i; ?></a></li>         
                    <li class="item"><a href="#aqs-step2" data-toggle="tab" class="vtip" title="Key Domain 2">Key Domain <?php echo ++$i; ?></a></li>
                    <li class="item"><a href="#aqs-step3" data-toggle="tab" class="vtip" title="Key Domain 3">Key Domain <?php echo ++$i; ?></a></li>
                    <li class="item"><a href="#aqs-step4" data-toggle="tab" class="vtip" title="Key Domain 4">Key Domain <?php echo ++$i; ?></a></li>
                    <li class="item"><a href="#aqs-step5" data-toggle="tab" class="vtip" title="Key Domain 5">Key Domain <?php echo ++$i; ?></a></li>
                    <li class="item"><a href="#aqs-step6" data-toggle="tab" class="vtip" title="Key Domain 6">Key Domain <?php echo ++$i; ?></a></li>
                    <li class="item"><a href="#aqs-step7" data-toggle="tab" class="vtip" title="Key Domain 7">Key Domain <?php echo ++$i; ?></a></li>
                </ul>
            </div>

        </div>  
        <div class="subTabWorkspace pad26">
            <div class="tab-content">
                                <?php 
                                $question_num = 0;
                                foreach($allKpaQuestionsData as $kpa_id=>$kpaData){
                                    $isActive = '';
                                    global  $question_num ;
                                    $question_num = 0;
                                    $params = array('level'=>1,'kpaId'=>$kpa_id,'sn_no'=>&$question_num);
                                    if(isset($kpa_id) && $kpa_id == 1) {
                                         $isActive = 'active';
                                    }
                                    
                                    $diagnosticModel = new diagnosticModel();
                                    $keyDomainName=$diagnosticModel->getKeyDomainName($kpa_id);
                                    ?>
                                        <div role="tabpanel" class="tab-pane fade in  <?php  echo $isActive;?>" id="aqs-step<?php echo $kpa_id;?>">
                                            <div class="clearfix mb25">
                                                <div class="pull-right"><em>All fields are mandatory.</em></div>
                                                <h2><?php echo $keyDomainName[0]['translation_text']; ?></h2>
                                            </div>
                                            
                                            <div class="boxBody">
                                                <div class="transLayer">
                                                    <div class="">
                                                        <ol>
                                                            <?php
                                                            $aqsformHelper->renderKpa($kpaData, $params);
                                                            ?>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                              <?php   } 
                                ?>
                                </ol>    
                            </div>
            
             <div class="clearfix btnHldr" id="savSbtRrow"><div class="fr">
             <?php if ($isReadOnly) { ?>
                <input type="button" autocomplete="off"  id="saveAqsForm" class="fl nuibtn saveBtn" disabled="disabled" value="Save" />
                <?php if ($assessment['aqs_status'] == 0) { ?><input type="button" autocomplete="off" id="submitAqsForm" <?php echo $aqsFilled == 1 ? "" : 'disabled="disabled"'; ?> class="fl nuibtn submitBtn" value="Submit" /><?php } ?>
            <?php } ?>
                </div>
        </div>
            
                        </div>								
                    </div>								
<?php $assessment['aqs_status']=0;// static condition till link does not generate

            $aqsFilled=1; // static condition till link does not generate
            $disable = 'disabled="disabled"';
            ?>
                        </div>								
                    </div>
        <div class="clearfix"></div>
    </div>
    <input type="hidden" name="assmntId_or_grpAssmntId" value="<?php echo $assmntId_or_grpAssmntId; ?>" />
    <input type="hidden" name="assessment_type_id" value="<?php echo $assessment_type_id; ?>" />
    <input type="hidden" id="school_profile_status" name="aqs_status" value="<?php echo $aqs_status; ?>" />
    <div id="validationErrors"></div>
</form>

<script>
    selfReview = <?php echo $isSelfReview ?>;
    collegeReview = <?php echo $isCollegeReview ?>;
    checkForTabTick();
</script>

