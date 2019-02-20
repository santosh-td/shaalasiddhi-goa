<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php 

$disableForSelfReview = (( $user['is_web'] == 1 && (in_array(6, $user['role_ids']) || in_array(5, $user['role_ids']))) && $user['has_view_video'] == 0) ? 'disabled=disabled style="pointer-events:none;"' : ''; ?>
<!DOCTYPE html>
<html lang="en" ng-app="app">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Adhyayan</title>
        
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo SITEURL; ?>favicon.ico">
        <?php echo $addToHeader; ?>
        <script type="text/javascript">
<?php

if (in_array(8, $user['role_ids'])) {
    ?>
                $(document).ready(function () {
                    alertCounts();
                });
    <?php }
?>
<?php $assessor_profile=in_array(4, $user['role_ids'])?(isset($user['assessor_profile']) && $user['assessor_profile']==1)?1:0:1 ?>
    
    </script>
    </head>
    <body>
        <header>
            <section class="nuiHeader">
                <div class="container clearfix"> 
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#hdrTopLinks" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="navbar-brand logo"><a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>public/images/logo.png" alt="Logo - Adhyayan"></a></div>
                    </div>
                </div>
            </section>
            <section class="nuiHdrBtmBar">
                <div class="container clearfix">
                    <?php
                    if (!isset($_REQUEST['process'])) {
                        ?>
                        <ul class="fl mainNav clearfix">
                            <li class="<?php echo $this->_controller == 'index' ? 'active' : ''; ?>"><a class="bigIcon" href="?"><i class="fa fa-home"></i></a></li>
                            <li class="<?php echo $this->_controller == 'index' ? '' : 'active'; ?>"><a href="#" <?php echo $disableForSelfReview ?>>Manage<i class="fa fa-sort-desc"></i></a>
                                <ul>
                                    <?php
                                    if (in_array(4, $user['role_ids']) || in_array(9,$user['role_ids'])) {
                                        $url = createUrl(array("controller" => "user", "action" => "editUser", "id" => $user['user_id']));
                                    } else {
                                        $url = createUrl(array("controller" => "user", "action" => "editUser", "id" => $user['user_id']));
                                    }
                                    if ((in_array("manage_all_users", $user['capabilities']) || in_array("manage_own_users", $user['capabilities']) || ($user['network_id'] > 0 && in_array("manage_own_network_users", $user['capabilities']))) && $user['is_guest']!=1) {
                                        ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "user", "action" => "user")); ?>" <?php echo $disableForSelfReview ?>>Manage <?php
                                if (current($user['role_ids']) == 8) {
                                    echo 'Assessors';
                                } else {
                                    echo 'Users';
                                }
                                        ?></a><i class="fa fa-caret-right"></i>
                                            <ul>
                                                <li><a href="<?php echo createUrl(array("controller" => "user", "action" => "createUser")); ?>" <?php echo $disableForSelfReview ?>>Add <?php
                                        if (current($user['role_ids']) == 8) {
                                            echo 'Assessor';
                                        } else {
                                            echo 'User';
                                        }
                                        ?></a></li>
                                                <li><a href="<?php echo createUrl(array("controller" => "user", "action" => "user")); ?>" <?php echo $disableForSelfReview ?>>All <?php
                                                        if (current($user['role_ids']) == 8) {
                                                            echo 'Assessors';
                                                            $user_type = 'Assessors';
                                                        } else {
                                                            echo 'Users';
                                                            $user_type = 'Users';
                                                        }
                                                        ?></a></li>
                                                <li><a href="<?php echo $url; ?>" <?php echo $disableForSelfReview ?>>My Profile</a></li>
                                                <?php $imp_user_url = createUrl(array("controller" => "user", "action" => "importUserDetails")); ?>

                                            </ul>
                                        </li>
        <?php } else {
        ?>
                                        <li><a href="<?php echo $url; ?>" <?php echo $disableForSelfReview ?>>My Profile</a></li>
                                    <?php }
                                    if (in_array("create_client", $user['capabilities']) || in_array("manage_own_network_clients", $user['capabilities'])) {
                                        ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "client", "action" => "client")); ?>" <?php echo $disableForSelfReview ?>>Manage Schools</a><i class="fa fa-caret-right"></i>
                                            <ul>
                                                <?php if (in_array("create_client", $user['capabilities'])) { ?><li><a href="<?php echo createUrl(array("controller" => "client", "action" => "createClient")); ?>" <?php echo $disableForSelfReview ?>>Add School</a></li><?php } ?>
                                                <li><a href="<?php echo createUrl(array("controller" => "client", "action" => "client")); ?>" <?php echo $disableForSelfReview ?>>All Schools</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    if (in_array("create_network", $user['capabilities'])) {
                                        ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "network", "action" => "network")); ?>" <?php echo $disableForSelfReview ?>>Manage Levels</a><i class="fa fa-caret-right"></i>
                                            <ul>
                                                <li><a href="<?php echo createUrl(array("controller" => "network", "action" => "createState")); ?>" <?php echo $disableForSelfReview ?>>Add Level-1 (State) </a></li>                                               
                                                <li><a href="<?php echo createUrl(array("controller" => "network", "action" => "createZone")); ?>" <?php echo $disableForSelfReview ?>>Add Level-2 (Zone)</a></li>                                              
                                                <li><a href="<?php echo createUrl(array("controller" => "network", "action" => "createNetwork")); ?>" <?php echo $disableForSelfReview ?>>Add Level-3 (Block)</a></li>                                               
                                                <li><a href="<?php echo createUrl(array("controller" => "network", "action" => "createProvince")); ?>" <?php echo $disableForSelfReview ?>>Add Level-4 (Hub)</a></li>                                               
                                                <li><a href="<?php echo createUrl(array("controller" => "network", "action" => "network")); ?>" <?php echo $disableForSelfReview ?>>All Levels</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <li>
                                     <?php    if (! count(array_intersect($user['role_ids'], array(10,7,11,12))) || $user['user_id']==1) { ?>
                                        <a href="<?php echo createUrl(array("controller" => "assessment", "action" => "assessment")); ?>" <?php echo $disableForSelfReview ?>>Manage Reviews</a><i class="fa fa-caret-right"></i>
                                     <?php } ?>
                                        <ul>
                                            <?php
                                            if (!in_array(8, $user['role_ids'])) {
                                                if (in_array("create_assessment", $user['capabilities'])) {
                                                    ?>
                                            <?php  if (in_array(1, $user['role_ids']) || in_array(2, $user['role_ids'])) {?>
                                                    <li><a href="<?php echo createUrl(array("controller" => "assessment", "action" => "createSchoolAssessment")); ?>" <?php echo $disableForSelfReview ?>>Create School Review</a></li>
                                                <?php } ?>


                                                        <?php
                                                }
                                            }
                                            ?>
                                            <?php if (in_array("create_self_review", $user['capabilities'])) { ?>

    <?php } ?>
                                            <li><a href="<?php echo createUrl(array("controller" => "assessment", "action" => "assessment")); ?>" <?php echo $disableForSelfReview ?>>All Reviews</a></li>
                                        </ul>
                                    </li>
                                    <?php if (in_array("manage_diagnostic", $user['capabilities'])) { ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "diagnostic", "action" => "diagnostic")); ?>" <?php echo $disableForSelfReview ?>>Manage Diagnostics</a><i class="fa fa-caret-right"></i>
                                            <ul>

                                                <li><a href="<?php echo createUrl(array("controller" => "diagnostic", "action" => "diagnostic")); ?>" <?php echo $disableForSelfReview ?>>All Diagnostics</a></li>
                                            </ul>
                                        </li>									
                                        <?php
                                    }
                                    if (in_array("view_all_assessments", $user['capabilities']) && (in_array(1, $user['role_ids']) || in_array(2, $user['role_ids']))) {
                                        ?>

                                        <?php
                                    }

                                    if (in_array("manage_diagnostic", $user['capabilities'])) {
                                        ?>                                                               
                                            <?php
                                        }
                                         
                                        if((in_array("manage_workshop",$user['capabilities']) || in_array("view_own_workshop",$user['capabilities'])) && $user['is_guest']!=1){ ?>
                                          <li><?php
                                            if(in_array("view_own_workshop",$user['capabilities']) && !in_array("manage_workshop",$user['capabilities'])){
                                            ?>
                                            <a href="<?php echo createUrl(array("controller" => "workshop", "action" => "myworkshop")); ?>" <?php echo $disableForSelfReview ?>>Manage Workshops</a>
                                            <?php
                                            }else{
                                             ?>
                                            <a href="<?php echo createUrl(array("controller" => "workshop", "action" => "allworkshop")); ?>" <?php echo $disableForSelfReview ?>>Manage Workshops</a><i class="fa fa-caret-right"></i>
                                            <?php
                                            }
                                            ?>
                                            <ul>
                                                <?php 
                                                if(in_array("manage_workshop",$user['capabilities'])) {
                                                ?>
                                                <li><a href="<?php echo createUrl(array("controller" => "workshop", "action" => "createWorkshop")); ?>" <?php echo $disableForSelfReview ?>>Add Workshop</a></li>
                                                <li><a href="<?php echo createUrl(array("controller" => "workshop", "action" => "allworkshop")); ?>" <?php echo $disableForSelfReview ?>>All Workshops</a></li>
                                                <?php 
                                                }
                                                if(in_array("view_own_workshop",$user['capabilities'])) {
                                                ?>
                                                <li><a href="<?php echo createUrl(array("controller" => "workshop", "action" => "myworkshop")); ?>" <?php echo $disableForSelfReview ?>>My Workshops</a></li>
                                                <?php 
                                                }
                                                
                                                ?>
                                            </ul>
                                        <li>
                                            
                                         <?php
                                        }
                                         
                                        if (in_array("manage_app_settings", $user['capabilities'])) {
                                            ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "settings", "action" => "settings")); ?>" <?php echo $disableForSelfReview ?>>Manage Settings</a>
                                        <li>
                                        <?php } ?>
                                        <?php
                                        if (current($user['role_ids']) == 8) {
                                            ?>
                                        <li>
                                            <a href="<?php echo createUrl(array("controller" => "communication", "action" => "communication")); ?>" <?php echo $disableForSelfReview ?>>MyCommunications</a>
                                        <li>  
                                            <?php
                                        }
                                        ?>
                                </ul>
                            </li>
                            
                        </ul>
                        <div class="fr desktop">

                            <ul class="hlinks clearfix">
                                <li><span>Welcome <?php echo $user['name']; ?></span></li>
                                <li><a href="<?php echo SITEURL . "?video=1" ?>"><i class="fa fa-question vtip" style="margin:0;" title="Click here to watch the video again"></i></a></li>
                                <li><a href="#"><i class="fa fa-envelope-o"></i><b>0</b></a></li>
                                <li>
                                    <a href="#" class="dropdown-toggle" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <i class="fa fa-bell-o"></i>
                                        <b id="totalAlertCount">
    <?php echo count( array_intersect(array(8,1,2), $user['role_ids']))==1 ? $user['alert_count'] : 0; ?>
                                        </b>
                                    </a>
                                    <input type="hidden" name="assessor_count" id="assessor_value" value=""/>
                                    <input type="hidden" name="review_count" id="review_value" value=""/>
                                    <?php
                                    if (count(array_intersect(array(8,1,2), $user['role_ids']))==1) {
                                        $assessorRef = $user['assessor_count'] > 0 ? 1 : 0;
                                        $reviewRef = $user['review_count'] > 0 ? 1 : 0;
                                        ?>
                                        <ul class="dropdown-menu" aria-labelledby="notification">
                                            <?php if(in_array(1, $user['role_ids']) || in_array(8, $user['role_ids'])) { ?>
                                            <li>
                                                <a href="<?php echo createUrl(array("controller" => "user", "action" => "user", 'ref' => $assessorRef)); ?>" 
                                                   id="assessor_count">New Assessor -
        <?php echo in_array(1, $user['role_ids']) || in_array(8, $user['role_ids']) ? $user['assessor_count'] : 0; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo createUrl(array("controller" => "assessment", "action" => "assessment", 'ref' => $reviewRef)); ?>" 
                                                   id="review_count">New Review -
        <?php echo in_array(1, $user['role_ids']) || in_array(8, $user['role_ids']) ? $user['review_count'] : 0; ?>
                                                </a>
                                            </li>
                                            <?php } ?>
                                            <?php if(in_array(1, $user['role_ids']) || in_array(2, $user['role_ids'])) { ?>
                                            <li>
                                                <a href="<?php echo createUrl(array("controller" => "assessment", "action" => "assessment", 'ref' => 2)); ?>" 
                                                   id="pending_assessment">Pending Assessment -
        <?php echo in_array(1, $user['role_ids']) || in_array(2, $user['role_ids'])  ? $user['ass_count'] : 0; ?>
                                                </a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                        <?php
                                    }
                                    ?>

                                </li>
                                <li><a href="<?php echo createUrl(array("controller" => "login", "action" => "logout")); ?>">Log Out</a></li>
                            </ul>

                        </div>
                        <div class="fr mobile">
                            <ul class="hlinks clearfix">
                                <li><a href="<?php echo createUrl(array("controller" => "login", "action" => "logout")); ?>"><i class="fa fa-unlock-alt"></i>Log Out</a></li>
                            </ul>
                        </div> 
    <?php }
    
?>
                </div>
            </section>
        </header>
        <section class="nuibody">
            <div class="container <?php if($this->_controller=="actionplan" && ($this->_action=="actionplan1" || $this->_action=="actionplan2")) echo 'fullWidth'; ?>">

