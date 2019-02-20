/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
var selfReview=0;
var collegeReview=0;
var isPaused = false;
var sTeam;

jQuery(document).ready(function($) {

       
        $(document).on("aqsDataChanged","body",function(){
		$("#saveAqsForm").removeAttr("disabled");
	});
		
	
	$(document).on("change","#aqsFormWrapper select,#aqsFormWrapper input[type=text],#aqsFormWrapper input[type=checkbox],#aqsFormWrapper input[type=radio],#aqsFormWrapper textarea",function(){
		$("body").trigger('aqsDataChanged');
	});
        $(document).on("#aqsFormWrapper change","#aqsFormWrapper select, #aqsFormWrapper input, #aqsFormWrapper textarea",function(){
		
                var p=$(this).closest(".tab-pane").first();
                var tabId = p.attr('id');
                var tabStatus = checkFormTabCompletionKpa(tabId);
                tabStatus==0?$("a[href=#"+p.attr("id")+"]").parent().addClass("completed"):$("a[href=#"+p.attr("id")+"]").parent().removeClass("completed");
              
	});
       

	disableAQS=true;
	$(".vertScrollArea").mCustomScrollbar({theme:"dark"});

	$(document).on("click", ".add-tabular-question-row", function(e){
		e.preventDefault();
		$(this).parents('tbody').first().append($(this).parents('tr').first()[0].outerHTML).find('td').last().html('<a href="#" class="remove-tabular-question-row btn btn-primary" >&times;</a>');
                $(this).parents('tbody').first().find("tr").last().find('textarea').val('');
                //alert($(this).parents('tr').first()[0].outerHTML);
        });

	$(document).on("click", ".remove-tabular-question-row", function(e){
		e.preventDefault();
		$(this).parents('tr').first().remove();
                $("#saveAqsForm").removeAttr("disabled");
	});
	
	
	$.mask.definitions['~']='[+-]';
	
	
	        $(document).on("click","#saveAqsForm",function(){
                    
                        var f=$("#aqsFormWrapper");
                        var schoolProfileStatus=$("#school_profile_status").val();
                        var hasError;
                        if(schoolProfileStatus==1){
                            hasError = validateAqsForm(f);
                        }
                        if(hasError){
                              alert("Please fill the required field/s.");
                                return;
                        }else {
                                var param=f.serialize()+"&token="+getToken();	

                                apiCall(f,"saveAqsForm",param,function(s,data){             
                                    $(".err-msg").html("");
                                    $("#saveAqsForm").attr("disabled", true);


                                },function(s,msg,data){
                                        if(data!=undefined && data!=null && data.errors!=undefined){
                                             $("#validationErrors").html("");
                                                for(var prop in data.errors)
                                                        addError("#validationErrors",data.errors[prop],prop);
                                        } 
                                        alert(msg); 
                                });
                        }
            
                });
              
                 $(document).on("click","#submitAqsForm",function(e){
                    
                                        if(confirm('Are you sure you want to submit this?')){
					var f=$("#aqsFormWrapper");
					
					var hasError = validateAqsForm(f);
					if(hasError){
						return;
					}else{
                                          
                                            var param=f.serialize()+"&is_submit=1&token="+getToken();
                                            apiCall(f,"saveAqsForm",param,function(s,data){             
                                            $("#validationErrors").html("");
                                           alert("Successfully submitted"); window.location.reload();
                                            


                                        },function(s,msg,data){
                                                if(data!=undefined && data!=null && data.errors!=undefined){
                                                     $("#validationErrors").html("");
                                                        for(var prop in data.errors)
                                                                addError("#validationErrors",data.errors[prop],prop);
                                                } 
                                                alert(msg); 
                                        });
                                            
                                        }
                                    }
       
                });
              
                
                $(document).on('click', '.show_child_if_active > .chkHldr > input[type=checkbox], .show_child_if_active > .chkHldr > input[type=radio]',function(){
					var p = $(this).parents('.show_child_if_active');
					var child = p.first().find(".question-wrap").first();
					if($(this).is(":checked")) {
						child.find('input').addClass('required');
						child.find('input').removeAttr("disabled", "disabled");;
						p.removeClass('hide_child');
                    } else{
						child.find('input').removeClass('required');
						child.find('input').attr("disabled", "disabled");
						p.addClass('hide_child');
                    }
				});

				$(document).on('click', '.question-template-2  > .chkHldr > input[type=radio]',function(){
					var parent_wrap = $(this).parents('.question-wrap').first();
					parent_wrap.find('> .show_child_if_active').addClass('hide_child').find('> .question-wrap').find('input').removeClass('required');
					parent_wrap.find('> .show_child_if_active').addClass('hide_child').find('> .question-wrap').find('input').attr("disabled", "disabled");;
					if($(this).is(":checked")) {
                        $(this).parent().parent('.show_child_if_active').first().removeClass('hide_child').find(".question-wrap").first().find('input').removeAttr("disabled", "disabled");;
                        $(this).parent().parent('.show_child_if_active').first().removeClass('hide_child').find(".question-wrap").first().find('input').addClass('required');;
                    } 
				});
        
});

