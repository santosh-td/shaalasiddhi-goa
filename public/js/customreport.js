jQuery(document).ready(function ($) {
    var questions = [];
    var grainArray = new Array(13, 23, 24, 25, 27);
    var stDate = null;
    var edDate = null;
    var substringMatcher = function (strs) {
        return function findMatches(q, cb) {
            var matches, substringRegex;
            matches = [];
            substrRegex = new RegExp(q, 'i');
            $.each(strs, function (i, str) {
                if (substrRegex.test(str)) {
                    matches.push(str);
                }
            });
            cb(matches);
        };
    };

    $(document).on("click", ".expRow .delete_row", function () {
        if ($(this).parents(".customTbl").first().find(".expRow").length > 1) {
            var p = $(this).parents(".customTbl");
            $(this).parents(".expRow").remove();
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
      $(document).on("change", ".required", function () {
        $(this).closest('td').find('.multiselect').removeClass('error');
    });
    $(document).on('emptyData', function () {
        $("#showButton").addClass('disabled');
    });
    $(document).on('filledData', function () {
        $("#showButton").removeClass('disabled');
    });
 
	$(document).on("click",".filterRowAdd",function(){		
		var sn = $(this).parents(".addBtnWrap").first().find(".filter_row").length;
		apiCall(this,"addFilterRow","sn="+($(this).parents(".addBtnWrap").first().find(".filter_row").length+1)+"&isDashboard="+$(this).parents('form').first().find(".isDashboard").val()+"&token="+getToken(),function(s,data){
			if($(s).parents(".addBtnWrap").first().find(".subrow").last().data('sno')==sn)
				$(s).parents(".addBtnWrap").first().find(".subrow").last().after(data.content)
			else
				$(s).parents(".addBtnWrap").first().find(".filter_row").last().after(data.content);			
		},function(s,msg){ alert(msg); });
	});
	$(document).on("click",".fltdAddRow.exp",function(){		
		apiCall(this,"addNetworkExpRow","sn="+($(this).parents(".addBtnWrap").first().find(".expRow").length+1)+"&token="+getToken(),function(s,data){
			$(s).parents(".addBtnWrap").first().find(".expRow").last().after(data.content)			
		},function(s,msg){ alert(msg); });
	});
	$(document).on("click",".expRow .delete_row",function(){
		if($(this).parents(".customTbl").first().find(".expRow").length>1){
			var p=$(this).parents(".customTbl");
			$(this).parents(".expRow").remove();			
			p.first().find(".s_no").each(function(i,v){;$(v).html(i+1)});
			var trgr=p.data('trigger');
			if(trgr!=undefined && trgr!=null && trgr!=""){
				$("body").trigger(trgr);
			}
		}else
			alert("You can't delete all the rows");
		return false;
	});
	
	$(document).on("change",".required",function(){
		$(this).closest('td').find('.multiselect').removeClass('error');
	});
	
	$(document).on('emptyData',function(){
		$("#showButton").addClass('disabled');
	});
	$(document).on('filledData',function(){
		$("#showButton").removeClass('disabled');
	});
	
	$(document).on('change','.filterValue',function(){
		var currFltr = $(this);
		var frm = $(this).parents('form').attr('id');
		var sNo = currFltr.closest(".filter_row").find('.s_no').html();
		var attrVal = currFltr.closest(".filter_row").find('.filterAttr').val();
		var id = currFltr.val();
		var name=currFltr.text();
		var cont = currFltr.closest(".filter_row").find('.currentSelection.sno_'+sNo);
		if($.inArray(+attrVal,grainArray)>=0){					
			cont.append('<div title="'+name+'" class="questionNode clearfix questionNode-'+id+'" data-id="'+id+'">'+name+'<input type="hidden" class="ajaxFilterAttach" name="question['+attrVal+'][]" value="'+id+'"/><span class="delete"><i class="fa fa-times"></i></span></div>').find(".empty").addClass('notEmpty');
		}
	});
	
    $('.the-basics .typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 3
    },
            {
                name: 'questions',
                source: substringMatcher(questions),
                limit: 30
            });

    $(document).on('sortremove', '#sortableR', function () {
        selectedClients();
    });//sortableRcol
    $(document).on('sortremove', '#sortableRcol,#sortableRrow', function () {
        var frm = "#admin_dashboard_frm";
        $(this).children('li').removeClass('selected');
        $(document).trigger('checkEmptyVars');
    });
    $(document).on('sortreceive', '#sortableRcol,#sortableRrow', function () {
        var frm = "#admin_dashboard_frm";
        $(this).children('li').removeClass('selected');
        $(document).trigger('checkEmptyVars');
    });
    $(document).on('checkEmptyVars', function () {
        var frm = "#admin_dashboard_frm";
        if ($(frm).find("#sortableRrow li").length < 1 || $(frm).find("#sortableRcol li").length < 1) {
            $(document).trigger('emptyData');
            return false;
        }
        if ($(frm).find("#sortableRrow li").length > 1 || $(frm).find("#sortableRcol li").length > 1) {
            $(document).trigger('emptyData');
            return false;
        }
        $(document).trigger('filledData');

    });
    $(document).on('sortreceive', '#sortableR', function () {
        selectedClients();
    });
    $(document).on('click', '#select-all-sortable', function () {
        $("#sortableL").find("li").addClass("selected");
    });
    
    $('.typeahead').bind('typeahead:select', function (ev, suggestion) {
        var title;
        $('#sortableL li span').each(function (i, elem) {
            suggestion = suggestion.replace("'", "");
            title = $(this).attr('title');
            title = title.replace("'", "");
            if (suggestion != title)
                $(this).parent().hide();
            else
                $(this).parent().show();
        });
    });

    $('.typeahead').bind('typeahead:render', function (ev, suggestion) {
        var text = '';
        $('#sortableL li').hide();
        $(".tt-dataset.tt-dataset .tt-suggestion").each(function () {
            text = $(this).text();
            $('#sortableL').find('li span[title="' + text + '"]').parent().show();
        });

    });
    $(document).on('keyup', '#searchbox', function (e) {
        if (e.which == '13')
            return false;
        if ($("#searchbox").val() == '')
        {
            $('#sortableL li span').each(function () {
                $(this).parent().show();
            });
        }
    });
    $(document).on('click', "#clrBtn", function () {
        $(document).trigger("clrBtnClick");

    })
    $(document).on('clrBtnClick', function () {
        $('#sortableL li span').each(function () {
            $(this).parent().show();
        });
        $("#searchbox").val('');
    });
   
});
$(document).on('checkEmptyClients', function () {
    var d = $(document);
    if (!(d.find('#sortableL').find('li').length))
    {
        d.find("#select-all-sortable").prop("disabled", true);
        d.find("#deselect-all-sortable").prop("disabled", true);
    } else
    {
        d.find("#select-all-sortable").prop("disabled", false);
        d.find("#deselect-all-sortable").prop("disabled", false);
    }
});
function selectedClients() {
    var tmpSt = null;
    var tmpEnd = null;
    var ct = null;
    var dformat = null;
    var frm = "#create_network_report_form";
    var list = $("#sortableR");
    $(document).trigger('checkEmptyClients');
    $(frm).find("#num-selected-schools").html(' (' + $(frm).find("#sortableR").find("li").length + ')');
    $(frm).find("#num-filtered-schools").html(' (' + $(frm).find("#sortableL").find("li").length + ')');
    $(list).find("li").each(function (i, e) {
        $(this).removeClass('selected');
        ct = ct + $(this).data('id') + ",";
        if (i == 0)
        {
            stDate = new Date($(this).data('aqs-start'));
            edDate = new Date($(this).data('aqs-end'));
        } else
        {
            tmpSt = new Date($(this).data('aqs-start'))
            tmpEnd = new Date($(this).data('aqs-end'))
            if (tmpSt < stDate)
                stDate = tmpSt;
            if (tmpEnd > edDate)
                edDate = tmpEnd;

        }
    });

    if (typeof (stDate) !== "undefined" && stDate !== null && typeof (edDate) !== "undefined" && edDate !== null)
    {
        dformat = [stDate.getDate().padLeft(),
            (stDate.getMonth() + 1).padLeft(),
            stDate.getFullYear()].join('-');

        $(frm).find("#frm-date").val(dformat);

        dformat = [edDate.getDate().padLeft(),
            (edDate.getMonth() + 1).padLeft(),
            edDate.getFullYear()].join('-');
        $(frm).find("#to-date").val(dformat)
    }
    ct = ct == null ? '' : ct.slice(0, -1);
    if (typeof (ct) === "undefined" || ct === null || ct == '')
    {
        $(frm).find("#frm-date").val('');
        $(frm).find("#to-date").val('')
    }
    $("#id_clients").val(ct);

}
Number.prototype.padLeft = function(base,chr){
	   var  len = (String(base || 10).length - String(this).length)+1;
	   return len > 0? new Array(len).join(chr || '0')+this : this;
	}

function type(d, i, columns) {
    for (i = 1, t = 0; i < columns.length; ++i)
        t += d[columns[i]] = +d[columns[i]];
    d.total = t;
    return d;
}
function wrap(text, width) {
    text.each(function () {
        var text = d3.select(this),
                words = text.text().split(/\s+/).reverse(),
                word,
                line = [],
                lineNumber = 0,
                lineHeight = 1.1, // ems
                y = text.attr("y"),
                dy = parseFloat(text.attr("dy")),
                tspan = text.text(null).append("tspan").attr("x", 0).attr("y", y).attr("dy", dy + "em");
        while (word = words.pop()) {
            line.push(word);
            tspan.text(line.join(" "));
            if (tspan.node().getComputedTextLength() > width) {
                line.pop();
                tspan.text(line.join(" "));
                line = [word];
                tspan = text.append("tspan").attr("x", 0).attr("y", y).attr("dy", ++lineNumber * lineHeight + dy + "em").text(word);
            }
        }
    });
}