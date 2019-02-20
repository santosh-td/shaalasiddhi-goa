<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php if (isset($eState['state_id'])) { ?>
    <h1 class="page-title">
        <?php if ($isPop == 0) { ?>
            <a href="<?php
            $args = array("controller" => "network", "action" => "network");
            echo createUrl($args);
            ?>">
                <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
                Manage State
            </a> &rarr;					
        <?php } ?>	

        Update State
    </h1>
    <div class="clr"></div>
    <div class="">
        <div class="ylwRibbonHldr">
            <div class="tabitemsHldr"></div>
        </div>
        <div class="subTabWorkspace pad26">
            <div class="form-stmnt">
                <form method="post" id="update_state_form" action="">
                    <div class="boxBody">
                        <dl class="fldList">
                            <dt>State Name<span class="astric">*</span>:</dt>
                            <dd class="the-basics network"><div class="row"><div class="col-sm-6"><input type="text" value="<?php echo $eState['state_name']; ?>" class="form-control typeahead tt-query"  name="name" required /></div></div></dd>
                        </dl>
                        <dl class="fldList">
                            <dt></dt>
                            <dd class="nobg">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <br>
                                        <input type="submit" value="Update State" class="btn btn-primary">
                                    </div>
                                </div>
                            </dd>
                        </dl>
                    </div>
                    <div class="ajaxMsg"></div>
                    <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
                    <input type="hidden" value="<?php echo $eState['state_id']; ?>" name="id" />
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <h1>State does not exist</h1>
<?php } ?>
<script>
    $(document).ready(function () {
        networks = [];
<?php foreach ($networks as $network) { ?>
            networks.push('<?php echo $network['state_name']; ?>');
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
            $('.network .typeahead').typeahead('val', '');
            alert("This Zone already exists! Please type a new zone name.");

        });

        $('.network .typeahead').bind('typeahead:select', function (ev, suggestion) {
            $('.typeahead').typeahead('val', '');
            alert("This zone already exists! Please type new zone name.");

        });
    });
</script>			