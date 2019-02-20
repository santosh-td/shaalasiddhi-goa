/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
var allAssessors = [];
var allFacilitators = [];
var reviewType = 0;
var isFilter = 0;
var networks = [];
var provinces = [];
var states = [];
var blocks = [];
var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;
        matches = [];
        substrRegex = new RegExp("^" + q.trim(), 'i');
        $.each(strs, function (i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });
        cb(matches);
    };
};
var logger = function ()
{
    var oldConsoleLog = null;
    var pub = {};

    pub.enableLogger = function enableLogger()
    {
        if (oldConsoleLog == null)
            return;

        window['console']['log'] = oldConsoleLog;
    };

    pub.disableLogger = function disableLogger()
    {
        oldConsoleLog = console.log;
        window['console']['log'] = function () {};
    };

    return pub;
}();
$(document).ready(function () {


    /*********************************/
    $(document).on("click", "#school_cluster", function () {//alert("fdf");

        var client_id = $("#school_id").val();
        var round = $("#round").val();
        var postData = "client_id=" + client_id + "&round=" + round + "&token=" + getToken();
        if ($("#school_id").val() != "" && $("#round").val() != "") {

            apiCall(this, "getAssessmentIdFromClient", postData, function (s, data) {
                window.location.href = "index.php?controller=actionplan&action=actionplan1&assessment_id=" + data.message;
            }, showErrorMsgInMsgBox);
        } else {
            if ($("#school_id").val() == "") {
                alert('Please select School.');
            } else if ($("#round").val() == "") {
                alert('Please select Round.');
            }

        }
    });

    $(document).on("change", "#school_id", function () {//alert("fdf");
        var client_id = $("#school_id").val();
        var postData = "client_id=" + client_id + "&token=" + getToken();
        if ($("#school_id").val() != "") {
            var allOption = [];
            document.getElementById("round").options.length = 1;
            apiCall(this, "getAssessmentRound", postData, function (s, data) {
                var i;
                for (i = 1; i <= data.message; i++) {
                    allOption = '<option value="' + i + '">Round-' + i + '</option>';
                    $('#round').append(allOption);
                }
                $('#round').selectpicker('refresh');
            }, showErrorMsgInMsgBox);
        }
    });

    /*********************************/
    $(document).on("keypress", ".mask_ph ,.aqs_ph", function (e) {
        var val = $(this).val();
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {

            //display error message        
            $("#errmsg").html("Digits Only").show().fadeOut("slow");
            return false;
        }
        if ((e.which >= 47 && e.which < 58) && val.length > 14) {

            return false;
        }

    });
    $(document).on("focusout", ".mask_ph ,.aqs_ph", function (e) {
        var val = $(this).val();
        if (val.length < 3) {
            $(this).val('');
        }
    });
    function modalWorkSpace() {
        $('.modal .modal-content .subTabWorkspace').css('max-height', $(window).height() - 150 + 'px');
    }
    modalWorkSpace();
    $(window).resize(function () {
        modalWorkSpace();
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('#scroll').fadeIn();
        } else {
            $('#scroll').fadeOut();
        }
    });
    $('#scroll').click(function () {
        $("html, body").animate({scrollTop: 0}, 600);
        return false;
    });
    $('[disabled] .vtip').removeClass('vtip');
    $('[disabled] a,[disabled] span').css('cursor', 'not-allowed');
    $('[disabled] a,[disabled] i').attr('title', '');
    $('[disabled] a').attr('href', '');
    $('[disabled] a').on('click', function (e) {
        e.preventDefault();
    });
    logger.disableLogger();
    logger.enableLogger();
    var arrFilter = document.location.search.slice(1).split('&');
    for (var temp = 0; temp < arrFilter.length; temp++)
        if (arrFilter[temp].indexOf("filter") >= 0)
            isFilter = (((arrFilter[temp]).split("="))[1]) == 1 ? 1 : 0;
    //if user comes back via clicking chevron, filter is retained
    if (isFilter) {
        var f = $(document).find('.filters-bar');
        $(f).find(".ajaxFilter").each(function (i, e) {
            var n = $(e).attr("name");
            if (sessionStorage.getItem("pFilter_" + n) !== null)
                $(e).val(sessionStorage.getItem("pFilter_" + n)).data("value", sessionStorage.getItem("pFilter_" + n));

        });

        filterByAjax($(document).find('.filters-bar'), sessionStorage.getItem("pFilter_page"));
    }

    if ($.isFunction($(document).datetimepicker)) {
        $('.datePicker').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false});
        $('.date-Picker').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false});
    }

    $(".page-loading-class").removeClass('page-loading-class').find(".page-loading").removeClass("page-loading");

    $(document).on("click", ".unlinkClient", function () {
        apiCall(this, "removeClientFromNetwork", {"client_id": $(this).data("id"), "network_id": $(this).data("nid"), "token": getToken()}, function (s, data) {
            var nid = $(s).data("nid");
            $(s).parents("tr").first().remove();
            $("td#clientCountFor-" + nid).each(function () {
                $(this).text($(this).text() - 1);
            });
            if ($(".network-list").length)
                filterByAjax($(".network-list"));
        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });
    $(document).on("click", ".unlinkClientFromProvince", function () {
        apiCall(this, "removeClientFromProvince", {"client_id": $(this).data("id"), "province_id": $(this).data("pid"), "token": getToken()}, function (s, data) {
            var pid = $(s).data("pid");
            $(s).parents("tr").first().remove();
            $("#clientInProvinceCountFor-" + pid).text($("#clientInProvinceCountFor-" + pid).text() - 1);
            if ($(".network-list").length)
                filterByAjax($(".network-list"));
        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });
   
    $(document).on("click", ".clientSelected", function () {
        var cid = $(this).data('id');
        var contnr = $(this).parents(".filterByAjax.client-list").data('for') != undefined ? "#" + $(this).parents(".filterByAjax.client-list").data('for') + " " : "";
        $(contnr + "#selected_client_id").val(cid);
        $(contnr + "#selected_client_name").html($(this).parents("tr").first().find(".client_name").text());
        $(contnr + "#selectClientBtn").text("Change");
        if ($(contnr + ".school_admin_id").length > 0) {
            var aDd = $(contnr + ".school_admin_id");
            aDd.find("option").next().remove();
            var aDd1 = $(contnr + ".student_round");
            aDd1.find("option").next().remove();
            if (cid > 0) {
                apiCall(this, "getSchoolAdmins", {"token": getToken(), "client_id": cid}, function (s, data) {
                    addOptions(aDd, data.schoolAdmins, 'user_id', 'name')
                }, showErrorMsgInMsgBox);

                apiCall(this, "getrounds", {"token": getToken(), "client_id": cid}, function (s, data) {
                    addOptionsDisabled(aDd1, data.rounds, 'aqs_round', 'aqs_round', data.roundsUnused)
                }, showErrorMsgInMsgBox);
            }
        }
        $(this).parents(".modal").modal("hide");
        $("body").trigger('dataChanged');
    });

    $(document).on('keyup', '#search_file', function (e) {

        var searchValue = $("#search_file").val();
        if (searchValue.length >= 3) {
            postData = "page=1&search_val= " + searchValue + "&token:" + getToken();
            var querystring = '';
            ajaxCall('#search_file_form', 'resource', 'searchResourceFiles', postData, querystring, function (s, data) {
                $("#resourceData").html(data.content);
            }, showErrorMsgInMsgBox);
        }
    })



    /***************************user type admin*********************************/
    /**************************************for create user starts***********************/
    // on change user type
    $(document).on("change", "#create_user_form #user_type", function () {
        var contnr = $(this).parents('form').first();
        var usertype_id = $(this).val();

        if (usertype_id != '' && usertype_id !== null && usertype_id !== undefined) {
            $('#errors').hide();
            var postData = "usertype_id=" + usertype_id + "&token=" + getToken();

            if (usertype_id == 1) {
                apiCall(this, "getState", postData, function (s, data) {
                    /****************Generic Note Ends*****************/
                    //data.message.val="hsdfsdh";

                    if (usertype_id == 1) {


                        /*refresh values*/
                        $("#states .state-list-dropdown").val("");
                        /*refresh values*/

                        /************ON CHANGE DESTOY ALL SELECTBOX******************/
                        $(contnr).find("#zones").hide();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $("#blocks .block-list-dropdown").multiselect("destroy");
                        $("#clusters .cluster-list-dropdown").multiselect("destroy");
                        /************************************************************/

                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $(contnr).find("#school").hide();
                        $(contnr).find("#school_level_role").hide();
                        $(contnr).find("#states").show();
                        $(contnr).find("#zones").hide();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                        $(contnr).find("#zone_level_role").hide();
                        $(contnr).find("#block_level_role").hide();
                        $(contnr).find("#cluster_level_role").hide();
                        $(contnr).find("#state_level_role").show();
                        $("#create_user_form #scl_cluster").removeAttr("required", "required");
                        $("#create_user_form #scl_block").removeAttr("required", "required");
                        $("#create_user_form #scl_zone").removeAttr("required", "required");
                        $("#create_user_form #scl_state").attr("required", "required");
                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");
                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/


                    } else {
                        $(contnr).find("#states").hide();
                    }

                }, showErrorMsgInMsgBox);
            } else if (usertype_id == 2) {
                apiCall(this, "getState", postData, function (s, data) {
                    if (usertype_id == 2) {

                        /*refresh values*/
                        $("#states .state-list-dropdown").val("");
                        $(contnr).find("#zones").hide();
                        $("#zones .zone-list-dropdown").val("");
                        $(contnr).find("#blocks").hide();
                        $("#blocks .block-list-dropdown").val("");
                        $(contnr).find("#clusters").hide();
                        $("#clusters .cluster-list-dropdown").val("");
                        /*refresh values*/

                        /************ON CHANGE DESTOY ALL SELECTBOX******************/
                        $(contnr).find("#zones").hide();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $("#blocks .block-list-dropdown").multiselect("destroy");
                        $("#clusters .cluster-list-dropdown").multiselect("destroy");
                        /************************************************************/

                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $(contnr).find("#states").show();
                        var aDd = $(contnr).find("#zones .zone-list-dropdown");
                        $(contnr).find("#school").hide();
                        $(contnr).find("#school_level_role").hide();
                        $(contnr).find("#state_level_role").hide();
                        $(contnr).find("#block_level_role").hide();
                        $(contnr).find("#cluster_level_role").hide();
                        $(contnr).find("#zone_level_role").show();
                        $("#create_user_form #scl_cluster").removeAttr("required", "required");
                        $("#create_user_form #scl_block").removeAttr("required", "required");
                        $("#create_user_form #scl_zone").attr("required", "required");
                        $("#create_user_form #scl_state").attr("required", "required");


                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");
                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/

                    } else {
                        $(contnr).find("#states").hide();

                    }

                }, showErrorMsgInMsgBox);

            } else if (usertype_id == 3) {




                apiCall(this, "getState", postData, function (s, data) {
                    if (usertype_id == 3) {

                        /*refresh values*/
                        $("#states .state-list-dropdown").val("");
                        /*refresh values*/

                        /************ON CHANGE DESTOY ALL SELECTBOX******************/
                        $(contnr).find("#zones").hide();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $("#blocks .block-list-dropdown").multiselect("destroy");
                        $("#clusters .cluster-list-dropdown").multiselect("destroy");
                        /************************************************************/

                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $(contnr).find("#states").show();
                        var aDd = $(contnr).find("#zones .zone-list-dropdown");
                        $(contnr).find("#school").hide();
                        $(contnr).find("#school_level_role").hide();
                        $(contnr).find("#state_level_role").hide();
                        $(contnr).find("#block_level_role").show();
                        $(contnr).find("#cluster_level_role").hide();
                        $(contnr).find("#zone_level_role").hide();
                        $(contnr).find("#clusters").hide();
                        $("#create_user_form #scl_cluster").removeAttr("required", "required");
                        $("#create_user_form #scl_block").attr("required", "required");
                        $("#create_user_form #scl_zone").attr("required", "required");
                        $("#create_user_form #scl_state").attr("required", "required");


                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/
                    } else {
                        $(contnr).find("#states").hide();
                    }
                }, showErrorMsgInMsgBox);
            } else if (usertype_id == 4) {
                apiCall(this, "getState", postData, function (s, data) {
                    $("#states .state-list-dropdown").val("");
                    /************ON CHANGE DESTOY ALL SELECTBOX******************/
                    $(contnr).find("#zones").hide();
                    $(contnr).find("#blocks").hide();
                    $(contnr).find("#clusters").hide();
                    $("#zones .zone-list-dropdown").multiselect("destroy");
                    $("#blocks .block-list-dropdown").multiselect("destroy");
                    $("#clusters .cluster-list-dropdown").multiselect("destroy");
                    /************************************************************/
                    $(contnr).find("#states").show();
                    $(contnr).find("#school").hide();
                    $(contnr).find("#school_level_role").hide();
                    $(contnr).find("#state_level_role").hide();
                    $(contnr).find("#block_level_role").hide();
                    $(contnr).find("#cluster_level_role").show();
                    $(contnr).find("#zone_level_role").hide();


                    $("#create_user_form #scl_cluster").attr("required", "required");
                    $("#create_user_form #scl_block").attr("required", "required");
                    $("#create_user_form #scl_zone").attr("required", "required");
                    $("#create_user_form #scl_state").attr("required", "required");


                    /*********Ajax select dropdown***********/
                    var aDd = $(contnr).find("#states .state-list-dropdown");
                    //aDd.find("option").remove();
                    $("#states .state-list-dropdown").multiselect("destroy");

                    aDd.find("option").next().remove();
                    addOptions(aDd, data.message, 'state_id', 'state_name');
                    // alert('hi');
                    $('#states .state-list-dropdown').multiselect({
                        includeSelectAllOption: false,
                        enableFiltering: false,
                        dropUp: false,
                        enableCaseInsensitiveFiltering: false,
                        buttonWidth: '100%',
                        maxHeight: 50,
                        numberDisplayed: 1,
                        templates: {
                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                            ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                        }

                    });
                    /************Ajax select dropdown*********/
                }, showErrorMsgInMsgBox);
            } else if (usertype_id == 5) {

                /*refresh values*/
                $("#states .state-list-dropdown").val("");
                /*refresh values*/
                $(contnr).find("#states").hide();
                $(contnr).find("#state_level_role").hide();
                $(contnr).find("#zones").hide();
                $(contnr).find("#blocks").hide();
                $(contnr).find("#clusters").hide();
                $(contnr).find("#zone_level_role").hide();
                $(contnr).find("#block_level_role").hide();
                $(contnr).find("#cluster_level_role").hide();
                $(contnr).find("#school").show();
                $(contnr).find("#school_level_role").show();

                $("#create_user_form #scl_cluster").removeAttr("required", "required");
                $("#create_user_form #scl_block").removeAttr("required", "required");
                $("#create_user_form #scl_zone").removeAttr("required", "required");
                $("#create_user_form #scl_state").removeAttr("required", "required");

            }
        } else {
        }


    });

    //on change of state 
    $(document).on("change", "#create_user_form #scl_state", function () {
        var contnr = $(this).parents('form').first();
        var state_id = $(this).val();//alert($("#user_type").val());
        var user_type_curID = $("#create_user_form #user_type").val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {
            $('#errors').hide();
            var postData = "state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
            apiCall(this, "getZonesInStateUser", postData, function (s, data) {
                var aDd = $(contnr).find("#zones .zone-list-dropdown");
                aDd.find("option").remove();
                $("#zones .zone-list-dropdown").multiselect("destroy");

                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'zone_id', 'zone_name');
                $('#zones .zone-list-dropdown').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: false,
                    dropUp: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 50,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                }); //alert(user_type_curID);
                if (user_type_curID == 1) {
                    $(contnr).find("#zones").hide();
                } else if (user_type_curID == 2 || user_type_curID == 3 || user_type_curID == 4) {//alert('hi');
                    $(contnr).find("#zones").show();
                }
            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });

    //on change of zone 
    $(document).on("change", "#create_user_form #scl_zone", function () {
        var contnr = $(this).parents('form').first();
        var state_id = $("#create_user_form #scl_state").val();
        var zone_id = $(this).val();
        var user_type_curID = $("#create_user_form #user_type").val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {//alert('arti');
            $('#errors').hide();
            var postData = "zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();

            apiCall(this, "getBlocksInZoneUser", postData, function (s, data) {//alert('hi');
                var aDd = $(contnr).find("#blocks .block-list-dropdown");
                aDd.find("option").remove();
                $('#blocks .block-list-dropdown').multiselect('destroy');

                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'network_id', 'network_name');
                $('#blocks .block-list-dropdown').multiselect({

                    includeSelectAllOption: true,
                    includeSelectAllDivider: false,
                    enableFiltering: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 50,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });
                if (user_type_curID == 3 || user_type_curID == 4) {//alert(user_type_curID);
                    $(contnr).find("#blocks").show();
                }
            }, showErrorMsgInMsgBox);
        } else {

            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });


    //on change of block
    $(document).on("change", "#create_user_form #scl_block", function () {
        var contnr = $(this).parents('form').first();
        var state_id = $("#create_user_form #scl_state").val();
        var zone_id = $("#create_user_form #scl_zone").val();
        var block_id = $(this).val();
        var user_type_curID = $("#create_user_form #user_type").val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {//alert('arti');
            $('#errors').hide();
            var postData = "block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
            apiCall(this, "getClustersInBlockUser", postData, function (s, data) {//alert('hi');
                var aDd = $(contnr).find("#clusters .cluster-list-dropdown");
                aDd.find("option").remove();
                $('#clusters .cluster-list-dropdown').multiselect('destroy');
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $('#clusters .cluster-list-dropdown').multiselect({

                    includeSelectAllOption: true,
                    includeSelectAllDivider: false,
                    enableFiltering: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 50,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });

                if (user_type_curID == 4) {
                    $(contnr).find("#clusters").show();
                }

            }, showErrorMsgInMsgBox);
        }
    });
    /**************************************for create user ends***********************/
    $(document).on("change", "#update_user_form #user_type", function () {
        var contnr = $(this).parents('form').first();
        var usertype_id = $(this).val();
        var userID = $("#update_user_form #userID").val();
        if (usertype_id != '' && usertype_id !== null && usertype_id !== undefined) {
            $('#errors').hide();
            var postData = "userID=" + userID + "&usertype_id=" + usertype_id + "&token=" + getToken();

            if (usertype_id == 1) {
                apiCall(this, "getStateEdit", postData, function (s, data) {
                    if (usertype_id == 1) {
                        var aDd = $(contnr).find("#states .state-list-dropdown");

                        if (usertype_id != data.selecteduserTypeId) {

                            $(contnr).find("#scl_state").val('');
                            $(contnr).find("#states").show();
                            $(contnr).find("#zones").hide();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find("#clusters").hide();
                            $(contnr).find("#school").hide();
                        } else if (usertype_id == data.selecteduserTypeId) {

                            $(contnr).find("#school").hide();
                            $(contnr).find("#states").show();
                            $(contnr).find("#zones").hide();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find("#clusters").hide();
                        }

                        $(contnr).find("#zone_level_role").hide();
                        $(contnr).find("#block_level_role").hide();
                        $(contnr).find("#cluster_level_role").hide();
                        $(contnr).find("#state_level_role").show();
                        $(contnr).find("#school_level_role").hide();

                        $("#update_user_form #scl_cluster").removeAttr("required", "required");
                        $("#update_user_form #scl_block").removeAttr("required", "required");
                        $("#update_user_form #scl_zone").removeAttr("required", "required");
                        $("#update_user_form #scl_state").attr("required", "required");
                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        if (usertype_id == data.selecteduserTypeId) {//alert(data.selectedstateId);
                            $('#states .state-list-dropdown').val(data.selectedstateId);
                        }
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/
                    } else {
                        $(contnr).find("#states").hide();
                    }

                }, showErrorMsgInMsgBox);
            } else if (usertype_id == 2) {
                apiCall(this, "getStateEdit", postData, function (s, data) {
                    if (usertype_id == 2) {

                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $(contnr).find("#states").show();
                        var aDd = $(contnr).find("#zones .zone-list-dropdown");


                        if (usertype_id != data.selecteduserTypeId) {
                            $(contnr).find("#scl_state").val('');
                            $(contnr).find('#states').show();
                            $(contnr).find("#school").hide();
                            $(contnr).find("#zones").hide();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find('#clusters').hide();
                        } else if (usertype_id == data.selecteduserTypeId) {

                            $(contnr).find('#states').show();
                            $(contnr).find("#school").hide();
                            $(contnr).find("#school_level_role").hide();
                            $(contnr).find("#zones").show();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find('#clusters').hide();
                        }

                        $(contnr).find("#state_level_role").hide();
                        $(contnr).find("#block_level_role").hide();
                        $(contnr).find("#cluster_level_role").hide();
                        $(contnr).find("#zone_level_role").show();
                        $(contnr).find("#school_level_role").hide();
                        $("#update_user_form #scl_cluster").removeAttr("required", "required");
                        $("#update_user_form #scl_block").removeAttr("required", "required");
                        $("#update_user_form #scl_zone").attr("required", "required");
                        $("#update_user_form #scl_state").attr("required", "required");


                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        if (usertype_id == data.selecteduserTypeId) {//alert(data.selectedstateId);
                            $('#states .state-list-dropdown').val(data.selectedstateId);
                        }
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/


                    } else {
                        $(contnr).find("#states").hide();

                    }


                    var state_id = [data.selectedstateId];
                    var zone_id = [data.selectedZones];//alert(zone_id);
                    /**************************Retain values of zone start*************************/

                    var postData = "userID=" + userID + "&state_id=" + state_id + "&usertype_id=" + usertype_id + "&token=" + getToken();
                    apiCall(this, "getZonesInStateUserEdit", postData, function (s, data) {

                        var aDd = $(contnr).find("#zones .zone-list-dropdown");
                        aDd.find("option").remove();
                        addOptions(aDd, data.message, 'zone_id', 'zone_name');
                        $("#zones .zone-list-dropdown").val(data.selectedZones);
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $('#zones .zone-list-dropdown').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                    }, showErrorMsgInMsgBox);
                    /**************************Retain values of zone end*************************/


                }, showErrorMsgInMsgBox);

            } else if (usertype_id == 3) {

                apiCall(this, "getStateEdit", postData, function (s, data) {
                    if (usertype_id == 3) {
                        if (usertype_id != data.selecteduserTypeId) {//alert("hr");
                            $(contnr).find("#scl_state").val('');
                            $(contnr).find('#states').show();
                            $(contnr).find("#zones").hide();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find("#clusters").hide();
                            $(contnr).find("#school").hide();

                        } else if (usertype_id == data.selecteduserTypeId) {//alert("helllo");
                            $(contnr).find("#zones").show();
                            $(contnr).find("#blocks").show();
                            $(contnr).find("#school").hide();
                            $(contnr).find("#clusters").hide();
                        }

                        $(contnr).find("#block_level_role").show();
                        $(contnr).find("#state_level_role").hide();
                        $(contnr).find("#zone_level_role").hide();
                        $(contnr).find("#school_level_role").hide();
                        $(contnr).find("#cluster_level_role").hide();
                        $("#update_user_form #scl_cluster").removeAttr("required", "required");
                        $("#update_user_form #scl_block").attr("required", "required");
                        $("#update_user_form #scl_zone").attr("required", "required");
                        $("#update_user_form #scl_state").attr("required", "required");

                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');

                        if (usertype_id == data.selecteduserTypeId) {//alert(data.selectedstateId);
                            $('#states .state-list-dropdown').val(data.selectedstateId);
                        }
                        // alert('hi');
                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/


                    } else {
                        $(contnr).find("#states").hide();

                    }
                    var state_id = [data.selectedstateId];
                    var zone_id = [data.selectedZones];//alert(zone_id);
                    /**************************Retain values of zone start*************************/

                    var postData = "userID=" + userID + "&state_id=" + state_id + "&usertype_id=" + usertype_id + "&token=" + getToken();
                    apiCall(this, "getZonesInStateUserEdit", postData, function (s, data) {

                        var aDd = $(contnr).find("#zones .zone-list-dropdown");
                        aDd.find("option").remove();
                        addOptions(aDd, data.message, 'zone_id', 'zone_name');
                        $("#zones .zone-list-dropdown").val(data.selectedZones);
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $('#zones .zone-list-dropdown').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                    }, showErrorMsgInMsgBox);
                    /**************************Retain values of zone end*************************/

                    /**************************Retain values of block start*************************/
                    var postData = "userID=" + userID + "&state_id=" + state_id + "&zone_id=" + zone_id + "&usertype_id=" + usertype_id + "&token=" + getToken();

                    apiCall(this, "getBlocksInZoneUserEdit", postData, function (s, data) {//alert('hi');
                        var aDd = $(contnr).find("#blocks .block-list-dropdown");

                        aDd.find("option").remove();
                        $('#blocks .block-list-dropdown').multiselect('destroy');

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'network_id', 'network_name');
                        //alert (data.message);

                        $('#blocks .block-list-dropdown').val(data.selectedBlocks);

                        $('#blocks .block-list-dropdown').multiselect({

                            includeSelectAllOption: true,
                            includeSelectAllDivider: false,
                            enableFiltering: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });

                    }, showErrorMsgInMsgBox);
                    /***********************Retain values of block end************************/

                }, showErrorMsgInMsgBox);
                //}


            } else if (usertype_id == 4) {
                apiCall(this, "getStateEdit", postData, function (s, data) {
                    if (usertype_id == 4) {//alert('arti4');
                        if (usertype_id != data.selecteduserTypeId) {  //alert('arti');      
                            $(contnr).find("#scl_state").val('');
                            $(contnr).find('#states').show();
                            $(contnr).find("#zones").hide();
                            $(contnr).find("#blocks").hide();
                            $(contnr).find("#clusters").hide();
                            $(contnr).find("#school").hide();

                        } else if (usertype_id == data.selecteduserTypeId) {
                            $(contnr).find('#states').show();
                            $(contnr).find("#zones").show();
                            $(contnr).find("#blocks").show();
                            $(contnr).find("#clusters").show();
                            $(contnr).find("#school").hide();
                        }

                        $(contnr).find("#state_level_role").hide();
                        $(contnr).find("#block_level_role").hide();
                        $(contnr).find("#zone_level_role").hide();
                        $(contnr).find("#school_level_role").hide();
                        $(contnr).find("#cluster_level_role").show();

                        $("#update_user_form #scl_cluster").attr("required", "required");
                        $("#update_user_form #scl_block").attr("required", "required");
                        $("#update_user_form #scl_zone").attr("required", "required");
                        $("#update_user_form #scl_state").attr("required", "required");

                        /*********Ajax select dropdown***********/
                        var aDd = $(contnr).find("#states .state-list-dropdown");
                        $("#states .state-list-dropdown").multiselect("destroy");
                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'state_id', 'state_name');
                        if (usertype_id == data.selecteduserTypeId) {//alert(data.selectedstateId);
                            $('#states .state-list-dropdown').val(data.selectedstateId);
                        }

                        $('#states .state-list-dropdown').multiselect({
                            includeSelectAllOption: false,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                        /************Ajax select dropdown*********/


                    } else {
                        $(contnr).find("#states").hide();

                    }
                    var state_id = [data.selectedstateId];
                    var zone_id = [data.selectedZones];
                    var block_id = [data.selectedBlocks];
                    /**************************Retain values of zone start*************************/
                    var postData = "userID=" + userID + "&state_id=" + state_id + "&usertype_id=" + usertype_id + "&token=" + getToken();
                    apiCall(this, "getZonesInStateUserEdit", postData, function (s, data) {
                        var aDd = $(contnr).find("#zones .zone-list-dropdown");
                        aDd.find("option").remove();
                        addOptions(aDd, data.message, 'zone_id', 'zone_name');
                        $("#zones .zone-list-dropdown").val(data.selectedZones);
                        $("#zones .zone-list-dropdown").multiselect("destroy");
                        $('#zones .zone-list-dropdown').multiselect({
                            includeSelectAllOption: true,
                            enableFiltering: false,
                            dropUp: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });
                    }, showErrorMsgInMsgBox);
                    /**************************Retain values of zone end*************************/

                    /**************************Retain values of block start*************************/
                    var postData = "userID=" + userID + "&state_id=" + state_id + "&zone_id=" + zone_id + "&usertype_id=" + usertype_id + "&token=" + getToken();

                    apiCall(this, "getBlocksInZoneUserEdit", postData, function (s, data) {//alert('hi');
                        var aDd = $(contnr).find("#blocks .block-list-dropdown");

                        aDd.find("option").remove();
                        $('#blocks .block-list-dropdown').multiselect('destroy');

                        aDd.find("option").next().remove();
                        addOptions(aDd, data.message, 'network_id', 'network_name');
                        //alert (data.message);

                        $('#blocks .block-list-dropdown').val(data.selectedBlocks);

                        $('#blocks .block-list-dropdown').multiselect({

                            includeSelectAllOption: true,
                            includeSelectAllDivider: false,
                            enableFiltering: false,
                            enableCaseInsensitiveFiltering: false,
                            buttonWidth: '100%',
                            maxHeight: 50,
                            numberDisplayed: 1,
                            templates: {
                                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                            }

                        });

                    }, showErrorMsgInMsgBox);
                    /***********************Retain values of block end************************/

                    /************************** Retain values of cluster start *************************/

                    var postData = "userID=" + userID + "&block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + usertype_id + "&token=" + getToken();

                    apiCall(this, "getClustersInBlockUserEdit", postData, function (s, data) {
                        if (data.message != null) {
                            var aDd = $(contnr).find("#clusters .cluster-list-dropdown");

                            aDd.find("option").remove();
                            $('#clusters .cluster-list-dropdown').multiselect('destroy');

                            aDd.find("option").next().remove();
                            addOptions(aDd, data.message, 'province_id', 'province_name');

                            $('#clusters .cluster-list-dropdown').val(data.selectedClusters);

                            $('#clusters .cluster-list-dropdown').multiselect({

                                includeSelectAllOption: true,
                                includeSelectAllDivider: false,
                                enableFiltering: false,
                                enableCaseInsensitiveFiltering: false,
                                buttonWidth: '100%',
                                maxHeight: 50,
                                numberDisplayed: 1,
                                templates: {
                                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                    ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                }

                            });
                        }

                    }, showErrorMsgInMsgBox);
                    /************************** Retain values of cluster end *************************/

                }, showErrorMsgInMsgBox);
            } else if (usertype_id == 5) {
                $(contnr).find("#states").hide();
                $(contnr).find("#state_level_role").hide();
                $(contnr).find("#zones").hide();
                $(contnr).find("#blocks").hide();
                $(contnr).find("#clusters").hide();
                $(contnr).find("#zone_level_role").hide();
                $(contnr).find("#block_level_role").hide();
                $(contnr).find("#cluster_level_role").hide();
                $(contnr).find("#school").show();
                $(contnr).find("#school_level_role").show();

                $("#update_user_form #scl_cluster").removeAttr("required", "required");
                $("#update_user_form #scl_block").removeAttr("required", "required");
                $("#update_user_form #scl_zone").removeAttr("required", "required");
                $("#update_user_form #scl_state").removeAttr("required", "required");
            }
        } else {
        }


    });

    //on change of state 
    $(document).on("change", "#update_user_form #scl_state", function () {
        var contnr = $(this).parents('form').first();
        var state_id = $(this).val();
        var user_type_curID = $("#update_user_form #user_type").val();
        var userID = $("#update_user_form #userID").val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {
            $('#errors').hide();
            var postData = "userID=" + userID + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
            apiCall(this, "getZonesInStateUserEdit", postData, function (s, data) {

                var aDd = $(contnr).find("#zones .zone-list-dropdown");
                aDd.find("option").remove();
                addOptions(aDd, data.message, 'zone_id', 'zone_name');
                $("#zones .zone-list-dropdown").val(data.selectedZones);
                $("#zones .zone-list-dropdown").multiselect("destroy");
                $('#zones .zone-list-dropdown').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: false,
                    dropUp: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 50,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });
                if (user_type_curID == 1) {

                    $(contnr).find("#zones").hide();
                    $(contnr).find("#blocks").hide();
                    $(contnr).find("#clusters").hide();

                    $("#update_user_form #scl_cluster").removeAttr("required", "required");
                    $("#update_user_form #scl_block").removeAttr("required", "required");
                    $("#update_user_form #scl_zone").removeAttr("required", "required");
                    $("#update_user_form #scl_state").attr("required", "required");

                } else if (user_type_curID == 2) {
                    //alert('hi');
                    $(contnr).find("#zones").show();
                    $(contnr).find("#blocks").hide();
                    $(contnr).find("#clusters").hide();
                    $("#update_user_form #scl_cluster").removeAttr("required", "required");
                    $("#update_user_form #scl_block").removeAttr("required", "required");
                    $("#update_user_form #scl_zone").attr("required", "required");
                    $("#update_user_form #scl_state").attr("required", "required");
                } else if (user_type_curID == 3) {
                    //alert('hi');
                    if (user_type_curID != data.selecteduserTypeId) {
                        $(contnr).find("#zones").show();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                    } else if (user_type_curID == data.selecteduserTypeId) {
                        $(contnr).find("#zones").show();
                        $(contnr).find("#blocks").show();
                        $(contnr).find("#clusters").hide();
                    }

                    $("#update_user_form #scl_cluster").removeAttr("required", "required");
                    $("#update_user_form #scl_block").attr("required", "required");
                    $("#update_user_form #scl_zone").attr("required", "required");
                    $("#update_user_form #scl_state").attr("required", "required");

                    if (state_id != '' && state_id !== null && state_id !== undefined) {
                        $('#errors').hide();

                        var zone_id = [data.selectedZones];
                        var postData = "userID=" + userID + "&state_id=" + state_id + "&zone_id=" + zone_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();

                        apiCall(this, "getBlocksInZoneUserEdit", postData, function (s, data) {//alert('hi');
                            var aDd = $(contnr).find("#blocks .block-list-dropdown");

                            aDd.find("option").remove();
                            $('#blocks .block-list-dropdown').multiselect('destroy');

                            aDd.find("option").next().remove();
                            addOptions(aDd, data.message, 'network_id', 'network_name');
                            $('#blocks .block-list-dropdown').val(data.selectedBlocks);

                            $('#blocks .block-list-dropdown').multiselect({

                                includeSelectAllOption: true,
                                includeSelectAllDivider: false,
                                enableFiltering: false,
                                enableCaseInsensitiveFiltering: false,
                                buttonWidth: '100%',
                                maxHeight: 50,
                                numberDisplayed: 1,
                                templates: {
                                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                    ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                }

                            });

                        }, showErrorMsgInMsgBox);
                    }

                } else if (user_type_curID == 4) {

                    if (user_type_curID != data.selecteduserTypeId) {//alert('hi');
                        $(contnr).find("#zones").show();
                        $(contnr).find("#blocks").hide();
                        $(contnr).find("#clusters").hide();
                    } else if (user_type_curID == data.selecteduserTypeId) {
                        $(contnr).find("#zones").show();
                        $(contnr).find("#blocks").show();
                        $(contnr).find("#clusters").show();
                    }
                    $("#update_user_form #scl_cluster").attr("required", "required");
                    $("#update_user_form #scl_block").attr("required", "required");
                    $("#update_user_form #scl_zone").attr("required", "required");
                    $("#update_user_form #scl_state").attr("required", "required");
                    if (state_id != '' && state_id !== null && state_id !== undefined) {
                        $('#errors').hide();
                        var zone_id = [data.selectedZones];
                        var postData = "userID=" + userID + "&state_id=" + state_id + "&zone_id=" + zone_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
                        apiCall(this, "getBlocksInZoneUserEdit", postData, function (s, data) {//alert('hi');
                            var aDd = $(contnr).find("#blocks .block-list-dropdown");
                            aDd.find("option").remove();
                            $('#blocks .block-list-dropdown').multiselect('destroy');
                            aDd.find("option").next().remove();
                            addOptions(aDd, data.message, 'network_id', 'network_name');
                            $('#blocks .block-list-dropdown').val(data.selectedBlocks);
                            $('#blocks .block-list-dropdown').multiselect({
                                includeSelectAllOption: true,
                                includeSelectAllDivider: false,
                                enableFiltering: false,
                                enableCaseInsensitiveFiltering: false,
                                buttonWidth: '100%',
                                maxHeight: 50,
                                numberDisplayed: 1,
                                templates: {
                                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                    ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                }

                            });
                            var block_id = [data.selectedBlocks];
                            var postData = "userID=" + userID + "&block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
                            apiCall(this, "getClustersInBlockUserEdit", postData, function (s, data) {//alert('hi');
                                var aDd = $(contnr).find("#clusters .cluster-list-dropdown");

                                aDd.find("option").remove();
                                $('#clusters .cluster-list-dropdown').multiselect('destroy');

                                aDd.find("option").next().remove();
                                addOptions(aDd, data.message, 'province_id', 'province_name');

                                $('#clusters .cluster-list-dropdown').val(data.selectedClusters);

                                $('#clusters .cluster-list-dropdown').multiselect({

                                    includeSelectAllOption: true,
                                    includeSelectAllDivider: false,
                                    enableFiltering: false,
                                    enableCaseInsensitiveFiltering: false,
                                    buttonWidth: '100%',
                                    maxHeight: 50,
                                    numberDisplayed: 1,
                                    templates: {
                                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                    }

                                });

                            }, showErrorMsgInMsgBox);

                        }, showErrorMsgInMsgBox);

                    }

                }

            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a state');
            $('#errors').show();
        }

    });

    $(document).on("change", "#update_user_form #scl_zone", function () {

        var contnr = $(this).parents('form').first();
        var state_id = $("#update_user_form #scl_state").val();
        var zone_id = $(this).val();
        var user_type_curID = $("#update_user_form #user_type").val();
        var userID = $("#update_user_form #userID").val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {//alert('arti');
            $('#errors').hide();
            var postData = "userID=" + userID + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();
            if (user_type_curID == 3 || user_type_curID == 4) {
                apiCall(this, "getBlocksInZoneUserEdit", postData, function (s, data) {//alert('hi');
                    var aDd = $(contnr).find("#blocks .block-list-dropdown");

                    aDd.find("option").remove();
                    $('#blocks .block-list-dropdown').multiselect('destroy');

                    aDd.find("option").next().remove();
                    addOptions(aDd, data.message, 'network_id', 'network_name');
                    $('#blocks .block-list-dropdown').val(data.selectedBlocks);
                    $('#blocks .block-list-dropdown').multiselect({

                        includeSelectAllOption: true,
                        includeSelectAllDivider: false,
                        enableFiltering: false,
                        enableCaseInsensitiveFiltering: false,
                        buttonWidth: '100%',
                        maxHeight: 50,
                        numberDisplayed: 1,
                        templates: {
                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                            ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                        }

                    });

                    if (user_type_curID == 3) {
                        $(contnr).find("#blocks").show();
                    } else if (user_type_curID == 4) {
                        $(contnr).find("#blocks").show();
                        var block_id = [data.selectedBlocks];
                        if (state_id != '' && state_id !== null && state_id !== undefined) {//alert('arti');
                            $('#errors').hide();
                            var postData = "userID=" + userID + "&block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();

                            apiCall(this, "getClustersInBlockUserEdit", postData, function (s, data) {//alert('hi');
                                if (data.message != null) {//alert(data.message);
                                    var aDd = $(contnr).find("#clusters .cluster-list-dropdown");

                                    aDd.find("option").remove();
                                    $('#clusters .cluster-list-dropdown').multiselect('destroy');

                                    aDd.find("option").next().remove();
                                    addOptions(aDd, data.message, 'province_id', 'province_name');

                                    $('#clusters .cluster-list-dropdown').val(data.selectedClusters);

                                    $('#clusters .cluster-list-dropdown').multiselect({

                                        includeSelectAllOption: true,
                                        includeSelectAllDivider: false,
                                        enableFiltering: false,
                                        enableCaseInsensitiveFiltering: false,
                                        buttonWidth: '100%',
                                        maxHeight: 50,
                                        numberDisplayed: 1,
                                        templates: {
                                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                            ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                        }

                                    });
                                }
                            }, showErrorMsgInMsgBox);
                        }
                    }
                }, showErrorMsgInMsgBox);
            }

            if (user_type_curID == 1) {
                $("#update_user_form #scl_cluster").removeAttr("required", "required");
                $("#update_user_form #scl_block").removeAttr("required", "required");
                $("#update_user_form #scl_zone").removeAttr("required", "required");
                $("#update_user_form #scl_state").attr("required", "required");
            } else if (user_type_curID == 2) {
                $("#update_user_form #scl_cluster").removeAttr("required", "required");
                $("#update_user_form #scl_block").removeAttr("required", "required");
                $("#update_user_form #scl_zone").attr("required", "required");
                $("#update_user_form #scl_state").attr("required", "required");
            } else if (user_type_curID == 3) {
                $(contnr).find("#blocks").show();
                $("#update_user_form #scl_cluster").removeAttr("required", "required");
                $("#update_user_form #scl_block").attr("required", "required");
                $("#update_user_form #scl_zone").attr("required", "required");
                $("#update_user_form #scl_state").attr("required", "required");
            } else if (user_type_curID == 4) {
                $(contnr).find("#blocks").show();
                $("#update_user_form #scl_cluster").attr("required", "required");
                $("#update_user_form #scl_block").attr("required", "required");
                $("#update_user_form #scl_zone").attr("required", "required");
                $("#update_user_form #scl_state").attr("required", "required");
            }
        } else {

            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });



    $(document).on("change", "#update_user_form #scl_block", function () {

        var contnr = $(this).parents('form').first();
        var state_id = $("#update_user_form #scl_state").val();
        var zone_id = $("#update_user_form #scl_zone").val();
        var block_id = $("#update_user_form #scl_block").val();
        //var block_id = $(this).val();
        var user_type_curID = $("#update_user_form #user_type").val();
        var userID = $("#update_user_form #userID").val();

        if (state_id != '' && state_id !== null && state_id !== undefined) {//alert('arti');
            $('#errors').hide();
            var postData = "userID=" + userID + "&block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&usertype_id=" + user_type_curID + "&token=" + getToken();

            apiCall(this, "getClustersInBlockUserEdit", postData, function (s, data) {//alert('hi');
                var aDd = $(contnr).find("#clusters .cluster-list-dropdown");

                aDd.find("option").remove();
                $('#clusters .cluster-list-dropdown').multiselect('destroy');

                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');

                $('#clusters .cluster-list-dropdown').val(data.selectedClusters);

                $('#clusters .cluster-list-dropdown').multiselect({

                    includeSelectAllOption: true,
                    includeSelectAllDivider: false,
                    enableFiltering: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 50,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });

                if (user_type_curID == 4) {

                    $(contnr).find("#clusters").show();
                }

            }, showErrorMsgInMsgBox);
        }


        if (user_type_curID == 1) {

            $("#update_user_form #scl_cluster").removeAttr("required", "required");
            $("#update_user_form #scl_block").removeAttr("required", "required");
            $("#update_user_form #scl_zone").removeAttr("required", "required");
            $("#update_user_form #scl_state").attr("required", "required");
        } else if (user_type_curID == 2) {
            $("#update_user_form #scl_cluster").removeAttr("required", "required");
            $("#update_user_form #scl_block").removeAttr("required", "required");
            $("#update_user_form #scl_zone").attr("required", "required");
            $("#update_user_form #scl_state").attr("required", "required");
        } else if (user_type_curID == 3) {
            $("#update_user_form #scl_cluster").removeAttr("required", "required");
            $("#update_user_form #scl_block").attr("required", "required");
            $("#update_user_form #scl_zone").attr("required", "required");
            $("#update_user_form #scl_state").attr("required", "required");
        } else if (user_type_curID == 4) {
            $(contnr).find("#clusters").show();
            $("#update_user_form #scl_cluster").attr("required", "required");
            $("#update_user_form #scl_block").attr("required", "required");
            $("#update_user_form #scl_zone").attr("required", "required");
            $("#update_user_form #scl_state").attr("required", "required");
        }

    });

    $(document).on("change", "#update_user_form #scl_state", function () {
        $("#update_user_form #scl_state").attr("required", "required");
    });
    $(document).on("change", "#update_user_form #scl_zone", function () {
        $("#update_user_form #scl_zone").attr("required", "required");
    });

    $(document).on("change", "#update_user_form #scl_block", function () {
        $("#update_user_form #scl_block").attr("required", "required");
    });

    $(document).on("change", "#update_user_form #scl_cluster", function () {
        $("#update_user_form #scl_cluster").attr("required", "required");
    });

    /**************************************for update user ends***********************/

    //on change of state 
    $(document).on("change", "#create_network_form #scl_state,#create_school_form #scl_state,#edit_school_form #scl_state, #create_province_form #scl_state", function () {
        var contnr = $(this).parents('form').first();
        var state_id = $(this).val();
        if (state_id != '' && state_id !== null && state_id !== undefined) {
            $('#errors').hide();
            var postData = "state_id=" + state_id + "&token=" + getToken();
            apiCall(this, "getZonesInState", postData, function (s, data) {
                var aDd = $(contnr).find("#zones .zone-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'zone_id', 'zone_name');
                $(contnr).find("#zones").show();
                $(contnr).find("#networks").hide();
                $(contnr).find("#province").hide();
                $(contnr).find("#blocks").hide();
                $(contnr).find("#provinces").hide();
            }, showErrorMsgInMsgBox);
        } else {
            $(contnr).find("#zones").hide();
            $(contnr).find("#blocks").hide();
            $(contnr).find("#networks").hide();
            $(contnr).find("#province").hide();
            $(contnr).find("#provinces").hide();
            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });

    $(document).on("change", "#create_network_form #scl_zone, #create_school_form #scl_zone, #edit_school_form #scl_zone", function () {
        var contnr = $(this).parents('form').first();
        var zone_id = $(this).val();
        var state_id = $(contnr).find("#scl_state option:selected").val();

        if (zone_id != '' && zone_id !== null && zone_id !== undefined) {
            $('#errors').hide();
            var postData = "zone_id=" + zone_id + "&state_id=" + state_id + "&token=" + getToken();
            apiCall(this, "getBlocksInZone", postData, function (s, data) {
                var aDd = $(contnr).find("#blocks .block-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'network_id', 'network_name');
                $(contnr).find("#blocks").show();
                $(contnr).find("#provinces").hide();
            }, showErrorMsgInMsgBox);
        } else {
            $(contnr).find("#blocks").hide();
            $(contnr).find("#provinces").hide();
            $('#errors').html('Please select a Zone');
            $('#errors').show();
        }
    });

    $(document).on("change", "#create_school_form #scl_block, #edit_school_form #scl_block", function () {
        var contnr = $(this).parents('form').first();
        var block_id = $(this).val();
        var zone_id = $(contnr).find("#scl_zone option:selected").val();
        var state_id = $(contnr).find("#scl_state option:selected").val();
        if (block_id && state_id && zone_id && block_id != '' && state_id != '' && zone_id !== null && zone_id !== undefined) {
            $('#errors').hide();
            var postData = "block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&token=" + getToken();
            apiCall(this, "getClusterInZone", postData, function (s, data) {
                var aDd = $(contnr).find("#provinces .province-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $(contnr).find("#blocks").show();
                $(contnr).find("#provinces").show();
            }, showErrorMsgInMsgBox);
        } else {
            $(contnr).find("#provinces").hide();
            $('#errors').html('Please select a Zone');
            $('#errors').show();
        }
    });

    $(document).on("change", "#create_province_form #scl_zone", function () {
        var contnr = $(this).parents('form').first();
        var zone_id = $(this).val();
        var state_id = $(contnr).find("#scl_state option:selected").val();

        if (zone_id != '' && zone_id !== null && zone_id !== undefined) {
            $('#errors').hide();
            var postData = "state_id=" + state_id + "&zone_id=" + zone_id + "&token=" + getToken();
            apiCall(this, "getBlocksInZone", postData, function (s, data) {
                var aDd = $(contnr).find("#blocks .block-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'network_id', 'network_name');
                $(contnr).find("#blocks").show();
                $(contnr).find("#provinces").hide();
            }, showErrorMsgInMsgBox);
        } else {
            $(contnr).find("#blocks").hide();
            $(contnr).find("#provinces").hide();
            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });

    $(document).on("change", "#create_province_form #scl_block", function () {
        var contnr = $(this).parents('form').first();
        var block_id = $(this).val();
        var zone_id = $(contnr).find("#scl_zone option:selected").val();
        var state_id = $(contnr).find("#scl_state option:selected").val();
        // alert(zone_id);
        if (block_id && state_id && zone_id && block_id != '' && state_id != '' && zone_id !== null && zone_id !== undefined) {
            $('#errors').hide();
            var postData = "block_id=" + block_id + "&zone_id=" + zone_id + "&state_id=" + state_id + "&token=" + getToken();
            apiCall(this, "getClusterInZone", postData, function (s, data) {
                var aDd = $(contnr).find("#provinces .province-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $(contnr).find("#blocks").show();
                $(contnr).find("#provinces").show();
            }, showErrorMsgInMsgBox);
        } else {
            //$(contnr).find("#blocks").hide();
            $(contnr).find("#provinces").hide();
            $('#errors').html('Please select a Zone');
            $('#errors').show();
        }

    });




    $(document).on("change", "#edit_school_form #edit_scl_network", function () {
        var contnr = $(this).parents('form').first();
        var network_id = $(this).val();
        if (network_id != '' && network_id !== null && network_id !== undefined) {
            $('#errors').hide();
            var postData = "state_id=" + state_id + "&token=" + getToken();
            apiCall(this, "getNetworksInState", postData, function (s, data) {
                var aDd = $(contnr).find("#networks .network-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'network_id', 'network_name');
                $(contnr).find("#networks").show();
            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a state');
            $('#errors').show();
        }
    });
    $(document).on("change", "#edit_school_form #edit_scl_network", function () {
        alert('dddd');
        var contnr = $(this).parents('form').first();
        var network_id = $(this).val();
        if (network_id != '' && network_id !== null && network_id !== undefined) {
            $('#errors').hide();
            var postData = "network_id=" + network_id + "&token=" + getToken();
            apiCall(this, "getProvincesInNetwork", postData, function (s, data) {
                var aDd = $(contnr).find("#provinces .province-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $(contnr).find("#provinces").show();
            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a zone');
            $('#errors').show();
        }
    });

    //change of diagnostic list
    $(document).on("change", "#create_school_assessment_form #diagnostic_id,#edit_school_assessment_form #diagnostic_id", function () {
        var contnr = $(this).parents('form').first();
        var diagnostic_id = $(this).val();
        if (diagnostic_id != '' && diagnostic_id !== null && diagnostic_id !== undefined) {
            
            $('#errors').hide();
            var postData = "diagnostic_id=" + diagnostic_id + "&token=" + getToken();
            apiCall(this, "getDiagnosticLanguages", postData, function (s, data) {
                var aDd = $(contnr).find("#diagnostic_lang_id");
                aDd.find("option").next().remove();
                addOptions(aDd, data.langDiagnostics, 'language_id', 'language_words');
                $(contnr).find("#diagnostic_lang_id").show();
                if (data.langDiagnostics[0]['diagnostic_type'] == 1) {
                    $("#diagnosticType").val("1");
                } else {
                    $("#diagnosticType").val("0");
                }
            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a diagnostic');
            $('#errors').show();
        }
    });

    $(document).on("click", "#converttopdf", function () {
        apiCall(this, "reportPdf", {"token": getToken()}, function (s, data) {
            console.log("success: " + data)
        }, showErrorMsgInMsgBox);
    })
    $(document).on("change", "#create_web_school_form .haveNetwork, #create_school_form .haveNetwork,#edit_school_form .haveNetwork", function () {
        $(this).parents("form").find(".haveNetwork:checked").val() == 1 ? $(this).parents("form").find("#networks").show().find("select").attr("required", "required").val('') : ($(this).parents("form").find("#networks").hide().find("select").removeAttr("required") && $(this).parents("form").find("#provinces").hide());
    });
    $(document).on("change", "#create_school_form #scl_network,#edit_school_form #edit_scl_network", function () {
        var contnr = $(this).parents('form').first();
        var network_id = $(this).val();
        if (network_id != '' && network_id !== null && network_id !== undefined) {
            $('#errors').hide();
            var postData = "network_id=" + network_id + "&token=" + getToken();
            apiCall(this, "getProvincesInNetwork", postData, function (s, data) {
                var aDd = $(contnr).find("#provinces .province-list-dropdown");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $(contnr).find("#provinces").show();
            }, showErrorMsgInMsgBox);
        } else {
            $('#errors').html('Please select a network');
            $('#errors').show();
        }
    });

    //create a sample for upload school assessment
    //get all schools
    $(document).on("click", " #sampleAssBtn", function () {

        var postData = "sample=1" + "&token=" + getToken();
        apiCall(this, "getDownloadSampleSchoolAssessment", postData, function (s, data) {
            if (data.message != '') {
                file_url = data.site_url + "uploads/sample_csv/sample.xlsx";
                window.open(file_url, '_blank');
            }
        }, showErrorMsgInMsgBox);

    });

    $(document).on("click", " #sampleStuBtn", function () {

        var postData = "gaid=" + $("#gaid").val() + "&sample=1" + "&token=" + getToken();
        apiCall(this, "getDownloadSampleStudentProfile", postData, function (s, data) {
            if (data.message != '') {
                file_url = data.site_url + "uploads/sample_csv/sample_student_profile.xlsx";
                window.open(file_url, '_blank');
            }
        }, showErrorMsgInMsgBox);
    });

    $("#login_popup form").submit(function () {
        postData = $(this).serialize();
        apiCall(this, "login", postData, function (s, data) {
            if (data.confirmstatus == 0) {
                $("#login_popup").find("input[type=email],input[type=password]").hide();
                $(".confirmMsg").show();
                $(".confirmMsg").html(data.errormsg);
                $("#login_popup").find("#loginsubmit").hide();
                $("#login_popup").find("#loginconfirm").show();
                $("#login_popup").find("#actionconfirm").val(1);
            } else {
                setToken(data.token);
                $("#login_popup").find("input[type=email],input[type=password]").show();
                $(".confirmMsg").hide();
                $("#login_popup").find("#loginsubmit").show();
                $("#login_popup").find("#loginconfirm").hide();
                $("#login_popup").find("#actionconfirm").val(0);

                $("#login_popup").find("input[type=email],input[type=password]").val('');
                $("#login_popup_wrap").removeClass("active").trigger("loggedIn");
            }

        }, showErrorMsgInMsgBox);
        return false;
    });

    $("#login_popup #logincancel").click(function () {
        $("#login_popup").find("input[type=email],input[type=password]").show();
        $("#login_popup").find("input[type=email],input[type=password]").val('');
        $(".confirmMsg").hide();
        $("#login_popup").find("#loginsubmit").show();
        $("#login_popup").find("#loginconfirm").hide();
        $("#login_popup").find("#actionconfirm").val(0);
    });



    $(document).on("change", "#create_school_assessment_form .external_assessor_id, #create_school_assessment_form .team_external_assessor_id, #edit_school_assessment_form .external_assessor_id, #edit_school_assessment_form .team_external_assessor_id", function () {
        allAssessors = [];
        var currentSelAssessorId = $(this).val();
        var currentSelAssessorHtmlId = $(this).attr('id');
        var frm = $(this).closest('form').first().attr('id');
        $('.team_row .external_assessor_id, .team_row .team_external_assessor_id ').each(function () {
            $(this).val() != '' ? allAssessors.push($(this).val()) : '';
        });

        //
        $('.team_row').each(function () {
            var currObjVal = $(this).find('.external_assessor_id, .team_external_assessor_id').val();
            var clientId = $(this).find('.external_client_id, .team_external_client_id').attr('id');
            var assessorId = $(this).find('.external_assessor_id, .team_external_assessor_id').attr('id');

            console.log("currentSelAssessorId" + " " + currentSelAssessorId)
            console.log("currObjVal " + currObjVal)
            if (assessorId != currentSelAssessorHtmlId)
                ReloadExternalAssesorTeamMembersListForAssessment('#' + frm, clientId, assessorId, currObjVal);


        });

    });

    //get all facilitator
    $(document).on("change", "#create_school_assessment_form .facilitator_id, #create_school_assessment_form .team_external_facilitator_id, #edit_school_assessment_form .facilitator_id, #edit_school_assessment_form .team_external_facilitator_id", function () {
        allFacilitators = [];
        $('.facilitator_row .facilitator_id, .facilitator_row .team_external_facilitator_id ').each(function () {
            $(this).val() != '' ? allFacilitators.push($(this).val()) : '';
        });

    });

    //Added by Vikas for workshop add
    $(document).on("change", "#create_workshop_form .external_assessor_id, #create_workshop_form .team_facilitator_id, #edit_workshop_form .external_assessor_id, #edit_workshop_form .team_facilitator_id", function () {
        allAssessors = [];
        var currentSelAssessorId = $(this).val();
        var currentSelAssessorHtmlId = $(this).attr('id');
        var frm = $(this).closest('form').first().attr('id');
        $('.team_row .external_assessor_id, .team_row .team_facilitator_id ').each(function () {
            $(this).val() != '' ? allAssessors.push($(this).val()) : '';
        });

        //
        count = 1;
        $('.team_row').each(function () {
            var currObjVal = $(this).find('.external_assessor_id, .team_facilitator_id').val();
            var clientId = $(this).find('.external_client_id, .team_facilitator_client_id').attr('id');
            var assessorId = $(this).find('.external_assessor_id, .team_facilitator_id').attr('id');

            console.log("currentSelAssessorId" + " " + currentSelAssessorId)
            console.log("currObjVal " + currObjVal)
            if (assessorId != currentSelAssessorHtmlId) {
                if (count == 1) {
                    ReloadUsersTeamMembersListForWorkshop('#' + frm, clientId, assessorId, currObjVal);
                } else {
                    ReloadFacilitatorTeamMembersListForWorkshop('#' + frm, clientId, assessorId, currObjVal);
                }
            }

            count++;
        });

    });

    //Added by Vikas for workshop add

    $(document).on("change", "#create_school_assessment_form .internal_client_id", function () {
        loadAssesorListForAssessment($("#create_school_assessment_form"), "internal");
    });

   

    //Added by Vikas for Facilitator Role
    $(document).on("change", "#create_school_assessment_form .facilitator_client_id", function () {
        var frm = $(this).closest('form');
        loadFacilitatorListForAssessment(frm, "facilitator");
    });
    $(document).on("change", "#create_school_assessment_form .team_facilitator_client_id, #edit_school_assessment_form .team_facilitator_client_id", function () {
        var frm = $(this).closest('form');
        ReloadFacilitatorTeamMembersList(frm, $(this).attr('id'), "team_external_facilitator_id" + $(this).attr('id').slice(-1), '');

    });

    
    //Added by Vikas for Facilitator Role
    $(document).on("change", "#create_school_assessment_form .external_client_id, #edit_school_assessment_form .external_client_id", function () {
        var frm = $(this).closest('form');
        loadAssesorListForAssessment(frm, "external");
    });
    //Added by Vikas for Facilitator Role
    $(document).on("click", "#collaborative-step1", function () {
        $("#ctreateSchoolAssessment-step1").show();
        $("#ctreateSchoolAssessment-step1").addClass('active');
        $("#ctreateSchoolAssessment-step2").removeClass('active');
        $("#ctreateSchoolAssessment-step2").hide();

    });

    $(document).on("change", "#create_school_assessment_form .team_external_client_id, #edit_school_assessment_form .team_external_client_id", function () {
        var frm = $(this).closest('form');
        ReloadExternalAssesorTeamMembersListForAssessment(frm, $(this).attr('id'), "team_external_assessor_id" + $(this).attr('id').slice(-1), '');
    });

    //Added by Vikas for Facilitator Role
    $(document).on("change", "#edit_school_assessment_form .facilitator_client_id", function () {
        loadFacilitatorListForAssessment($("#edit_school_assessment_form"), "facilitator");
    });
    
    //new code for creating school review according tap admin on 06-06-2016 by Mohit Kumar
    $(document).on("submit", "#create_school_assessment_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();

        apiCall(this, "createSchoolAssessmentNew", postData, function (s, data) {
            showSuccessMsgInMsgBox(s, data);
            var reviewType = $("#review_type").val();
            if (reviewType != '' && reviewType != undefined && reviewType == 1) {
                $("#collaborative-step1").removeClass('active');
                $("#collaborative-step2").show();
                $("#collaborative-step2").removeClass('disabledTab');
                $('#collaborative-step1').css('pointer-events', 'none')
                $("#collaborative-step2").addClass('active');
                window.location.href = "index.php?isPop=1&controller=assessment&action=editSchoolAssessment&said=" + data.assessment_id + "&tab2=1&assessment_type=1&new=1";
            }
            $(s).find("select").val('');
            $('#create_school_assessment_form .external_assessor_id').each(function (i, k) {
                $(this).find('option').next().remove();
                $('input[type="text"]').val('');
            })
            $('#create_school_assessment_form .internal_assessor_id').each(function (i, k) {
                $(this).find('option').next().remove();
                $('input[type="text"]').val('');
            })

            if ($(".assessment-list").length > 0)
                filterByAjax($(".assessment-list"));
        }, showErrorMsgInMsgBox);
        return false;
    });

    
    //new code for assign kpa to collaborative review
    $(document).on("submit", "#edit-review-kpa", function () {
        postData = $(this).serialize() + "&token=" + getToken();

        var isNewReview = $('.isNewReview').val();
        var assessmentRating = $('.assessmentRating').val();
        var assessmentRatingKpa = $('.assessmentRatingKpa').val();
        if (assessmentRatingKpa >= 1 && assessmentRating > 0 && isNewReview != 1) {
            if (confirm("You will lose rating data if you change assessor KPA")) {
                updateAssessorsKpas(postData);
            }
        } else {
            updateAssessorsKpas(postData);
        }

        return false;
    });


    $(document).on("submit", "#edit_school_assessment_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();
        console.log(postData);
        apiCall(this, "editSchoolAssessment", postData, function (s, data) {
            showSuccessMsgInMsgBox(s, data);
            var reviewType = $("#review_type").val();

            if (reviewType != '' && reviewType != undefined && reviewType != 1) {
                $(s).find("select").val('');
                $('#edit_school_assessment_form .external_assessor_id').each(function (i, k) {
                    $(this).find('option').next().remove();
                    $('input[type="text"]').val('');
                })
                $('#edit_school_assessment_form .internal_assessor_id').each(function (i, k) {
                    $(this).find('option').next().remove();
                    $('input[type="text"]').val('');
                })
                if ($(".assessment-list").length > 0)
                    filterByAjax($(".assessment-list"));
                $("#notification_settings").hide();

            }
        }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#add_school_to_network_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "addSchoolToNetwork", postData, function (s, data) {
            showSuccessMsgInMsgBox(s, data);
            var cnt = $(s).find("select option:selected").length;
            $(s).find("select option:selected").remove();
            $("#schoolsInNetwork tbody").append(data.content);
            $("#clientCountFor-" + data.network_id).html(parseInt($("#clientCountFor-" + data.network_id).text()) + cnt);
            if ($(".network-list").length)
                filterByAjax($(".network-list"));
        }, showErrorMsgInMsgBox);
        return false;
    });
    $(document).on("submit", "#add_school_to_province_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "addSchoolToProvince", postData, function (s, data) {
            showSuccessMsgInMsgBox(s, data);
            var cnt = $(s).find("select option:selected").length;
            $(s).find("select option:selected").remove();
            $("#schoolsInProvince tbody").append(data.content);
            $("#clientInProvinceCountFor-" + data.province_id).html(parseInt($("#clientInProvinceCountFor-" + data.province_id).text()) + cnt);
            if ($(".network-list").length)
                filterByAjax($(".network-list"));
        }, showErrorMsgInMsgBox);
        return false;
    });


    //Get diagonastic list for internal assessor
    $(document).on("change", '#create_school_assessment_form .internal_assessor_id, #edit_school_assessment_form .internal_assessor_id', function () {
        var frm = $(this).closest('form');
        var postData = frm.serialize() + "&token=" + getToken();
        apiCall(this, "getDiagnosticsForInternalAssessor", postData, function (s, data) {
            if (typeof data.hidediagnostics.hidediagnostics != undefined && data.hidediagnostics.hidediagnostics !== '' && data.hidediagnostics.hidediagnostics != null)
            {
                var diagOpt = $(frm).find("[name='diagnostic_id']");
                $(frm).find("[name='diagnostic_id'] option:gt(0)").remove();
                addOptions(diagOpt, data.allDiagnostics, 'diagnostic_id', 'name');
                var opt = $(diagOpt).find("option");
                var len = opt.length;
                var arr = (data.hidediagnostics.hidediagnostics).split(",");
                for (var i = 0; i < len; i++) {
                    if (typeof opt[i].value != undefined && opt[i].value !== '' && opt[i].value != null && $.inArray(opt[i].value, arr) >= 0)
                        $(frm).find("[name='diagnostic_id'] option[value=" + opt[i].value + "]").remove();
                }
                if ($(frm).find("[name='diagnostic_id'] option").length < 2)
                    alert("Sorry! You cannot assign any diagnostic because the user has outstanding reviews.");

            }
        }, function (s, msg) {
            console.log(s);
            console.log(msg)
        });
        return false;
    });


    $(document).on("submit", "#create_user_form", function () {
        $user_typeID = $(this).find("#user_type").val();
        if (!($(this).find("#selected_client_id").val() > 0) && $user_typeID == 5) {
            //alert($user_typeID);
            alert("Please select a school");
        } else if ($(this).find(".pwd").val().length < 6) {
            alert("Minimum 6 character password required");
        } else if ($(this).find(".pwd").val() != $(this).find(".cpwd").val()) {
            alert("Confirm password didn't match");
        } else if ($(this).find('input[type=checkbox]').length > 0 && $(this).find('input[type=checkbox]:checked').length == 0 && $user_typeID == 5) {
            alert("Please select user role");
        } else {
            postData = $(this).serialize();
            var pData = $(this).serialize() + "&token=" + getToken();
            var isPrincipal = 0;
            var deleteUser = 0;
            var users_id;
            apiCall(this, "checkUserRole", pData, //for principal user
                    function (s, data) {
                        isPrincipal = data.duplicate ? 1 : 0;
                        if (data.duplicate && confirm(data.message + " Do you really want to add another user with this role?"))
                        {
                            users_id = data.duplicate;
                            deleteUser = 1;
                            apiCall($("#create_user_form"), "createUser", postData + "&token=" + getToken() + "&role_id=6" + "&users_id=" + users_id,
                                    function (s, data) {
                                        var s = $("#create_user_form");
                                        showSuccessMsgInMsgBox(s, data);
                                        $(s).find(".ajaxMsg").removeClass("danger warning info").html(data.message).addClass("success active");
                                        if ($(".internal_assessor_id").length && $(s).find(".user-roles[value=3]:checked")) {
                                            loadAssesorListForAssessment($(".internal_assessor_id").parents("form"), "internal");
                                        }
                                        if ($(".external_assessor_id").length && $(s).find(".user-roles[value=3]:checked")) {
                                            loadAssesorListForAssessment($(".external_assessor_id").parents('form'), "external");
                                        }
                                        if ($(".user-list").length) {
                                            filterByAjax($(".user-list"));
                                        }
                                        if ($(".externalAssessorList").length) {
                                            filterByAjax($(".externalAssessorList"));
                                        }
                                        $(s).find("input[type=email],input[type=text],input[type=password]").val('');
                                        $(s).find('input[type=checkbox]').removeAttr("checked");
                                        if ($('#login_user_id').val() == 8) {
                                            var selected_client_id = '221';
                                            var selected_client_name = 'Independent Consultant';
                                        } else {
                                            var selected_client_id = '';
                                            var selected_client_name = '';
                                        }
                                        if ($(s).find("#selectClientBtn").length) {
                                            $(s).find("#selectClientBtn").text("Change School");
                                            $(s).find("#selected_client_id").val(selected_client_id);
                                            $(s).find("#selected_client_name").html(selected_client_name);
                                        }
                                    }, showErrorMsgInMsgBox);

                        } else if (!data.duplicate)
                        {
                            apiCall($("#create_user_form"), "createUser", postData + "&token=" + getToken(),
                                    function (s, data) {

                                        var s = $("#create_user_form");
                                        showSuccessMsgInMsgBox(s, data);
                                        $(s).find(".ajaxMsg").removeClass("danger warning info").html(data.message).addClass("success active");
                                        if ($(".internal_assessor_id").length && $(s).find(".user-roles[value=3]:checked")) {
                                            loadAssesorListForAssessment($(".internal_assessor_id").parents("form"), "internal");
                                        }
                                        if ($(".external_assessor_id").length && $(s).find(".user-roles[value=3]:checked")) {
                                            loadAssesorListForAssessment($(".external_assessor_id").parents('form'), "external");
                                        }
                                        if ($(".user-list").length) {
                                            filterByAjax($(".user-list"));
                                        }
                                        if ($(".externalAssessorList").length) {
                                            filterByAjax($(".externalAssessorList"));
                                        }
                                        $(s).find("input[type=email],input[type=text],input[type=password]").val('');
                                        $(s).find('input[type=checkbox]').removeAttr("checked");
                                        if ($('#login_user_id').val() == 8) {
                                            var selected_client_id = '221';
                                            var selected_client_name = 'Independent Consultant';
                                        } else {
                                            var selected_client_id = '';
                                            var selected_client_name = '';
                                        }
                                        if ($(s).find("#selectClientBtn").length) {
                                            $(s).find("#selectClientBtn").text("Change School");
                                            $(s).find("#selected_client_id").val(selected_client_id);
                                            $(s).find("#selected_client_name").html(selected_client_name);
                                        }

                                        $("#create_user_form #user_type").multiselect("destroy");
                                        var postData = "token=" + getToken();

                                        apiCall(this, "getUserTypeRefresh", postData, function (s, data) {//alert('hi');
                                            var aDd = $("#create_user_form #user_type");
                                            aDd.find("option").next().remove();
                                            $("#create_user_form #user_type").multiselect('destroy');
                                            addOptions(aDd, data.message, 'user_type_id', 'user_type_name');
                                            $("#create_user_form #user_type").multiselect({

                                                includeSelectAllOption: false,
                                                includeSelectAllDivider: false,
                                                enableFiltering: false,
                                                enableCaseInsensitiveFiltering: false,
                                                buttonWidth: '100%',
                                                maxHeight: 50,
                                                numberDisplayed: 1,
                                                templates: {
                                                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                                                    ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                                                }

                                            });

                                        }, showErrorMsgInMsgBox);
                                        /****** Refresh user type dropdown*******/

                                        $("#scl_state").val('');
                                        $('#create_user_form #states').hide();
                                        $('#create_user_form #zones').hide();
                                        $('#create_user_form #blocks').hide();
                                        $('#create_user_form #clusters').hide();
                                        $('#create_user_form #school').hide();
                                        $('#create_user_form #state_level_role').hide();
                                        $('#create_user_form #zone_level_role').hide();
                                        $('#create_user_form #block_level_role').hide();
                                        $('#create_user_form #cluster_level_role').hide();
                                        $('#create_user_form #school_level_role').hide();
                                        $("#states .state-list-dropdown").multiselect("destroy");
                                        $("#zones .zone-list-dropdown").multiselect("destroy");
                                        $("#blocks .block-list-dropdown").multiselect("destroy");
                                        $("#clusters .cluster-list-dropdown").multiselect("destroy");

                                    }, showErrorMsgInMsgBox);



                        }


                    }, showErrorMsgInMsgBox);
        }
       return false;
    });



    $(document).on("submit", "#update_user_form", function () {
        $user_typeID = $("#user_type").val();
        if ($(this).find(".pwd").val() != "" && $(this).find(".pwd").val().length < 6) {
            alert("Minimum 6 character password required");
        } else if ($(this).find(".pwd").val() != $(this).find(".cpwd").val()) {
            alert("Confirm password didn't match");
        } else if ($(this).find('input[type=checkbox]').length > 0 && $(this).find('input[type=checkbox]:checked').length == 0 && $user_typeID == 5) {
            alert("Please select user role");
        } else {
            var isPrincipal = 0;
            var deleteUser = 0;
            var users_id;
            postData = $(this).serialize();
            var pData = $(this).serialize() + "&token=" + getToken();
            var s = $("#update_user_form");
            // pass user id for gettig the user data on 16-05-2016 by Mohit Kumar
            var id = $('input[name="id"]').val();
            apiCall(s, "checkUserRole", pData, //for principal user
                    function (s, data) {
                        isPrincipal = data.duplicate ? 1 : 0;
                        if (data.duplicate && confirm(data.message + "Do you really want to add another user with this role?"))
                        {
                            users_id = data.duplicate;
                            deleteUser = 1;
                            apiCall(s, "updateUser", postData + "&token=" + getToken() + "&role_id=6" + "&users_id=" + users_id,
                                    function (s, data) {
                                        showSuccessMsgInMsgBox(s, data);
                                        if ($(".user-list").length) {
                                            filterByAjax($(".user-list"));
                                        }
                                    }, showErrorMsgInMsgBox);

                        } else if (!data.duplicate)
                        {

                            apiCall(s, "updateUser", postData + "&token=" + getToken(),
                                    function (s, data) {

                                        showSuccessMsgInMsgBox(s, data);
                                        if ($(".user-list").length) {
                                            filterByAjax($(".user-list"));
                                        }
                                    }, showErrorMsgInMsgBox);
                        }
                    }, showErrorMsgInMsgBox);
        }
        return false;
    });


    $(document).on("submit", "#edit_school_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "updateClient", postData,
                function (s, data) {
                    showSuccessMsgInMsgBox(s, data);
                    if ($(".client-list").length) {
                        filterByAjax($(".client-list"));
                    }
                }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#create_school_form", function () {
        if ($(this).find(".pwd").val().length < 6) {
            alert("Minimum 6 character password required");
        } else if ($(this).find(".pwd").val() != $(this).find(".cpwd").val()) {
            alert("Confirm password didn't match");
        } else {
            postData = $(this).serialize() + "&token=" + getToken();
            apiCall(this, "createClient", postData,
                    function (s, data) {
                        showSuccessMsgInMsgBox(s, data);
                        $(s).find("textarea,input[type=email],input[type=text],input[type=password],select").val('');
                        if ($(".client-list").length) {
                            filterByAjax($(".client-list"));
                        }
                    }, showErrorMsgInMsgBox);
        }
        return false;
    });

    $(document).on("submit", "#resetForm", function () {

        var postData = $(this).serialize();
        if ($("#hashkey").val() != undefined && $("#hashkey").val() != '')
        {
            if ($(this).find(".pwd").val().length < 6) {
                alert("Minimum 6 character password required");
            } else if ($(this).find(".pwd").val() != $(this).find(".cpwd").val()) {
                alert("Confirm password didn't match");
            } else
            {
                webApiCall(this, "updatePassApiWebClient", postData, "api=1",
                        function (s, data) {

                            if ($('#process').val() == 'assessor') {
                                //login
                                webApiCall(this, "loginApi", postData + "&email=" + $('#email').val(), "api=1",
                                        function (s, data) {

                                            window.location = data.message;

                                        }, showErrorMsgInMsgBox);
                            } else {
                                location.href = data.siteurl;
                            }

                        }, showErrorMsgInMsgBox);
            }
        } else
        {
            webApiCall(this, "resetPassApiWebClient", postData, "api=1",
                    function (s, data) {
                        alert(data.message);

                    }, showErrorMsgInMsgBox);
        }
        return false;
    });

    $(document).on("click", ".execUrl", function () {
        var hook = $(this).data('validator');
        if (hook != undefined && !window[hook]()) {
            return;
        }
        var e = $(this);
        var urlObj = deSerialize(e.attr("href"));
        var c = urlObj.controller;
        var a = urlObj.action;
        delete urlObj.controller;
        delete urlObj.action;
        var postData = {};
        var pSelector = e.data('postdata');
        if (pSelector != undefined && pSelector.length > 0) {

            $(pSelector).each(function (i, e2) {
                var n = $(e2).attr("name");
                var v = $(e2).val();
                if (n != undefined && n != "" && v != "") {
                    if (n.indexOf("[") > 0 && n.indexOf("]") > 0) {
                        n = n.substring(0, n.indexOf("["));
                        if (postData[n] == undefined)
                            postData[n] = [];
                        postData[n].push(v);
                    } else
                        postData[n] = v;
                }
            });
        }
        if (e.data("postformid") != undefined) {
            postData[e.data("postformid")] = e.parents("form").first().attr("id");
        }
        load_popup_page(e.parents("form"), c, a, postData, $.param(urlObj), e.data("size"), e.data("top"), e.data('modalclass'));
        return false;
    });

    $(document).on("click", ".paginHldr .paging,.paging_wrap .paging", function () {
        var val = $(this).data("value");
        if (val > 0)
            filterByAjax(this, $(this).data("value"));

        return false;
    });

    $(document).on("submit", ".filters-bar", function () {
        filterByAjax(this, 1);
        return false;
    });

    $(document).on("click", ".ajaxFilterReset", function () {
        $(this).parents(".filterByAjax").find('.ajaxFilter').val('');
        filterByAjax(this, 1);
        return false;
    });

    $(document).on("click", ".filterByAjax .sort", function () {
        filterByAjax(this, 1, $(this).data("value"), $(this).hasClass("sorted_asc") ? "desc" : "asc");
        return false;
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open'); //fixes scrolling issue when we open multi popup
    });

    if ($(document).tooltip != undefined)
        $('[data-toggle="tooltip"]').tooltip();

   $(document).on("click", ".school-report.generate_report", function () {
        var f = $(this).parents("#reportsListWrapper");
        var yr = f.find(".valid_years").val(), mth = f.find(".valid_months").val();
        langId=$("#lang_id").val();
        var reportId = $(this).data('reportid');
        var report = true;
        //alert(langId);
        if((reportId==1 || reportId==3) && langId==""){
            //alert("Please select language");
            //return false;
            report=false;
        }
        if ((yr > 0 || mth > 0) && report==true) {
           url=$(this).data("url") + "&years=" + yr + "&months=" + mth; 
           if(langId!="" && (reportId==1  || reportId==3)){
           url += "&lang_id="+langId+""; 
           }
           window.open(url);
        } else{
            if(reportId==1 || reportId==3){
                if(yr <= 0 && mth <= 0){
                alert("Please select validity period for the report.");
                return false;
            }
        } else if(reportId==2 || reportId==6){
                url=$(this).data("url") + "&years=" + yr + "&months=" + mth+ "&lang_id=" + langId;
                window.open(url);
        }
        
        if((reportId==1 || reportId==3 ) && langId==""){
        alert("Please select language");
        return false;
        }
        
        }
    });

    $(document).on("change", "#report_id_4", function () {
        var href = $(document).find(".tchr-recomm").attr('href');
        var diagIndex = href.search("diagnostic_id");
        var diagnostic = $("#report_id_4").val();
        var dept = "";
        href = href.slice(0, diagIndex - 1);
        //alert(href);
        href = href + "&diagnostic_id=" + diagnostic + "&dept_id=" + dept + "";
        //alert(href);
        $(document).find(".tchr-recomm").attr('href', href);
    });

    $(document).on("change", "#report_id_cat_4", function () {
        var href = $(document).find(".tchr-recomm").attr('href');
        var diagIndex = href.search("dept_id");
        var dept = $("#report_id_cat_4").val();
        href = href.slice(0, diagIndex - 1);
        href = href + "&dept_id=" + dept + "";
        //alert(href);
        $(document).find(".tchr-recomm").attr('href', href);

    });

    $(document).on("change", "#report_id_8", function () {
        var href = $(document).find(".tchr-recomm").attr('href');
        var diagIndex = href.search("diagnostic_id");
        var diagnostic = $("#report_id_8").val();
        href = href.slice(0, diagIndex - 1);
        href = href + "&diagnostic_id=" + diagnostic;
        $(document).find(".tchr-recomm").attr('href', href);
    });

    $(document).on("click", ".tchr-report.generate_report", function () {
        var f = $(this).parents("#reportsListWrapper");

        var yr = f.find(".valid_years").val(), mth = f.find(".valid_months").val();
        var langId = $("#lang_id").val();
        var reportId = $(this).data('reportid');
        var url = $(this).data("url");

        if (reportId == 4)
            url += "&diagnostic_id=" + $("#report_id_4").val() + "&dept_id=" + $("#report_id_cat_4").val() + "&assessment_id=0";
        else if (reportId == 5)
            url += "&assessment_id=" + $("#report_id_5").val() + "&diagnostic_id=0";
        else if (reportId == 7)
            url += "&assessment_id=" + $("#report_id_7").val() + "&diagnostic_id=0";
        else if (reportId == 8)
            url += "&diagnostic_id=" + $("#report_id_8").val() + "&assessment_id=0";
        else if (reportId == 9)
            url += "&assessment_id=" + $("#report_id_9").val() + "&diagnostic_id=0";
        else if (reportId == 10)
            url += "&assessment_id=" + $("#report_id_10").val() + "&diagnostic_id=0";
        if (yr > 0 || mth > 0) {
            window.open(url + "&years=" + yr + "&months=" + mth);
        } else
            alert("Please select validity period for the report.");
    });

    $(document).on("click", ".tchr-report.view_report", function () {
        var f = $(this).parents("#reportsListWrapper");
        var reportId = $(this).data('reportid');
        var url = $(this).data("url");
        if (reportId == 4)
            url += "&diagnostic_id=" + $("#report_id_4").val() + "&dept_id=" + $("#report_id_cat_4").val() + "&assessment_id=0";
        else if (reportId == 5)
            url += "&assessment_id=" + $("#report_id_5").val() + "&diagnostic_id=0";
        else if (reportId == 7)
            url += "&assessment_id=" + $("#report_id_7").val() + "&diagnostic_id=0";
        else if (reportId == 8)
            url += "&diagnostic_id=" + $("#report_id_8").val() + "&assessment_id=0";
        else if (reportId == 9)
            url += "&assessment_id=" + $("#report_id_9").val() + "&diagnostic_id=0";
        else if (reportId == 10)
            url += "&assessment_id=" + $("#report_id_10").val() + "&diagnostic_id=0";
        window.open(url);
    });

    $(document).on("change", "#report_id_4", function () {
        var groupassessment_id = $(this).data("groupassessment_id");
        var diagnostic_id = $("#report_id_4").val();
        postData = "groupassessment_id=" + groupassessment_id + "&diagnostic_id=" + diagnostic_id + "&token=" + getToken();
        apiCall(this, "updateDepartment", postData,
                function (s, data) {
                    var aDd = $("#report_id_cat_4");
                    aDd.find("option").next().remove();
                    addOptions(aDd, data.department, 'department_id', 'department');

                }, showErrorMsgInMsgBox);
        return false;

    });

    $(document).on("click", ".publish_report", function () {
        var f = $(this).parents("#reportsListWrapper");
        var yr = f.find(".valid_years").val(), mth = f.find(".valid_months").val(), aid = f.data("assessmentorgroupassessmentid"), c = f.find("#keyNotesAccepted"), atid = f.data("assesmenttypeid");
        if (c.length > 0 && !c.is(":checked")) {
            alert("Please approve the assessor key recommendations");
        } else if (yr > 0 || mth > 0) {
            apiCall(this, "publishReport", {"token": getToken(), "years": yr, "months": mth, "ass_or_group_ass_id": aid, "assessment_type_id": atid},
                    function (s, data) {
                        if (data.content != undefined && data.content != null) {
                            $(s).parents('.modal-body').html(data.content).find('.page-title').remove();
                            if ($(".assessment-list").length)
                                filterByAjax($(".assessment-list"));
                        }
                    }, function (s, msg) {
                alert(msg);
            });
        } else
            alert("Please select validity period for the report.");
    });

    $(document).on("click", ".eAssessorRow", function () {
        var id = $(this).data('id');
        var cont = $(".currentSelection");
        if (cont.find(".eAssessorNode-" + id).length)
            return;
        var name = $(this).addClass('selected').find(".eAssessorName").text();
        var email = $(this).addClass('selected').find(".eAssessorName").attr('title');
        cont.append('<div title="' + email + '" class="eAssessorNode clearfix eAssessorNode-' + id + '" data-id="' + id + '">' + name + '<input type="hidden" class="ajaxFilterAttach" name="eAssessor[' + id + ']" value="' + id + '"/><span class="delete"><i class="fa fa-times"></i></span></div>').find(".empty").addClass('notEmpty');
    });

    $(document).on("click", ".eAssessorNode .delete", function () {
        var id = $(this).parents(".eAssessorNode").data("id");
        var p = $(this).parents(".tag_boxes").first();
        $(".eAssessorNode-" + id).remove();
        $("#ex-user-" + id).removeClass('selected');
        if (p.find(".eAssessorNode").length == 0) {
            p.find(".empty").removeClass("notEmpty");
        }
        var trgr = p.data('trigger');
        if (trgr != undefined && trgr != null && trgr != "") {
            $("body").trigger(trgr);
        }
    });

    $(document).on("mouseenter", ".vtip", function (a) {
        //alert("enter");
        this.t = this.title;
        this.title = "";
        this.top = (a.pageY + 20);
        this.left = (a.pageX - 25);
        $("body").append('<p id="vtip"><img id="vtipArrow" />' + this.t + "</p>");
        //  $("body").append('<p class="vtip"><img id="vtipArrow" />' + this.t + "</p>");
        $("p#vtip #vtipArrow").attr("src", "public/images/vtip_arrow.png");
        $("p#vtip").css("top", this.top + "px").css("left", this.left + "px").fadeIn("slow");
    }).on("mouseleave", ".vtip", function () {
        this.title = this.t;
        $("p#vtip").fadeOut("slow").remove()
    }).on("mousemove", ".vtip", function (a) {
        this.top = (a.pageY + 20);
        this.left = (a.pageX - 25);
        $("p#vtip").css("top", this.top + "px").css("left", this.left + "px");
    });

    $(document).on("click", ".team_table .team_row .delete_row", function () {
        if ($(this).parents(".team_table").first().find(".team_row").length > 1) {
            var p = $(this).parents(".team_table");
            $(this).parents(".team_row").remove();
            p.first().find(".s_no").each(function (i, v) {
                $(v).html(i + 1)
            });
            var trgr = p.data('trigger');
            if (trgr != undefined && trgr != null && trgr != "") {
                $("body").trigger(trgr);
            }
        } else
            alert("You can't delete all the rows");
        return false;
    });

    $(document).on("click", " .facilitator_table .facilitator_row .delete_row", function () {
        if ($(this).parents(".facilitator_table").first().find(".facilitator_row").length > 1) {
            var p = $(this).parents(".facilitator_table");
            $(this).parents(".facilitator_row").remove();
            p.first().find(".s_no").each(function (i, v) {
                $(v).html(i + 1)
            });
            var trgr = p.data('trigger');
            if (trgr != undefined && trgr != null && trgr != "") {
                $("body").trigger(trgr);
            }
        } else
            alert("You can't delete all the rows");
        return false;
    });

    $(document).on("click", ".provinceField .delete_row", function () {
        var frm = $(this).parents("#create_network_form,#create_province_form");
        if (frm.first().find(".provinceField").length > 1)
            $(this).closest('.provinceField').remove();
        else
            alert("You can't delete all the rows");
        return false;
    });

    //add more rows of team reviewer for assessment
    $(document).on("click", ".team_table .extteamAddRow", function () {
        var frm = $('.extteamAddRow').closest('form').first().attr('id');
        var removeIds = [];
        //find all the selected Ids
        $("#" + frm + " .team_row").each(function () {
        });
        console.log(removeIds)
        apiCall(this, "addExternalReviewTeam", "sn=" + ($(this).parents(".team_table").first().find(".team_row").length + 1) + "&frm=" + frm + "&token=" + getToken(), function (s, data) {

            $.when($(s).parents(".team_table").first().find(".team_row").last().after(data.content)).then(function () {
                return false;
            });
        }, function (s, msg) {
            alert(msg);
        });
    });

    /*
     * create facilitator row to add  multiple facilitator in school review
     * Owner:Deepak Thakur
     */
    $(document).on("click", ".facilitator_table .extteamAddRow", function () {
        var frm = $('.extteamAddRow').closest('form').first().attr('id');

        var removeIds = [];
        //find all the selected Ids
        $("#" + frm + " .facilitator_row").each(function () {
        });
        console.log(removeIds)
        apiCall(this, "addFacilitatorReviewTeam", "sn=" + ($(this).parents(".facilitator_table").first().find(".facilitator_row").length + 1) + "&frm=" + frm + "&token=" + getToken(), function (s, data) {

            $.when($(s).parents(".facilitator_table").first().find(".facilitator_row").last().after(data.content)).then(function () {
                return false;
            });
        }, function (s, msg) {
            alert(msg);
        });
    });

    
   
    $(document).on("click", ".addnewprovince", function () {
        var a = $(this).data('attach');
        apiCall(this, "addProvinceField", "&token=" + getToken() + "&attach=" + (a == undefined ? '' : a), function (s, data) {
            $(s).parents("#create_province_form").find(".provinceField").last().after(data.content);
        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });

    $(document).on("click", ".teamsInfoHldr .ovwRecomm.fltdAddRow", function () {
        var type = $(this).data('type');
        var pos = 0;
        var instance_id = $(this).data('id');
        apiCall(this, "addOverviewRecommendations", "instance_id=" + (instance_id) + "&type=" + (type) + "&" + "sn=" + ($(this).parents().find(".customTbl." + type + "." + instance_id).first().find(".recRow").length + 1) + "&token=" + getToken() + "&attach=", function (s, data) {
            $(s).parents().find(".customTbl." + type + "." + instance_id).first().find(".recRow").last().after(data.content);
        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });
    $(document).on("click", ".recRow .delete_row", function () {
        if ($(this).parents(".customTbl").first().find(".recRow").length > 1) {
            var p = $(this).parents(".customTbl");
            $(this).parents(".recRow").remove();
            p.first().find(".s_no").each(function (i, v) {
                ;
                $(v).html(i + 1)
            });
            var trgr = p.data('trigger');
            if (trgr != undefined && trgr != null && trgr != "") {
                $("body").trigger(trgr);
            }
        } else
            alert("You can't delete all the rows");
        return false;
    });

    $(document).on("click", ".approve_eAssessorNode", function () {
        var s = $(this).parents(".tag_boxes").first().find(".eAssessorNode.selected");
        s.find("input").val('1');
        s.appendTo("#status_id_1-0.tag_boxes").removeClass('selected');
        s.length ? $("body").trigger('tchrAssessorsChanged') : '';
        $(this).parents(".tag_boxes").first().find(".eAssessorNode").length == 0 ? $(this).parents(".tag_boxes").first().find('.empty').removeClass('notEmpty') : '';
        $("#status_id_1-0.tag_boxes .empty").addClass('notEmpty');

    });

    $(document).on("click", ".reject_eAssessorNode", function () {
        var s = $(this).parents(".tag_boxes").first().find(".eAssessorNode.selected");
        s.find("input").val('3');
        s.appendTo("#status_id_3.tag_boxes").removeClass('selected');
        s.length ? $("body").trigger('tchrAssessorsChanged') : '';
        $(this).parents(".tag_boxes").first().find(".eAssessorNode").length == 0 ? $(this).parents(".tag_boxes").first().find('.empty').removeClass('notEmpty') : '';
        $("#status_id_3.tag_boxes .empty").addClass('notEmpty');
        
    });

    $(".nuibody .related").on("mouseenter", "input", function () {
        $(this).addClass("editLblField");
        $(this).removeAttr('readonly', 'readonly');
    }).on("mouseout", "input", function () {
        $(this).removeClass("editLblField");
        $(this).attr('readonly', 'readonly');
    });

    $("#chooseAssmt").trigger('click');
   
    $(document).on("change", "#create_school_form #country_id, #edit_school_form #country_id, #school_dashboard_frm #country_id", function () {
        var countryId = $(this).parents('form').find('#country_id').val();
        var aDd = $(this).parents('form').find('#state_id');
        var aDd2 = $(this).parents('form').find('#city_id');

        if (countryId > 0) {
            apiCall(this, "getStateByCountry", {"token": getToken(), "country": countryId}, function (s, data) {
                aDd.find("option").next().remove();
                aDd2.find("option").next().remove();
                addOptions(aDd, data.states, 'state_id', 'state_name');
            }, showErrorMsgInMsgBox);
        }
        return false;
    });

    $(document).on("change", "#create_school_form #state_id, #edit_school_form #state_id, #create_student_profile #state_id", function () {
        //alert("ok");
        var state_id = $(this).parents('form').find('#state_id').val();
        var aDd = $(this).parents('form').find('#city_id');
        if (state_id > 0) {
            apiCall(this, "getCityByState", {"token": getToken(), "state": state_id}, function (s, data) {
                aDd.find("option").next().remove();
                addOptions(aDd, data.cities, 'city_id', 'city_name');
            }, showErrorMsgInMsgBox);
        }
        return false;
    });
    $(document).on("change", ".filterByAjax [name=country_id]", function () {
        var countryId = $(this).parents('form').find('[name=country_id]').val();
        var aDd = $(this).parents('form').find('[name=state_id]');
        var aDd2 = $(this).parents('form').find('[name=city]');

        if (countryId > 0) {
            apiCall(this, "getStateByCountry", {"token": getToken(), "country": countryId}, function (s, data) {
                aDd.find("option").next().remove();
                aDd2.find("option").next().remove();
                addOptions(aDd, data.states, 'state_id', 'state_name');
            }, showErrorMsgInMsgBox);
        }
        return false;
    });
    $(document).on("change", ".filterByAjax [name=state_id]", function () {
        var state_id = $(this).parents('form').find('[name=state_id]').val();
        var aDd = $(this).parents('form').find('[name=city]');
        if (state_id > 0) {
            apiCall(this, "getCityByState", {"token": getToken(), "state": state_id}, function (s, data) {
                aDd.find("option").next().remove();
                addOptions(aDd, data.cities, 'city_name', 'city_name');
            }, showErrorMsgInMsgBox);
        }
        return false;
    });
    $(document).on("change", ".filterByAjax [name=network_id]", function () {
        var networkId = $(this).parents('form').find('[name=network_id]').val();
        var aDd2 = $(this).parents('form').find('[name=province_id]');
        if (networkId > 0) {
            apiCall(this, "getProvincesInNetwork", {"token": getToken(), "network_id": networkId}, function (s, data) {
                aDd2.find("option").next().remove();
                addOptions(aDd2, data.message, 'province_id', 'province_name');
            }, showErrorMsgInMsgBox);
        } else {
            $(this).parents('form').find('[name=province_id]').val('');
        }
        return false;
    });

    $(document).on("change", ".filterByAjax [name=stat_id]", function () {
        var stateId = $(this).parents('form').find('[name=stat_id]').val();
        var aDd2 = $(this).parents('form').find('[name=zone_id]');
        if (stateId > 0) {
            apiCall(this, "getZonesInState", {"token": getToken(), "state_id": stateId}, function (s, data) {
                aDd2.find("option").next().remove();
                addOptions(aDd2, data.message, 'zone_id', 'zone_name');
            }, showErrorMsgInMsgBox);
            $(this).parents('form').find('[name=network_id]').val('');
            $(this).parents('form').find('[name=province_id]').val('');
        } else {
            $(this).parents('form').find('[name=zone_id]').val('');
            $(this).parents('form').find('[name=network_id]').val('');
            $(this).parents('form').find('[name=province_id]').val('');
        }
        return false;
    });

    $(document).on("change", ".filterByAjax [name=zone_id]", function () {
        var stateId = $(this).parents('form').find('[name=stat_id]').val();
        var zoneId = $(this).parents('form').find('[name=zone_id]').val();
        var aDd2 = $(this).parents('form').find('[name=network_id]');
        if (stateId > 0 && zoneId > 0) {
            apiCall(this, "getBlocksInZone", {"token": getToken(), "state_id": stateId, "zone_id": zoneId}, function (s, data) {
                aDd2.find("option").next().remove();
                addOptions(aDd2, data.message, 'network_id', 'network_name');
            }, showErrorMsgInMsgBox);
            $(this).parents('form').find('[name=province_id]').val('');
        } else {
            $(this).parents('form').find('[name=network_id]').val('');
            $(this).parents('form').find('[name=province_id]').val('');
        }
        return false;
    });

   
    // function for update password on 25-07-2016 by Mohit Kumar
    $(document).on('submit', '#update_user_password', function () {
        if ($('.pwd').val() == '' && $('.pwd').val().length < 6) {
            alert('New Password can not be empty!');
        } else if ($('.cpwd').val() == '' && $('.cpwd').val().length < 6) {
            alert('Confirm Password can not be empty!')
        } else if ($('.pwd').val() != $('.cpwd').val()) {
            alert('New and Confirm password should be same!')
        } else {
            var postData = $('#update_user_password').serialize();
            apiCall($('#update_user_password'), "updateUserPassword", postData + "&token=" + getToken(),
                    function (s, data) {
                        var s = $("#update_user_password");
                        showSuccessMsgInMsgBox(s, data);
                        if (data.status == 1) {
                            $('.pwd').val('');
                            $('.cpwd').val('');
                        }
                    }, showErrorMsgInMsgBox
                    );
        }
        return false;
    });

    $(document).on('click', '.delfolder', function () {

        var directoryId = $(this).attr("id");

        var postData = "directory_id=" + directoryId + "&token=" + getToken()
        var querystring = '';

        apiCall('#list_resourcedirectory_form', "deleteFolder", postData,
                function (s, data) {
                    showSuccessMsgInMsgBox(s, data);
                    if (data.status == 1) {
                        if (data.parent_id >= 1 && data.childs < 1 && data.num_files < 1) {
                            $(".parent-" + data.parent_id).hide();
                            $(".child-" + data.parent_id).removeAttr('style');
                            $(".action-" + data.parent_id).append('<a id="' + data.parent_id + '" class="delete-btn-resource vtip delfolder" href="#" title="Delete"><i class="edit-btn-resource vtip fa fa-trash-o" title="Delete"></i>')
                        }
                        $("." + directoryId).hide();
                    }

                }, showErrorMsgInMsgBox);
        return false;


    });
    $(document).on('click', '.dwndhistory', function () {

        var resource_id = $(this).attr('id');
        var postData = "resource_id=" + resource_id + "&token=" + getToken();
        apiCall(this, "resourceDownloadHistory", postData, function (s, data) {
            if (data.message != '') {
                file_url = data.site_url + "public/resources/resource_log.xlsx";
                window.open(file_url, '_blank');
            } else {

            }
        }, showErrorMsgInMsgBox);

        return false;
    });

    //collapse and expand by folder name
    $(document).on("click", ".expandName", function () {
        var liClass = $(this).closest("li").attr('class');
        liClass = liClass.split(" ");
        if (liClass[1] != '' && liClass[1] != 'undefined') {
            if ($("." + liClass[1]).closest("li").children("ul").length) {
                $("." + liClass[1]).closest("li").children("ul").toggle();
                var a = $("." + liClass[1]).closest("li").children().attr("class");
                var parentId = a.split(" ");
                if (parentId[1] != '' && parentId[1] != 'undefined') {

                    $("." + parentId[1]).find(".expand").toggle();
                    $("." + parentId[1]).find(".collapse").toggle();
                }

            }
        }



    });
    //collapse and expand by folder name
    $(document).on("click", ".expandName1", function () {
        var liClass = $(this).closest("li").attr('class');
        liClass = liClass.split(" ");
        if (liClass.length < 2) {
            liClass[1] = liClass[0];
        }
        if (liClass[1] != '' && liClass[1] != 'undefined') {

            if ($("." + liClass[1]).closest("li").children("ul").length) {
                $("." + liClass[1]).closest("li").children("ul").toggle();
                var a = $("." + liClass[1]).closest("li").children().attr("class");
                var parentId = a.split(" ");
                if (parentId[0] != '' && parentId[0] != 'undefined') {
                    $(this).closest("li").children().children('.expand').toggle();
                    $(this).closest("li").children().children('.collapse').toggle();
                }

            }
        }



    });
    $('input[name="directory"]').on('change', function () {
        $('input[name="' + this.name + '"]').not(this).prop('checked', false);
    });

    $("li").click(function () {
        if ($(this).next("li").attr('class') == 'file_tree') {
            if ($(this).next("li").css('display') == 'none') {
                $(this).next("li").css("display", "block");
                if ($(this).children('div').last().attr("class") == "chkbox") {
                    $(this).children('div').last().css("display", "block");
                }
            } else {
                $(this).next("li").css("display", "none");
            }
        }
    });

    /*
     * Function for upload user_excel_file_form
     */
    $(document).on("submit", "#user_excel_file_form", function (event) {
        var postData = new FormData(this);
        postData.append("token", getToken());
        apiCall(this, "uploadUserDetails", postData,
                function (s, data) {

                    showSuccessMsgInMsgBox(s, data);

                },
                showErrorMsgInMsgBox, undefined, {processData: false, contentType: false});
        return false;
    });
    $(document).on("submit", "#create_state_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "createState", postData,
                function (s, d) {
                    apiCall(s, "getStateList", {"token": getToken()}, function (s, data) {
                        states = []
                        for (var i = 0; i < data.states.length; i++)
                            states.push(data.states[i].state_name);
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        $('.the-basics.state .typeahead').typeahead('destroy');
                        $('.the-basics.state .typeahead').typeahead({
                            hint: true,
                            highlight: true,
                            minLength: 3
                        },
                                {
                                    name: 'states',
                                    source: substringMatcher(states),
                                    limit: 30
                                });
                    }, showErrorMsgInMsgBox);

                    if ($(".state-list").length) {
                        filterByAjax($(".state-list"));
                    }
                    if ($(".state-list-dropdown").length) {
                        var nDd = $(".state-list-dropdown");
                        nDd.find("option").next().remove();
                        apiCall(s, "getStateList", {"token": getToken()}, function (s, data) {
                            addOptions(nDd, data.states, 'state_id', 'state_name')
                        }, showErrorMsgInMsgBox);
                    }
                    $("#zones").hide();
                    $("#blocks").hide();
                    $("#provinces").hide();
                    $(s).find("input[type=text]").val('');
                }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#create_block_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        var action=$.urlParam('action');
        apiCall(this, "createBlock", postData,
                function (s, d) {
                    apiCall(s, "getBlockList", {"token": getToken()}, function (s, data) {
                        blocks = []
                        for (var i = 0; i < data.blocks.length; i++)
                            blocks.push(data.blocks[i].block_name);
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        $('.the-basics.block .typeahead').typeahead('destroy');
                        $('.the-basics.block .typeahead').typeahead({
                            hint: true,
                            highlight: true,
                            minLength: 3
                        },
                                {
                                    name: 'states',
                                    source: substringMatcher(blocks),
                                    limit: 30
                                });
                    }, showErrorMsgInMsgBox);

                    if ($(".block-list").length) {
                        filterByAjax($(".block-list"));
                    }

                    if ($(".block-list-dropdown").length) {
                        var nDd = $(".block-list-dropdown");
                        nDd.find("option").next().remove();
                        
                        if(action=="createUser" || action=="client" || action=="user" || action=="editUser" || action=="createSchoolAssessment"){ 
	 var postData1 = "state_id=" + $("#create_block_form #scl_state").val() + "&zone_id=" + $("#create_block_form #scl_zone").val() + "&token=" + getToken();
}else{
 	 var postData1 = "state_id=" + $("#scl_state").val() + "&zone_id=" + $("#scl_zone").val() + "&token=" + getToken();
}
 
                        apiCall(this, "getBlocksInZone", postData1, function (s, data) {
                            addOptions(nDd, data.message, 'network_id', 'network_name')
                        }, showErrorMsgInMsgBox);
                    }
                    $("#provinces").hide();
                    $(s).find("input[name=name]").val('');
                }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#create_zone_form", function () {
        
        var action=$.urlParam('action');
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "createZone", postData,
                function (s, d) {
                    apiCall(s, "getZoneList", {"token": getToken()}, function (s, data) {
                        zones = []
                        for (var i = 0; i < data.zones.length; i++)
                            zones.push(data.zones[i].zone_name);
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        $('.the-basics.zone .typeahead').typeahead('destroy');
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
                    }, showErrorMsgInMsgBox);

                    if ($(".zone-list").length) {
                        filterByAjax($(".zone-list"));
                    }

                    if ($(".zone-list-dropdown").length) {
                        var nDd = $(".zone-list-dropdown");
                        nDd.find("option").next().remove();
                        if(action=="createUser" || action=="client" || action=="user" || action=="editUser" || action=="createSchoolAssessment"){ 
                            var postData1 = "state_id=" + $("#create_zone_form #scl_state").val() + "&token=" + getToken();
                        }else{
                            var postData1 = "state_id=" + $("#scl_state").val() + "&token=" + getToken();
                        }
                        apiCall(this, "getZonesInState", postData1, function (s, data) {
                            addOptions(nDd, data.message, 'zone_id', 'zone_name')
                        }, showErrorMsgInMsgBox);
                    }
                    $("#blocks").hide();
                    $("#provinces").hide();
                    $(s).find("input[name=name]").val('');

                }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#create_network_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "createNetwork", postData,
                function (s, d) {
                    apiCall(s, "getNetworkList", {"token": getToken()}, function (s, data) {
                        networks = []
                        for (var i = 0; i < data.networks.length; i++)
                            networks.push(data.networks[i].network_name);
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        $('.the-basics.network .typeahead').typeahead('destroy');
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
                    }, showErrorMsgInMsgBox);

                    if ($(".network-list").length) {
                        filterByAjax($(".network-list"));
                    }
                    if ($("#scl_network").length) {
                        var nDd = $("#scl_network");
                        nDd.find("option").next().remove();
                        apiCall(s, "getNetworkList", {"token": getToken()}, function (s, data) {
                            addOptions(nDd, data.networks, 'network_id', 'network_name')
                        }, showErrorMsgInMsgBox);
                    }
                    if ($(".network-list-dropdown").length) {
                        var nDd = $(".network-list-dropdown");
                        nDd.find("option").next().remove();
                        apiCall(s, "getNetworkList", {"token": getToken()}, function (s, data) {
                            addOptions(nDd, data.networks, 'network_id', 'network_name')
                        }, showErrorMsgInMsgBox);
                    }
                    $(s).find("input[type=text]").val('');
                }, showErrorMsgInMsgBox);
        return false;
    });

    $(document).on("submit", "#create_province_form", function () {
        var action=$.urlParam('action');
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "createProvince", postData, function (s, d) {
            apiCall(s, "getProvinceList", {"token": getToken()}, function (s, data) {
                provinces = []
                for (var i = 0; i < data.provinces.length; i++)
                    provinces.push(data.provinces[i].province_name);
                data.message = d.message;
                showSuccessMsgInMsgBox(s, data);
                $('.the-basics.province .typeahead').typeahead('destroy');
                $('.the-basics.province .typeahead').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 3
                },
                        {
                            name: 'provinces',
                            source: substringMatcher(provinces),
                            limit: 30
                        });
            }, showErrorMsgInMsgBox);

            if ($(".province-list").length) {
                filterByAjax($(".province-list"));
            }

            if ($(".province-list-dropdown").length) {
                var nDd = $(".province-list-dropdown");
                nDd.find("option").next().remove();
                
                if(action=="network" || action=="createUser" || action=="client" || action=="user" || action=="editUser" || action=="createSchoolAssessment"){ 
	var postData1 = "state_id=" + $("#create_province_form #scl_state").val() + "&zone_id=" + $("#create_province_form #scl_zone").val() + "&block_id=" + $("#create_province_form #scl_block").val() + "&token=" + getToken();
}else{
 	var postData1 = "state_id=" + $("#scl_state").val() + "&zone_id=" + $("#scl_zone").val() + "&block_id=" + $("#scl_block").val() + "&token=" + getToken();
}
                
                
                apiCall(this, "getClusterInZone", postData1, function (s, data) {
                    addOptions(nDd, data.message, 'province_id', 'province_name')
                }, showErrorMsgInMsgBox);
            }
            $(s).find("input[name=name]").val('');
        }, showErrorMsgInMsgBox);
        return false;
    });
    $(document).on("submit", "#update_network_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "updateNetwork", postData,
                function (s, d) {
                    apiCall(s, "getNetworkList", {"token": getToken()}, function (s, data) {
                        networks = []
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        for (var i = 0; i < data.networks.length; i++)
                            networks.push(data.networks[i].network_name)
                        $('.the-basics.network .typeahead').typeahead('destroy');
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
                    }, showErrorMsgInMsgBox);
                    if ($(".network-list").length) {
                        filterByAjax($(".network-list"));
                    }
                    showSuccessMsgInMsgBox(s, d);
                }, showErrorMsgInMsgBox);

        return false;
    });

    // for update State,zone and cluster by prashant
    $(document).on("submit", "#update_state_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "updateState", postData,
                function (s, d) {
                    apiCall(s, "getStateList", {"token": getToken()}, function (s, data) {
                        networks = []
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        for (var i = 0; i < data.networks.length; i++)
                            networks.push(data.networks[i].state_name)
                        $('.the-basics.network .typeahead').typeahead('destroy');
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
                    }, showErrorMsgInMsgBox);
                    if ($(".network-list").length) {
                        filterByAjax($(".network-list"));
                    }
                    showSuccessMsgInMsgBox(s, d);
                }, showErrorMsgInMsgBox);

        return false;
    });

    $(document).on("submit", "#update_zone_form", function () {
        var postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "updateZone", postData,
                function (s, d) {
                    apiCall(s, "getZoneList", {"token": getToken()}, function (s, data) {
                        networks = []
                        data.message = d.message;
                        showSuccessMsgInMsgBox(s, data);
                        for (var i = 0; i < data.networks.length; i++)
                            networks.push(data.networks[i].zone_name)
                        $('.the-basics.network .typeahead').typeahead('destroy');
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
                    }, showErrorMsgInMsgBox);
                    if ($(".network-list").length) {
                        filterByAjax($(".network-list"));
                    }
                    showSuccessMsgInMsgBox(s, d);
                }, showErrorMsgInMsgBox);

        return false;
    });


    $(document).on("click", "#aqsReportView", function () {
        var reportUrl = $(this).attr('rel');
        var reportId = $(this).data('reportid');
        var langId = $("#lang_id").val();
        if (langId == "" && (reportId == 1 || reportId == 2 || reportId == 3)) {
            alert("Please select language");
            return false;
        } else if (reportId == 1 || reportId == 2 || reportId == 3) {
            var reportUrl = reportUrl + "&lang_id=" + langId;
        }
        window.open(reportUrl, '_blank');
        return false;
    });

    $(document).on("submit", "#update_network_province_form", function () {
        postData = $(this).serialize() + "&token=" + getToken();
        apiCall(this, "updateProvince", postData,
                function (s, d) {
                    apiCall(s, "getProvinceList", {"token": getToken()}, function (s, data) {
                        provinces = []
                        for (var i = 0; i < data.provinces.length; i++)
                            provinces.push(data.provinces[i].province_name);
                        data.message = d.message;
                        $('.the-basics.province .typeahead').typeahead('destroy');
                        $('.the-basics.province .typeahead').typeahead({
                            hint: true,
                            highlight: true,
                            minLength: 3
                        },
                                {
                                    name: 'provinces',
                                    source: substringMatcher(provinces),
                                    limit: 30
                                });
                    }, showErrorMsgInMsgBox);
                    if ($(".network-list").length) {
                        filterByAjax($(".network-list"));
                    }
                    showSuccessMsgInMsgBox(s, d);
                }, showErrorMsgInMsgBox);
        return false;
    });
    /*
     * Function for upload user_excel_file_form
     */
  

    // show all warning of AQS upload
    $(document).on("click", "#warningStatus", function (event) {
        $("#myModal").show();
    });
    // show all warning of AQS upload
    $(document).on("click", "#uploadaqserrorpopup", function (event) {
        $("#myModal").modal("hide")

    });
    $('#create_student_profile input').on('change', function () {
        $('input[type="radio"]:checked').each(function () {
            var val = $(this).val();
            var radioId = $(this).attr('id');
            if (val == 1 && radioId != '') {

                $("." + radioId).show();
            } else if (radioId != '') {
                $("." + radioId).hide();
            }
        })
    });

});


function filterByAjax(s, page, orderBy, ordertype) {
    var f = $(s).hasClass("filterByAjax") ? $(s) : $(s).parents(".filterByAjax");
    if (f.data("controller") != undefined && f.data("action") != undefined) {
        var a = f.data("action");
        var c = f.data("controller");
        var querystring = f.data("querystring") == undefined ? "" : f.data("querystring");

        var cPage = f.find(".paginHldr .paging.active,.paging_wrap .paging.active").data("value");
        page = page == undefined ? (cPage == undefined ? 1 : cPage) : page;
        var postData = {page: page};
        $(f).find(".ajaxFilter").each(function (i, e) {
            var n = $(e).attr("name");
            var v = page > 1 ? $(e).data("value") : $(e).val();
            if (n != undefined && n != "" && v != "")
                postData[n] = v;
        });

        $(f).find(".ajaxFilterAttach").each(function (i, e) {
            var n = $(e).attr("name");
            var v = $(e).val();
            if (n != undefined && n != "" && v != "") {
                if (n.indexOf("[") > 0 && n.indexOf("]") > 0) {
                    n = n.substring(0, n.indexOf("["));
                    if (postData[n] == undefined)
                        postData[n] = [];
                    postData[n].push(v);
                } else
                    postData[n] = v;
            }
        });

        if (orderBy != undefined) {
            postData['order_by'] = orderBy;
        } else if (f.find(".sorted_desc,.sorted_asc").length > 0) {
            postData['order_by'] = f.find(".sorted_desc,.sorted_asc").data("value");
        }

        postData['order_type'] = ordertype != undefined ? ordertype : (f.find(".sorted_desc").length > 0 ? "desc" : "asc");
        ajaxCall(f, c, a, postData, querystring, function (s, data) {
            sessionStorage.clear();
            for (key in postData)
                sessionStorage.setItem("pFilter_" + key, postData[key]);

            s.replaceWith(data.content);
            $(".modal-dialog .page-title").remove();
            if (a == "resourcelist") {
                $(".loader").fadeOut("slow");
            }

            if (c == "actionplan" && a == "actionplan1") {

                $('.selectpicker').selectpicker('refresh');
                $(".ribWrap").hide();
            }
            ///////////////
            $('.owl-carousel').owlCarousel({
                loop: false,
                margin: 0,
                nav: true,
                autoWidth: true,
                mouseDrag: false,
                touchDrag: false,
                pullDrag: false
            });
            $(".filters-bar").keypress(function (e) {
                if ((e.keyCode == 13)) {
                    e.preventDefault();
                    $(this).submit();
                }
            });
            /////////////
        }, showErrorMsgInMsgBox);
    }
}

function load_popup_page(senderForm, controller, action, postdata, queryString, size, top, modalclass) {
    ajaxCall(senderForm, controller, action, postdata, queryString, function (s, data) {
        var id = controller + "_" + action;
        $.createModal(id, "", data.content, size, top, modalclass);
        $("#popup-" + id + " .modal-title").html($("#popup-" + id + " .page-title").html());
        $("#popup-" + id + " .page-title").remove();

        if (controller == "actionplan" && action == "action1new") {
            $('.selectpicker').selectpicker('refresh');
        }

        if (controller == "assessment" && action == "reportAllSchools") {
            $('#create_all_school_data_form #rec_state,#create_all_school_data_form #rec_zone,#create_all_school_data_form #rec_block,#create_all_school_data_form #rec_province').multiselect({
                enableFiltering: false,
                includeSelectAllOption: true,
                buttonWidth: '420px',
                maxHeight: 210,
                templates: {
                    ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                },
            });
            $(".caret").css('float', 'right');
            $(".caret").css('margin', '8px 0');
        }
        if (action == "keyrecommendations") {

            $('.rec_dropdown').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '100%',
                maxHeight: 5,
                numberDisplayed: 1,
                templates: {
                    filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                    ul: '<ul class="multiselect-container dropdown-menu" style="width:100%;"></ul>',
                }

            });

        }

        if (controller == "user" && action == "createUser") {
            if ($("#popup-" + id + " .multiselect-dd")) {
                $("#popup-" + id + " .multiselect-dd").multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 5,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });
            }
        }


        if (controller == "user" && action == "editUser") {
            if ($("#popup-" + id + " .multiselect-dd")) {
                $("#popup-" + id + " .multiselect-dd").multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: false,
                    enableCaseInsensitiveFiltering: false,
                    buttonWidth: '100%',
                    maxHeight: 5,
                    numberDisplayed: 1,
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container position-bottom dropdown-menu" style="width:100%;"></ul>',
                    }

                });
            }
        }


    }, showErrorMsgInMsgBox);
}


