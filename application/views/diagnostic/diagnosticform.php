<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
    <a href="<?php
    $args = array("controller" => "diagnostic", "action" => "diagnostic");
    $args["filter"] = 1;
    echo createUrl($args);
    ?>">
        <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
        Manage Diagnostics
    </a> &rarr;
    Diagnostic Form : <?php echo $diagnostic['name']; ?></h1>
    <div class="clearfix recmLbl">
			<div class="pull-right">
				<h2>Assessor Recommendations Level:</h2>
				<div class="recmLblDD">
					<select style="visibility:hidden;" name="recommendations_levels" id="id_recommendations_levels" class="form-control mulselect" multiple="multiple">
						<option value="kpa_recommendations" <?php echo (isset($kpaRecommendations) && $kpaRecommendations==1)?'selected="selected"':'';?>>KEY DOMAIN</option>
					</select>
			    </div>
			</div>
		</div>
<div id="diagnosticForm" data-id="<?php echo $diagnostic['diagnostic_id']; ?>" class="whitePanel">
    <div class="tab1Hldr mb10">
        <div class="tabitemsHldr">
            <ul class="redTab nav nav-tabs">
                <?php
                $i = 0;
                foreach ($kpas as $kpa_id => $kpa) {
                    $i++;
                    ?>
                    <li class="item<?php echo $i == 1 ? " active" : ""; ?>"><a href="#kpa<?php echo $kpa_id; ?>" data-toggle="tab" class="vtip" title="<?php echo htmlspecialchars($kpa['kpa_name']); ?>"><?php echo $diagnosticLabels['KEY_DOMAIN']." ". $i; ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <!-- Tab panes -->                    
    <div class="tab-content mainTabCont">
        <!-- KPA Tab Contents -->
        <?php
        $activeKpa = 1;
        include("common/kpatabs.php");
        ?>
    </div>
</div>