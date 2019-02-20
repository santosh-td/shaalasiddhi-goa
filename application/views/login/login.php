<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php if(isset($errorexist)){
                            ?>
<form name="loginForm" method="post" action="">
                          <div id="myModal" class="modal fade">
    <div class="modal-dialog" ><div class="modal-content">
                                <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title fa fa-info-circle"> Login Already Exist</h4></div>
                                <div class="modal-body">
				<div class="clr"></div>
                                <div class="">
					<div class="ylwRibbonHldr">
						<div class="tabitemsHldr"></div>
					</div>
                                    <div class="subTabWorkspace pad26">
                                        <div class="form-stmnt">
                         <div class="loginputHldr">
                          <?php echo isset($errorexist)?'<div class="alert alert-danger">'.$errorexist.'</div>':''; ?>   
                         <span class="info-login"> 
                         <input type="hidden" name="confirm"  value="1" required>    
                         <input type="text" name="email" id="email" placeholder="User Name" value="<?php echo isset($email)?$email:''; ?>" required>
                         <input type="password" name="password" id="password" placeholder="Password" value="<?php echo isset($password)?$password:''; ?>" required>
                         </span>
                         </div>
                         <div class="clearfix">
                            
                            <div class="fr">
                                <input class="btn btn-danger" id="cancel" type="button" value="Cancel">
                                <input class="btn btn-danger" type="submit" value="Confirm">
                                
                            </div>
                         </div>
                         </div>                                            

                         </div>
                                    </div>
                                </div>
                                </div></div></div>
<input type="hidden" name="_action" value="login" />
                  </form>  
<script src="<?php echo SITEURL;?>public/js/login.js"></script>
                        <?php        
                        }
                        ?>		


<div class="nuiLogBox">
              <header class="hdr">Member Log In</header>
              <article class="cont">
                  <form name="loginForm" method="post" action="">
		  <?php echo isset($error)?'<div class="alert alert-danger">'.$error.'</div>':''; ?>
                    
                      <ul class="logList">
                        
                        <li class="loginputHldr">
                            <input type="text" name="email" placeholder="User Name" value="" required>
                            <i class="fa fa-user"></i>
                        </li>
                        <li class="loginputHldr">
                            <input type="password" name="password" placeholder="Password" value="" required>
                            <i class="fa fa-lock"></i>
                        </li>
                        <li class="clearfix">
                            
                            <div class="fl">
                             	<a class="btn btn-block" href="?controller=web&action=reset"><i class=""></i>Forgot password?</a>
                            </div>
                            <div class="fr">
                                <input class="redlogBtn" type="submit" value="Log In">
                            </div>
                        </li>
                        
                    </ul>
					<input type="hidden" name="_action" value="login" />
                  </form>
              </article>
              <footer class="foot foot-new" >
                  <div class="logfootWrap">
                      <div class="social-buttons">
                          
                          <div><a class="btn btn-block btn-social btn-facebook"><i class="fa fa-facebook"></i>Sign in with Facebook</a></div>
                          <div><a class="btn btn-block btn-social btn-twitter"><i class="fa fa-twitter"></i>Sign in with Twitter</a></div>
                          <div><a class="btn btn-block btn-social btn-google"><i class="fa fa-google-plus"></i>Sign in with Google+</a></div>
                      </div>
                  </div>
              </footer>
          </div>