function loadFacilitatorListForAssessment(senderForm, assessorType) {
    var cid = $(senderForm).find("." + assessorType + "_client_id").val();
    var aDd = $(senderForm).find("." + assessorType + "_id");
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getFacilitators", {"token": getToken(), "client_id": cid}, function (s, data) {
            addOptions(aDd, data.assessors, 'user_id', 'name')
        }, showErrorMsgInMsgBox);
}

function loadFacilitatorListForEditAssessment(senderForm, assessorType, selectedAssesor) {
    var cid = $(senderForm).find("." + assessorType + "_client_id").val();
    var aDd = $(senderForm).find("." + assessorType + "_id");
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getFacilitators", {"token": getToken(), "client_id": cid, "user_id": selectedAssesor}, function (s, data) {
            addOptions(aDd, data.assessors, 'user_id', 'name');
            if (selectedAssesor != undefined)
                for (i = 0; i < data.assessors.length; i++)
                {
                    if (selectedAssesor.indexOf(data.assessors[i].user_id) > -1)
                        aDd.find('option[value=' + data.assessors[i].user_id + ']').attr('selected', 'selected');
                }

        }, showErrorMsgInMsgBox);


}
//Added by Vikas for facilitator

function getExternalAssesorListForAssessment(senderForm) {
    allAssessors = [];
    $('.team_row .external_assessor_id, .team_row .team_external_assessor_id ').each(function () {
        $(this).val() != '' ? allAssessors.push($(this).val()) : '';
    });

}

