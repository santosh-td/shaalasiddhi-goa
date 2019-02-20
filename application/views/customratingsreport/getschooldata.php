<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<style>
style-tip.op-default {
  padding:5px 8px; left: 5px; font-size:13px;font-weight:600; background-color:#dfdfdf;color:#000000;-moz-border-radius:5px;-webkit-border-radius:5px;z-index:9999;border:solid 1px #828282;
}
</style>
<script>
    (function() {
                //"use strict";

  var tooltipDelay = 500;
  
  var timer = null;
  document.body.addEventListener("mouseout", function() {
    window.clearTimeout(timer);
  });
  document.body.addEventListener("mousemove", function(e) {
    var el = e.target;
    if (el != document.body && (el.hasAttribute("title") || el.hasAttribute("data-styletip"))) {

      if (el.title) {
        el["tt-title"] = el.title;
        el["tt-show"] = function(pos) {
          var tip = document.createElement("style-tip");
          if (el.hasAttribute("styletip-class")) {
            tip.className = el.getAttribute("styletip-class");
          }
          tip.innerText = el["tt-title"];
          tip.style.zIndex = 9e9;
          tip.style.pointerEvents = "none";
          tip.style.position = "absolute";
          tip.style.left = pos.x + "px";
          tip.style.top = pos.y + "px";
          document.body.appendChild(tip);
          el["tt-tip"] = tip;
          this.addEventListener("mouseout", el["tt-destroy"]);
        };
        el["tt-destroy"] = function() {
          if (el["tt-tip"]) {
            document.body.removeChild(el["tt-tip"]);
            delete el["tt-tip"];
          }
        };
        el.removeAttribute("title");
        el.setAttribute("data-styletip", true);
      }

      clearTimeout(timer);
      timer = window.setTimeout(function() {
        el["tt-destroy"]();
        el["tt-show"]({
          x: e.pageX,
          y: e.pageY
        });
      }, tooltipDelay);
    }
  });

})();
</script>
<?php //echo "<pre>";print_r($data);echo "</pre>";?>
<h4 style="margin:15px 8px 10px;">School level performance - <?php echo $_POST['cluster_name'];?></h4>
<div class="performanceTable" id="">
    <div class="btmLegends topRight">
        <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
        <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
        <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
    </div>
    <h4>Domain <?php
    $level1="";
    if($request['kpa_id']==1){
       $level1=" - A&A";
       if($request['type'] == 'level2'){
           $level1=" - Q&U";
       }
   }
    echo $request['kpa_id'].$level1;?> : <?php echo $kpa;?></h4>
    
    <table class="table table-bordered" style="margin-bottom:100px;">
        <thead>
            <tr>
                <th>SCHOOL NAME</th>
                <?php foreach ($header as $headerKey => $headerValue){ ?>
                <th class="text-center"><?php echo $headerValue[0]['jstext'];?></th>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; $level1=0;$level2=0;$level3=0; foreach ($data as $schoolId => $schoolValue){ ?>
            <tr>
                <th class='wd200'><?php echo $sname[$schoolId][0]['client_name'];?></th>
                <?php foreach ($header as $headerKey => $headerValue){ 
                    if($request['type']=='level2'){
                        $rating =  explode("-",$data[$schoolId][$headerKey][0]['kd1_part2rating'])[1];
                    }else {
                        $rating =  explode("-",$data[$schoolId][$headerKey][0]['rating'])[1];
                    }
                $rating_level = $data[$schoolId][$headerKey][0]['rating_level'];
                if($rating=='3'){
                    $level = '3';$color='#38c176';
                } elseif($rating=='1'){
                    $level = '1';$color='#ff4c4c';
                }else{
                    $level = $rating;$color='#d2a828';
                }
                ?>
                <td class="text-center" <?php if($rating_level){ echo 'styletip-class="op-default"'; }?>" <?php if($rating_level){ echo "title='$rating_level'";}?> style="background-color:<?php echo $color?>;"></td>
                <?php }?>
            </tr>

            <?php $i++; }?>
            <tr class='percentage'>
                <th>Level 1</th>
                <?php foreach ($header as $headerKey => $headerValue){
                    $level1C = isset($lcount[$headerKey]['Level-1'])?count($lcount[$headerKey]['Level-1']):0; 
                    $level2C = isset($lcount[$headerKey]['Level-2'])?count($lcount[$headerKey]['Level-2']):0;
                    $level3C = isset($lcount[$headerKey]['Level-3'])?count($lcount[$headerKey]['Level-3']):0;
                    $total = $level1C+$level2C+$level3C;
                    ?>
                <td class="text-center"><?php echo round($level1C==0?0:($level1C/$total)*100,2)."%";?></td>
                <?php }?>
            </tr>
            <tr class='percentage'>
                <th>Level 2</th>
               <?php foreach ($header as $headerKey => $headerValue){ 
                   $level1C = isset($lcount[$headerKey]['Level-1'])?count($lcount[$headerKey]['Level-1']):0; 
                    $level2C = isset($lcount[$headerKey]['Level-2'])?count($lcount[$headerKey]['Level-2']):0;
                    $level3C = isset($lcount[$headerKey]['Level-3'])?count($lcount[$headerKey]['Level-3']):0;
                    $total = $level1C+$level2C+$level3C;
                   ?>
                <td class="text-center"><?php echo round($level2C==0?0:($level2C/$total)*100,2)."%";?></td>
                <?php }?>
                
            </tr>
            <tr class='percentage'>
                <th>Level 3</th>
                <?php foreach ($header as $headerKey => $headerValue){ 
                    $level1C = isset($lcount[$headerKey]['Level-1'])?count($lcount[$headerKey]['Level-1']):0; 
                    $level2C = isset($lcount[$headerKey]['Level-2'])?count($lcount[$headerKey]['Level-2']):0;
                    $level3C = isset($lcount[$headerKey]['Level-3'])?count($lcount[$headerKey]['Level-3']):0;
                    $total = $level1C+$level2C+$level3C;
                    ?>
                <td class="text-center"><?php echo round($level3C==0?0:($level3C/$total)*100,2)."%";?></td>
                <?php }?>
            </tr>
        </tbody>
    </table>
</div>
