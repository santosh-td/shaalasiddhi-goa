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

                <?php if (empty($state_id) && empty($zone_id) && empty($block_id)) { ?>

                <div class="boxBody">
                    <div class="addFldHldr">
                        <dl class="fldList">
                            <dt>State<span class="astric">*</span>:</dt>
                            <dd class="inputHldr">
                                <div class="row">
                                        <div class="col-sm-9">
                                            <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required>
                                            <option value=""> - Select State - </option>
                                                <?php
                                                foreach ($states as $state) {
                                                    if ($state_id == $state['state_id']) {
                                                        echo "<option selected value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                                        $state_name = $state['state_name'];
                                                    } else
                                                        echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </dd>
                            </dl> 
                            <dl class="fldList" id="zones" style="display: none">
                                <dt>Zone<span class="astric">*</span>:</dt>
                                <dd class="inputHldr">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <select class="form-control zone-list-dropdown" id="scl_zone" name="zone_id" required>
                                                <option value=""> - Select Zone - </option>
                                                <?php
                                                foreach ($zones as $zone) {
                                                    if ($zone_id == $zone['zone_id']) {
                                                        echo "<option selected value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                                        $zone_name = $zone['zone_name'];
                                                    } else
                                                        echo "<option value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="fldList" id="blocks" style="display: none">
                                <dt>Block<span class="astric">*</span>:</dt>
                                <dd class="inputHldr">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <select class="form-control block-list-dropdown" id="scl_block" name="block_id" required>
                                                <option value=""> - Select Block - </option>
                                                <?php
                                                foreach ($blocks as $block) {
                                                    if ($block_id == $block['network_id']) {
                                                        echo "<option selected value=\"" . $block['network_id'] . "\">" . $block['network_name'] . "</option>\n";
                                                        $block_name = $block['network_name'];
                                                    } else
                                                        echo "<option value=\"" . $block['network_id'] . "\">" . $block['network_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                    </div>
                                </dd>
                            </dl>
                            <dl class="fldList provinceField" id="provinces" style="display: none">
                                <dt>Hub Name<span class="astric">*</span>:</dt>
                                <dd class="the-basics province inputHldr"><div class="row"><div class="col-sm-9"><input type="text" value="" class="form-control typeahead tt-query" name="name" required /></div></div></dd>
                                <dd class="nobg inputHldr">
                                <div class="btnHldr text-left">
                                    <div class="pull-left"><input type="submit" value="Add Hub" class="btn btn-primary"></div>
                                </div>

                            </dd>
                        </dl> 
                        </div>
                    </div>

                <?php }else { ?>

                    <div class="boxBody">
                        <div class="addFldHldr">
                            <dl class="fldList">
                                <dt>State<span class="astric">*</span>:</dt>
                                <dd class="inputHldr">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required style="display: none;">
                                                <option value=""> - Select State - </option>
                                                <?php
                                                foreach ($states as $state) {
                                                    if ($state_id == $state['state_id']) {
                                                        echo "<option selected value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                                        $state_name = $state['state_name'];
                                                    } else
                                                        echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                                }
                                                ?>
                                            </select>
                                            <input type="text" class="form-control" id="scl_state" readonly="true" value="<?php echo $state_name; ?>">
                                        </div> 
                                        
                                    </div>
                                </dd>
                            </dl> 
                        <dl class="fldList" id="zones">
                        <dt>Zone<span class="astric">*</span>:</dt>
                        <dd class="inputHldr">
                            <div class="row">
                                <div class="col-sm-9">
                                    <select class="form-control zone-list-dropdown" id="scl_zone" name="zone_id" required style="display: none">
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
                                    <input type="text" class="form-control" id="scl_zone" readonly="true" value="<?php echo $zone_name;?>">
                                </div>

                            </div>
                        </dd>
                    </dl>
                        <dl class="fldList" id="blocks">
                            <dt>Block<span class="astric">*</span>:</dt>
                            <dd class="inputHldr">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <select class="form-control block-list-dropdown" id="scl_block" name="block_id" required style="display: none">
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
                                            <input type="text" class="form-control" id="scl_block" readonly="true" value="<?php echo $block_name; ?>">
                                        </div> 
                                        
                                    </div>
                                </dd>
                            </dl>
                            <dl class="fldList provinceField" id="province">
                                <dt>Hub Name<span class="astric">*</span>:</dt>
                                <dd class="the-basics province inputHldr"><div class="row"><div class="col-sm-9"><input type="text" value="" class="form-control typeahead tt-query" name="name" required /></div></div></dd>
                            </dl> 
                            
                        </div>

                        <dl class="fldList btnHldr" id="province">
                            <dd class="nobg inputHldr">
                                <div class="clearfix">
                                    <div class="pull-left"><input type="submit" value="Submit" class="btn btn-primary"></div>
                                </div>

                            </dd>
                        </dl>
                    </div>

                <?php } ?>
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