function ReloadExternalAssesorTeamMembersListForAssessment(senderForm, assessorId, memberId, currObjVal) {
    var cid = $(senderForm).find("#" + assessorId).val();
    var aDd = $(senderForm).find("#" + memberId);
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getExternalAssessors", {"token": getToken(), "client_id": cid}, function (s, data) {
            console.log(data.assessors.length)
            for (var i = 0; i < data.assessors.length; i++)
            {
                if ($.inArray(data.assessors[i].user_id, allAssessors) >= 0 && data.assessors[i].user_id != currObjVal)
                    data.assessors.splice(i, 1);
            }
            $.when(addOptions(aDd, data.assessors, 'user_id', 'name')).then(function () {
                currObjVal != '' ? aDd.find('option[value=' + currObjVal + ']').attr('selected', 'selected') : '';
            });
        }, showErrorMsgInMsgBox);
}

//Added by Vikas for workshop add
function ReloadFacilitatorTeamMembersListForWorkshop(senderForm, assessorId, memberId, currObjVal) {
    var cid = $(senderForm).find("#" + assessorId).val();
    var aDd = $(senderForm).find("#" + memberId);
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getFacilitators", {"token": getToken(), "client_id": cid}, function (s, data) {
            console.log(allAssessors)
            for (var i = 0; i < data.assessors.length; i++)
            {
                if ($.inArray(data.assessors[i].user_id, allAssessors) >= 0 && data.assessors[i].user_id != currObjVal)
                    data.assessors.splice(i, 1);
            }
            $.when(addOptions(aDd, data.assessors, 'user_id', 'name')).then(function () {
                currObjVal != '' ? aDd.find('option[value=' + currObjVal + ']').attr('selected', 'selected') : '';
            });
        }, showErrorMsgInMsgBox);
}
//Added by deepak for add facilitator in school review
function ReloadFacilitatorTeamMembersList(senderForm, assessorId, memberId, currObjVal) {
    var cid = $(senderForm).find("#" + assessorId).val();
    var aDd = $(senderForm).find("#" + memberId);
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getFacilitators", {"token": getToken(), "client_id": cid}, function (s, data) {
            for (var i = 0; i < data.assessors.length; i++)
            {
                if ($.inArray(data.assessors[i].user_id, allFacilitators) >= 0 && data.assessors[i].user_id != currObjVal)
                    data.assessors.splice(i, 1);
            }

            $.when(addOptions(aDd, data.assessors, 'user_id', 'name')).then(function () {
                currObjVal != '' ? aDd.find('option[value=' + currObjVal + ']').attr('selected', 'selected') : '';
            });
        }, showErrorMsgInMsgBox);
}

