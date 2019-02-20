<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>
<h1 class="page-title">My Dashboard</h1>
<div class="ylwRibbonHldr"><div class="tabitemsHldr"></div></div>
<div class="subTabWorkspace ratingReport pad26">
<?php  //echo "<pre>";print_r($userdata);echo '</pre>'; ?>
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
            <select class="form-control" style="width:110px;" id="zonechange">
                <?php   foreach($userdata as $key=>$val){?>
                <option value="<?php echo $val['zone_id'];?>"><?php echo $val['zone_name'];?></option>
                <?php }?>
            </select>
           
        </dd>
    </dl>
    <h4 class="title" style="position:relative;top:-30px;">Overall Zone performance - <text id="zone_name"><?php echo $userdata['0']['zone_name'];?></text></h4>
</div>
<?php if($data){?>
<div id="zonedata">
<div class="chartBoxPanel fstBox">        
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
                       $pcLevel1 = isset($data[$key]['Level-1'])?round(($data[$key]['Level-1'][0]['level1']/$totalKpaRating)*100,2):0;
                       $pcLevel2 = isset($data[$key]['Level-2'])?round(($data[$key]['Level-2'][0]['level1']/$totalKpaRating)*100,2):0;
                       $pcLevel3 = isset($data[$key]['Level-3'])?round(($data[$key]['Level-3'][0]['level1']/$totalKpaRating)*100,2):0;
                       ?>
                       <a href="#<?php echo $key ?>"><div class="lineBar cb" style="height:211px;width:7%;margin:0 2% 0 0;" onclick="blockReport(<?php echo $key; ?>)">
                           <div style="height:<?php echo round(2.11*$pcLevel3,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel3>0?$pcLevel3:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel2,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel2>0?$pcLevel2:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel1,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel1>0?$pcLevel1:'';?></span></div>
                           <label class="lineLbl" style="left:-28px;"><?php if($key==1){echo "KD1 - A&A";} else { echo "KD".$key;  }?></label>
                       </div></a>
                       
                       <?php if($key==1){ 
                       /*$totalKpaRating2 = $data[$key]['Level-1'][0]['level2']+$data[$key]['Level-2'][0]['level2']+$data[$key]['Level-3'][0]['level2'];
                       $pcLevel11 = round(($data[$key]['Level-1'][0]['level2']/$totalKpaRating2)*100,2);
                       $pcLevel12 = round(($data[$key]['Level-2'][0]['level2']/$totalKpaRating2)*100,2);
                       $pcLevel13 = round(($data[$key]['Level-3'][0]['level2']/$totalKpaRating2)*100,2);*/
                       
                       $L2_1=isset($data[$key]['Level-1'][0]['level2'])?explode(",", $data[$key]['Level-1'][0]['level2']):array();
                       $L2_2=isset($data[$key]['Level-2'][0]['level2'])?explode(",", $data[$key]['Level-2'][0]['level2']):array();
                       $L2_3=isset($data[$key]['Level-3'][0]['level2'])?explode(",", $data[$key]['Level-3'][0]['level2']):array();
                       //echo"<pre>";
                       $final_values=array_merge($L2_1,$L2_2,$L2_3);
                       
                       $final_values_count=array_count_values($final_values);
                       //print_r($final_values_count);
                       $totalKpaRating2=(isset($final_values_count['Level-1'])?$final_values_count['Level-1']:0) + (isset($final_values_count['Level-2'])?$final_values_count['Level-2']:0) + (isset($final_values_count['Level-3'])?$final_values_count['Level-3']:0);
                       /*$L2_1_C=array_count_values($L2_1);
                       print_r($L2_1_C);
                       
                       $L2_2_C=array_count_values($L2_2);
                       print_r($L2_2_C);
                       
                       $L2_3_C=array_count_values($L2_3);
                       print_r($L2_3_C);*/
                       
                       $pcLevel11 = isset($final_values_count['Level-1'])?round(($final_values_count['Level-1']/$totalKpaRating2)*100,2):0;
                       $pcLevel12 = isset($final_values_count['Level-2'])?round(($final_values_count['Level-2']/$totalKpaRating2)*100,2):0;
                       $pcLevel13 = isset($final_values_count['Level-3'])?round(($final_values_count['Level-3']/$totalKpaRating2)*100,2):0;
                       
                       ?>
                           <a href="#<?php echo $key ?>_2"> <div class="lineBar cb" style="height:211px;width:7%;margin:0 2%;" onclick="blockReport(<?php echo $key; ?>,2)">
                           <div style="height:<?php echo round(2.11*$pcLevel13,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel13>0?$pcLevel13:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel12,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel12>0?$pcLevel12:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel11,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel11>0?$pcLevel11:'';?></span></div>
                           <label class="lineLbl" style="left:-28px;">KD1 - Q&U</label>
                           </div></a>

                       
                        <?php }?>
                       
                           <?php }?>

               </div>
               <!-- <div class="btmLegends">
                   <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
                   <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
                   <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
               </div> -->
           </div>
        </div>
 
  <h4 style="margin:15px 8px 10px;">Block Level performance at key domain level </h4>
  <div class="chartboxOuterArea">
    <div class="chartBoxPanel blockbox">
        <div class="btmLegends topRight">
            <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
            <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
            <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
        </div>
        <?php foreach($block_data as $key=>$val){
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
                      <a href="#data_cluster"><div id="<?php $key."_".$keyblocks ?>" class="lineBar cb" style="height:211px;" onclick="clusterReport('level1',<?php echo $key; ?>,'<?php echo $keyblocks; ?>')">
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
                      <a href="#data_cluster" id="<?php echo $key;?>_2"><div class="lineBar cb" style="height:211px;" onclick="clusterReport('level2',<?php echo $key; ?>,'<?php echo $keyblocks; ?>')">
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
    </div>
    <?php } else {
        echo "Data is not available";
    }?>
  <div id="data_cluster"></div>
  <div id="data_school"></div>
  </div>
