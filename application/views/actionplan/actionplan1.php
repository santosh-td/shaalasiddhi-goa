<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<div class="filterByAjax" data-querystring="assessment_id=<?php echo $assessment_id; ?>" data-action="<?php echo $this->_action; ?>"  data-controller="<?php echo $this->_controller; ?>">    
    <?php if ($assessment_id > 0 || $roleID_all[0] == "12") { ?>
        <h1 class="page-title">

            <?php if ($roleID_all[0] == "12") { ?>
                <a href="<?php
                $args = array("controller" => "index", "action" => "index");
                echo createUrl($args);
                ?>">
                    <i class="fa fa-chevron-circle-left vtip" title="Back"></i></a>
                Monitor Action Planning <?php if ($assessment_id > 0) {
               echo "&rarr;";
           } ?>
            <?php } else { ?>
                <a href="<?php
                $args = array("controller" => "assessment", "action" => "assessment");
                $args["filter"] = 1;
                echo createUrl($args);
                ?>">
                    <i class="fa fa-chevron-circle-left vtip" title="Back"></i></a>
                Action Planning Dashboard&rarr;
        <?php } ?>
        <?php echo $assessment_details['client_name']; ?></h1>

<?php } ?>



    <div class="asmntTypeContainer">

        <?php
        $ajaxFilter = new ajaxFilter();
        $ajaxFilter->addHidden("assessment_id", $assessment_id);
        $ajaxFilter->generateFilterBarHidden(1);
        ?>
<?php if (count($akns) > 0) { ?><div class="tab-pane-mand"><div class="wrapNote"><span>all fields are mandatory.</span></div></div> <?php } ?>
        <div class="subTabWorkspace">
            <form name="cluster_ap" id="cluster_ap" action="" method="post">