function ReloadUsersTeamMembersListForWorkshop(senderForm, assessorId, memberId, currObjVal) {
    var cid = $(senderForm).find("#" + assessorId).val();
    var aDd = $(senderForm).find("#" + memberId);
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, "getUsers", {"token": getToken(), "client_id": cid}, function (s, data) {
            console.log(data.assessors.length)
            for (var i = 0; i < data.assessors.length; i++)
            {
                if ($.inArray(data.assessors[i].user_id, allAssessors) >= 0 && data.assessors[i].user_id != currObjVal)
                    data.assessors.splice(i, 1);
            }
            $.when(addOptions(aDd, data.assessors, 'user_id', 'name')).then(function () {
                currObjVal != '' ? aDd.find('option[value=' + currObjVal + ']').attr('selected', 'selected') : '';
            });
        }, showErrorMsgInMsgBox);
}
//Added by Vikas for workshop add

function loadAssesorListForSchoolAssessment(cid, senderForm, assessorType, removeIds) {
    var aDd = $('#' + senderForm + ' .external_assessor_id').last();
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, assessorType == "external" ? "getExternalAssessors" : "getInternalAssessors", {"token": getToken(), "client_id": cid}, function (s, data) {
            for (var i = 0; i < data.assessors.length; i++) {
                if ($.inArray(data.assessors[i].user_id, removeIds) >= 0)
                {
                    data.assessors.splice(i, 1);
                }
            }
            addOptions(aDd, data.assessors, 'user_id', 'name');
        }, showErrorMsgInMsgBox);
}
function loadAssesorListForEditAssessment(senderForm, assessorType, selectedAssesor, isEditable = 0) {
    var cid = $(senderForm).find("." + assessorType + "_client_id").val();
    var aDd = $(senderForm).find("." + assessorType + "_assessor_id");
    aDd.find("option").next().remove();
    if (cid > 0)
        apiCall(senderForm, assessorType == "external" ? "getExternalAssessors" : "getEditInternalAssessors", {"token": getToken(), "client_id": cid, "user_id": selectedAssesor, "isEditable": isEditable}, function (s, data) {
            addOptions(aDd, data.assessors, 'user_id', 'name');
            if (assessorType == 'external')
            {
                allAssessors = data.assessors;
            }
            if (selectedAssesor != undefined)
                for (i = 0; i < data.assessors.length; i++)
                {
                    if (selectedAssesor.indexOf(data.assessors[i].user_id) > -1)
                        aDd.find('option[value=' + data.assessors[i].user_id + ']').attr('selected', 'selected');
                }

        }, showErrorMsgInMsgBox);


}

