/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
$(function () {
   $('#create_block_data_form #rec_state,#create_block_data_form #rec_zone,#create_block_data_form #rec_block,#create_block_data_form #rec_province,#create_block_data_form #evd_round,#create_block_data_form #report_type').multiselect({
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
});

$(document).ready(function () {

if($(".filterByAjax [name=assessment_type_id]").val()==4){

$(".filterByAjax [name=report_id]").show();
$(".filterByAjax [name=network_id]").show();
$(".filterByAjax [name=province_id]").show();
$(".filterByAjax [name=client_id]").show();
$(".filterByAjax [name=round_id]").show();

}else{
  
$(".filterByAjax [name=report_id]").hide();
$(".filterByAjax [name=network_id]").hide();
$(".filterByAjax [name=province_id]").hide();
$(".filterByAjax [name=client_id]").hide();
$(".filterByAjax [name=round_id]").hide();

var vars = {};
var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
vars[key] = value;
//alert(vars[key]);
    if(vars[key]=="assessment"){
        $(".filterByAjax [name=network_id]").show();
        $(".filterByAjax [name=province_id]").show();
    }

});
  
}    

$(document).on("change", ".filterByAjax [name=assessment_type_id]", function () {

if($(this).val()==4){

$(".filterByAjax [name=report_id]").show();
$(".filterByAjax [name=network_id]").show();
$(".filterByAjax [name=province_id]").show();
$(".filterByAjax [name=client_id]").show();
$(".filterByAjax [name=round_id]").show();

}else{
  
$(".filterByAjax [name=report_id]").hide();
$(".filterByAjax [name=network_id]").hide();
$(".filterByAjax [name=province_id]").hide();
$(".filterByAjax [name=client_id]").hide();
$(".filterByAjax [name=round_id]").hide();

}    
    
});

});


$(document).on("change", ".filterByAjax [name=province_id]", function () {
        var ProvinceId = $(this).parents('form').find('[name=province_id]').val();        
        var aDd2 = $(this).parents('form').find('[name=client_id]');
        if (ProvinceId > 0) {
            apiCall(this, "getSchoolsInProvinces", {"token": getToken(), "province_id": ProvinceId}, function (s, data) {                
                aDd2.find("option").next().remove();
                addOptions(aDd2, data.message, 'client_id', 'client_name');
            }, showErrorMsgInMsgBox);
        }
        return false;
    });
    
$(document).on("change", ".filterByAjax  [name=report_id]", function () {
    var report_type=$(this).val();
    if(report_type=="11"){
       $(".filterByAjax  [name=client_id]").attr("disabled","disabled");
       $(".filterByAjax  [name=province_id]").removeAttr("disabled");
       //$("#rec_schools .astric").hide();
       //$("#provinces .astric").show();
    }else if(report_type=="12"){
        $(".filterByAjax  [name=client_id]").attr("disabled","disabled");
        $(".filterByAjax  [name=province_id]").attr("disabled","disabled");
        //$("#rec_schools .astric").hide();
        //$("#provinces .astric").hide();
    }else{
        $(".filterByAjax  [name=client_id]").removeAttr("disabled");
        $(".filterByAjax  [name=province_id]").removeAttr("disabled");
        //$("#rec_schools .astric").show();
        //$("#provinces .astric").show();
    }
    
    
});    

