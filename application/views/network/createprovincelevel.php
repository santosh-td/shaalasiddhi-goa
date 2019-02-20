<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
    <?php if ($isPop == 0) { ?>
        <a href="<?php
        $args = array("controller" => "network", "action" => "network");
        echo createUrl($args);
        ?>">
            <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
            Manage Levels
        </a> &rarr;				
    <?php } ?>				
    Add Hub
</h1>
<div class="clr"></div>
<div>
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form method="post" id="create_province_form" action="">

                <div class="boxBody">
                    <div class="addFldHldr">
                        <dl class="fldList">
                            <dt>State<span class="astric">*</span>:</dt>
                            <dd class="inputHldr">
                                <div class="row">
                                    <div class="col-sm-6 width-50-modal">
                                        <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required>
                                            <option value=""> - Select State - </option>
                                                <?php
                                            foreach ($states as $state) {
                                                if ($state_id == $state['state_id']){
                                                    echo "<option selected value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                                    $state_name=$state['state_name'];
                                                }else
                                                    echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                            }
                                            ?>
                                        </select>
                                    </div> 
                                    <div class="col-sm-3">
                                        <a href="?controller=network&action=createState&ispop=1" class="btn btn-primary execUrl vtip" title="Click to add State." id="addNetworkBtn">Add State</a> 
                                    </div>
                                </div>
                            </dd>
                        </dl> 
                        <dl class="fldList" id="zones" style="display: none">
                        <dt>Zone<span class="astric">*</span>:</dt>
                        <dd class="inputHldr">
                            <div class="row">
                                <div class="col-sm-6 width-50-modal">
                                    <select class="form-control zone-list-dropdown" id="scl_zone" name="zone_id" required>
                                        <option value=""> - Select Zone - </option>
                                        <?php
                                        foreach ($zones as $zone) {
                                            if ($zone_id == $zone['zone_id']){
                                                echo "<option selected value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                                $zone_name=$zone['zone_name'];
                                            }else
                                                echo "<option value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <a href="?controller=network&action=createZone&ispop=1" class="btn btn-primary execUrlZ vtip" title="Click to add zone." id="addZoneBtn">Add Zone</a>
                                </div>
                            </div>
                        </dd>
                    </dl>
                        <dl class="fldList" id="blocks" style="display: none">
                            <dt>Block<span class="astric">*</span>:</dt>
                            <dd class="inputHldr">
                                <div class="row">
                                    <div class="col-sm-6 width-50-modal">
                                        <select class="form-control block-list-dropdown" id="scl_block" name="block_id" required>
                                            <option value=""> - Select Block - </option>
                                            <?php
                                            foreach ($blocks as $block) {
                                            if ($block_id == $block['network_id']){
                                                echo "<option selected value=\"" . $block['network_id'] . "\">" . $block['network_name'] . "</option>\n";
                                                $block_name=$block['network_name'];
                                            }else
                                                echo "<option value=\"" . $block['network_id'] . "\">" . $block['network_name'] . "</option>\n";
                                        }

                                            ?>
                                        </select>
                                    </div> 
                                    <div class="col-sm-3">
                                        <a href="?controller=network&action=createBlock&ispop=1" class="btn btn-primary execUrlB vtip" title="Click to add Block." id="addNetworkBtn">Add Block</a> 
                                    </div>
                                </div>
                            </dd>
                        </dl>
                        <dl id="provinces" style="display:none;" class="fldList">
                        <dt>Hub:</dt>
                        <dd class="inputHldr">
                            <div class="row">
                                <div class="col-sm-6 width-50-modal">
                                    <select class="form-control province-list-dropdown" id="scl_province" name="province" required>
                                        <option value=""> - Select Hub - </option>														
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                        <a href="?controller=network&action=createProvince&amp;ispop=1" class="btn btn-primary execUrlC vtip" title="Click to add hub" id="addProvinceBtn">Add Hub</a>
                                </div>
                            </div>
                        </dd>
                    </dl>
                    </div>

                </div>
                <div class="ajaxMsg"></div>
                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        provinces = [];
<?php foreach ($provinces as $province) { ?>
            provinces.push('<?php echo $province['province_name']; ?>');
<?php } ?>
        $('.the-basics .typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        },
                {
                    name: 'provinces',
                    source: substringMatcher(provinces),
                    limit: 30
                });

        $('.province .typeahead').bind('typeahead:select', function (ev, suggestion) {

        });
        $('.province .typeahead').bind('typeahead:render', function (ev, suggestion) {
            console.log('render')
        });
    });
</script>				