function ajaxCall(senderForm, controller, action, postData, queryString, onSuccess, onError, onSessionOut, setExtraVariables) {
    serverCall(senderForm, controller, action, 'ajaxRequest=1&' + queryString, postData, onSuccess, onError, onSessionOut, setExtraVariables);
}

function apiCall(senderForm, action, postData, onSuccess, onError, onSessionOut, setExtraVariables, async) {
    serverCall(senderForm, 'api', action, '', postData, onSuccess, onError, onSessionOut, setExtraVariables, async);
}

function serverCall(senderForm, controller, action, queryString, postData, onSuccess, onError, onSessionOut, setExtraVariables, async) {
    if (typeof (async) === 'undefined')
        async = true;
    $(senderForm).find(".ajaxMsg").removeClass("active");
    var ajaxParamObj = {
        url: "?controller=" + controller + "&action=" + action + "&" + queryString,
        method: "post",
        data: postData,
        dataType: 'json',
        async: async,

        beforeSend: function () {
            if (action != "actionplantip") {
                $("#ajaxLoading").show();
            }
        },
        complete: function () {
            if (action != "actionplantip") {
                $("#ajaxLoading").hide();
            }
        },
        success: function (rData) {
            if (rData != undefined && rData != null && rData.status != undefined) {
                if (rData.status == -1) {
                    onSessionOut != undefined ? onSessionOut(senderForm, rData) : '';
                    sessionExpired();
                } else if (rData.status == 1) {
                    onSuccess != undefined ? onSuccess(senderForm, rData) : '';
                } else {
                    onError != undefined ? onError(senderForm, rData.message, rData) : '';
                }
            } else {
                onError != undefined ? onError(senderForm, 'Unknown response from server') : '';
            }
            ''
        },
        error: function (a, status) {
            var msg = "Error while connecting to server";
            if (status == "timeout")
                msg = 'Request time out';
            else if (status == "parsererror")
                msg = 'Unknown response from server';
            onError != undefined ? onError(senderForm, msg) : '';
        }
    };

    if (setExtraVariables != undefined && setExtraVariables != null && typeof setExtraVariables == "object") {
        for (var objKey in setExtraVariables) {
            ajaxParamObj[objKey] = setExtraVariables[objKey];
        }
    }

    $.ajax(ajaxParamObj);

}
function webApiCall(senderForm, action, postData, queryString, onSuccess, onError) {
    $(senderForm).find(".ajaxMsg").removeClass("active");
    $.ajax({
        url: "?controller=web" + "&action=" + action + "&" + queryString,
        method: "post",
        data: postData,
        dataType: 'json',
        beforeSend: function () {
            $("#ajaxLoading").show();
        },
        complete: function () {
            $("#ajaxLoading").hide();
        },
        success: function (rData) {
            if (rData != undefined && rData != null && rData.status != undefined) {
                if (rData.status == 1) {
                    onSuccess != undefined ? onSuccess(senderForm, rData) : '';
                } else {
                    onError != undefined ? onError(senderForm, rData.message, rData) : '';
                }
            } else {
                onError != undefined ? onError(senderForm, 'Unknown response from server') : '';
            }
        },
        error: function (a, status) {
            var msg = "Error while connecting to server";
            if (status == "parsererror")
                msg = 'Unknown response from server';
            onError != undefined ? onError(senderForm, msg) : '';
        }
    });
}