//Addead for cluster report
$(function () {
   $('#create_cluster_data_form #rec_state,#create_cluster_data_form #rec_zone,#create_cluster_data_form #rec_block,#create_cluster_data_form #rec_province,#create_cluster_data_form #evd_school,#create_cluster_data_form #evd_round,#create_cluster_data_form #report_type').multiselect({
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
});

    $(document).on("change", "#create_cluster_data_form #rec_state", function () {

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
                    $('#rec_province').append($("<option/>", {value: '', text: 'Select Hub'}));
                    $('#evd_school').append($("<option/>", {value: '', text: 'Select School/Batch'}));
                    $("#rec_block,#rec_province,#evd_school").multiselect('destroy');
                    $('#rec_block,#rec_province,#evd_school').multiselect({buttonWidth: '420px',});
                    $("#rec_block,#rec_province,#evd_school").multiselect('refresh');
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
            });
        }

    });

    $(document).on("change", "#create_cluster_data_form #rec_zone", function () {

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
                    $("#rec_province option,#evd_school option").remove();
                    $('#rec_province').append($("<option/>", {value: '', text: 'Select Hub'}));
                    $('#evd_school').append($("<option/>", {value: '', text: 'Select School/Batch'}));
                    $("#rec_province,#evd_school").multiselect('destroy');
                    $('#rec_province,#evd_school').multiselect({buttonWidth: '420px',});
                    $("#rec_province,#evd_school").multiselect('refresh');
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
            });
        }

    });
    
    $(document).on("change", "#create_cluster_data_form #rec_block", function () {

        var contnr = $(this).parents('form').first();
        var state_id = $('#rec_state').val();
        var zone_id = $('#rec_zone').val();
        var block_id = $('#rec_block').val();
        var aDd = $("#provinces .province-list-dropdown");
        aDd.find("option").remove();

        if (state_id != '' && state_id !== null && state_id !== undefined && zone_id != '' && zone_id !== null && zone_id !== undefined && block_id != '' && block_id !== null && block_id !== undefined) {
            var postData = "state_id=" + state_id + "&zone_id=" + zone_id +"&network_id=" + block_id+ "&token=" + getToken();

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
                    $('#rec_province').prop('multiple', false);

                    $("#rec_province").multiselect('destroy');
                    $('#rec_province').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonWidth: '420px',
                        maxHeight: ($(window).height() - ($('#rec_block').offset().top + 110)),
                        templates: {
                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                            ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                        },
                    });
                    $("#evd_school option").remove();
                    $('#evd_school').append($("<option/>", {value: '', text: 'Select School/Batch'}));
                    $("#evd_school").multiselect('destroy');
                    $('#evd_school').multiselect({buttonWidth: '420px',});
                    $("#evd_school").multiselect('refresh');
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
            });
        }

    });
    
    $(document).on("change", "#create_cluster_data_form #rec_province", function () {

        var contnr = $(this).parents('form').first();
        var provience_ids = $('#rec_province').val();

        expr = /all/;  // no quotes here
        var all_ids = '';
        if (expr.test(provience_ids)) {
            $("#rec_province option").each(function ()
            {
                if ($(this).val() != '' && $(this).val() != 'undefined') {
                    all_ids = all_ids + $(this).val() + ",";
                }
            });
            all_ids = all_ids.substring(0, all_ids.length - 5);
        }

        if (all_ids.length >= 1) {
            provience_ids = all_ids;
        }
        var totselected = 0;
        var valData = provience_ids;
        if (valData !== null) {
            var valNew = valData.toString().split(',');
            totselected = valNew.length;
        }

        var aDd = $("#rec_schools .province-list-dropdown");
        aDd.find("option").remove();
//        $('#evd_school').append($("<option/>", {
//            value: '',
//            text: 'Select School/ Batch'
//        }));

        if (provience_ids !== null && provience_ids !== undefined) {
            $('#errors').hide();
            var postData = "province_id=" + provience_ids + "&token=" + getToken();
            apiCall(contnr, "getSchoolsInProvinces", postData, function (s, data) {
                if (data.status) {
                    addOptions(aDd, data.message, 'client_id', 'client_name');
                    $("#rec_schools").show();
                    $('#evd_school').prop('multiple', true);

                    $("#evd_school").multiselect('destroy');
                    $('#evd_school').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonWidth: '420px',
                        maxHeight: ($(window).height() - ($('#rec_province').offset().top + 110)),
                        templates: {
                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                            ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                        },
                    });
                    $("#evd_school").multiselect('deselect',[data.message[0]['client_id']]);
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
                
            });
        }

    });
    
    $(document).on('submit',"#create_cluster_data_form",function(e){
    	postData = $(this).serialize() + "&token=" + getToken();
        //alert(postData);
    	 apiCall(this, "createClusterReport", postData,
                 function (s, data) {
    		 
                    showSuccessMsgInMsgBox(s, data);
                    
            //$(s).find("select").val('');
            //$(s).find("textarea").val('');
            $(s).find("input[type=text]").val('');
            
                    
                 }, showErrorMsgInMsgBox);
    	 return false;
});