<?php if ($roleID_all[0] == "12") { ?>
                    <div class="row actPlan">
                        <div class="col-sm-3"><label>Select School</label>
                            <select class="selectpicker ld" id="school_id"  name="school_id" required >
                                <option value="">--Select School--</option>
                                <?php
                                foreach ($clusters_school as $keyU => $valU) {
                                    ?>
                                    <option value="<?php echo $valU['client_id'] ?>" <?php echo (isset($valU['client_id']) && $valU['client_id'] == $assessment_details['client_id']) ? 'selected="selected"' : '' ?> ><?php echo $valU['client_name'] ?></option>
                                    <?php
                                }
                                ?>

                            </select>
                            <span id="errmsg"></span>
                        </div>
                        <?php
                        if (isset($_REQUEST['assessment_id'])) {
                            $asses_id = $_REQUEST['assessment_id'];

                            $actionModel = new actionModel();
                            $curr_clientID = $actionModel->getclientID($asses_id);
                            $ClientID_val = $curr_clientID[0]['client_id'];
                            $round_res = $actionModel->getAssessmentNumRound($ClientID_val);
                            $num_of_rounds = count($round_res);
                        } else {
                            $num_of_rounds = 1;
                        }
                        ?>
                        <div class="col-sm-3"><label>Select Round</label>
                            <select class="selectpicker ld" id="round"  name="round" required >
                                <option value="">--Select Round--</option>
                                <?php
                                for ($i = 1; $i <= $num_of_rounds; $i++) {
                                    ?>
                                    <option value="<?php echo $i ?>" <?php echo ($i == $assessment_details['aqs_round']) ? 'selected="selected"' : '' ?> ><?php echo "Round-" . $i ?></option>
                                    <?php
                                }
                                ?>

                            </select>   
                            <span id="errmsgR"></span>
                        </div> 
                        <div class="col-sm-3">
                            <input type="button" title="Submit to view action plan." value="Submit" id="school_cluster" name="school_cluster" class="btn btn-primary vtip">
                        </div>
                    </div>
                <?php } ?>

            </form>
            <form id="actionplanform1" method="post" data-item-id="" data-item-type="" action="">
                <input type="hidden" name="assessment_id" id="assessment_id" value="<?php echo $assessment_id ?>" >    
                <input type="hidden" name="principle_email"  value="<?php echo $assessment_details['pricniple_email']; ?>" >    
                <input type="hidden" name="principle_name"  value="<?php echo $assessment_details['principle_name']; ?>" >    
                <input type="hidden" name="school_name"  value="<?php echo $school_name; ?>" >    
                <div class="actionPlanCont"> 

                    <div class="actcontBoxHldr">

                        <?php if (count($akns) > 0) { ?>

                            <div>

                                <div class="ajaxMsg"></div>

                                <table class="table customTbl fldSet" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="slNo">Sl.</th>

                                            <th style="width:15%;">Core Standard</th>
                                            <th style="width:46%;">How will the impact look like? <i class="fa fa-info-circle vtip" title="Select the relevant stakeholders and mention how the impact will look like
                                                                                                     for those stakeholders when the recommendations are implemented at the end of the Action plan."></i></th>

                                            <th style="width:12%;">Duration <i class="fa fa-info-circle vtip" title="Time period during which the action plan will be implemented"></i></th>

                                            <th>Leader <i class="fa fa-info-circle vtip" title="Select a person who would be responsible for implementing and updating
                                                          the Action Plan"></i></th>
                                            <th style="width:4%;">Frequency of<br><b>Reports</b> <i class="fa fa-info-circle vtip" title="It is the frequency at which reports on the action plan would be sent to
                                                                                                    the concerned person."></i></th>
                                            <th style="width:14%;">Reporting <br>Authority <i class="fa fa-info-circle vtip" title="Mention email ids of people to whom the report on the action plan should
                                                                                              be sent. The report will be sent to the principal and the leader by default."></i></th>
                                            <th>Status</th>
                                            <th style="width:2%"></th>
                                        </tr>	
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        if (!empty($akns)) {
                                            foreach ($akns as $key => $val) {
                                                ?>
                                                <tr class="language_row" id="tr_<?php echo $val['id'] ?>">
                                                    <td class="slNo" style="vertical-align: top;"><?php echo $i ?> <?php echo $val['rec_type'] == 1 ? "#" : ''; ?></td>
                                                    <td style="vertical-align: top;" >
                                                        <div class="mb10 f12 text-left">
                                                            <?php
                                                            $kk1 = explode(",", $val['kk1']);
                                                            $kk2 = explode("@#@$", $val['js_text']);
                                                            foreach ($kk1 as $skey => $sval) {
                                                                $jst = $kk2[$skey];
                                                                echo '<div class="vtip" title="' . $jst . '" ><strong>' . $sval . '</strong></div>';
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="text-left"><?php echo $val['js_text']; ?>
                                                            <?php
                                                            if (substr($val['text_data'], 100, 1) != "") {
                                                                echo"....";
                                                            }
                                                            ?>
                                                        </div>
                                                        <input type="hidden" name="assessor_key_notes_id[]" class="rec_id" value="<?php echo $val['id'] ?>" >
                                                        <input type="hidden" name="mail_status[]"  value="<?php echo $val['mail_status'] ?>" >
                                                    </td>
                                                    <td style="vertical-align: top">
                                                        <div class="tableHldr teamsInfoHldr team_action noShadow ">
                                                            <?php if ($disabled == 0) { ?>
                                                                <a href="javascript:void(0)" title="Add New Impact" class="impactteam impactmsg" data-id="<?php echo $val['id'] ?>"><i class="fa fa-plus"></i></a>
                                                                <?php
                                                            }
                                                            ?>
                                                            <table class="table customTbl_inner" width="100%" id="team_<?php echo $val['id'] ?>">
                                                                <tbody>


                                                                    <?php
                                                                    $co = 1;
                                                                    if (isset($val['designations']) && count($array_design = explode(",", $val['designations'])) > 0) {
                                                                        $array_impact = explode("#--#", $val['impact_statements']);
                                                                        $array_id_exists = explode(",", $val['assessor_action1_impact_ids']);
                                                                        foreach ($array_design as $key_d => $val_d) {
                                                                            echo kpajsHelper::getExternalImpactTeamHTMLRow($co, $val['id'], $val_d, $array_impact[$key_d], $array_id_exists[$key_d], $disabled);

                                                                            $co++;
                                                                        }
                                                                    } else {
                                                                        echo kpajsHelper::getExternalImpactTeamHTMLRow($co, $val['id']);
                                                                    }
                                                                    ?>
                                                                </tbody>

                                                            </table>
                                                        </div>

                                                    </td>

                                                    <td style="vertical-align: top;" class="da">From:<div class="datePicker" data-hideid="<?php echo $val['id'] ?>"><input type="text" name="from_date[]" class="form-control date" id="fromdate_<?php echo $val['id'] ?>" data-id="fromdate_<?php echo $val['id'] ?>" placeholder="DD-MM-YYYY" value="<?php echo (isset($val['from_date']) && $val['from_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['from_date'])) : '' ?>">
                                                        </div>
                                                        <br>
                                                        To:    
                                                        <div class="datePicker" data-hideid="<?php echo $val['id'] ?>"><input type="text" class="form-control date " data-id="todate_<?php echo $val['id'] ?>" name="to_date[]"  id="todate_<?php echo $val['id'] ?>" placeholder="DD-MM-YYYY" value="<?php echo (isset($val['to_date']) && $val['to_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['to_date'])) : '' ?>">
                                                        </div>
                                                        <input type="hidden" name="previous_fromdate_<?php echo $val['id'] ?>"  value="<?php echo (isset($val['from_date']) && $val['from_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['from_date'])) : '' ?>">
                                                        <input type="hidden"  name="previous_todate_<?php echo $val['id'] ?>"  value="<?php echo (isset($val['to_date']) && $val['to_date'] != "0000-00-00") ? date("d-m-Y", strtotime($val['to_date'])) : '' ?>">

                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <div class="boxWAdd teamsInfoHldr">

                                                            <select class="selectpicker ld" id="leader_<?php echo $val['id'] ?>"  name="leader[]">
                                                                <option value="">--Leader--</option>
            <?php
            foreach ($users as $keyU => $valU) {
                ?>
                                                                    <option value="<?php echo $valU['user_id'] ?>" <?php echo (isset($val['leader']) && $val['leader'] == $valU['user_id']) ? 'selected="selected"' : '' ?> ><?php echo $valU['name'] ?></option>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </select>
            <?php if ($disabled == 0) { ?>
                                                                <a href="?controller=user&amp;action=createUser&amp;ispop=1" title="Click to add a new user" class="impactteam execUrl" ><i class="fa fa-plus"></i></a>
                                                            <?php } ?>
                                                        </div> 
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <select class="selectpicker fr" id="frequency_r_<?php echo $val['id'] ?>" name="frequency_report[]" >
                                                            <option value="">--Frequency--</option>

            <?php
            foreach ($frequency as $keyF => $valF) {
                ?>
                                                                <option value="<?php echo $valF['frequency_id'] ?>" <?php echo (isset($val['frequency_report']) && $val['frequency_report'] == $valF['frequency_id']) ? 'selected="selected"' : '' ?>><?php echo $valF['frequecy_text'] ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <textarea name="reporting_authority[]" placeholder="Reporting Authority" class="form-control ra" data-id="authority_<?php echo $val['id'] ?>"><?php echo isset($val['reporting_authority']) ? $val['reporting_authority'] : ''; ?></textarea>
                                                        <b>Note:</b> Use comma(,) to add multiple email ids.
                                                    </td>
                                                    <td style="vertical-align: top;">

            <?php
            if (isset($val['action_status']) && $val['action_status'] == 1) {
                ?><h5 class="prog">In Progress</h5>
                                                            <?php if ($isleader) { ?>    
                                                                <a class="btnTxt blue" style="color:#337ab7" href="<?php echo SITEURL; ?>index.php?controller=actionplan&action=actionplan2&id_c=<?php echo $val['id'] ?>&assessment_id=<?php echo $assessment_id ?>">Click here to view</a>
                                                           
                    <?php } else {
                    ?>   
                                                                <button class="saverow btnTxt blue" data-item-id="<?php echo $val['id'] ?>" value="1">Click here to view</button>
                                                                <?php
                                                            }
                                                        } else if (isset($val['action_status']) && $val['action_status'] == 2) {
                                                            ?><h5 class="comp">Completed</h5>
                                                            <?php if ($isleader) { ?>    
                                                                <a class="btnTxt blue" style="color:#337ab7" href="<?php echo SITEURL; ?>index.php?controller=actionplan&action=actionplan2&id_c=<?php echo $val['id'] ?>&assessment_id=<?php echo $assessment_id ?>">Click here to view</a>
                                                               
                                                                <?php } else {
                                                                ?>
                                                                <a class="btnTxt blue" style="color:#337ab7" href="<?php echo SITEURL; ?>index.php?controller=actionplan&action=actionplan2&id_c=<?php echo $val['id'] ?>&assessment_id=<?php echo $assessment_id ?>">Click here to view</a>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?><h5 class="nots">Not started</h5>
                                                            <?php if ($isleader) { ?>  
                                                                <a class="btnTxt blue" style="color:#337ab7" href="Javascript:alert('Not started yet. Please contact School Principle/ Admin to start');">Click here to start</a>
                                                              
                                                                <?php } else {
                                                                ?>
                                                                <button class="saverow btnTxt blue" data-item-id="<?php echo $val['id'] ?>" value="0">Click here to start</button>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </td >
                                                    <td  style="vertical-align: top;">
                                                        <?php
                                                        if (!$isleader && $val['rec_type'] == 1 && ((isset($val['action_status']) && $val['action_status'] == 0) || !isset($val['action_status']))) {
                                                            ?>
                                                            <a href="javascript:void(0)" class="delete_new" data-deleteid="<?php echo $val['id'] ?>"><i class="fa fa-times"></i></a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                ?>                                     
                                    </tbody>
                                </table>

                            </div>
                                        <?php
                                    }
                                    ?>
                        <!--# Added By school<br>-->
                        <div class="ajaxMsg"></div>
                        <div class="clearfix">
                        <?php if ($assessment_id > 0 && $roleID_all[0] != "12") { ?>
                            <?php if (!$isleader) { ?>
                                <?php if (count($akns) > 0) { ?>
                                        <button class="btn btn-primary saverow fr" data-item-id="0" value="0">Save All</button>
                                        <a href="?controller=actionplan&action=action1new&assessment_id=<?php echo $assessment_id ?>" data-size="1270"  class="btn btn-primary execUrl"><i class="fa fa-plus"></i>Add Another Area</a>
            <?php
        } else {
            ?>
                                        <a text-align="center" href="?controller=actionplan&action=action1new&assessment_id=<?php echo $assessment_id ?>" data-size="1270"  class="btn btn-primary execUrl"><i class="fa fa-plus"></i>Add Area For Action Planning</a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            <?php if ($assessment_id > 0 && $roleID_all[0] == "12") { ?>
                                <?php if (!$isleader) { ?>
                                    <?php if (count($akns) > 0) { ?>
                                        <?php
                                    } else {
                                        ?>
                                        No action plan found.       
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>

                </div>
            </form></div>
    </div>

</div>


<script type="text/javascript">
    (function ($) {
        var date = new Date();
        var date = new Date('<?php echo date("Y-m-d h:i:s", strtotime($assessment_details['aqs_start_date'])); ?>');
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('.datePicker').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, minDate: today, pickTime: false, }).on('dp.change', function (ev) {
            var itemid = $(this).data("hideid");
            $('#tr_' + itemid + ' [data-id=fromdate_' + itemid + ']').popover('destroy');
            $('#tr_' + itemid + ' [data-id=todate_' + itemid + ']').popover('destroy');

        });

    })(jQuery);

</script>