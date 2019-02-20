<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<h1 class="page-title">
    <?php if ($isPop == 0) { ?>
        <a href="<?php
        $args = array("controller" => "client", "action" => "client");
        echo createUrl($args);
        ?>">
            <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
            Manage Schools
        </a>&rarr;	
    <?php } ?>				
    Add School</h1>
<div class="clr"></div>
<div class="">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            <form method="post" id="create_school_form" action="">
                <input type="hidden" name="is_create" value="1" id="is_create">
                <div class="boxBody">
                    <dl class="fldList">
                        <dt class="nobr">Type of Institution<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-9"><select class="form-control" name="client_institution_id" id="client_institution_id" required>
                                        <option value=""> - Select Institution Type - </option>
                                        <?php
                                        foreach ($client_institution_type as $c_type)
                                            echo "<option value=\"" . $c_type['client_institution_id'] . "\">" . $c_type['institution'] . "</option>\n";
                                        ?>
                                    </select></div></div></dd>
                    </dl>
                    <dl class="fldList">
                        <dt  class="nobr">School Name<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-9"><input type="text" class="form-control" value="" name="client_name" required /></div></div></dd>
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">School Address<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-9">
                                    <input type="text" class="form-control mb5" placeholder="Address Line 1" value="" name="street" required />	
                                    <input type="text" class="form-control mb5" placeholder="Address Line 2" value="" name="addrline2" />										
                                    
                                    <select class="form-control" name="country" id="country_id" required>
                                        <option value=""> - Select Country - </option>
                                        <?php
                                        foreach ($countries as $country)
                                            echo "<option value=\"" . $country['country_id'] . "\">" . $country['country_name'] . "</option>\n";
                                        ?>
                                    </select>
                                    <select class="form-control" name="state" id="state_id" required>
                                        <option value=""> - Select State - </option>												
                                    </select>
                                    <select class="form-control" name="city" id="city_id" required>
                                        <option value=""> - Select City - </option>												
                                    </select>											
                                </div></div></dd>
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">Principal Name<span class="astric">*</span>:</dt>
                        <dd><div class="row">
                                <div class="col-sm-9"><input type="text" class="form-control" value="" name="principal_name" required /></div></div>
                        </dd>
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">Email ID<span class="astric">*</span>:</dt>
                        <dd><div class="row">
                                <div class="col-sm-9"><input type="email" class="form-control" value="" placeholder="this will be the username" name="email" required /></div></div>
                        </dd>
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">Phone Number:</dt>
                        <dd>
                            <div class="inlContBox ftySixty">
                                <div class="inlCBItm fty">
                                    <div class="fld blk">
                                        <div>
                                            <select name="country_code" id="country_code" class="form-control" >
                                                <?php
                                                foreach ($countryCodeList as $value) {
                                                    ?>
                                                    <option value="<?php echo $value['phonecode'] ?>"
                                                            <?php echo!empty($eUser['school_contact_number']) && $sc_country_code[1] == $value['phonecode'] ? 'Selected' : '' ?>>
                                                            <?php echo "(+".$value['phonecode'] .") "; ?></option>
                                                        <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="inlCBItm sixty">
                                    <div class="fld">
                                        <div>
                                            <input type="text" class="form-control mask_ph "  name="phone" id="phone"  value="<?php echo!empty($school_contact_number) ? str_replace("-", '', $school_contact_number) : '' ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dd>   
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">Password<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-9"><input type="password" class="form-control pwd" value="" name="password" required /></div></div></dd>
                    </dl>
                    <dl class="fldList">
                        <dt class="nobr">Confirm Password<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-9"><input type="password" class="form-control cpwd" value="" required /></div></div></dd>
                    </dl>

                    <dl class="fldList">
                        <dt class="nobr">Remarks:</dt>
                        <dd><div class="row"><div class="col-sm-9"><textarea name="remarks" class="form-control" ></textarea></div></div></dd>
                    </dl>

                    <dl class="fldList" style="display:none">
                        <dt>Is the school part of a zone<span class="astric">*</span>:</dt>
                        <dd>
                            <div class="clearfix">
                                <div class="chkHldr autoW"><input type="radio" class="haveNetwork" name="haveNetwork" value="1"><label class="chkF radio"><span>Yes</span></label></div>
                                <div class="chkHldr autoW"><input type="radio" class="haveNetwork" checked="checked" name="haveNetwork" value="0"><label class="chkF radio"><span>No</span></label></div>
                            </div>
                        </dd>
                    </dl>
                    <dl class="fldList">
                            <dt>State<span class="astric">*</span>:</dt>
                            <dd class="inputHldr">
                                <div class="row">
                                    <div class="col-sm-6 width-50-modal">
                                        <select class="form-control state-list-dropdown" id="scl_state" name="state_id" required>
                                            <option value=""> - Select State - </option>
                                            <?php
                                            foreach ($states as $state)
                                                echo "<option value=\"" . $state['state_id'] . "\">" . $state['state_name'] . "</option>\n";
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
                                        foreach ($zones as $zone)
                                            echo "<option value=\"" . $zone['zone_id'] . "\">" . $zone['zone_name'] . "</option>\n";
                                        ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <a href="?controller=network&action=createZone&ispop=1" class="btn btn-primary execUrlZ vtip" title="Click to add zone." id="addZoneBtn">Add Zone</a>
                                </div>
                            </div>
                        </dd>
                    </dl>
                        <dl class="fldList" id="blocks"  style="display:none">
                            <dt>Block<span class="astric">*</span>:</dt>
                            <dd>
                                <div class="row">
                                    <div class="col-sm-6 width-50-modal">
                                        <select class="form-control block-list-dropdown" id="scl_block" name="block_id" required>
                                            <option value=""> - Select Block - </option>
                                            <?php
                                            foreach ($blocks as $block)
                                                echo "<option value=\"" . $block['network_id'] . "\">" . $block['network_name'] . "</option>\n";
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
                        <dt>Hub<span class="astric">*</span>:</dt>
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

                    <dl class="fldList">
                        <dt></dt>
                        <dd class="nobg">
                            <div class="row">
                                <div class="col-sm-6">
                                    <br>
                                    <input type="submit" value="Add" class="btn btn-primary">
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
