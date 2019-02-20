<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<option value="">--Select Round--</option>
<?php foreach ($roundList as $key => $val){?>
    <option value="<?php echo $key;?>"><?php echo "Round-".$key;?></option>
<?php }?>

