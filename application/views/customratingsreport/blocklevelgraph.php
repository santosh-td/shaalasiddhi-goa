<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<script>
$(document).ready(function () {
    var kpaId;
    var blockId;
    var typed;
    var block = '<?php echo $block;?>';
    var zone = '<?php echo $zone_name;?>';
    var zone_id ='<?php echo $zone_id?>';
    var round = $('#round-list').val();
    blockchange = function(e){
        $('#data_cluster').html('');
        $('#data_school').html('');
        var block_id = e.target.value;
        
        block = e.target.selectedOptions["0"].text;
        $('#clock_name').text(block);
        var rdata = "blockId="+block_id+"&round="+round+"&zone_id="+zone_id+"&zone_name="+zone+"&block="+block+"&token="+getToken();
            var querystring = '';
            ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
                                                $('#blockdataload').html(data.content);
                                    },showErrorMsgInMsgBox);
        
    }
    
    zonechange = function(e){
        $('#data_cluster').html('');
        $('#data_school').html('');
        zone_id = e.target.value;
        zone = e.target.selectedOptions["0"].text;
        block = $('#blockList')[0][0].text;
        blockId = $('#blockList')[0][0].value;
        $('#blockList').val($("#blockList option:first").val());
        $('#clock_name').text(block);
        $('#zone_name').text(zone);
        var rdata = "zone_id="+zone_id+"&blockId="+blockId+"&block="+block+"&zone_name="+zone+"&token="+getToken();
            var querystring = '';
            ajaxCall(this,"customRatingsReport","blockList",rdata,querystring,function(s,data){
                                            $('#blockList').html(data.content);
                                            block = $('#blockList')[0][0].text;
                                            blockId = $('#blockList')[0][0].value;
                                            $('#blockList').val($("#blockList option:first").val());
                                            $('#clock_name').text(block);
 var rdata = "zone_id="+zone_id+"&round="+round+"&blockId="+blockId+"&block="+block+"&zone_name="+zone+"&token="+getToken();
            ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
                                                 $('#blockdataload').html(data.content);
                                    },showErrorMsgInMsgBox);
            },showErrorMsgInMsgBox);
        
    }
    
    clusterReport = function (type,kpa_id,blockname){
        block = $('#blockList option:selected').text();
        kpaId = kpa_id;
        typed = type;
        var str1 = kpa_id;
        if(type=='level2'){
            str1 = str1+'_2'
        }
        jQuery('.zone_box a').each(function(e,val){
          var id2 = $(val).attr('href');
          var str2 = id2.replace (/#/g, "");
          jQuery(val).removeClass('disableKpa');
          jQuery(val).removeClass('enableKpa');
          
          if(str1 == str2){
            jQuery(val).addClass('enableKpa');   
          } else{
              jQuery(val).addClass('disableKpa');
          }
        })

      
        var rdata = "type="+type+"&round="+round+"&kpa_id="+kpa_id+"&block="+block+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getClusterData",rdata,querystring,function(s,data){
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
        zone_id = $('#zonelist').val();
        zone = $("#zonelist option:selected").text();
        cluster = $("#clusterList option:selected").text();
        clusterId = $('#clusterList').val();
        block = $("#blockList option:selected").text();
        blockId = $('#blockList').val();
        var rdata = "zone_id="+zone_id+"&round="+round+"&zone_name="+zone+"&cluster="+cluster+"&clusterId="+clusterId+"&blockId="+blockId+"&block="+block+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
                                            $('#data_cluster').html('');
                                            $('#data_school').html('');
                                            $('#blockdataload').html(data.content);
                                },showErrorMsgInMsgBox);
    })
   
    });
