<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php //echo "<pre>";print_r($data);echo "</pre>";?>
<h4 style="margin:15px 8px 10px;">Hub level performance</h4>
<div class="chartBoxPanel">
           <div class="chartBoxOuter lbg cluster_level" style="min-width: 40%;width:100%;">
               <h4>Key Domain <?php $level1="";
                if($request['kpa_id']==1){
                   $level1=" - A&A";
                   if($request['type'] == 'level2'){
                       $level1=" - Q&U";
                   }
               }
             echo $request['kpa_id'].$level1." : ".$request['block']."'s Performance";?></h4>
               <div class="btmLegends topRight">
                   <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
                   <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
                   <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
               </div>
               
               <div class="leftLegend"><div>100%</div><div>90%</div><div>80%</div><div>70%</div><div>60%</div><div>50%</div><div>40%</div><div>30%</div><div>20%</div><div>10%</div><div>0%</div></div>
               <div class="chartBox" style="width:<?php echo (count($data)*60+50).'px';?>">                  
                      
                       <?php foreach($data as $key => $value) {  
                       $totalKpaRating = (isset($data[$key]['Level-1'])?$data[$key]['Level-1'][0]['level1']:0)+(isset($data[$key]['Level-2'])?$data[$key]['Level-2'][0]['level1']:0)+(isset($data[$key]['Level-3'])?$data[$key]['Level-3'][0]['level1']:0);
                       $pcLevel1 = isset($data[$key]['Level-1'])?round(($data[$key]['Level-1'][0]['level1']/$totalKpaRating)*100,2):'0';
                       $pcLevel2 = isset($data[$key]['Level-2'])?round(($data[$key]['Level-2'][0]['level1']/$totalKpaRating)*100,2):'0';
                       $pcLevel3 = isset($data[$key]['Level-3'])?round(($data[$key]['Level-3'][0]['level1']/$totalKpaRating)*100,2):'0';
                       ?>
                   <a href="#data_school"><div class="lineBar cb" style="height:211px;width:40px;margin:0 20px;" onclick="schoolReport('<?php echo $key;?>')">
                           <div style="height:<?php echo round(2.11*$pcLevel3,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel3>0?$pcLevel3:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel2,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel2>0?$pcLevel2:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel1,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel1>0?$pcLevel1:'';?></span></div>
                           <label class="lineLbl" style="left:-38px;"><?php echo $key; ?></label>
                       </div></a>
                       
                                              
                           <?php }?>

               </div>               
           </div>
</div>

