<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<script>
$(document).ready(function () {
    var kpaId;
    var typed;
    var zonen;
    var block;
    var round = $('#round-list').val();
    blockReport = function (type,kpa_id,zone_name){
        $('#data_cluster').html('');
        $('#data_school').html('');
        kpaId = kpa_id;
        typed = type;
        zonen = zone_name;
        var str1 = kpa_id;
        if(type=='level2'){
            str1 = str1+'_2'
        }
        
        jQuery('.blockbox .chartBoxOuter').each(function(e,val){
          $(this).removeClass('selected');
          $(this).removeClass('unselected');          
          var id2 = $(val).attr('id');
          var str2 = id2.replace (/#/g, "");
          if(str1 == str2){
           jQuery(this).addClass('selected');   
          } else {
              jQuery(this).addClass('unselected');
          }
        }); 

        var rdata = "type="+type+"&round="+round+"&kpa_id="+kpa_id+"&zone_name="+zone_name+"&token="+getToken;
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getBlockData",rdata,querystring,function(s,data){
                                            $('#data_block').html(data.content);
                                            $('body, html').animate({scrollTop: $('#data_block').offset().top},1500);
                                            
                                            $('#data_school').html(''); 
				},showErrorMsgInMsgBox);
    }

    clusterReport = function (type,kpa_id,blockname){
        kpaId = kpa_id; 
        typed = type;
        block = blockname;
        
        var rdata = "type="+type+"&round="+round+"&kpa_id="+kpa_id+"&block="+blockname+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getClusterData",rdata,querystring, function(s,data){
                                            $('#data_cluster').html(data.content);
                                            $('body, html').animate({scrollTop: $('#data_cluster').offset().top},1500);
                                            
                                            $('#data_school').html(''); 
				},showErrorMsgInMsgBox);
    }
    
    $(document).on('click', '.cluster_level .lineBar', function (e) {
        $('.cluster_level .lineBar').removeClass('disableKpa');
        $('.cluster_level .lineBar').removeClass('enableKpa'); 
        $('.cluster_level .lineBar').addClass('disableKpa');
        $(this).removeClass('disableKpa')
        $(this).addClass('enableKpa');
    });
    
    $(document).on('click', '.block_click .lineBar', function (e) {
        $('.block_click .lineBar').removeClass('disableKpa');
        $('.block_click .lineBar').removeClass('enableKpa'); 
        $('.block_click .lineBar').addClass('disableKpa');
        $(this).removeClass('disableKpa')
        $(this).addClass('enableKpa');
    });
 
   $(document).on('click', '.blockbox .lineBar', function (e) {
        $('.blockbox .lineBar').removeClass('disableKpa');
        $('.blockbox .lineBar').removeClass('enableKpa'); 
        $('.blockbox .lineBar').addClass('disableKpa');
        $(this).removeClass('disableKpa')
        $(this).addClass('enableKpa');
    });
    
    schoolReport = function (cluster_name) {
        var rdata = "type="+typed+"&round="+round+"&cluster_name="+cluster_name+"&block="+block+"&kpa_id="+kpaId+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getSchoolData",rdata,querystring,function(s,data){
                                        $('#data_school').html(data.content);
                                        $('body, html').animate({scrollTop: $('#data_school').offset().top},1500);
                                        
				},showErrorMsgInMsgBox);
    }
    
    $('#round-list').change(function(){
        round = $(this).val();
        var rdata = "round="+round+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
            $('#data_school').html('');
            $('#data_cluster').html('');
            $('#data_block').html('');
                                            $('#statedataload').html(data.content);
                                            
                                },showErrorMsgInMsgBox);
    });
    
    });
</script>
<h1 class="page-title">My Dashboard</h1>
<div class="ylwRibbonHldr"><div class="tabitemsHldr"></div></div>
<div class="subTabWorkspace ratingReport pad26">
<?php  //echo "<pre>";print_r($roundlist);echo '</pre>'; ?>
<div class="clearfix">
     <dl class="fldList pull-right">
        <dd><strong>Round:</strong> 
        <select id="round-list" class="form-control" style="width:90px;">
                 <?php    foreach ( $roundlist as $key=>$val){?>
                <option value="<?php echo $val['aqs_round'];  ?>"><?php echo $val['aqs_round'];  ?></option>
                <?php }?>
            </select>
         </dd>
    </dl>
</div>
<div class="clearfix">
    <dl class="fldList pull-right">
        <dd>
            <select class="form-control" style="width:110px;">
                <option><?php echo $stateName;?></option>
            </select>
            
        </dd>
    </dl>
    <h4 class="title" style="position:relative;top:-30px;">State performance at key domain level - <?php echo $stateName;?></h4>
