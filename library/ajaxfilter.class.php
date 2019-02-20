
<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class ajaxFilter {

    protected $_fields = array();
    protected $_links = array();

    function addTextBox($name, $value, $placeholder = "") {
        $this->_fields[] = '<div class="item"><input type="text" autocomplete="off" data-value="' . $value . '" class="ajaxFilter" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '" /></div>';
    }

    function addTextBoxEtc($name, $value, $placeholder = "", $etc = '') {
        $this->_fields[] = '<div class="item"><input type="text" autocomplete="off" data-value="' . $value . '" class="ajaxFilter" name="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '" ' . $etc . ' /></div>';
    }

    function addDropDown($name, $values, $id_key, $value_key, $selected = "", $placeholder = "", $cssClass = '') {
        $html = '<div class="item"><select autocomplete="off" data-value="' . $selected . '" class="ajaxFilter ' . $cssClass . '" name="' . $name . '">';
        if (!empty($placeholder)) {
            $html .= '<option ' . ("" == $selected ? 'selected="selected"' : "") . ' value=""> -- ' . $placeholder . ' -- </option>
		';
        }

        foreach ($values as $value)
            $html.='<option ' . ($value[$id_key] == $selected ? 'selected="selected"' : "") . ' value="' . $value[$id_key] . '">' . $value[$value_key] . '</option>
			';
        $html.='</select></div>';
        $this->_fields[] = $html;
    }

    function addDropDown_etc($name, $values, $id_key, $value_key, $selected = "", $placeholder = "", $cssClass = '', $etc = '') {
        $html = '<div class="item"><select autocomplete="off" data-value="' . $selected . '" class="ajaxFilter ' . $cssClass . '" name="' . $name . '" ' . $etc . '>
		<option ' . ("" == $selected ? 'selected="selected"' : "") . ' value=""> -- ' . $placeholder . ' -- </option>
		';
        foreach ($values as $value)
            $html.='<option ' . ($value[$id_key] == $selected ? 'selected="selected"' : "") . ' value="' . $value[$id_key] . '">' . $value[$value_key] . '</option>
			';
        $html.='</select></div>';
        $this->_fields[] = $html;
    }

    function addHidden($name, $value) {
        $this->_fields[] = '<div class="item"><input type="hidden" autocomplete="off" data-value="' . $value . '" class="ajaxFilter" name="' . $name . '" value="' . $value . '" /></div>';
    }

    function addLink($value) {
        $this->_links[] = $value;
    }

    function addHiddenEtc($name, $value, $etc) {
        $this->_fields[] = '<div class="item"><input type="hidden" autocomplete="off" data-value="' . $value . '" class="ajaxFilter" name="' . $name . '" value="' . $value . '" ' . $etc . '/></div>';
    }

    function generateFilterBar($cleanAfterPrinting = 0) {
        $size = count($this->_fields);
        if ($size) {
            ?>
            <form action="" method="post" class="filters-bar ylwRibbonHldr stickyFltr">
                <div class="ribWrap clearfix">
                    <div class="fieldsArea"> 
                        <label>Filter By:</label>
                        <div class="filterFlds">
                            <div class="owl-carousel">
                                <?php
                                foreach ($this->_fields as $field) {
                                    echo $field;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="btnBox">
                            <!--<button type="submit" class="ajaxFilterBtn"><i class="fa fa-filter"></i>Filter</button>
                            <button type="button" class="ajaxFilterReset">Reset</button>-->
                            <button type="submit" class="ajaxFilterBtn fa fa-filter vtip"  title="Filter"></button>
                            <button type="button" class="ajaxFilterReset fa fa-remove vtip" title="Reset"></button>
                        </div>
                    </div>
                    <?php if (isset($this->_links) && count($this->_links) >= 1) { ?>
                        <span style=" float: right;">
                            <?php
                            foreach ($this->_links as $field) {
                                echo $field;
                            }
                            ?>
                        </span>
                    <?php } ?>
                </div>
            </form>
            <?php
        }
        if ($cleanAfterPrinting)
            $this->clean();
    }

    function generatesearchBar($cleanAfterPrinting = 0) {
        $size = count($this->_fields);
        if ($size) {
            ?>
            <form action="" method="post" class="filters-bar ylwRibbonHldr stickyFltr">
                <div class="ribWrap clearfix">
                    <div class="fieldsArea"> 
                        <label>Search By:</label>
                        <div class="filterFlds">
                            <div class="owl-carousel">
                                <?php
                                foreach ($this->_fields as $field) {
                                    echo $field;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="btnBox">
                            <!--<button type="submit" class="ajaxFilterBtn"><i class="fa fa-filter"></i>Filter</button>
                            <button type="button" class="ajaxFilterReset">Reset</button>-->
                            <button type="submit" class="ajaxFilterBtn fa fa-filter vtip"  title="Filter"></button>
                            <button type="button" class="ajaxFilterReset fa fa-remove vtip" title="Reset"></button>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        if ($cleanAfterPrinting)
            $this->clean();
    }

    function generateFilterBarHidden($cleanAfterPrinting = 0) {
        $size = count($this->_fields);
        if ($size) {
            ?>
            <form action="" method="post" class="filters-bar ylwRibbonHldr stickyFltr" >
                <div class="ribWrap clearfix" style="display: none;">
                    <div class="fieldsArea"> 
                        <label>Filter By:</label>
                        <div class="filterFlds">
                            <div class="owl-carousel">
                                <?php
                                foreach ($this->_fields as $field) {
                                    echo $field;
                                }
                                ?>
                            </div>
                        </div>
                        <div class="btnBox">
                            <!--<button type="submit" class="ajaxFilterBtn"><i class="fa fa-filter"></i>Filter</button>
                            <button type="button" class="ajaxFilterReset">Reset</button>-->
                            <button type="submit" class="ajaxFilterBtn fa fa-filter vtip"  title="Filter"></button>
                            <button type="button" class="ajaxFilterReset fa fa-remove vtip" title="Reset"></button>
                        </div>
                    </div>
                </div>
            </form>
            <?php
        }
        if ($cleanAfterPrinting)
            $this->clean();
    }

    function clean() {
        $this->_fields = array();
    }

    // add function for date filter on 25-07-2016 by Mohit Kumar
    function addDateBox($name, $value, $placeholder = "", $onclick = '', $etc = '') {
        if ($onclick != '') {
            $onclick = 'onchange="' . $onclick . '"';
        }
        $this->_fields[] = '<input type="text" autocomplete="off" data-value="' . $value . '" class="ajaxFilter ' . $name . '" name="' . $name . '" id="' . $name . '" value="' . $value . '" placeholder="' . $placeholder . '" title="' . $placeholder . '" ' . $onclick . ' ' . $etc . ' readonly/>';
    }

}
