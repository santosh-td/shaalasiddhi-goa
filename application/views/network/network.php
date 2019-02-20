<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<div class="filterByAjax network-list" data-action="<?php echo $this->_action; ?>" data-controller="<?php echo $this->_controller; ?>">
    <h1 class="page-title">
        Levels
        <a href="?controller=network&action=createProvinceLevel&amp;ispop=1" class="btn btn-primary pull-right vtip execUrl" title="Click to add state/zone/block/hub." id="addProvinceBtn">Add New</a>
        
        <div class="clr"></div>
    </h1>
    <div class="asmntTypeContainer">			
        <?php
        $ajaxFilter = new ajaxFilter();
        $ajaxFilter->addTextbox("state", $filterParam["state_like"], "State Name");
        $ajaxFilter->addTextbox("name", $filterParam["name_like"], "Zone Name");
        $ajaxFilter->addTextbox("block", $filterParam["block_like"], "Block Name");
        $ajaxFilter->addTextbox("province", $filterParam["province_like"], "Hub Name");
        $ajaxFilter->generateFilterBar(1);
        ?>

        <div class="tableHldr">
            <table class="cmnTable">
                <thead>
                    <tr>
                        <th data-value="state" class="sort <?php echo $orderBy == "state" ? "sorted_" . $orderType : ""; ?>">State Name</th>
                        <th data-value="name" class="sort <?php echo $orderBy == "name" ? "sorted_" . $orderType : ""; ?>">Zone Name</th>
                        <th data-value="block" class="sort <?php echo $orderBy == "block" ? "sorted_" . $orderType : ""; ?>">Block Name</th>
                        <th data-value="province" class="sort <?php echo $orderBy == "province" ? "sorted_" . $orderType : ""; ?>">Hub Name</th>
                        <th data-value="noofclients" class="sort <?php echo $orderBy == "noofclients" ? "sorted_" . $orderType : ""; ?>">Schools in State</th>
                        <th data-value="clientinzone" class="sort <?php echo $orderBy == "clientinzone" ? "sorted_" . $orderType : ""; ?>">Schools in Zone</th>
                        <th data-value="clientinblock" class="sort <?php echo $orderBy == "clientinblock" ? "sorted_" . $orderType : ""; ?>">Schools in Block</th>
                        <th data-value="clientincluster" class="sort <?php echo $orderBy == "clientincluster" ? "sorted_" . $orderType : ""; ?>">Schools in Hub</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($networks))
                        foreach ($networks as $network) {
                            ?>
                            <tr <?php echo!empty($network['clustername']) ? 'class="provinceRow"' : ''; ?>>
                                <td><?php echo $network['statename']; ?></td>
                                <td><?php echo $network['zonename']; ?></td>
                                <td><?php echo $network['blockname']; ?></td>
                                <td><?php echo $network['clustername']; ?></td>
                                <td id="clientInStateFor-<?php echo $network['stateid']; ?>"><?php echo $network['noofclients']; ?></td>
                                <td id="clientInZoneFor-<?php echo $network['zoneid']; ?>"><?php echo $network['clientinzone']; ?></td>
                                <td id="clientInBlockFor-<?php echo $network['blockid']; ?>"><?php echo $network['clientinblock']; ?></td>
                               

                                <td id="clientInProvinceCountFor-<?php echo $network['clusterid']; ?>"><?php echo $network['clientincluster']; ?></td>
                                <td>
                                    <?php if (!empty($network['stateid']) && empty($network['zoneid']) && empty($network['blockid']) && empty($network['clusterid'])) {
                                        ?><a href="?controller=network&action=editState&id=<?php echo $network['stateid']; ?>&amp;ispop=1" class="execUrl"><i class="vtip glyphicon glyphicon-pencil" title="Update State"></i></a>
                                    <?php } ?>
                                    <?php if (!empty($network['stateid']) && !empty($network['zoneid']) && empty($network['blockid']) && empty($network['clusterid'])) {
                                        ?><a href="?controller=network&action=editZone&id=<?php echo $network['zoneid']; ?>&amp;ispop=1" class="execUrl"><i class="vtip glyphicon glyphicon-pencil" title="Update Zone"></i></a>
                                        <?php } ?>
                                        <?php if (!empty($network['stateid']) && !empty($network['zoneid']) && !empty($network['blockid']) && empty($network['clusterid'])) {
                                            ?><a href="?controller=network&action=editNetwork&id=<?php echo $network['blockid']; ?>&amp;ispop=1" class="execUrl"><i class="vtip glyphicon glyphicon-pencil" title="Update Block"></i></a>
                                        <?php } ?>
                                        <?php if (!empty($network['stateid']) && !empty($network['zoneid']) && !empty($network['blockid']) && !empty($network['clusterid'])) {
                                            ?><a href="?controller=network&action=editNetworkProvince&pid=<?php echo $network['clusterid']; ?>&amp;ispop=1" class="execUrl"><i class="vtip glyphicon glyphicon-pencil" title="Update Hub"></i></a>
                                        <?php } ?>

                                </td>
                            </tr>
                            <?php
                        } else {
                        ?>
                        <tr>
                            <td colspan="6">No zone found</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>

        <?php echo $this->generateAjaxPaging($pages, $cPage); ?>

        <div class="ajaxMsg"></div>
    </div>
</div>