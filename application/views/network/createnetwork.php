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
    Add Block
</h1>
<div class="clr"></div>
<div class="">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form method="post" id="create_network_form" action="">

                <div class="boxBody">
                    <dl class="fldList">
                        <dt>State<span class="astric">*</span>:</dt>
                        <dd class="the-basics network">
                            <div class="row">
                                <div class="col-sm-6 ">
                                    <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required>
                                        <option value=""> - Select State - </option>
                                        <?php
                                        foreach ($states as $state)
                                            echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
                                        ?>
                                    </select>
                                </div> 
                            </div>
                        </dd>
                    </dl> 
                    <dl class="fldList" id="zones" style="display: none">
                        <dt>Zone<span class="astric">*</span>:</dt>
                        <dd class="the-basics zone">
                            <div class="row">
                                <div class="col-sm-6">
                                    <select class="form-control zone-list-dropdown" id="scl_zone" name="zone_id" required>
                                        <option value=""> - Select Zone - </option>
                                        <?php
                                        foreach ($zones as $zone)
                                            echo "<option value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </dd>
                    </dl>
                    <dl class="fldList" id="blocks" style="display: none">
                        <dt>Block Name<span class="astric">*</span>:</dt>
                        <dd class="the-basics block"><div class="row"><div class="col-sm-6"><input type="text" value="" class="form-control typeahead tt-query" name="name" required /></div></div></dd>
 
                    </dl> 
                    <dl class="fldList">
                        <dt></dt>
                        <dd class="nobg">
                            <div class="row">
                                <div class="col-sm-6">
                                    <br>
                                    <input type="submit" value="Add BLock" class="btn btn-primary">
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
        networks = [];
<?php foreach ($networks as $network) { ?>
            networks.push('<?php echo $network['network_name']; ?>');
<?php } ?>
        $('.the-basics.network .typeahead').typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        },
                {
                    name: 'networks',
                    source: substringMatcher(networks),
                    limit: 30
                });
        $('.network .typeahead').bind('typeahead:select', function (ev, suggestion) {

        });
        
    });
</script>