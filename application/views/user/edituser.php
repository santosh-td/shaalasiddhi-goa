<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>


			<?php 
                        if(isset($eUser['user_id'])){
                        ?>
<script type="text/javascript">
    
<?php
if(current($eUser['role_ids'])!=''){
    ?>
        
    window.document.onload = checkboxEnableDisable("role_id_",'<?php echo current($eUser['role_ids'])?>',"user-roles");   
    <?php
}
?>   

</script>
<h1 class="page-title">
<?php /*if($isPop==0 &&  current($eUser['role_ids'])==3 || current($eUser['role_ids'])==4 || current($eUser['role_ids'])==9 || current($eUser['role_ids'])==8 ){
	$args=array();	My Profile*/?>

    
 <?php /*}else*/ if($isPop==0){
     $args=array();
     ?>My Profile

				
 <?php }else if($isPop==1){
     $args=array();?>
     Update User
 <?php }?>
                                        
<?php 
if(!empty($usertype_ID)){
$usertypeID=$usertype_ID[0]['user_type_id'];



if($usertypeID==2 || $usertypeID==3 || $usertypeID==4){
     $displaystate='style="display: none"';
     $displaystateRole='style="display: none"';
}
else if($usertypeID==1){
     $displaystate=''; 
     $displaystateRole=''; 
}


if($usertypeID==1 || $usertypeID==3 || $usertypeID==4){
     $displayzone='style="display: none"';
     $displayzoneRole='style="display: none"';
     
}
else if($usertypeID==2){
     $displayzone=''; 
     $displaystate=''; 
     $displaystateRole='style="display: none"';
     $displayzoneRole='';
}

if($usertypeID==3){ 
     $displaystate=''; 
     $displayzone='';
     $displayblock=''; 
     $displayblockRole='';
     $displaystateRole='style="display: none"';
     $displayzoneRole='style="display: none"';
    
}else if($usertypeID==1 || $usertypeID==2 || $usertypeID==4){
    $displayblock='style="display: none"'; 
     $displayblockRole='style="display: none"';   
}

if($usertypeID==4){ 
     $displaystate=''; 
     $displayzone='';
     $displayblock='';
     $displaycluster='';
     $displayclusterRole='';
     $displayblockRole='style="display: none"';
     $displaystateRole='style="display: none"';
     $displayzoneRole='style="display: none"';
    
}else if($usertypeID==1 || $usertypeID==2 || $usertypeID==3){
    $displaycluster='style="display: none"'; 
     $displayclusterRole='style="display: none"';   
}


if($usertypeID==1 || $usertypeID==2 || $usertypeID==3 || $usertypeID==4){
     $displayschool='style="display: none"';
}

}else if(empty($usertype_ID)){
     $displayschool='';
     $displaystate='style="display: none"';
     $displaystateRole='style="display: none"';
     $displayzone='style="display: none"';
     $displayzoneRole='style="display: none"';
     $displayblock='style="display: none"';
     $displayblockRole='style="display: none"';
     $displaycluster='style="display: none"';
     $displayclusterRole='style="display: none"';
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
                    <form method="post" id="update_user_form" action="">
				<div class="boxBody">
                                    <?php if(in_array("manage_all_users",$user['capabilities']) && in_array("edit_all_submitted_assessments",$user['capabilities'])){ ?>
                                    
                                    <dl class="fldList">
										<dt>User Type<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"> <select class="form-control multiselect-dd user-list-dropdown" id="user_type" name="usertype_id" required>
                                            <option value=""> - Select User Type - </option>
                                                <?php 
                                                $usertypeID=$usertype_ID[0]['user_type_id'];
                                                if(empty($usertype_ID)){
                                                    $usertypeID=5;
                                                }
                                                foreach ($usertypes as $usertype) {
                                                    if ($usertypeID== $usertype['user_type_id']) {
                                                        echo "<option selected value=\"" . $usertype['user_type_id'] . "\">" . $usertype['user_type_name'] . "</option>\n";
                                                        $usertype_name = $usertype['user_type_name'];
                                                    } else
                                                        echo "<option value=\"" . $usertype['user_type_id'] . "\">" . $usertype['user_type_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select></div></div></dd>
									</dl>
                                                                    <dl class="fldList" id="states" <?php echo $displaystate;?> >
										<dt>State<span class="astric">*</span>:</dt>
										<dd><div class="row"><div class="col-sm-6"> <select class="form-control multiselect-dd state-list-dropdown" id="scl_state" name="state_id">
                                            <option value=""> - Select State - </option>
                                            
                                            <?php 
                                                
                                                foreach($state_ID as $state_IDs){
                                                    $selected_stateIDS[]=$state_IDs['state_id'];
                                                }
                                                
                                                if(!empty($statelist)){
                                                $i=0;  
                                                
                                                foreach ($statelist as $state) { 
                                                  
                                                  
                                                     echo "<option value=\"" . $state['state_id'] . "\"" .(in_array($state['state_id'], $selected_stateIDS) ? 'selected=selected' : '') . ">" . $state['state_name'] . "</option>\n";
                                       
                                                     
                                            $i++; 
                                            
                                            
                                                }
                                    }
                                                ?>
                                                
                                            </select></div></div></dd>
									</dl>
                                                                            
                                                                    <dl class="fldList" id="zones" <?php echo $displayzone;?>>
										<dt>Zone<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control multiselect-dd zone-list-dropdown" id="scl_zone" name="zone_id[]">
                                           
                                                <?php 
                                                
                                                foreach($zone_ID as $zone_IDs){
                                                    $selected_zoneIDS[]=$zone_IDs['zone_id'];
                                                }
                                                
                                                if(!empty($zonelist)){
                                                $i=0;  
                                                
                                                foreach ($zonelist as $zone) { 
                                                   
                                                     echo "<option value=\"" . $zone['zone_id'] . "\"" .(in_array($zone['zone_id'], $selected_zoneIDS) ? 'selected=selected' : '') . ">" . $zone['zone_name'] . "</option>\n";
                                                     
                                            $i++; 
                                            
                                                }
                                    }
                                                ?>
                                            </select></div></div></dd>
									</dl>
                                    
                                    <dl class="fldList" id="blocks" <?php echo $displayblock;?>>
										<dt>Block<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control multiselect-dd block-list-dropdown" id="scl_block" name="block_id[]">
                                           
                                                <?php 
                                                foreach($block_ID as $block_IDs){
                                                    $selected_blockIDS[]=$block_IDs['block_id'];
                                                }
                                                
                                                if(!empty($blocklistedit)){
                                                $j=0;      
                                                foreach ($blocklistedit as $block) { 
                                                   
                                                   
                                                     echo "<option value=\"" . $block['network_id'] . "\"" . (in_array($block['network_id'], $selected_blockIDS) ? 'selected=selected' : '') . ">" . $block['network_name'] . "</option>\n";
                                                
                                            $j++;  }
                                    }
                                                ?>
                                            </select></div></div></dd>
									</dl>
                                    
                                    <dl class="fldList" id="clusters" <?php echo $displaycluster;?>>
										<dt>Hub<span class="astric">*</span>:</dt>
                                                                                <dd><div class="row"><div class="col-sm-6"> <select multiple="multiple" class="form-control multiselect-dd cluster-list-dropdown" id="scl_cluster" name="cluster_id[]">
                                           
                                                <?php 
                                                
                                                foreach($cluster_ID as $cluster_IDs){
                                                    $selected_clusterIDS[]=$cluster_IDs['cluster_id'];
                                                }
                                                
                                                if(!empty($clusterlist)){
                                                $j=0;      
                                                foreach ($clusterlist as $cluster) {
                                                   
                                                   
                                                     echo "<option value=\"" . $cluster['province_id'] . "\"" . (in_array($cluster['province_id'], $selected_clusterIDS) ? 'selected=selected' : '') . ">" . $cluster['province_name'] . "</option>\n";
                                               
                                            $j++;  }
                                    }
                                                ?>
                                            </select></div></div></dd>
									</dl>
                                    
                                    <dl class="fldList" id="school" <?php echo $displayschool;?> >
                                                                            <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo current($user['role_ids'])?>"/>
									                                                            <dt>School<span class="astric">*</span>:</dt>
										<dd>
											<div class="row">
												<div class="col-sm-6">
													
                                                                                                        <?php
                                                                                                        
                                                                                                            $span = $eUser['client_name'];;
                                                                                                            $value = $eUser['client_id'];
                                                                                                            $labal = 'Change School';
                                                                                                        
                                                                                                        ?>
													<span id="selected_client_name"><?php echo $span;?></span> &nbsp;
													<a href="?controller=client&action=clientList" data-postformid="for" class="btn btn-danger vtip execUrl" title="Click to select a school." data-size="1050" id="selectClientBtn"><?php echo $labal?></a>
                                                                                                        <input type="hidden" autocomplete="off" name="client_id" id="selected_client_id" value="<?php echo $value;?>" />
												</div>
											</div>
										</dd>
									</dl>
                                    <?php }else{
                                    ?>
                                    <input type="hidden" value="<?php echo $eUser['client_id']; ?>" name="client_id" id="selected_client_id" />
                                    <?php
                                    } ?>				
                                    
					<dl class="fldList">
						<dt>
							Name<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="row">
								<div class="col-sm-6">
									<input type="text" value="<?php echo $eUser['name']; ?>"
										class="form-control" name="name"  />
								</div>
							</div>
						</dd>
					</dl>
					<dl class="fldList">
						<dt>
							Email ID<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="row">
								<div class="col-sm-6">
                                                                    <?php
                                                                    if(in_array("manage_all_users",$user['capabilities']) && in_array("edit_all_submitted_assessments",$user['capabilities'])){
                                                                    ?>
									
                                                                    <input type="text"  class="form-control"
										value="<?php echo $eUser['email']; ?>"
										placeholder="this will be the username" name="email" required />
                                                                    <?php
                                                                    }else{
                                                                    ?>
                                                                    <input type="email" disabled="disabled" class="form-control"
										value="<?php echo $eUser['email']; ?>"
										placeholder="this will be the username" name="email" required />
                                                                    <?php
                                                                    }
                                                                    ?>
								</div>
							</div>
						</dd>
					</dl>

					<dl class="fldList">
						<dt>
							New Password<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="row">
								<div class="col-sm-6">
									<input type="password" class="form-control pwd" value=""
										name="password" />
								</div>
							</div>
						</dd>
					</dl>
					<dl class="fldList">
						<dt>
							Confirm Password<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="row">
								<div class="col-sm-6">
									<input type="password" class="form-control cpwd" value="" />
								</div>
							</div>
						</dd>
					</dl>
									<?php 
                                                                        if(in_array(1, $user ['role_ids'])){
                                                                            $superRoleId = 1;
                                                                        } else {
                                                                            $superRoleId = 2;
                                                                        }
                                                                       
                                                                        
                                                                        if(in_array("manage_all_users",$user['capabilities']) && !in_array(8, $user ['role_ids'])){ ?>
									<dl class="fldList" id="school_level_role" <?php echo $displayschool;?>>
						<dt>
							User Role<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="clearfix">
											<?php $disabled='';
					foreach ( $roles as $role ){
                                            if($role['role_id']!=8){ 
                                            
                                            $disabled='';
                                            if(in_array(8,$eUser['role_ids'])){
                                                if((!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            } else {
                                                if($role['role_id']==8 && (!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            }
                                            if(!in_array(8, $user ['role_ids'])&& $role['role_id']<10 && $role['role_id']!=7){
						echo "<div class=\"chkHldr\"><input type=\"checkbox\" " . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") . " class=\"user-roles\" name=\"roles[]\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n";
                                           }
                                        }
                                        }
                                        ?>
											</div>
						</dd>
					</dl>

                                                                        <dl class="fldList" id="state_level_role" <?php echo $displaystateRole; ?>>
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $state_admin_role;?>
                                                                                                
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="zone_level_role" <?php echo $displayzoneRole;?>>
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $zone_admin_role;?>
                                                                                               
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="block_level_role" <?php echo $displayblockRole;?>>
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $block_admin_role;?>
                                                                                               
                                                                                            </div>
											</div>
										</dd>
									</dl>
                                                                        <dl class="fldList" id="cluster_level_role" <?php echo $displayclusterRole;?>>
										<dt>User Role<span class="astric">*</span>:</dt>
										<dd>
											<div class="clearfix"><div style='margin-top: 5px;'>
                                                                                                <?php echo $cluster_admin_role;?>
                                                                                                
                                                                                            </div>
											</div>
										</dd>
									</dl>
									<?php
				
} else if (in_array ( "manage_own_users", $user ['capabilities'] ) && in_array ( 6, $user ['role_ids'] ) && !in_array(8, $user ['role_ids'])) {
					?>
					<dl class="fldList">
						<dt>
							User Role<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="clearfix">
																			<?php
                                         $disabled='';                                                                                                                      
					
					foreach ( $roles as $role )
					{
                                            
                                            if(in_array(8,$eUser['role_ids'])){
                                                if((!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            } else {
                                                if($role['role_id']==8 && (!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            }
						if(in_array(6,$eUser['role_ids']) && $role ['role_id']==6)
						{
							echo in_array ( $role ['role_id'], array(3,5,6)) ? "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") .(  $role ['role_id']==6  ? 'disabled="disabled"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n" : '';
							echo "<input type='hidden' name=\"roles[]\" value='6' />";
						}
						else{
                                                        if(in_array ( $role ['role_id'], array(3,5))){
                                                         echo "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n";   
                                                        }else if (in_array($role ['role_id'], $eUser ['role_ids'])){
                                                        $disabled_etc="disabled=''";
                                                        echo "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled_etc."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n";
                                                        }
							
                                                }
					}
						?>
																			</div>
						</dd>
					</dl>
					<?php
                                }else if (in_array ( "manage_own_users", $user ['capabilities'] ) && in_array ( 7, $user ['role_ids'] ) && !in_array(8, $user ['role_ids'])) {
                                    if($eUser['user_id']!=$user['user_id']){
					?>
					<dl class="fldList">
						<dt>
							User Role<span class="astric">*</span>:
						</dt>
						<dd>
							<div class="clearfix">
																			<?php
                                         $disabled='';                                                                                                                      
					// school principal is able to add internal reviewer and school admin only
					foreach ( $roles as $role )
					{
                                            // add id and onlick event into input field for enabling and disabling the checkboxes and call the onclick event by Mohit Kumar
                                            if(in_array(8,$eUser['role_ids'])){
                                                if((!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            } else {
                                                if($role['role_id']==8 && (!in_array('1',$user['role_ids']))){
                                                    $disabled="disabled=''";
                                                }
                                            }
						if(in_array(7,$eUser['role_ids']) && $role ['role_id']==7)
						{
							echo in_array ( $role ['role_id'], array(5,6,7)) ? "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") .(  $role ['role_id']==7  ? 'disabled="disabled"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n" : '';
							echo "<input type='hidden' name=\"roles[]\" value='7' />";
						}
						else{
							
                                                       if(in_array ( $role ['role_id'], array(3,5,6))){
                                                        echo "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n";   
                                                       }else if (in_array($role ['role_id'], $eUser ['role_ids'])){
                                                        $disabled_etc="disabled=''";
                                                        echo "<div class=\"chkHldr\" style='margin-top:8px;' ><input type=\"checkbox\" class=\"user-roles\"" . (in_array ( $role ['role_id'], $eUser ['role_ids'] ) ? 'checked="checked"' : "") ." name=\"roles[]\" autocomplete=\"off\" value=\"" . $role ['role_id'] . "\" id='role_id_".$role['role_id']."' onclick='checkboxEnableDisable(\"role_id_\",".$role['role_id'].",\"user-roles\",".$superRoleId.")' ".$disabled_etc."><label class=\"chkF checkbox\"><span>" . $role ['role_name'] . "</span></label></div>\n";   
                                                       }
                                                }
                                                
                                                }
						?>
																			</div>
						</dd>
					</dl>
					<?php
                                    }
                                } else if(in_array(8, $user ['role_ids'])){
                                    echo '<input type="hidden" name="roles[]" value="8">';
                                }
				
				?>
                                    
                                      <?php
                                      if(in_array("manage_all_users",$user['capabilities'])){ ?> 
                                    
                                    <dl class="fldList" style="display:none;">
										<dt>Add /Update to Moodle<span class="astric">*</span>:</dt>
										<dd class="nobg">
											<div class="row">
												<div class="col-sm-6"> 
                                                                                                    
                                                                                            <div class="chkHldr wAuto"><input name="moodle_user" value="1" type="radio" <?php if($eUser['add_moodle']==1) echo"checked=checked"; ?> ><label class="chkF radio"><span>Yes</span></label></div>
                                                                                            <div class="chkHldr wAuto"><input  name="moodle_user" value="0" type="radio" <?php if($eUser['add_moodle']==0 || empty($eUser['add_moodle'])) echo"checked=checked"; ?> ><label class="chkF radio"><span>No</span></label></div>
                                                                                                </div>
											</div>
										</dd>
									</dl> 
				   <?php
                                    }else{
                                                                        ?>
                                                                                <input type="hidden" name="moodle_user" value="<?php echo isset($eUser['add_moodle'])?$eUser['add_moodle']:0 ?>" >       
                                                                        <?php       
                                                                        }
                                                                        ?>
                                    
									<dl class="fldList">
						<dt></dt>
                                               
						<dd class="nobg">
							<div class="row">
								<div class="col-sm-6">
									<br> <input type="submit" value="Update User"
										class="btn btn-primary"> <input type="hidden"
										value="<?php echo $eUser['user_id']; ?>" name="id" /> 
                                                                                <input
										type="hidden" value="<?php echo $eUser['client_id']; ?>"
										name="client_id_old" id="selected_client_id_old" />
								</div>
							</div>
						</dd>
					</dl>
				</div>
				<div class="ajaxMsg"></div>
				<input type="hidden" class="isAjaxRequest" name="isAjaxRequest"
					value="<?php echo $ajaxRequest; ?>" />
                                
                                <input type="hidden"  name="userID" id="userID"
					value="<?php echo $_GET['id'] ?>" />
			</form>
		</div>
	</div>
</div>
<?php }else{ ?>
<h1>User does not exist</h1>
<?php } ?>