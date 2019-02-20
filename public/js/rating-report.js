/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
$(document).ready(function () {
    var kpaId;
    var blockName;
    var typed;
    var round = $('#round-list').val();
    blockReport = function (kpa_id,type){
        $('#data_cluster').html('');
        $('#data_school').html('');
    }
    clusterReport = function (type,kpa_id,block){
        kpaId = kpa_id;
        blockName = block;
        typed = type;
        var str1 = kpa_id;
        if(type=='level2'){
            str1 = str1+'_2'
        }
        jQuery('.zone_box a').each(function(e,val){
          var id2 = $(val).attr('href');
          var str2 = id2.replace (/#/g, "");
          jQuery(val).removeClass('disableKpa');
          jQuery(val).removeClass('enableKpa');
          
          if(str1 == str2){
            jQuery(val).addClass('enableKpa');   
          } else{
              jQuery(val).addClass('disableKpa');
          }
        })

      jQuery('.blockbox .chartBoxOuter').each(function(e,val){
          $(this).removeClass('selected');
          $(this).removeClass('unselected');          
          var id2 = $(val).attr('id');
          var str2 = id2.replace (/#/g, "");
          if(str1 == str2){
           jQuery(this).addClass('selected');   
          } else {
              jQuery(this).addClass('unselected');
          }
      });
      
        var rdata = "type="+type+"&round="+round+"&kpa_id="+kpa_id+"&block="+block+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getClusterData",rdata,querystring,function(s,data){
                                            $('#data_cluster').html(data.content);
                                            $('body, html').animate({scrollTop: $('#data_cluster').offset().top},1500);
                                            
                                            $('#data_school').html(''); 
				},showErrorMsgInMsgBox);
    }
    
    schoolReport = function (cluster_name) {
        var rdata = "type="+typed+"&round="+round+"&cluster_name="+cluster_name+"&block="+blockName+"&kpa_id="+kpaId+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","getSchoolData",rdata,querystring,function(s,data){
                                        $('#data_school').html(data.content);
                                        $('body, html').animate({scrollTop: $('#data_school').offset().top},1500);
                                        
				},showErrorMsgInMsgBox);
    }
    
    $('#round-list').change(function(){
        round = $(this).val();
        var zone_id = $('#zonechange').val();
        var zone = $("#zonechange option:selected").text();
        
        var rdata = "zone_id="+zone_id+"&round="+round+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
                                            $('#data_cluster').html('');
                                            $('#data_school').html(''); 
                                            $('#zonedata').html(data.content);
                                },showErrorMsgInMsgBox);
    })
    
    $(document).on('click', '.blockbox .lineBar', function (e) {
        $('.blockbox .lineBar').removeClass('disableKpa');
        $('.blockbox .lineBar').removeClass('enableKpa'); 
        $('.blockbox .lineBar').addClass('disableKpa');
        $(this).removeClass('disableKpa')
        $(this).addClass('enableKpa');
    });
    
    $(document).on('click', '.cluster_level .lineBar', function (e) {
        $('.cluster_level .lineBar').removeClass('disableKpa');
        $('.cluster_level .lineBar').removeClass('enableKpa'); 
        $('.cluster_level .lineBar').addClass('disableKpa');
        $(this).removeClass('disableKpa')
        $(this).addClass('enableKpa');
    });
    // handle links with @href started with '#' only
  $(document).on('click', '.zone_box a[href^="#"]', function (e) {
      // target element id
      $('.blockbox .lineBar').removeClass('disableKpa');
      $('.blockbox .lineBar').removeClass('enableKpa'); 
      var id = $(this).attr('href');
      var str1 = id.replace (/#/g, "");
      
      //alert(str1);
      jQuery('.zone_box a').each(function(e,val){
          var id2 = $(val).attr('href');
          var str2 = id2.replace (/#/g, "");
          jQuery(val).removeClass('disableKpa');
          jQuery(val).removeClass('enableKpa'); 
          if(str1 == str2){
           jQuery(val).addClass('enableKpa');   
          }else {
              jQuery(val).addClass('disableKpa');
          }
      })
      // target element
      var $id = $(id);
      if ($id.length === 0) {
          return;
      }
      
      // prevent standard hash navigation (avoid blinking in IE)
      e.preventDefault();
      // top position relative to the document
      var pos = $id.offset().top;
      jQuery('.blockbox .chartBoxOuter').removeClass('selected');
      jQuery('.blockbox .chartBoxOuter').removeClass('unselected');
      
      jQuery('.blockbox .chartBoxOuter').each(function(e,val){
          var id2 = $(val).attr('id');
          var str2 = id2.replace (/#/g, "");
          if(str1 == str2){
           jQuery(this).addClass('selected');   
          }else{
              jQuery(this).addClass('unselected');
          }
      })
      // animated top scrolling
      $('body, html').animate({scrollTop: pos},1500);
  });
  
  jQuery('#zonechange').change(function(){
      var zone_id = $(this).val();
      $('#zone_name').text($("#zonechange option:selected").text());
      var rdata = "zone_id="+zone_id+"&round="+round+"&token="+getToken();
        var querystring = '';
        ajaxCall(this,"customRatingsReport","index",rdata,querystring,function(s,data){
                                            $('#data_cluster').html('');
                                            $('#data_school').html(''); 
                                            $('#zonedata').html(data.content);
				},showErrorMsgInMsgBox);
  })
})