function showSuccessMsgInMsgBox(s, data) {
    $("#createresource").show();
    $(s).find(".ajaxMsg").show();
    $(s).find(".ajaxMsg").removeClass("danger warning info").html(data.message).addClass("success active");
    $("#createresource").delay(2000).fadeOut();
}

function showErrorMsgInMsgBox(s, msg) {
    $("#createresource").show();
    $(s).find(".ajaxMsg").removeClass("success warning info").html(msg).addClass("danger active");
    $("#createresource").delay(2000).fadeOut();
}


function showErrorMsgInMsgBoxSelfReview(s, msg) {
    //alert("ok"+msg);
    $(".internal_client_id").val("");
    $("#createresource").show();
    $(s).find(".ajaxMsg").removeClass("success warning info").html(msg).addClass("danger active");
    $("#createresource").delay(2000).fadeOut();
    alert("Info: " + msg);
}

function showAssErrorMsgInMsgBox(s, msg, userId) {
    var msgId = '';
    if (userId) {
        msgId = "#createresource_" + userId;
    } else {
        msgId = "#createresource";
    }
    $(s).find(msgId).show();
    $(s).find(msgId).removeClass("success warning info").html(msg).addClass("danger active");
    $(s).find(msgId).delay(2000).fadeOut();
}

function showAssSuccessMsgInMsgBox(s, data, userId) {
    var msgId = '';
    if (userId) {
        msgId = "#createresource_" + userId;
    } else {
        msgId = "#createresource";
    }
    $(s).find(msgId).show();
    $(s).find(msgId).removeClass("danger warning info").html(data.message).addClass("success active");
    $(s).find(msgId).delay(2000).fadeOut();
}


