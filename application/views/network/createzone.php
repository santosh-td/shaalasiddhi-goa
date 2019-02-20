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
    Add Zone
</h1>
<div class="clr"></div>
<div class="">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form method="post" id="create_zone_form" action="">

                <div class="boxBody">
                    <dl class="fldList">
                        <dt>State<span class="astric">*</span>:</dt>
                        <dd class="the-basics zone">
                            <div class="row">
                                <div class="col-sm-9 ">
                                    <?php if (empty($state_id)) { ?>
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

                                    <?php } else { ?>
                                        <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required style="display: none">
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
                                        <input type="text" class="form-control" readonly="true" value="<?php echo $state_name; ?>">
                                    <?php } ?>
                                </div>
                                
                            </div>
                        </dd>
                    </dl>
                    <dl class="fldList zoneField">
                        <dt>Zone Name<span class="astric">*</span>:</dt>
                        <dd class="the-basics zone"><div class="row"><div class="col-sm-9"><input type="text" value="" class="form-control typeahead tt-query" name="name" required /></div></div></dd>
                    </dl> 
                    <dl class="fldList">
                        <dt></dt>
                        <dd class="nobg">
                            <div class="row">
                                <div class="col-sm-6">
                                    <br>
                                    <input type="submit" value="Add Zone" class="btn btn-primary">
                                </div>
                            </div>
                        </dd>
                    </dl>
                </div>
                <div class="ajaxMsg"></div>
                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        zones = [];
<?php foreach ($zones as $zone) { ?>
            zones.push('<?php echo $zone['zone_name']; ?>');
<?php } ?>
        $('.the-basics.zone .typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        },
                {
                    name: 'zones',
                    source: substringMatcher(zones),
                    limit: 30
                });
        $('.zone .typeahead').bind('typeahead:select', function (ev, suggestion) {
            

        });
        
    });
</script>
