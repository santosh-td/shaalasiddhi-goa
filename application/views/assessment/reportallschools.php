<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php ?>
<h1 class="page-title">
    Bulk download of School Evaluation reports
</h1>
<div class="clr"></div>
<div id="reportsListWrapper">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form id="create_all_school_data_form" action="#" name="clusterreport"><input type="hidden" name="report_id" value="1"><input type="hidden" name="group_assessment_id" value="0"><input name="lang_id" type="hidden" value="9">
                <div class="ajaxMsg" style="text-align:center;margin:0 0 15px;"></div>
                
                <dl class="fldList">
                    <dt>Report Validity<span class="astric">*</span>:</dt>
                    <dd>
                        <div class="col-sm-12">
                            <select class="valid_years" name="years" required="required">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select> <b>Years</b>
                            <select class="valid_months" name="months" required="required">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                            </select> <b>Months</b>
                        </div>
                    </dd>
                </dl>
                <br>    
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
//echo "<option value=\"" . 'all' . "\">" . 'ALL' . "</option>\n";
?>
                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>
                <dl id="zones"  class="fldList">
                    <dt>Zone:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control zones-list-dropdown" id="rec_zone" name="zone">

                                </select>
                            </div>
                    </dd>
                </dl>
                <dl id="networks"  class="fldList">
                    <dt>Block:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control blocks-list-dropdown" id="rec_block" name="network[]">

                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>
                <dl id="provinces"  class="fldList">
                    <dt>Hub:</dt>
                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <select class="form-control province-list-dropdown" name="province[]" id="rec_province">

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
                                    <option value="1">1</option>             
                                </select>
                            </div>
                        </div>
                    </dd>
                </dl>

                <dl class="fldList">

                    <dd>
                        <div class="row">
                            <div class="col-sm-6 width-50-modal">
                                <div id="errors" style=" display: none;"></div>
                                <input type="submit" id="clusterreport" name="submitevidencedata" value="Download reports" class="btn btn-primary mt25 mb30">
                            </div>
                        </div>
                    </dd>
                </dl>


                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
            </form>
        </div>
    </div>
</div>
<script>

</script>