function validateAqsForm(f){
    
        f.find('.err-msg').remove();
        var hasError = false;
        f.find(".required").each(function(){
                if($.trim($(this).val()) == "" && !$(this).parents('.hide_child').length){
                        $(this).parent('.main-question-wrap, td').first().append('<span class="err-msg">This field is required!</span>');
                        hasError = true;
                }
        });

        var checkedBtns = [];
        f.find('.radioRow, .checkRow').each(function(){
                var pid = $(this).data('parentid');

                if(checkedBtns.indexOf(pid) == -1 && $(".parent-id-"+pid+":checked").length==0) {

                        if($('#'+pid).is(':visible')) {
                            checkedBtns.push(pid);
                            $('.question-id-'+pid+' > .main-question-wrap').append('<span class="err-msg">This field is required!</span>');
                            hasError = true;
                        }
                }
        });
        return hasError;
}
function checkForTabTick(){
    
    $('.tab-pane').each(function() {
            
            tabId = $(this).attr("id");
                var tabStatus = checkFormTabCompletionKpa2(tabId);
                tabStatus<=0?$("a[href=#"+tabId+"]").parent().addClass("completed"):$("a[href=#"+tabId+"]").parent().removeClass("completed");
              
    });
   
}

function checkFormTabCompletionKpa(tabId){
    
                var emptyField = 0;
                var emptyVal = 0;
                var filledFileds = 1;
                $("#"+tabId).find("input[type=text], textarea").each(function(){
                        if($(this).val() == '' && $(this).is(':visible')){
                             emptyField++;
                             return false;
                        }
                });
                $("#"+tabId).find(".main").each(function(){ 
                    mainTabId = $(this).attr("id");
                    
                    $("#"+mainTabId).find(".question-wrap").each(function(){
                            $(this).find(".radioRow").each(function(){

                                var radioBtn = $(this).first().attr('name');
                                if(!$('[name="'+radioBtn+'"]:checked').length && $(this).is(':visible')){
                                     emptyField++;
                                     return false;
                                }
                            } );

                            $(this).find(".checkRow").each(function(){

                                var chkBx = $(this).first().attr('name');
                                if(!$('[name="'+chkBx+'"]:checked').length && $(this).is(':visible')){
                                      emptyField++; 
                                     return false;
                                }
                            } );
                            
                    } );
                    
                } );
              
               return emptyField;
}

function checkFormTabCompletionKpa2(tabId){
    
                var emptyField = 0;
                var emptyVal = 0;
                var filledFileds = 1;
                var emptyField2 = 0;
              
                $("#"+tabId).find("input[type=text], textarea").each(function(){
                        if($(this).val() == ''){
                                emptyField++;                        
                        }
                });
                $("#"+tabId +' .show_child_if_active.hide_child ').find("input[type=text], textarea ").each(function() {                              
                    emptyField--;
                }); 
                $("#"+tabId).find(".main").each(function(){ 
                    mainTabId = $(this).attr("id");
                   
                    $("#"+mainTabId).find(".question-wrap").each(function(){
                            $(this).find(".radioRow").each(function(){
                                var radioBtn = $(this).first().attr('name');
                                if(!$('[name="'+radioBtn+'"]:checked').length ){

                                    var radioId = $(this).attr("id");
                                    
                                     if($('#'+radioId).is(':visible')) {
                                          emptyField++;
                                     }
 
                                     return false;
                                }
                            } );

                            $(this).find(".checkRow").each(function(){
                                var chkBx = $(this).first().attr('name');
                                if(!$('[name="'+chkBx+'"]:checked').length ){
                                      emptyField++; 
                                     return false;
                                }
                            } );
                           
                    } );
                    
                } );
           
               return emptyField;
}
function focusAqsElm(e){
	var f=$("#aqsFormWrapper");
	f.find(".nav-tabs li.item.active,.tab-pane.active").removeClass("active");
	f.find("li.item a[href=#"+$(e).parents(".tab-pane").first().addClass("fade in active").attr("id")+"]").parent().addClass("active");
	$(e).focus();
}


function aqsFormDisable()
{
	
	var f = $("#aqsFormWrapper");		//
	f.find("#aqsf_version_id").attr('disabled','disabled').css("pointer-events","none");
	f.find(".subTabWorkspace .tab-pane:gt(0) *,.ylwRibbonHldr .tabitemsHldr .nav .item:gt(0) *").each(function(){
		$(this).attr('disabled','disabled');
		$(this).css('pointer-events','none');		
	});
	$("#aqsf_terms_agree").val(0);	
        
}	
function aqsFormEnable(){	
	var f = $("#aqsFormWrapper");		//
	f.find("#aqsf_version_id").removeAttr('disabled').css("pointer-events","none");
	f.find(".subTabWorkspace .tab-pane:gt(0) *,.ylwRibbonHldr .tabitemsHldr .nav .item:gt(0) *").each(function(){
		$(this).removeAttr('disabled');
		$(this).css('pointer-events','auto');
	});
	$('.selectpicker').selectpicker('refresh');
	f.find("input[name=nstatus]").attr('disabled','disabled');
	f.find("#aqsf_principal_email").attr('disabled','disabled');
	f.find("#aqsf_terms_agree").val(1);
}

