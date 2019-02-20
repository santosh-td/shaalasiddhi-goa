<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>


<h1 class="page-title">
				<?php if($isPop==0){?>
				<a href="<?php
						$args=array("controller"=>"user","action"=>"user");												
						echo createUrl($args); 
						?>">
						<i class="fa fa-chevron-circle-left vtip" title="Back"></i>
						
					Manage <?php
                                                if(current($user['role_ids'])==8){
                                                    echo 'Assessors';
                                                } else {
                                                    echo 'Users';
                                                }
                                                ?>
					</a> &rarr;
				<?php } ?>	
					Add  <?php
                                                if(current($user['role_ids'])==8){
                                                    echo 'Assessor';
                                                } else {
                                                    echo 'User';
                                                }
                                                ?>
				</h1>
				<div class="clr"></div>
				<div class="">
					<div class="ylwRibbonHldr">
						<div class="tabitemsHldr"></div>
					</div>
					<div class="subTabWorkspace pad26">
						<div class="form-stmnt">
							<form method="post" id="create_user_form" action="">
								<div class="boxBody">
									<?php if(in_array("manage_all_users",$user['capabilities']) || in_array("manage_own_network_users",$user['capabilities'])){ ?>
                                                                    <dl class="fldList">
										<dt>User Type<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"> <select class="form-control multiselect-dd user-list-dropdown" id="user_type" name="usertype_id" required>
                                            <option value=""> - Select User Type - </option>
                                                <?php
                                                foreach ($usertypes as $usertype) {
                                                    
                                                        echo "<option value=\"" . $usertype['user_type_id'] . "\">" . $usertype['user_type_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select></div></div></dd>
									</dl>
                                                                    <dl class="fldList" id="states" style="display: none">
										<dt>State<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"> <select class="form-control multiselect-dd state-list-dropdown" id="scl_state" name="state_id">
                                            <option value=""> - Select State - </option>
                                                
                                            </select></div></div></dd>
									</dl>
                                                                    <dl class="fldList" id="zones" style="display: none">
										<dt>Zone<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control zone-list-dropdown" id="scl_zone" name="zone_id[]">
                                            
                                                
                                            </select>
                                                                                            <input type="hidden" autocomplete="off" name="client_zoneID"  value="389" />
                                         
                                                                                        </div></div></dd>
									</dl>
                                                                    <dl class="fldList" id="blocks" style="display: none">
										<dt>Block<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control block-list-dropdown" id="scl_block" name="block_id[]">
                                            
                                            </select>
                                                                                        </div></div></dd>
									</dl>
                                                                    <dl class="fldList" id="clusters" style="display: none">
										<dt>Hub<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control cluster-list-dropdown" id="scl_cluster" name="cluster_id[]">
                                            
                                            </select>
                                                                                        </div></div></dd>
									</dl>
									<dl class="fldList" id="school" style="display: none" >
                                                                            <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo current($user['role_ids'])?>"/>
										<dt>School<span class="astric">*</span>:</dt>
										<dd>
											<div class="row">
												<div class="col-sm-6">
													
                                                                                                        <?php
                                                                                                        if(in_array(8, $user['role_ids'])){
                                                                                                            $span = 'Independent Consultant';
                                                                                                            $value = '221';
                                                                                                            $labal = 'Change School';
                                                                                                        } else {
                                                                                                            $span = '';
                                                                                                            $value = '';
                                                                                                            $labal = 'Select School';
                                                                                                        }
                                                                                                        ?>
													<span id="selected_client_name"><?php echo $span;?></span> &nbsp;
													<a href="?controller=client&action=clientList" data-postformid="for" class="btn btn-danger vtip execUrl" title="Click to select a school." data-size="1200" id="selectClientBtn"><?php echo $labal?></a>
                                                                                                        <input type="hidden" autocomplete="off" name="client_id" id="selected_client_id" value="<?php echo $value;?>" />
												</div>
											</div>
										</dd>
									</dl>
									<?php }else{ ?>
										<input type="hidden" autocomplete="off" name="client_id" id="selected_client_id" value="<?php echo $user['client_id']; ?>" />
									<?php } ?>
									<dl class="fldList">
										<dt>Name<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"><input type="text" value="" class="form-control" name="name" required autocomplete="off" /></div></div></dd>
									</dl>
									<dl class="fldList">
										<dt>Email ID<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"><input type="email" class="form-control" value="" placeholder="this will be the username" name="email" required autocomplete="off" /></div></div></dd>
									</dl>
									<dl class="fldList">
										<dt>Password<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"><input type="password" class="form-control pwd" value="" name="password" required="required" autocomplete="off" /></div></div></dd>
									</dl>
									<dl class="fldList">
										<dt>Confirm Password<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"><input type="password" class="form-control cpwd" value="" required="required" autocomplete="off" /></div></div></dd>
									</dl>
									<?php 
                                                                        
                                                                        if(in_array(1, $user ['role_ids'])){
                                                                            $superRoleId = 1;
                                                                        } else {
                                                                            $superRoleId = 2;
                                                                        }
                                                                        if(in_array("manage_all_users",$user['capabilities'])){ ?>
									<dl class="fldList" id="school_level_role" style="display: none">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix">
											<?php $disabled='';
                                                                                        
                                                                                        
											foreach($roles as $role){
                                                                                            if($role['role_id']!=8){ 
                                                                                                if($role['role_id']==8  && (!in_array('1',$user['role_ids']) && !in_array('2',$user['role_ids']))){
                                                                                                    $disabled="disabled=''";
                                                                                                }
                                                                                                if(in_array(8, $user ['role_ids']) && $role['role_id']==4 && $role['role_id']<10 && $role['role_id']!=7){
                                                                                                    echo "<div class=\"chkHldr\" style='margin-top: 5px;'><input type=\"hidden\" class=\"user-roles\" name=\"roles[]\" autocomplete=\"off\" value='4' id='role_id_4'><label class=\"chkF\"><span style='margin-left: -30px;'>External reviewer</span></label></div>\n";
                                                                                                    
                                                                                                } else if(!in_array(8, $user ['role_ids'])&& $role['role_id']<10 && $role['role_id']!=7){
                                                                                                    echo "<div class=\"chkHldr\"><input type=\"checkbox\" class=\"user-roles\" name=\"roles[]\" autocomplete=\"off\" value=\"".$role['role_id']."\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>".$role['role_name']."</span></label></div>\n";
                                                                                                }
                                                                                        }
                                                                                        }   
                                                                                        ?>
											</div>
										</dd>
									</dl>
              

                                                                        <dl class="fldList" id="state_level_role" style="display: none">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $state_admin_role;?>
                                                                                                
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="zone_level_role" style="display: none">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $zone_admin_role;?>
                                                                                               
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="block_level_role" style="display: none">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $block_admin_role;?>
                                                                                               
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="cluster_level_role" style="display: none">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                            <?php echo $cluster_admin_role;?>
                                                                                               
                                                                                            </div>
											</div>
										</dd>
									</dl>
									<?php 
										}
										else if(in_array("manage_own_users",$user['capabilities']) && in_array(6,$user['role_ids']))
										{?>
										<dl class="fldList">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix">
										<?php $disabled='';
											
											foreach($roles as $role){
                                                                                            
                                                                                            if($role['role_id']==8 && (!in_array('1',$user['role_ids']) && !in_array('2',$user['role_ids']))){
                                                                                                $disabled="disabled=''";
                                                                                            }
                                                                                            
												in_array($role['role_id'],array(3,5))? print "<div class=\"chkHldr\" style='margin-top:8px;'><input type=\"checkbox\" class=\"user-roles\" name=\"roles[]\" autocomplete=\"off\" value=\"".$role['role_id']."\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>".$role['role_name']."</span></label></div>\n":'';											
                                                                                        }
                                                                                    ?>
										</div>
										</dd>
										</dl>
										<?php 
										}else if(in_array("manage_own_users",$user['capabilities']) && in_array(7,$user['role_ids']))
										{?>
										<dl class="fldList">
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix">
										<?php $disabled='';
											
											foreach($roles as $role){
                                                                                            
                                                                                            if($role['role_id']==8 && (!in_array('1',$user['role_ids']) && !in_array('2',$user['role_ids']))){
                                                                                                $disabled="disabled=''";
                                                                                            }
                                                                                            
												in_array($role['role_id'],array(3,6,5))? print "<div class=\"chkHldr\" style='margin-top:8px;'><input type=\"checkbox\" class=\"user-roles\" name=\"roles[]\" autocomplete=\"off\" value=\"".$role['role_id']."\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>".$role['role_name']."</span></label></div>\n":'';											
                                                                                        }
                                                                                    ?>
										</div>
										</dd>
										</dl>
										<?php 
										}
									
									?>
                                                                        <?php
                                                                        if(in_array("manage_all_users",$user['capabilities'])){ ?>        
                                                                        <dl class="fldList" style="display:none;">
										<dt>Add /Update to Moodle<span class="astric">*</span>:</dt>
										<dd class="nobg">
											<div class="row">
												<div class="col-sm-6"> 
                                                                                                    
                                                                                            <div class="chkHldr wAuto"><input name="moodle_user" value="1" type="radio"><label class="chkF radio"><span>Yes</span></label></div>
                                                                                            <div class="chkHldr wAuto"><input checked="checked" name="moodle_user" value="0" type="radio"><label class="chkF radio"><span>No</span></label></div>
                                                                                                </div>
											</div>
										</dd>
									</dl> 
                                                                        <?php
                                                                        }else{
                                                                        ?>
                                                                                <input type="hidden" name="moodle_user" value="0" >       
                                                                        <?php       
                                                                        }
                                                                        ?>
									<dl class="fldList">
										<dt></dt>
										<dd class="nobg">
											<div class="row">
												<div class="col-sm-6">
													<br>
													<input type="submit" value="Add User" class="btn btn-primary">
												</div>
											</div>
										</dd>
									</dl>
								</div>
								<div class="ajaxMsg"></div>
								<input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
							</form>
						</div>
					</div>
				</div>