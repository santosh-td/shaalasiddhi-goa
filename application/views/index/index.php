<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php 
if( $user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && ($user['has_view_video']==0)){ ?>
<div class="alert alert-info alert-dismissible" style="display:none;"><strong>You cannot proceed without watching the video.</strong> Please click <strong class="bldTxt">'?'</strong> icon to watch the video.
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<?php } ?>
			<div class="dashboardBox">
			<?php if(($user['is_web']==1 && $user['has_view_video']==0 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids']))) ||(isset($_GET['video']) && $_GET['video']==1)){ ?>
		
			<script> 
			var player;			
			var done = false;
    jQuery(document).ready(function ($) {
        var $midlayer = $('.modal-body'); 
        var done = false;    
        $('#myModal').on('show.bs.modal', function (e) {          
            var tag = document.createElement('script');

            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);   
        });

        $('#myModal').on('hide.bs.modal', function (e) {
           $(".alert").show();
            player.stopVideo();
        }); 
        $("#introvideo").trigger('click'); 
    });
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
        videoId: 'jQw-hIGiK_0',  
        playerVars: {
            'showinfo': 0,
            'controls':1            
        },        
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

   // autoplay video
      function onPlayerReady(event) {
    	// player.loadVideoByUrl('http://www.youtube.com/v/jQw-hIGiK_0?version=3&showinfo=0');
          //player.setOption('cc','showinfo','0');
         // event.target.playVideo();
      }

      // when video ends
      function onPlayerStateChange(event) {  
      <?php if($user['is_web']==1 && $user['has_view_video']==0){ ?>
		          if(event.data === 0) {      
		        	  postData=$(this).serialize()+"&token="+getToken();
		  			apiCall(this,"updateVideo",postData,function(s,data){
		  	  			$('.alert').remove();
		  				$('.dashboardBoxInner a').each(function(){$(this).removeAttr('disabled');$(this).find('span').css('cursor','pointer');$(this).css('pointer-events','auto')});
		  				$('.mainNav a').each(function(){$(this).removeAttr('disabled');$(this).css('pointer-events','auto');$(this).find('span').css('cursor','pointer');});
		  					alert("Congratulations! You can now proceed to create Self-Review.");  	
		  					$('#myModal').modal("hide");				
		  				},showErrorMsgInMsgBox);                  
		          }
         <?php } ?> 
      }
</script>

<a class="video" data-toggle="modal" data-backdrop="static" data-keyboard="false"  data-target="#myModal" rel="jQw-hIGiK_0" id="introvideo" style="display:none">?</a>


<div class="modal fade video-lightbox" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close videoCloseBtn" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
            <div class="embed-responsive embed-responsive-4by3">
            	<div id="player" class="embed-responsive-item"></div>
            </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php } 
