<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
    <a href="<?php
    $args = array("controller" => "customreport", "action" => "networkreportlist");
    echo createUrl($args);
    ?>">
        <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
        Manage MyOverview Reports
    </a> &rarr;
    Create Block Level Report</h1>
<div class="clr"></div>

<div class="">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>

    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form enctype="multipart/form-data" method="post" id="create_block_data_form" action="#" name="blockreport">
                <dl id="schools_type"  class="fldList">
                    <dt>Report Type<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control" id="report_type" name="report_type" required="required">
                                    <!--<option value="">Report Type</option>-->
                                    <?php
                                    foreach ($reportType as $report) {
                                        //if($report['report_id']==12) continue;    
                                        echo "<option selected = \" selected\" value=\"" . $report['report_id'] . "\">" . $report['report_name'] . "</option>\n";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>    
                    </dd>
                </dl>
                <dl class="fldList">
                    <dt>State<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control" id="rec_state" name="state"  required="required">
                                    <option value="">Select State</option> 
                                    <?php
                                    foreach ($states as $state)
                                        echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                    
                                    ?>
                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>
                <dl id="zones"  class="fldList">
                    <dt>Zone<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control zones-list-dropdown" id="rec_zone" name="zone"  required="required">
                                    <option value="">Select Zone</option> 
                                    
                                </select>
                            </div>
                    </dd>
                </dl>
                <dl id="networks"  class="fldList">
                    <dt>Block<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control blocks-list-dropdown" id="rec_block" name="network"  required="required">
                                    <option value="">Select Block</option> 
                                   
                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>

                <dl id="rec_rounds" class="fldList">
                    <dt>Round<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control round-list-dropdown" name="round" id="evd_round"  required="required">
                                    <option value="">Select Round</option>
                                    <?php
                                    foreach ($aqsRounds as $aqsRound) {
                                        if ($aqsRound['aqs_round'] == 1 || $aqsRound['aqs_round'] == 2) {
                                            echo "<option value=\"" . $aqsRound['aqs_round'] . "\">" . $aqsRound['aqs_round'] . "</option>\n";
                                        }
                                    }
                                    ?>                                        
                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>


                <dl id="rec_report_name" class="fldList">
                    <dt>Report Name<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <input type="text" name="report_name" class="form-control" id="report_name" value="" maxlength="60">
                            </div>
                        </div>
                    </dd>
                </dl>
                

                <dl class="fldList">

                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <div id="errors" style=" display: none;"></div>
                                <input type="submit" id="createBlockLevelReport" name="submitevidencedata" value="Generate Report" class="btn btn-primary mt25 mb30">
                             
                            </div>
                        </div>
                    </dd>
                </dl>
                <br>
                <div class="row"><div class="col-sm-1"></div>
                    <div class="ajaxMsg"></div>

                </div>
                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
            </form>
        </div>
    </div>
</div>