</script>
<h1 class="page-title">My Dashboard</h1>
<div class="ylwRibbonHldr"><div class="tabitemsHldr"></div></div>
<div class="subTabWorkspace ratingReport pad26">
<?php if($datalist){  //echo "<pre>";print_r($data[1]['Level-1'][0]['level2']);echo '</pre>'; ?>
    
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
            <select class="form-control" id="zonelist" style="width:110px;" onchange="zonechange(event)">
                <?php foreach ($zonelist as $key => $val){ ?>
                <option value="<?php echo $key;?>"><?php echo $val[0]['zone_name'];?></option>
                <?php }?>
            </select>
            <select class="form-control" id="blockList" onchange="blockchange(event)" style="width:110px;">
               <?php foreach ($blocklist as $key => $val){ ?>
                <option value="<?php echo $key;?>"><?php echo $val[0]['network_name'];?></option>
                <?php }?>
            </select>
            
        </dd>
    </dl>
    <h4 class="title" style="position:relative;top:-30px;">Overall Block level performance - <text id="zone_name"><?php echo $zone_name;?></text> - <text id="clock_name"><?php echo $block;?></text></h4>
</div>
<?php if($data){?>
<div id="blockdataload" class="chartBoxPanel fstBox">        
           <div class="chartBoxOuter lbg" style="min-width: 40%;width:100%;">
               <div class="btmLegends topRight">
                <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
                <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
                <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
            </div>
               <div class="leftLegend"><div>100%</div><div>90%</div><div>80%</div><div>70%</div><div>60%</div><div>50%</div><div>40%</div><div>30%</div><div>20%</div><div>10%</div><div>0%</div></div>
               <div class="chartBox zone_box">                  
                      
                       <?php foreach($data as $key => $value) {  
                       $totalKpaRating = (isset($data[$key]['Level-1'])?$data[$key]['Level-1'][0]['level1']:0)+(isset($data[$key]['Level-2'])?$data[$key]['Level-2'][0]['level1']:0)+(isset($data[$key]['Level-3'])?$data[$key]['Level-3'][0]['level1']:0);
                       $pcLevel1 = round(($data[$key]['Level-1'][0]['level1']/$totalKpaRating)*100,2);
                       $pcLevel2 = round(($data[$key]['Level-2'][0]['level1']/$totalKpaRating)*100,2);
                       $pcLevel3 = round(($data[$key]['Level-3'][0]['level1']/$totalKpaRating)*100,2);
                       ?>
                       <a href="#<?php echo $key ?>"><div class="lineBar cb" style="height:211px;width:7%;margin:0 2% 0 0;" onclick="clusterReport('level1',<?php echo $key; ?>,'<?php echo $block;?>')">
                           <div style="height:<?php echo round(2.11*$pcLevel3,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel3;?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel2,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel2;?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel1,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel1;?></span></div>
                           <label class="lineLbl" style="left:-28px;"><?php if($key==1){echo "KD1 - A&A";} else { echo "KD".$key;  }?></label>
                       </div></a>
                       
                       <?php if($key==1){ 
                       
                       $L2_1=isset($data[$key]['Level-1'][0]['level2'])?explode(",", $data[$key]['Level-1'][0]['level2']):array();
                       $L2_2=isset($data[$key]['Level-2'][0]['level2'])?explode(",", $data[$key]['Level-2'][0]['level2']):array();
                       $L2_3=isset($data[$key]['Level-3'][0]['level2'])?explode(",", $data[$key]['Level-3'][0]['level2']):array();
                       $final_values=array_merge($L2_1,$L2_2,$L2_3);
                       
                       $final_values_count=array_count_values($final_values);
                       $totalKpaRating2=(isset($final_values_count['Level-1'])?$final_values_count['Level-1']:0) + (isset($final_values_count['Level-2'])?$final_values_count['Level-2']:0) + (isset($final_values_count['Level-3'])?$final_values_count['Level-3']:0);
                       $pcLevel11 = isset($final_values_count['Level-1'])?round(($final_values_count['Level-1']/$totalKpaRating2)*100,2):0;
                       $pcLevel12 = isset($final_values_count['Level-2'])?round(($final_values_count['Level-2']/$totalKpaRating2)*100,2):0;
                       $pcLevel13 = isset($final_values_count['Level-3'])?round(($final_values_count['Level-3']/$totalKpaRating2)*100,2):0;
                       
                       ?>
                           <a href="#<?php echo $key ?>_2"> <div class="lineBar cb" style="height:211px;width:7%;margin:0 2%;" onclick="clusterReport('level2',<?php echo $key; ?>,'<?php echo $block;?>')">
                           <div style="height:<?php echo round(2.11*$pcLevel13,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel13;?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel12,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel12;?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel11,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel11;?></span></div>
                           <label class="lineLbl" style="left:-28px;">KD1 - Q&U</label>
                           </div></a>

                       
                        <?php }?>
                       
                           <?php }?>

               </div>

           </div>   
        </div>
 
<?php } else { echo "Data is not available"; }  } else {
    echo "Data is not available";
}
?>
  <div id="data_cluster"></div>
  <div id="data_school"></div> 
  </div>