?>
                 <div class="dashboardBoxInner">
                 	                        
                                         <?php if(in_array("my_dashboard",$user['capabilities']) && count(array_intersect($user['role_ids'], array(10,7,11,12))) > 0) { ?>
                                                <a href='<?php echo createUrl(array("controller"=>"customratingsreport","action"=>"index")); ?>' title="Click to view your Zone/Block/Hub level performance." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled style="pointer-events:none;"':''; ?>><i class="fa fa-bar-chart"></i><span>MyDashboard</span></a> 
                                        <?php } ?>
					
					<?php if(in_array("create_client",$user['capabilities']) || in_array("manage_own_network_clients",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"client","action"=>"client")); ?>' title="Click to add schools and to view the complete list of existing schools." class="ylw-gradient vtip"><i class="fa fa-building"></i><span>MySchools</span></a>
					<?php } ?>
					
					<?php 
                                            
                                           if(current($user['role_ids'])!=8  && $user['is_guest']!=1) {
                                                $name='Users';
                                                if(in_array("manage_all_users",$user['capabilities']) || ($user['network_id']>0 && in_array("manage_own_network_users",$user['capabilities'])) || in_array("manage_own_users",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"user","action"=>"user")); ?>' title="Click to Add <?php echo $name?> and to view complete list of existing users with other details." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled style="pointer-events:none;"':''; ?> ><i class="fa fa-users"></i><span>My<?php echo $name?></span></a>
					<?php }
                                            }
                                        ?>
					
					<?php if(in_array("view_all_assessments",$user['capabilities']) || in_array("view_own_network_assessment",$user['capabilities']) || in_array("view_own_institute_assessment",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"assessment","action"=>"assessment")); ?>' title="Click to view list of reviews and their related details such as, status, edit/view scores, and generate reports." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled  style="pointer-events:none;"':''; ?>><i class="fa fa-list-ul"></i><span>Manage MyReviews</span></a>
					<?php }  else if(in_array("take_internal_assessment",$user['capabilities']) || in_array("take_external_assessment",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"assessment","action"=>"assessment","myAssessment"=>1)); ?>' title="Click to view list of reviews and their related details such as, status, edit/view scores, and generate reports." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled  style="pointer-events:none;"':''; ?>><i class="fa fa-list-ul"></i><span>MyReviews</span></a>
					<?php } ?>
					
					<?php if(in_array("manage_diagnostic",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"diagnostic","action"=>"diagnostic")); ?>' title="Click to view diagnostics." class="ylw-gradient vtip"><i class="fa fa-user-md"></i><span>MyDiagnostics</span></a>
					<?php } ?>
					
					<?php if(in_array("create_network",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"network","action"=>"network")); ?>' title="Click to view list of levels." class="ylw-gradient vtip"><i class="fa fa-sitemap"></i><span>MyLevels</span></a>
					<?php } ?>
					<?php
                                            $url = createUrl(array("controller"=>"user","action"=>"editUser","id"=>$user['user_id']));
                                        ?>
					<a href='<?php echo $url; ?>' title="Click to view / update profile details." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled style="pointer-events:none;"':''; ?>><i class="fa fa-edit"></i><span>MyProfile</span></a>
                                        
	                                        
                                        <?php if(current($user['role_ids'])==8  && $user['is_guest']!=1){ ?>
                                                <a  title="Click to see the details of mails sent to Assessors." class="ylw-gradient vtip" 
                                                    href="<?php echo createUrl(array("controller"=>"communication","action"=>"communication")); ?>">
                                                    <i class="fa fa-envelope"></i><span>MyCommunications</span>
                                                </a>
                                        <?php } ?>
                                       
                                        <?php if(in_array("view_all_assessments",$user['capabilities']) && current($user['role_ids'])!=8 && $user['is_guest']!=1){ ?>
						<a href='<?php echo createUrl(array("controller"=>"customreport","action"=>"networkreportlist")); ?>' title="Click to view and generate overview reports." class="ylw-gradient vtip" <?php print ($user['is_web']==1 && (in_array(6,$user['role_ids'])||in_array(5,$user['role_ids'])) && $user['has_view_video']==0)?'disabled=disabled  style="pointer-events:none;"':''; ?>><i class="fa fa-clipboard"></i><span>Manage MyOverview Reports</span></a>
					<?php } ?>
                                                
                                        <?php if(in_array("view_own_workshop",$user['capabilities']) && $user['is_guest']!=1){ ?>
						<a href='<?php echo createUrl(array("controller"=>"workshop","action"=>"myworkshop")); ?>' title="Click to view my workshops." class="ylw-gradient vtip"><i class="fa fa-book"></i><span>MyWorkshops</span></a>
					<?php } ?>
                                                
                                        <?php if(in_array("manage_workshop",$user['capabilities']) && $user['is_guest']!=1){ ?>
						<a href='<?php echo createUrl(array("controller"=>"workshop","action"=>"allworkshop")); ?>' title="Click to add/edit workshops." class="ylw-gradient vtip"><i class="fa fa-graduation-cap"></i><span>Manage Workshops</span></a>
					<?php } ?>        
                                                
                 	                <?php if(in_array("manage_app_settings",$user['capabilities'])){ ?>
						<a href='<?php echo createUrl(array("controller"=>"settings","action"=>"settings")); ?>' title="Click to change the user privileges." class="ylw-gradient vtip"><i class="fa fa-user"></i><span>Manage Roles &amp; Capabilities</span></a>
					<?php } ?>
                                                
                                           
                                         <?php if(in_array("my_dashboard",$user['capabilities']) && (in_array(12,$user['role_ids'])||in_array(11,$user['role_ids']))){ ?>
						<a href='<?php echo createUrl(array("controller"=>"actionplan","action"=>"actionplan1")); ?>' title="Click to view your school's action planning status." class="ylw-gradient vtip"><i class="fa fa-list-ul"></i><span>Monitor Action Planning</span></a>
					<?php } ?>
                                                
                                                
                 </div>
             </div>
        