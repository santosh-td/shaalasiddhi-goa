<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
    Unlock Settings
</h1>
<div class="clr"></div>
<div id="reportsListWrapper">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <h5>Select an option to unfreeze data</h5>
        <div class="unLockAssessment">
            <form id="unlockform" class="clearfix">
                <div class="btnHldr pull-right">
                    <input type="button" class="btn btn-primary form-post vtip" title="Unlock" value="Save" />
                </div>
                <?php if($_GET['profile_status']){?>
                <div>
                    <div class="chkHldr">
                        <input type="checkbox" name="unlockarr[]" value="demographic" id="demographic">
                        <label class="chkF checkbox"><span>Demographic profile</span></label>
                    </div>
                </div>
                <?php }?>
                <?php if($_GET['is_filled']){?>
                <div>
                    <div class="chkHldr">
                        <input type="checkbox" name="unlockarr[]" value="rating" id="rating">
                        <label class="chkF checkbox"><span>Ratings</span></label>
                    </div>
                </div>
                <?php }?>
                <?php //echo "<pre>"; print_r($_GET);?>
<!--                <div>
                    <div class="chkHldr">
                        <input type="checkbox" name="unlockarr[]" id="actionplan">
                        <label class="chkF checkbox"><span>Action plan</span></label>
                    </div>  
                </div>                -->
           </form>            
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
   
$(document).on("click","#unlockform .form-post",function(e){
    var alltypes = []
    $('input[type=checkbox]').each(function () {
        var sThisVal = (this.checked ? $(this).val() : "");
        if(sThisVal){
            alltypes.push(sThisVal);
        }
    });

    if(alltypes.length>0){
         var postData = "assessment_id="+<?php echo $_GET['assessment_id'];?>+"&unlocktypes="+alltypes+"&token="+ getToken();

             apiCall(this, "unblockreview", postData, function (s, data) {
                 //console.log(data)
                 $(".modal-header button").trigger("click");
                 location.reload()
             })

    } else{
        alert('Please select an options');
    }
   })   
})
</script>