function sessionExpired() {
    $("#login_popup .ajaxMsg").html('Session Expired. Please login again.');
    $("#login_popup").find("input[type=email],input[type=password]").val('');
    $("#login_popup_wrap").addClass("active");
}
function getToken() {
    return getCookie("ADH_TOKEN");
}
function setToken(token) {
    setCookie("ADH_TOKEN", token);
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1);
        if (c.indexOf(name) == 0)
            return c.substring(name.length, c.length);
    }
    return "";
}

function setCookie(cname, value) {
    document.cookie = cname + "=" + value;
}

function addOptions(to, list, key, value) {
    for (var i = 0; i < list.length; i++)
        to.append('<option value="' + list[i][key] + '">' + list[i][value] + '</option>');
}

function addOptionsDisabled(to, list, key, value, list1) {
    for (var i = 0; i < list.length; i++) {
        if ($.inArray(list[i][key], list1) > -1) {
            to.append('<option value="' + list[i][key] + '" >' + list[i][value] + '</option>');
        } else {
            to.append('<option value="' + list[i][key] + '" disabled="disabled">' + list[i][value] + '</option>');
        }
    }
}

function addOptions_Optgroup(to, list, key, value, type_opt) {
    var optgroup = $('<optgroup>');
    optgroup.attr('label', type_opt);
    for (var i = 0; i < list.length; i++)
        optgroup.append('<option value="' + list[i][key] + '">' + list[i][value] + '</option>');

    to.append(optgroup);
}

function validate(form) {
    var valid = true
//	$(form).find(".")
}

function deSerialize(queryString) {
    var iH = queryString.indexOf("#");
    var Arr = queryString.substring(queryString.indexOf("?") + 1, iH == -1 ? queryString.length : iH).split("&");
    var Obj = {};
    for (var i = 0; i < Arr.length; i++) {
        var t = Arr[i].split("=");
        Obj[t[0]] = t.length > 1 ? t[1] : "";
    }
    return Obj;
}

+function ($) {
    jQuery.createModal = function (id, title, content, size, top, modalclass) {
        var html = '<div id="popup-' + id + '" class="modal fade" data-backdrop="static" data-keyboard="false"><div class="modal-dialog ' + (modalclass != undefined ? modalclass : '') + '" style="' + (top != undefined && top > 0 ? 'margin-top:' + top + 'px;' : '') + (size != undefined && size > 0 ? 'width:' + size + 'px;' : '') + '"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + title + '</h4></div><div class="modal-body">' + content + '</div><!--<div class="modal-footer"><button type="button" class="btn btn-primary" data-dismiss="modal">Close</button></div>--> </div></div></div>';
        $("#popup_wrapper #popup-" + id).remove();
        $("#popup_wrapper").append(html);
        $("#popup_wrapper #popup-" + id).modal("show");
    }
}(jQuery);

function isValidEmail(email) {
    var pattern = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    return pattern.test(email);
}

function isValidUrl(u) {
    u = u.split(".");
    return u.length > 1 && u[0].length > 1 && u[1].length > 1 ? true : false;
}
function addError(c, m, k) {
    var hasKey = k == undefined || k == null || k == "" || $("#aqsf_" + k).length == 0 ? false : true;
    $(c).append('<div class="alert alert-danger mt25 alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="' + (hasKey ? 'hasKey' : '') + '" data-id="' + (hasKey ? k : '') + '">' + m + '</span></div>');
}
function isDiagnosticName() {
    var ctr = $(this).parents('.sortable-form').data('for') != undefined ? "#" + $(this).parents(".sortable-form").data('for') + " " : "";
    var diagnosticName = $(ctr + "#diagnostic_name").val();
    if (diagnosticName.trim() == '') {
        alert("Please fill diagnostic title!");
        return false;
    }
    return true;

}
function isValidText(t) {

    if (t == null || t == "" || t.trim() == '')
        return false;

    return true;

}
// function for enable and disable the role checkbox on 12-05-2016 by Mohit
function checkboxEnableDisable(id, role_id, class_name, login_user_role) {
    if (role_id == 8) {
        if ($('#' + id + role_id).is(':checked')) {
            $('input.' + class_name).prop('disabled', true);
            $('#' + id + role_id).removeAttr('disabled');
        } else {
            $('input.' + class_name).prop('disabled', false);
        }
    } else {
        if ($('#' + id + role_id).is(':checked')) {
            $('#' + id + '8').prop('disabled', true);
        } else {
            if ($('input.' + class_name).is(':checked')) {
                $('#' + id + '8').prop('disabled', true);
            } else {
                if (login_user_role == 1) {
                    $('#' + id + '8').prop('disabled', false);
                } else {
                    $('#' + id + '8').prop('disabled', true);
                }
            }
        }
    }
}

//function for check & uncheck checkbox
function checkall() {
    if (document.frm.tickall.checked) {
        for (i = 0; i < document.frm.elements.length; i++)
        {
            if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].id == "delid")
            {
                document.frm.elements[i].checked = true
            }
        }
    } else {
        for (i = 0; i < document.frm.elements.length; i++)
        {
            for (i = 0; i < document.frm.elements.length; i++)
            {
                if (document.frm.elements[i].type == "checkbox" && document.frm.elements[i].id == "delid")
                {
                    document.frm.elements[i].checked = false
                }
            }
        }
    }
}



// function for getting alert count
function alertCounts() {
    $.ajax({
        type: 'post',
        url: "?controller=api&action=getAlertCount",
        data: {'token': getToken(), 'assessor_value': $('#assessor_value').val(), 'review_value': $('#review_value').val()},
        async: true,
        cache: false,
        timeout: 50000,
        success: function (response) {
            var a = $.parseJSON(response);
            addMessage("new", a);
            setTimeout(alertCounts, 1000);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            setTimeout(alertCounts, 15000);
        }
    });
}
// function for updating the updated alert count on 08-08-2016 by mohit kumar
function addMessage(type, a) {
    if (a.status == 1) {
        $('b#totalAlertCount').text(a.totalCount);
        var assessorRef = Number(a.assessorCount) > 0 ? 1 : 0;
        var reviewRef = Number(a.reviewCount) > 0 ? 1 : 0;
        $('a#assessor_count').text("New Assessor - " + a.assessorCount).attr('href', '?controller=user&action=user&ref=' + assessorRef);
        $('a#review_count').text("New Review - " + a.reviewCount).attr('href', '?controller=assessment&action=assessment&ref=' + reviewRef);
    }
}

// Callback that creates and populates a data table, 
// instantiates the pie chart, passes in the data and
// draws it on 17-06-2016 by Mohit Kumar

function drawChart() {
    // Create the data table.

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Role');
    data.addColumn('number', 'Review Count');
    var jsonPieChartData = $.ajax({
        type: 'post',
        url: "?controller=api&action=getAllAssessorsReviewCount",
        data: {'token': getToken()},
        async: false,
        cache: false
    }).responseText;
    var a = $.parseJSON(jsonPieChartData);

    if (a.count.Lead_Assessor == 0 && a.count.Apprentice == 0 && a.count.Associate == 0 && a.count.Intern == 0) {
        $('div#chart_div').html('There is no review count for any users');
    } else {
        var lead = Number(a.count.Lead_Assessor) != '' ? Number(a.count.Lead_Assessor) : 0;
        var apprentice = Number(a.count.Apprentice) != '' ? Number(a.count.Apprentice) : 0;
        var associate = Number(a.count.Associate) != '' ? Number(a.count.Associate) : 0;
        var intern = Number(a.count.Intern) != '' ? Number(a.count.Intern) : 0;

        data.addRows([
            ['Lead Assessor', lead],
            ['Apprentice', apprentice],
            ['Associate', associate],
            ['Intern', intern]
        ]);

        // Set chart options
        var options = {'title': 'External Assessor Review Counts',
            'width': 600,
            'height': 350,
            'backgroundColor': '#a4a4a4',
            'is3D': true
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

}

function drawPie(pieName, dataSet, selectString, colors, margin, outerRadius, innerRadius, sortArcs) {

    // Color Scale Handling...


    var colorScale = d3.scale.category20c();
    switch (colors)
    {
        case "colorScale10":
            colorScale = d3.scale.category10();
            break;
        case "colorScale20":
            colorScale = d3.scale.category20();
            break;
        case "colorScale20b":
            colorScale = d3.scale.category20b();
            break;
        case "colorScale20c":
            colorScale = d3.scale.category20c();
            break;
        default:
            colorScale = d3.scale.category20c();
    }


    var canvasWidth = 700;
    var pieWidthTotal = outerRadius * 2;

    var pieCenterX = outerRadius + margin / 2;
    var pieCenterY = outerRadius + margin / 2;
    var legendBulletOffset = 30;
    var legendVerticalOffset = outerRadius - margin;
    var legendTextOffset = 20;
    var textVerticalSpace = 20;

    var canvasHeight = 0;
    var pieDrivenHeight = outerRadius * 2 + margin * 2;
    var legendTextDrivenHeight = (dataSet.length * textVerticalSpace) + margin * 2;
    // Autoadjust Canvas Height
    if (pieDrivenHeight >= legendTextDrivenHeight)
    {
        canvasHeight = pieDrivenHeight;
    } else
    {
        canvasHeight = legendTextDrivenHeight;
    }

    var x = d3.scale.linear().domain([0, d3.max(dataSet, function (d) {
            return d.magnitude;
        })]).rangeRound([0, pieWidthTotal]);
    var y = d3.scale.linear().domain([0, dataSet.length]).range([0, (dataSet.length * 20)]);


    var synchronizedMouseOver = function () {
        var arc = d3.select(this);
        var indexValue = arc.attr("index_value");

        var arcSelector = "." + "pie-" + pieName + "-arc-" + indexValue;
        var selectedArc = d3.selectAll(arcSelector);
        selectedArc.style("fill", "Maroon");

        var bulletSelector = "." + "pie-" + pieName + "-legendBullet-" + indexValue;
        var selectedLegendBullet = d3.selectAll(bulletSelector);
        selectedLegendBullet.style("fill", "Maroon");

        var textSelector = "." + "pie-" + pieName + "-legendText-" + indexValue;
        var selectedLegendText = d3.selectAll(textSelector);
        selectedLegendText.style("fill", "Maroon");
    };

    var synchronizedMouseOut = function () {
        var arc = d3.select(this);
        var indexValue = arc.attr("index_value");

        var arcSelector = "." + "pie-" + pieName + "-arc-" + indexValue;
        var selectedArc = d3.selectAll(arcSelector);
        var colorValue = selectedArc.attr("color_value");
        selectedArc.style("fill", colorValue);

        var bulletSelector = "." + "pie-" + pieName + "-legendBullet-" + indexValue;
        var selectedLegendBullet = d3.selectAll(bulletSelector);
        var colorValue = selectedLegendBullet.attr("color_value");
        selectedLegendBullet.style("fill", colorValue);

        var textSelector = "." + "pie-" + pieName + "-legendText-" + indexValue;
        var selectedLegendText = d3.selectAll(textSelector);
        selectedLegendText.style("fill", "Blue");
    };

    var tweenPie = function (b) {
        b.innerRadius = 0;
        var i = d3.interpolate({startAngle: 0, endAngle: 0}, b);
        return function (t) {
            return arc(i(t));
        };
    }

    // Create a drawing canvas...
    var canvas = d3.select(selectString)
            .append("svg:svg") //create the SVG element inside the <body>
            .data([dataSet]) //associate our data with the document
            .attr("width", canvasWidth) //set the width of the canvas
            .attr("height", canvasHeight) //set the height of the canvas
            .append("svg:g") //make a group to hold our pie chart
            .attr("transform", "translate(" + pieCenterX + "," + pieCenterY + ")") // Set center of pie

    // Define an arc generator. This will create <path> elements for using arc data.
    var arc = d3.svg.arc()
            .innerRadius(innerRadius) // Causes center of pie to be hollow
            .outerRadius(outerRadius);

    // Define a pie layout: the pie angle encodes the value of dataSet.
    // Since our data is in the form of a post-parsed CSV string, the
    // values are Strings which we coerce to Numbers.
    var pie = d3.layout.pie()
            .value(function (d) {
                return d.magnitude;
            })
            .sort(function (a, b) {
                if (sortArcs == 1) {
                    return b.magnitude - a.magnitude;
                } else {
                    return null;
                }
            });

    // Select all <g> elements with class slice (there aren't any yet)
    var arcs = canvas.selectAll("g.slice")
            // Associate the generated pie data (an array of arcs, each having startAngle,
            // endAngle and value properties) 
            .data(pie)
            // This will create <g> elements for every "extra" data element that should be associated
            // with a selection. The result is creating a <g> for every object in the data array
            // Create a group to hold each slice (we will have a <path> and a <text>      // element associated with each slice)
            .enter().append("svg:a")
            .attr("xlink:href", function (d) {
                return d.data.link;
            })
            .append("svg:g")
            .attr("class", "slice")    //allow us to style things in the slices (like text)
            // Set the color for each slice to be chosen from the color function defined above
            // This creates the actual SVG path using the associated data (pie) with the arc drawing function
            .style("stroke", "White")
            .attr("d", arc);

    arcs.append("svg:path")
            // Set the color for each slice to be chosen from the color function defined above
            // This creates the actual SVG path using the associated data (pie) with the arc drawing function
            .attr("fill", function (d, i) {
                return colorScale(i);
            })
            .attr("color_value", function (d, i) {
                return colorScale(i);
            }) // Bar fill color...
            .attr("index_value", function (d, i) {
                return "index-" + i;
            })
            .attr("class", function (d, i) {
                return "pie-" + pieName + "-arc-index-" + i;
            })
            .style("stroke", "White")
            .attr("d", arc)
            .on('mouseover', synchronizedMouseOver)
            .on("mouseout", synchronizedMouseOut)
            .transition()
            .ease("bounce")
            .duration(2000)
            .delay(function (d, i) {
                return i * 50;
            })
            .attrTween("d", tweenPie);

    // Add a magnitude value to the larger arcs, translated to the arc centroid and rotated.
    arcs.filter(function (d) {
        return d.endAngle - d.startAngle > .2;
    }).append("svg:text")
            .attr("dy", ".35em")
            .attr("text-anchor", "middle")
            //.attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")rotate(" + angle(d) + ")"; })
            .attr("transform", function (d) { //set the label's origin to the center of the arc
                //we have to make sure to set these before calling arc.centroid
                d.outerRadius = outerRadius; // Set Outer Coordinate
                d.innerRadius = innerRadius; // Set Inner Coordinate
                return "translate(" + arc.centroid(d) + ")rotate(" + angle(d) + ")";
            })
            .style("fill", "White")
            .style("font", "normal 12px Arial")
            .text(function (d) {
                return d.data.magnitude;
            });

    // Computes the angle of an arc, converting from radians to degrees.
    function angle(d) {
        var a = (d.startAngle + d.endAngle) * 90 / Math.PI - 90;
        return a > 90 ? a - 180 : a;
    }

    // Plot the bullet circles...
    canvas.selectAll("circle")
            .data(dataSet).enter().append("svg:circle") // Append circle elements
            .attr("cx", pieWidthTotal + legendBulletOffset)
            .attr("cy", function (d, i) {
                return i * textVerticalSpace - legendVerticalOffset;
            })
            .attr("stroke-width", ".5")
            .style("fill", function (d, i) {
                return colorScale(i);
            }) // Bullet fill color
            .attr("r", 5)
            .attr("color_value", function (d, i) {
                return colorScale(i);
            }) // Bar fill color...
            .attr("index_value", function (d, i) {
                return "index-" + i;
            })
            .attr("class", function (d, i) {
                return "pie-" + pieName + "-legendBullet-index-" + i;
            })
            .on('mouseover', synchronizedMouseOver)
            .on("mouseout", synchronizedMouseOut);

    // Create hyper linked text at right that acts as label key...
    canvas.selectAll("a.legend_link")
            .data(dataSet) // Instruct to bind dataSet to text elements
            .enter().append("svg:a") // Append legend elements
            .attr("xlink:href", function (d) {
                return d.link;
            })
            .append("text")
            .attr("text-anchor", "center")
            .attr("x", pieWidthTotal + legendBulletOffset + legendTextOffset)
            .attr("y", function (d, i) {
                return i * textVerticalSpace - legendVerticalOffset;
            })
            .attr("dx", 0)
            .attr("dy", "5px") // Controls padding to place text in alignment with bullets
            .text(function (d) {
                return d.legendLabel;
            })
            .attr("color_value", function (d, i) {
                return colorScale(i);
            }) // Bar fill color...
            .attr("index_value", function (d, i) {
                return "index-" + i;
            })
            .attr("class", function (d, i) {
                return "pie-" + pieName + "-legendText-index-" + i;
            })
            .style("fill", "Blue")
            .style("font", "normal 1.5em Arial")
            .on('mouseover', synchronizedMouseOver)
            .on("mouseout", synchronizedMouseOut);

}

// function to get schools 
function getSchools(network_id, senderFrom) {
    var contnr = $(this).parents('form').first();

    var postData = "network_id=" + network_id + "&token=" + getToken();
    apiCall(senderFrom, "getSchoolsInNetworks", postData, function (s, data) {
        if (data.message != '') {
            $('#rec_schools').show();
            $('#rec_users').hide();
            $('#errors').hide();
            var aDd = $("#rec_school");
            aDd.find("option").next().remove();
            addOptions(aDd, data.message, 'client_id', 'client_name');
            $(contnr).find("#rec_schools").show();
        } else {
            $('#rec_schools').hide();
            $('#errors').html('No record exist for this network');
            $('#errors').show();
        }
    }, showErrorMsgInMsgBox);
}

// function to get all schools by option type 
function getAllSchools(option_type, senderFrom) {
    var contnr = $(this).parents('form').first();
    if (option_type != '1') {
        var postData = "school_related_to=" + option_type + "&token=" + getToken();
        apiCall(senderFrom, "getAllSchools", postData, function (s, data) {
            if (data.message != '') {
                $('#rec_schools').show();
                $('#errors').hide();
                $('#networks').hide();
                $('#provinces').hide();
                $("#rec_users").hide();
                var aDd = $("#rec_school");
                aDd.find("option").next().remove();
                addOptions(aDd, data.message, 'client_id', 'client_name');
                $(contnr).find("#rec_schools").show();
            } else {
                $('#rec_schools').hide();
                $('#errors').html('No record exist for this network');
                $('#errors').show();
            }
        }, showErrorMsgInMsgBox);
    } else {
        $('#rec_schools').hide();
        $("#rec_users").hide();
        $('#errors').hide();
        $('#provinces').hide();
        $('#networks').show();
    }
}


$(document).on("change", "#create_block_data_form #rec_state", function () {

    var contnr = $(this).parents('form').first();
    var state_id = $('#rec_state').val();
    var aDd = $("#zones .zones-list-dropdown");
    aDd.find("option").remove();

    if (state_id != '' && state_id !== null && state_id !== undefined) {
        var postData = "state_id=" + state_id + "&token=" + getToken();

        apiCall(this, "getZonesInState", postData, function (s, data) {
            if (data.message != '') {
                $('#errors').hide();
                $('#provinces').show();
                $(aDd).append($("<option/>", {
                    value: '',
                    text: 'Select Zone'
                }));
                addOptions(aDd, data.message, 'zone_id', 'zone_name');
                $(contnr).find("#provinces").show();
                $('#rec_zone').prop('multiple', false);

                $("#rec_zone").multiselect('destroy');
                $('#rec_zone').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth: '420px',
                    maxHeight: ($(window).height() - ($('#rec_state').offset().top + 110)),
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                    },
                });
                $("#rec_block option,#rec_province option,#evd_school option").remove();
                $('#rec_block').append($("<option/>", {value: '', text: 'Select Block'}));

                $(".caret").css('float', 'right');
                $(".caret").css('margin', '8px 0');
            }
        });
    }

});

