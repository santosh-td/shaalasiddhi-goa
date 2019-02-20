
<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>
 
<h1 class="page-title">Select Core Standard</h1>
<div class="clr"></div>

                    
<div class="ylwRibbonHldr">
    <div class="tabitemsHldr"></div>
</div>
<div class="subTabWorkspace pad26">
    <?php //echo "<pre>"; print_r($kpas);'</pre>';
    if (count($kpas) == 0) {
        echo"No Recommendations found for Level1";
    } else {
        ?>
        <form name="selectRecommend" id="action1new" action="" method="post">
            <input type="hidden" name="assessment_id" id="assessment_id" value="<?php echo $assessment_id ?>" >
            <dl class="fldList">
                <div class="addBtnWrap">
                    <a href="javascript:void(0)" class="filterRowAdd pRowAdd"><i class="fa fa-plus vtip" title="Click to add more rows."></i></a>	

                    <div class="tableHldr noShadow">
                        
                        <table class="table customTbl postRevTbl">
                            <thead>
                                <tr><th style="width:12%;">Sr. No.</th>
                                    <th style="width:40%">Key Domain</th>
                                    <th style="width:40%">Core Standard</th>
                                    <th style="width:5%;"></th></tr>	
                            </thead>
                            <tbody>
                                <?php
                                $row = $kpajsHelper->getAction1HTMLNew(1, $assessment_id, 0, array(), $lang_id);
                                echo $row;
                                ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </dl>                         

            <div class="ajaxMsg"></div>
            <div class="btnHldr text-right">                                    
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
        <?php
    }
    ?>
</div>