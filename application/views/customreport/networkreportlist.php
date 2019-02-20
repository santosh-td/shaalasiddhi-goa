<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>
<div class="filterByAjax networkreportlist" data-action="<?php echo $this->_action; ?>" data-controller="<?php echo $this->_controller; ?>">
    <div class="clearfix hdrTitle"><div class="pull-left"><h1 class="page-title">MyOverview Reports</h1></div>
						 <div class="pull-right">
                                                     
            <ul class="mainNav">
                <li class="active"><a href="javascript:void(0);">Create Overview Report <i class="fa fa-sort-desc"></i></a>
                    <ul>
      <li><a href="?controller=customreport&action=clusterReport">Hub Level Report</a> 											
      <li><a href="?controller=customreport&action=blockReport">Block Level Report</a> 											
						</li>
      
    </ul>
                </li>
            </ul>                                 
  </div>
                                        <div class="clr"></div>
					</div>
					<div class="nwListContainer">
					 <?php
                                         $customrptModel = new customreportModel();
						$ajaxFilter=new ajaxFilter();
                                                $ajaxFilter->addDropDown("report_type",$report_type,'report_id','assessment_type_name',$filterParam["report_id"],"Report Type");
						$ajaxFilter->addTextbox("report_name",$filterParam["report_name_like"],"Report Name");							
                                                $ajaxFilter->generateFilterBar(1);

						?>
					 <div class="tableHldr">
						 <table class="cmnTable">
							 <thead>
								 <tr><th data-value="report_name" class="sort <?php echo $orderBy=="report_name"?"sorted_".$orderType:""; ?>">Name</th>
                                                                     <th data-value="report_type" class="sort <?php echo $orderBy=="report_type"?"sorted_".$orderType:""; ?>">Overview Report type</th>
                                                                     <th data-value="create_date" class="sort <?php echo $orderBy=="create_date"?"sorted_".$orderType:""; ?>">Creation Date</th><th>Overview Report</th><!--<th>Data Summary</th>--></tr>
							 </thead>
							 <tbody>
								 <?php 
							if(count($networkReportList)){
								 foreach($networkReportList as $nwrow){
								 	echo "<tr data-id='".$nwrow['network_report_id']."'><td>".$nwrow['report_name']."</td>
                                                                            <td>".$nwrow['report_type_name']."</td>
                                                                         <td>".ChangeFormat($nwrow['create_date'])."</td>";
                                                                                        if($nwrow['report_id']==4)    
                                                                                         echo"<td><a href='?controller=customReport&action=blockDataReport&report_id=".$nwrow['report_id']."&org_id=".$nwrow['network_id']."&centre_id=".$nwrow['province_id']."&batch_id=".$nwrow['client_id']."&round_id=".$nwrow['round']."&block=".$nwrow['network']."&zone=".$nwrow['zone']."&state=".$nwrow['state']."&name=".$nwrow['report_name']."'  target='_blank'><i title='Click to view report.' class='vtip glyphicon glyphicon-eye-open'></i></a></td>";
                                                                                        if($nwrow['report_id']==3){
                                                                                          $dt=$customrptModel->getClsReportDetail($nwrow['network_report_id']);
                                                                                          $clientsAll= array_column($dt, 'client_id');
                                                                        $state_id=!empty($dt[0]['state_id'])?$dt[0]['state_id']:0;
                                                                        $zone_id=!empty($dt[0]['zone_id'])?$dt[0]['zone_id']:0;
                                                                        $block_id=!empty($dt[0]['block_id'])?$dt[0]['block_id']:0;
                                                                        $cluster_id=!empty($dt[0]['cluster_id'])?$dt[0]['cluster_id']:0;
                                                                                                                                                                    echo "<td><a href='?controller=customReport&action=clusterDataReport&report_id=" . $nwrow['report_id'] . "&centre_id=" . $cluster_id . "&state=" . $state_id ."&zone=" . $zone_id ."&block=" . $block_id ."&name=" . $nwrow['report_name'] ."&batch_id=" . implode(",",$clientsAll) . "&round_id=" . 1 . "'  target='_blank'><i title='Click to view report.' class='vtip glyphicon glyphicon-eye-open'></i></a></td>";
                                                                                        
                                                                                        }
										echo"</tr>";
                                                                 }									
							}else{
								echo "<tr><td colspan='3'>No results found</td></tr>";
							}
								 ?>
							 </tbody>
						 </table>
					 </div>
					 
					 
					  <?php  echo $this->generateAjaxPaging($pages,$cPage); ?>
								
						<div class="ajaxMsg"></div>
					  </div>
				</div>