$(document).on("change", "#create_block_data_form #rec_zone", function () {

    var contnr = $(this).parents('form').first();
    var state_id = $('#rec_state').val();
    var zone_id = $('#rec_zone').val();
    var aDd = $("#networks .blocks-list-dropdown");
    aDd.find("option").remove();

    if (state_id != '' && state_id !== null && state_id !== undefined && zone_id != '' && zone_id !== null && zone_id !== undefined) {
        var postData = "state_id=" + state_id + "&zone_id=" + zone_id + "&token=" + getToken();

        apiCall(this, "getBlocksInZone", postData, function (s, data) {

            if (data.message != '') {
                $('#errors').hide();
                $('#provinces').show();
                $(aDd).append($("<option/>", {
                    value: '',
                    text: 'Select Block'
                }));
                addOptions(aDd, data.message, 'network_id', 'network_name');
                $(contnr).find("#provinces").show();
                $('#rec_block').prop('multiple', false);

                $("#rec_block").multiselect('destroy');
                $('#rec_block').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    enableCaseInsensitiveFiltering: true,
                    buttonWidth: '420px',
                    maxHeight: ($(window).height() - ($('#rec_zone').offset().top + 110)),
                    templates: {
                        filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                        ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                    },
                });

                $(".caret").css('float', 'right');
                $(".caret").css('margin', '8px 0');
            }
        });
    }

});

$(document).on("change", "#create_block_data_form #rec_block", function () {

    var contnr = $(this).parents('form').first();
    var state_id = $('#rec_state').val();
    var zone_id = $('#rec_zone').val();
    var block_id = $('#rec_block').val();
    var aDd = $("#provinces .province-list-dropdown");
    aDd.find("option").remove();

    if (state_id != '' && state_id !== null && state_id !== undefined && zone_id != '' && zone_id !== null && zone_id !== undefined && block_id != '' && block_id !== null && block_id !== undefined) {
        var postData = "state_id=" + state_id + "&zone_id=" + zone_id + "&network_id=" + block_id + "&token=" + getToken();

        apiCall(this, "getProvincesInNetwork", postData, function (s, data) {

            if (data.message != '') {
                $('#errors').hide();
                $('#provinces').show();
                $(aDd).append($("<option/>", {
                    value: '',
                    text: 'Select Hub'
                }));
                addOptions(aDd, data.message, 'province_id', 'province_name');
                $(contnr).find("#provinces").show();

                $(".caret").css('float', 'right');
                $(".caret").css('margin', '8px 0');
            }
        });
    }

});



$(document).on('submit', "#create_block_data_form", function (e) {
    postData = $(this).serialize() + "&token=" + getToken();
    apiCall(this, "createBlockReport", postData,
            function (s, data) {

                showSuccessMsgInMsgBox(s, data);

                $(s).find("input[type=text]").val('');


            }, showErrorMsgInMsgBox);
    return false;
});

//Add js for tree structure Start
$(document).on("click", ".execUrlZ", function () {
    var hook = $(this).data('validator');
    if (hook != undefined && !window[hook]()) {
        return;
    }
    var e = $(this);
    var urlZ = e.attr("href") + "&state_id=" + $("#scl_state").val();
    var urlObj = deSerialize(urlZ);
    var c = urlObj.controller;
    var a = urlObj.action;
    delete urlObj.controller;
    delete urlObj.action;
    var postData = {};
    var pSelector = e.data('postdata');
    if (pSelector != undefined && pSelector.length > 0) {

        $(pSelector).each(function (i, e2) {
            var n = $(e2).attr("name");
            var v = $(e2).val();
            if (n != undefined && n != "" && v != "") {
                if (n.indexOf("[") > 0 && n.indexOf("]") > 0) {
                    n = n.substring(0, n.indexOf("["));
                    if (postData[n] == undefined)
                        postData[n] = [];
                    postData[n].push(v);
                } else
                    postData[n] = v;
            }
        });
    }
    if (e.data("postformid") != undefined) {
        postData[e.data("postformid")] = e.parents("form").first().attr("id");
    }
    load_popup_page(e.parents("form"), c, a, postData, $.param(urlObj), e.data("size"), e.data("top"), e.data('modalclass'));
    return false;
});

$(document).on("click", ".execUrlB", function () {
    var hook = $(this).data('validator');
    var action=$.urlParam('action');
    var is_edit=$('#is_edit').val();
    var is_create=$('#is_create').val();
    if (hook != undefined && !window[hook]()) {
        return;
    }
    var e = $(this);
     //alert(is_edit+action);
    if(action=="createUser" || action=="client" || action=="user" || action=="editUser" || action=="createSchoolAssessment"){
        
    if(is_create == 1 && is_edit != 1){
           
            var urlZ = e.attr("href") + "&state_id=" + $("#create_school_form #scl_state").val() + "&zone_id=" +         $("#create_school_form #scl_zone").val();
    }else if(is_edit == 1){
       var urlZ = e.attr("href") + "&state_id=" + $("#edit_school_form #scl_state").val() + "&zone_id=" + $("#edit_school_form #scl_zone").val(); 
    }
    }else{
        var urlZ = e.attr("href") + "&state_id=" + $("#scl_state").val() + "&zone_id=" + $("#scl_zone").val();
    }
    
    var urlObj = deSerialize(urlZ);
    var c = urlObj.controller;
    var a = urlObj.action;
    delete urlObj.controller;
    delete urlObj.action;
    var postData = {};
    var pSelector = e.data('postdata');
    if (pSelector != undefined && pSelector.length > 0) {

        $(pSelector).each(function (i, e2) {
            var n = $(e2).attr("name");
            var v = $(e2).val();
            if (n != undefined && n != "" && v != "") {
                if (n.indexOf("[") > 0 && n.indexOf("]") > 0) {
                    n = n.substring(0, n.indexOf("["));
                    if (postData[n] == undefined)
                        postData[n] = [];
                    postData[n].push(v);
                } else
                    postData[n] = v;
            }
        });
    }
    if (e.data("postformid") != undefined) {
        postData[e.data("postformid")] = e.parents("form").first().attr("id");
    }
    load_popup_page(e.parents("form"), c, a, postData, $.param(urlObj), e.data("size"), e.data("top"), e.data('modalclass'));
    return false;
});

$(document).on("click", ".execUrlC", function () {
    var hook = $(this).data('validator');
    var action=$.urlParam('action');
    var is_edit=$('#is_edit').val();
    var is_create=$('#is_create').val();
    
    if (hook != undefined && !window[hook]()) {
        return;
    }
    var e = $(this);
    
    if(action=="createUser" || action=="client" || action=="user" || action=="editUser" || action=="createSchoolAssessment"){ 
        if(is_create == 1 && is_edit != 1){
	var urlZ = e.attr("href") + "&state_id=" + $("#create_school_form #scl_state").val() + "&zone_id=" + $("#create_school_form #scl_zone").val() + "&block_id=" + $("#create_school_form #scl_block").val();
    }else if(is_edit == 1){
       var urlZ = e.attr("href") + "&state_id=" + $("#edit_school_form #scl_state").val() + "&zone_id=" + $("#edit_school_form #scl_zone").val() + "&block_id=" + $("#edit_school_form #scl_block").val();
    }
}else{
 	var urlZ = e.attr("href") + "&state_id=" + $("#scl_state").val() + "&zone_id=" + $("#scl_zone").val() + "&block_id=" + $("#scl_block").val();
}

    
    var urlObj = deSerialize(urlZ);
    var c = urlObj.controller;
    var a = urlObj.action;
    delete urlObj.controller;
    delete urlObj.action;
    var postData = {};
    var pSelector = e.data('postdata');
    if (pSelector != undefined && pSelector.length > 0) {

        $(pSelector).each(function (i, e2) {
            var n = $(e2).attr("name");
            var v = $(e2).val();
            if (n != undefined && n != "" && v != "") {
                if (n.indexOf("[") > 0 && n.indexOf("]") > 0) {
                    n = n.substring(0, n.indexOf("["));
                    if (postData[n] == undefined)
                        postData[n] = [];
                    postData[n].push(v);
                } else
                    postData[n] = v;
            }
        });
    }
    if (e.data("postformid") != undefined) {
        postData[e.data("postformid")] = e.parents("form").first().attr("id");
    }
    load_popup_page(e.parents("form"), c, a, postData, $.param(urlObj), e.data("size"), e.data("top"), e.data('modalclass'));
    return false;
});


function basename(path) {
    return path.split(/[\\/]/).pop();
}
function getOtherText() {
    if ($('#school_rating_txt').is(':visible')) {
        $("#school_rating_txt").hide();
    } else {
        $("#school_rating_txt").show();
    }
}

/**
 * Set the list opened or closed
 * */
function setStatus(node) {
    var elements = [];
    $(node).each(function () {
        elements.push($(node).nextAll());
    });
    for (var i = 0; i < elements.length; i++) {
        if (elements[i].css('display') == 'none') {
            elements[i].fadeIn(0);
        } else {
            elements[i].fadeOut(0);
        }
    }
    if (elements[0].css('display') != 'none') {
        $(node).addClass('active');
    } else {
        $(node).removeClass('active');
    }
}
// function for displaying the error msgs on 29-07-2016 by Mohit Kumar
function addErrorNew(c, m) {
    var fp = false;
    var i = 0;
    var div = '';
    for (var prop in m) {
        if (i == 0) {
            $('#' + prop).focus();
        }
        $('#' + prop).addClass('errorRed');
        fp = fp ? fp : prop;
        var hasKey = prop == undefined || prop == null || prop == "" || $("#aqsf_" + prop).length == 0 ? false : true;
        div += '<div class="alert alert-danger mt25 alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><span class="' + (hasKey ? 'hasKey' : '') + '" data-id="' + (hasKey ? prop : '') + '">' + m[prop] + '</span></div>';
        i++;
    }
    $(c).html(div);
}

function isNumberKey1(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    //alert(charCode);
    if (charCode >= 48 && charCode <= 57)
    {
        return true;
    } else if (charCode == 46 || charCode == 8)
    {
        return true;
    } else
    {

        return false;
    }
}

function updateUser(postData, s, updateUserProfileData) {


    var isPrincipal = 0;
    var deleteUser = 0;
    var users_id;
    // pass user id for gettig the user data on 16-05-2016 by Mohit Kumar
    var id = $('input[name="id"]').val();
    apiCall(s, "checkUserRole", postData, //for principal user
            function (s, data) {
                isPrincipal = data.duplicate ? 1 : 0;
                if (data.duplicate && confirm(data.message + "Do you really want to add another user with this role?"))
                {
                    users_id = data.duplicate;
                    deleteUser = 1;
                    apiCall(s, "updateUser", postData + "&role_id=6" + "&users_id=" + users_id,
                            function (s, data) {

                                updateUserProfileData(data);

                            }, showErrorMsgInMsgBox);

                } else if (!data.duplicate)
                {

                    apiCall(s, "updateUser", postData,
                            function (s, data) {

                                updateUserProfileData(data);

                            }, showErrorMsgInMsgBox);
                }
            }, showErrorMsgInMsgBox);
    return client_id;

}

//Assessment step 2 data
function getStep2(assessment_id, isEditable = 0) {

    var postData = "assessment_id=" + assessment_id + "&editStatus=" + isEditable + "&token:" + getToken()
    var querystring = '';

    ajaxCall('#create-review-kpa', 'assessment', 'schoolAssessmentData', postData, querystring, function (s, data) {

        $("#ctreateSchoolAssessment-step2").show();
        $("#ctreateSchoolAssessment-step1").hide();
        $("#kpa-step2").html(data.content);
        $(".team_kpa_id").multiselect('destroy');
        $('.team_kpa_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            numberDisplayed: 1,
            buttonWidth: '420px',
            maxHeight: 300,

            templates: {
                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
            }
        });
        toggleKpas();
        
        $(document).on("change", "#create-review-kpa .team_kpa_id,#edit-review-kpa .team_kpa_id ", function () {
            var selectedKpaId = $(this).attr("id");
            var id = '';
            selectedKpaValues = [];
            allKpaValues = [];
            allSelectedKpaValues = [];
            var strVal = $(this).val();
            if (strVal != '' && strVal != undefined) {
                strVal = strVal.toString();
                selectedKpaValues = strVal.split(",");
            }

            $(".team_kpa_id").each(function () {
                var id = $(this).attr("id");
                var allKpaValue = $("#" + id).val();
                if (allKpaValue != '' && allKpaValue != undefined) {
                    allKpaValue = allKpaValue.toString();
                    allKpaValues = $.merge(allKpaValues, allKpaValue.split(","));
                }
            });
            $(".team_kpa_id").each(function () {
                var id = $(this).attr("id");

                if ($(this).attr("id") != selectedKpaId) {

                    $("#" + id + " option").removeAttr('disabled');

                    $.each(allKpaValues, function (index, value) {

                        if ($("#" + id + " option[value='" + value + "']").is(":checked") == false) {

                            $("#" + id + " option[value='" + value + "']").attr("disabled", "disabled");
                        }

                    });

                }

            });

        });


    }, showErrorMsgInMsgBox);
}

//Save kpa for assessment
function updateAssessorsKpas(postData) {

    apiCall('#edit-review-kpa', "editSchoolAssessmentKpa", postData, function (s, data) {
        $(".ajaxMsg").show();
        showSuccessMsgInMsgBox(s, data);
        $(".ajaxMsg").delay(2000).fadeOut();

    }, function (s, data) {
        $(".ajaxMsg").show();
        showErrorMsgInMsgBox(s, data);
        $(".ajaxMsg").delay(2000).fadeOut();
    });

}

function loadAssesorListForAssessment(senderForm, assessorType) {
    var cid = $(senderForm).find("." + assessorType + "_client_id").val();
    var aDd = $(senderForm).find("." + assessorType + "_assessor_id");
    var rDd = $(senderForm).find(".aqs_rounds");
    aDd.find("option").next().remove();

    if (cid > 0)
        apiCall(senderForm, assessorType == "external" ? "getExternalAssessors" : "getInternalAssessors", {"token": getToken(), "client_id": cid}, function (s, data) {

            if (assessorType != "external") {
                rDd.find("option").next().remove();
                addOptionsDisabled(rDd, data.rounds, 'aqs_round', 'aqs_round', data.roundsUnused)
            }
            addOptions(aDd, data.assessors, 'user_id', 'name')
        }, showErrorMsgInMsgBox);
}

(function ($) {
    $(window).on('load', function () {
        $('.nuiHdrBtmBar .container ul.mainNav ul li').each(function () {
            $(this).find('i.fa').on('click', function () {
                $(this).parent('li').find('ul').toggle();
            });
        });
    });
})(jQuery);

$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null) {
       return null;
    }
    return decodeURI(results[1]) || 0;
}