//Addead for all school report

    var refreshData = function(id,name){
        $(id).find("option").remove();
        $(id).multiselect('destroy');
        if(name=='Zone'){
            $(id).append($("<option/>", {value: '', text: 'Select '+name}));
        }
        $(id).multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            buttonWidth: '420px',
            templates: {
                filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
            },
        });
    } 

    $(document).on("change", "#create_all_school_data_form #rec_state", function () {

        var contnr = $(this).parents('form').first();
        var state_id = $('#rec_state').val();
        var aDd = $("#zones .zones-list-dropdown");
        aDd.find("option").remove();
        refreshData('#rec_zone','Zone');
        refreshData('#rec_block', 'Block');
        refreshData('#rec_province', 'Hub');
        if (state_id != '' && state_id !== null && state_id !== undefined) {
            var postData = "state_id=" + state_id + "&token=" + getToken();

            apiCall(this, "getZonesInState", postData, function (s, data) {
                if (data.message != '') {
                    
                    $('#errors').hide();
                    $('#provinces').show();
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
                    $("#rec_zone").multiselect('deselect',[data.message[0]['zone_id']]);
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
            });
        }

    });

    $(document).on("change", "#create_all_school_data_form #rec_zone", function () {

        var contnr = $(this).parents('form').first();
        var state_id = $('#rec_state').val();
        var zone_id = $('#rec_zone').val();
        var aDd = $("#networks .blocks-list-dropdown");
        aDd.find("option").remove();
        refreshData('#rec_block','Block');
        refreshData('#rec_province', 'Hub');
//        $('#rec_block').append($("<option/>", {
//            value: '',
//            text: 'Select Block'
//        }));
        if (state_id != '' && state_id !== null && state_id !== undefined && zone_id != '' && zone_id !== null && zone_id !== undefined) {
            var postData = "state_id=" + state_id + "&zone_id=" + zone_id + "&token=" + getToken();

            apiCall(this, "getBlocksInZone", postData, function (s, data) {

                if (data.message != '') {
                    
                    $('#errors').hide();
                    $('#provinces').show();
                   
                    addOptions(aDd, data.message, 'network_id', 'network_name');
                    $(contnr).find("#provinces").show();
                    $('#rec_block').prop('multiple', true);

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
                    $("#rec_block").multiselect('deselect',[data.message[0]['network_id']]);
                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
            });
        }

    });
    
    
    $(document).on("change", "#create_all_school_data_form #rec_block", function () {

        var contnr = $(this).parents('form').first();
        var block_ids = $('#rec_block').val();

        expr = /all/;  // no quotes here
        var all_ids = '';
        if (expr.test(block_ids)) {
            $("#rec_block option").each(function ()
            {
                if ($(this).val() != '' && $(this).val() != 'undefined') {
                    all_ids = all_ids + $(this).val() + ",";
                }
            });
            all_ids = all_ids.substring(0, all_ids.length - 5);
        }

        if (all_ids.length >= 1) {
            block_ids = all_ids;
        }
        var totselected = 0;
        var valData = block_ids;
        if (valData !== null) {
            var valNew = valData.toString().split(',');
            totselected = valNew.length;
        }

        var aDd = $("#provinces .province-list-dropdown");
        aDd.find("option").remove();
        refreshData('#rec_province','Hub');
//        $('#rec_province').append($("<option/>", {
//            value: '',
//            text: 'Select Province'
//        }));

        if (block_ids !== null && block_ids !== undefined) {
            $('#errors').hide();
            var postData = "network_id=" + block_ids + "&token=" + getToken();
            apiCall(contnr, "getProvincesInMultiNetwork", postData, function (s, data) {
                if (data.status) {
                    
                    addOptions(aDd, data.message, 'province_id', 'province_name');
                    
                    $('#rec_province').prop('multiple', true);

                    $("#rec_province").multiselect('destroy');
                    $('#rec_province').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        enableCaseInsensitiveFiltering: true,
                        buttonWidth: '420px',
                        maxHeight: ($(window).height() - ($('#rec_block').offset().top + 110)),
                        templates: {
                            filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
                            ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
                        },
                    });
                    $("#rec_province").multiselect('deselect',[data.message[0]['province_id']]);

                    $(".caret").css('float', 'right');
                    $(".caret").css('margin', '8px 0');
                }
                
            });
        }

    });
    
    $(document).on('submit',"#create_all_school_data_form",function(e){
        e.preventDefault();
        $(this).find(".ajaxMsg").removeClass("active");
    	var postData = $(this).serialize() + "&token=" + getToken();
        //var postData = "network_id=1&token=" + getToken();
        var yr =  $('#create_all_school_data_form').find('select[name="years"]').val();
        var mth =  $('#create_all_school_data_form').find('select[name="months"]').val();
        if(yr <= 0 && mth <= 0){
            alert("Please select validity period for the report.");
            return false;
        }
        $('.ajaxMsg').html('').removeClass("danger active");
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                if(this.response.type=='application/zip'){
                    var anchor = document.createElement("a");
                    var url = window.URL || window.webkitURL;
                    anchor.href = url.createObjectURL(new Blob([this.response],{type:'application/zip'}));
                    anchor.download = 'SchoolEvaluationReport.zip';
                    document.body.appendChild(anchor);
                    anchor.click();
                    document.body.removeChild(anchor);
                    //console.log(anchor);
                    $("#ajaxLoading").hide();
                } else{
                    var reader = new FileReader();
                    reader.addEventListener("loadend", function() {
                         var msg = JSON.parse(this.result).message
                         $('.ajaxMsg').html(msg).addClass("danger active");
                    });
                    reader.readAsText(this.response);
                    $("#ajaxLoading").hide();
                }
                
            }
        }
        xhr.open('POST', '?controller=api&action=reportallScholls');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.responseType = 'blob';
        xhr.onloadstart = function(){
             $("#ajaxLoading").show();
        }
        xhr.onerror = function(a, status){
            $("#ajaxLoading").hide();
            var msg = "Error while connecting to server";
            if (status == "timeout")
                msg = 'Request time out';
            else if (status == "parsererror")
                msg = 'Unknown response from server';
            $('.ajaxMsg').html(msg).addClass("danger active");
        } 
        xhr.send(postData);    
});