</div>
<!--    <h4 style="margin:15px 8px 10px;">State performance at key domain level <?php echo $stateName;?></h4>-->
    
  <?php if($block_data){?>
  
  <div class="chartboxOuterArea" id="statedataload">
    <div class="chartBoxPanel blockbox">
        <div class="btmLegends topRight">
            <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
            <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
            <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
        </div>
        <?php  foreach($block_data as $key=>$val){
            $level1="";
            $level2="";
            if($key==1){
            $level1=" - A&A";
            $level2=" - Q&U";
            }
         ?>
        <div class="chartBoxOuter" id="<?php echo $key ?>">
              <h4>Key Domain <?php echo $key; ?><?php echo $level1 ?></h4>
                  <div class="leftLegend"><div>100%</div><div>90%</div><div>80%</div><div>70%</div><div>60%</div><div>50%</div><div>40%</div><div>30%</div><div>20%</div><div>10%</div><div>0%</div></div>
                  <div class="chartBox">
                      <?php foreach($val as $keyblocks=>$valblocks){
                          $totalKpaRating = (isset($valblocks['Level-1'])?$valblocks['Level-1'][0]['level1']:0)+(isset($valblocks['Level-2'])?$valblocks['Level-2'][0]['level1']:0)+(isset($valblocks['Level-3'])?$valblocks['Level-3'][0]['level1']:0);

                          $pcLevel1 = isset($valblocks['Level-1'][0]['level1'])?round(($valblocks['Level-1'][0]['level1']/$totalKpaRating)*100,2):0;
                          $pcLevel2 = isset($valblocks['Level-2'][0]['level1'])?round(($valblocks['Level-2'][0]['level1']/$totalKpaRating)*100,2):0;
                          $pcLevel3 = isset($valblocks['Level-3'][0]['level1'])?round(($valblocks['Level-3'][0]['level1']/$totalKpaRating)*100,2):0;
                          ?>
                      <a href="#data_cluster"><div id="<?php $key."_".$keyblocks ?>" class="lineBar cb" style="height:211px;" onclick="blockReport('level1',<?php echo $key; ?>,'<?php echo $keyblocks; ?>')">
                          <div style="height:<?php echo round(2.11*$pcLevel3,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel3>0?$pcLevel3:'';?></span></div>
                          <div style="height:<?php echo round(2.11*$pcLevel2,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel2>0?$pcLevel2:'';?></span></div>
                          <div style="height:<?php echo round(2.11*$pcLevel1,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel1>0?$pcLevel1:'';?></span></div>
                          <label class="lineLbl"><?php echo $keyblocks ;?></label>
                          </div> </a>
                      <?php } ?>

 
                  </div>
                  
              </div>
 
 <?php if($key==1){ ?>
             <div class="chartBoxOuter" id="<?php echo $key ?>_2">
              <h4>Key Domain <?php echo $key;  ?><?php echo $level2 ?></h4>
                  <div class="leftLegend"><div>100%</div><div>90%</div><div>80%</div><div>70%</div><div>60%</div><div>50%</div><div>40%</div><div>30%</div><div>20%</div><div>10%</div><div>0%</div></div>
                  <div class="chartBox">
                      <?php foreach($val as $keyblocks=>$valblocks){

                         $L2_1=isset($valblocks['Level-1'][0]['level2'])?explode(",", $valblocks['Level-1'][0]['level2']):array();
                         $L2_2=isset($valblocks['Level-2'][0]['level2'])?explode(",", $valblocks['Level-2'][0]['level2']):array();
                         $L2_3=isset($valblocks['Level-3'][0]['level2'])?explode(",", $valblocks['Level-3'][0]['level2']):array();
                         //echo"<pre>";
                         $final_values=array_merge($L2_1,$L2_2,$L2_3);

                         $final_values_count=array_count_values($final_values);
                         //echo"<pre>";
                         //print_r($final_values_count);

                         $totalKpaRating2=(isset($final_values_count['Level-1'])?$final_values_count['Level-1']:0) + (isset($final_values_count['Level-2'])?$final_values_count['Level-2']:0) + (isset($final_values_count['Level-3'])?$final_values_count['Level-3']:0);
                         $pcLevel11 = isset($final_values_count['Level-1'])?round(($final_values_count['Level-1']/$totalKpaRating2)*100,2):0;
                         $pcLevel12 = isset($final_values_count['Level-2'])?round(($final_values_count['Level-2']/$totalKpaRating2)*100,2):0;
                         $pcLevel13 = isset($final_values_count['Level-3'])?round(($final_values_count['Level-3']/$totalKpaRating2)*100,2):0;

                          ?>
                      <a href="#data_cluster" id="<?php echo $key;?>_2"><div class="lineBar cb" style="height:211px;" onclick="blockReport('level2',<?php echo $key; ?>,'<?php echo $keyblocks; ?>')">
                          <div style="height:<?php echo round(2.11*$pcLevel13,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel13>0?$pcLevel13:'';?></span></div>
                          <div style="height:<?php echo round(2.11*$pcLevel12,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel12>0?$pcLevel12:'';?></span></div>
                          <div style="height:<?php echo round(2.11*$pcLevel11,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel11>0?$pcLevel11:'';?></span></div>
                          <label class="lineLbl"><?php echo $keyblocks ;?></label>
                          </div> </a>
                      <?php } ?>

                  </div>
              </div>
              <?php
              }
              ?>

              <?php } ?>

          </div>
  </div>
  <?php  } else{ ?>
Data is not available
  <?php }?>
  <div id="data_block"></div>
  <div id="data_cluster"></div>
  <div id="data_school"></div>
  </div>