/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
if (typeof scoreToText === 'undefined') {
    var scoreToText = {"scheme-1": {0: "", 1: "Level3", 2: "Level2", 3: "Level1", 4: "Outstanding"}}, isFileUploading = false;
}
var isRevCompleteNtSubmitted = 0;
var checkUnload = 1;
var diagnostic_type = 0;
jQuery(document).ready(function ($) {

    $(document).on('click', ".good_stmnt", function () {

        var jsDropDownId = $(this).attr("id");
        var goodstatementurl = $("#goodstatementurl").val();
        var params = '';
        $.each($("#" + jsDropDownId + " option:selected"), function () {

            if (params != '') {
                params = params + "," + $(this).val();
            } else {
                params = $(this).val();
            }
        });
        if (params != '') {
            goodstatementurl = goodstatementurl + params;
            window.open(goodstatementurl, '_blank');
        } else {
            alert("Please select judgement statement first");
        }


    });
    if ($("#assessmentForm").length) {
        diagnostic_type = $("#diagnostic_type").val();
        init_AssessmentForm();
        checktabTicks(diagnostic_type);
    }
    $(document).on('click', ".checkReviewSubmit", function () {
        alert("test");
    })
    $(document).on("change", ".radio_js", function () {

        diagnostic_type = $("#diagnostic_type").val();
        if (diagnostic_type != 1) {
            recalculateStatementResults(this, diagnostic_type);
        } else {
            recalculateStatementResultsKpa(this, diagnostic_type);
        }
        $("body").trigger('dataChanged');
        checktabTicks(diagnostic_type);
        checkFormCompletion();
    });
    $(document).find('.mulselect').multiselect({
        numberDisplayed: 1
    });
    $(document).on("dataChanged", "body", function () {
        $("#saveAssessment").removeAttr("disabled");
        $("#aKeyNotesAccepted").removeAttr("checked");
    });

    $(document).on("click", ".judgementS", function () {
        $(".judgementS.cactive").removeClass("cactive");
        $(this).addClass("cactive");
    });
    $(document).on('click', 'footer,header,h1', function (event) {
        return showSubmitAlert();
    });
});
function showSubmitAlert() {
    checkUnload = 1;
    if (isRevCompleteNtSubmitted)
    {
        var isConfirmOK = window.confirm("You have filled the complete review form. Please submit the review form.");
        if (isConfirmOK == true)
            return false;
        checkUnload = 0;
    }
    return true;
}
function checkFormCompletion() {

    var t = $(".radioWrapper").length, c = $(".radio_js:checked").length;
    $(".key-notes-val").each(function (i, cont) {
        t++;
        var isCom = true;

        if ($(this).val() == "0")
            isCom = false;
        isCom ? c++ : '';
    });
    if (t == c)
        $("#submitAssessment").removeAttr("disabled");
    else
        $("#submitAssessment").attr("disabled", "disabled");
    if (t > 0) {
        $("#percProg").width(((c * 100) / t) + "%");
    }
}
//new check tabs

function checktabTicks(diagnostic_type = 0) {
    // tabs
    var tabsNum = 3;
    if (diagnostic_type == 1) {
        tabsNum = 1;
    }
    var t = '', c = '', t_k = '', c_k = '', t_kpa = '', c_kpa = '', kqNotesDone, cqNotesDone = 0, jsNotesDone, kNotesDone, tabType = '', p = '';
    var frmId = 'assessmentForm';
    $(".tab-pane.kpa").each(function (i, k) {
        kqNotesDone = 0;
        t_kpa = $(this).find(".radioWrapper").length, c_kpa = $(this).find(".radio_js:checked").length;
        $(this).find('.tab-pane.keyQ').each(function () {
            cqNotesDone = 0;
            t_k = $(this).find(".radioWrapper").length, c_k = $(this).find(".radio_js:checked").length;
            $(this).find('.tab-pane.coreQ').each(function () {
                t = $(this).find(".radioWrapper").length, c = $(this).find(".radio_js:checked").length;
                //js notes
                jsNotesDone = true;
                tabType = 'judgementS';
                p = $(this);
                if (p.find("." + tabType + ".key-notes-val").length)
                    p.find("." + tabType + ".key-notes-val").each(function (i, v) {
                        if ($(v).val() == "1") {
                            $("#kr_" + tabType + "_" + $(this).attr("id").match(/\d+/)).find('i').addClass('fa-check')
                        } else {
                            jsNotesDone = false;
                            $("#kr_" + tabType + "_" + $(this).attr("id").match(/\d+/)).find('i').removeClass('fa-check')
                        }
                        ;
                    });

                //cq notes
                kNotesDone = jsNotesDone ? true : false;
                tabType = 'coreQ';
                p = $(this);
                if ($("a[href=#" + p.attr("id") + "]").next("." + tabType + ".key-notes-val").length)
                    $("#" + frmId).find("." + tabType + "#" + tabType + "_" + p.attr("id").match(/\d+/) + ".key-notes-val").each(function (i, v) {
                        if ($(v).val() == "0")
                            kNotesDone = false;
                    });
                if (c == t && kNotesDone) {
                    $("a[href=#" + $(this).attr("id") + "]").parent().addClass("completed");
                    $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').addClass('fa-check')
                } else {
                    $("a[href=#" + $(this).attr("id") + "]").parent().removeClass("completed");
                    $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').removeClass('fa-check')
                }
                kNotesDone ? cqNotesDone++ : 0;
            });
            kNotesDone = cqNotesDone == tabsNum ? true : false;

            tabType = 'keyQ';
            p = $(this);
            if (kNotesDone == true && $("a[href=#" + p.attr("id") + "]").next("." + tabType + ".key-notes-val").length)
                $("#" + frmId).find("." + tabType + "#" + tabType + "_" + p.attr("id").match(/\d+/) + ".key-notes-val").each(function (i, v) {
                    if ($(v).val() == "0")
                        kNotesDone = false;
                });

            if (c_k == t_k && kNotesDone) {
                $("a[href=#" + $(this).attr("id") + "]").parent().addClass("completed");
                $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').addClass('fa-check')
            } else {
                $("a[href=#" + $(this).attr("id") + "]").parent().removeClass("completed");
                $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').removeClass('fa-check')
            }
            kNotesDone ? kqNotesDone++ : 0;
        });
        tabType = 'kpa';
        kNotesDone = kqNotesDone == tabsNum ? true : false;
        p = $(this);
        if ($("a[href=#" + p.attr("id") + "]").next("." + tabType + ".key-notes-val").length)
            $("#" + frmId).find("." + tabType + "#" + tabType + "_" + p.attr("id").match(/\d+/) + ".key-notes-val").each(function (i, v) {
                if ($(v).val() == "0")
                    kNotesDone = false;
            });
        if (c_kpa == t_kpa && kNotesDone) {
            $("a[href=#" + $(this).attr("id") + "]").parent().addClass("completed");
            $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').addClass('fa-check')
        } else {
            $("a[href=#" + $(this).attr("id") + "]").parent().removeClass("completed");
            $("#kr_" + tabType + "_" + p.attr("id").match(/\d+/)).find('i').removeClass('fa-check')
        }
    });
}

function recalculateStatementResults(sender, diagnostic_type = 0) {
    var hierarchy = {kpa: "keyQ", keyQ: "coreQ"};
    var p = $(sender).parents(".tab-pane").first();
    var frmId = $(sender).parents('form').attr('id');
    if (p.length) {
        var arr = [];
        var arrJsRatings = [];
        var arrSqRatings = [];
        var tabType = p.data("tabtype");
        var ratingScheme = p.data("schemeid");
        $("#" + frmId).find('.radio_js:checked').each(function (j, k) {
            arrJsRatings.push($(k).val());
        })
        $("#" + frmId).find('.grade-coreQ .thescore').each(function (j, k) {
            arrSqRatings.push($(k).data('score'));
        })
        if (hierarchy[tabType] != undefined) {
            var fls = p.find(".grade-" + hierarchy[tabType] + " .thescore");
            fls.each(function (i, v) {
                var vl = $(v).data("score");
                if (vl > 0)
                    arr.push(vl);
            });
            if (arr.length == fls.length) {
                var s = calculateStatementResult(arr, ratingScheme, tabType, arrJsRatings, arrSqRatings);
                p.find(".grade-" + tabType + " .thescore").html(scoreToText[ratingScheme][s]).removeClass("score-1 score-2 score-3 score-4 score-5 score-6").addClass("score-" + s).data("score", s);
                var kNotesDone = true;
                if ($("a[href=#" + p.attr("id") + "]").next("." + tabType + ".key-notes-val").length)
                    $("#" + frmId).find("." + tabType + "#" + tabType + "_" + p.attr("id").match(/\d+/) + ".key-notes-val").each(function (i, v) {
                        if ($(v).val() == "0")
                            kNotesDone = false;
                    });
                if (kNotesDone)
                    $("a[href=#" + p.attr("id") + "]").parent().addClass("completed");
                else
                    $("a[href=#" + p.attr("id") + "]").parent().removeClass("completed");
                recalculateStatementResults(p);
            }
        } else {
            var c = p.find(".radio_js:checked");
            if (c.length == p.find(".radioWrapper").length) {
                c.each(function (i, v) {
                    arr.push($(v).val());
                });
                var s = calculateStatementResult(arr, ratingScheme, tabType, arrJsRatings, arrSqRatings);
                p.find(".grade-" + tabType + " .thescore").html(scoreToText[ratingScheme][s]).removeClass("score-1 score-2 score-3 score-4 score-5 score-6").addClass("score-" + s).data("score", s);
                $("a[href=#" + p.attr("id") + "]").parent().addClass("completed");
                //}
                recalculateStatementResults(p);
            }
        }
}
}
function recalculateStatementResultsKpa(sender, diagnostic_type = 0) {
    var hierarchy = {kpa: "keyQ", keyQ: "coreQ"};
    var p = $(sender).parents(".tab-pane").first();
    var frmId = $(sender).parents('form').attr('id');
    if (p.length) {
        var arr = [];
        var arrJsRatings = [];
        var arrSqRatings = [];
        var tabType = p.data("tabtype");
        var ratingScheme = p.data("schemeid");
        p.find('.radio_js:checked').each(function (j, k) {
            arrJsRatings.push($(k).val());
        })
        $("#" + frmId).find('.grade-coreQ .thescore').each(function (j, k) {
            arrSqRatings.push($(k).data('score'));
        })
        if (hierarchy[tabType] != undefined) {
            var fls = p.find(".grade-" + hierarchy[tabType] + " .thescore");
            fls.each(function (i, v) {
                var vl = $(v).data("score");
                if (vl > 0)
                    arr.push(vl);
            });
            if (arr.length == fls.length) {

                var s = calculateStatementResultKpa(arr, ratingScheme, tabType, arrJsRatings, arrSqRatings);

                p.find(".grade-" + tabType + " .thescore").html(scoreToText[ratingScheme][s]).removeClass("score-1 score-2 score-3 score-4 score-5 score-6").addClass("score-" + s).data("score", s);

                var kNotesDone = true;
                if ($("a[href=#" + p.attr("id") + "]").next("." + tabType + ".key-notes-val").length)
                    $("#" + frmId).find("." + tabType + "#" + tabType + "_" + p.attr("id").match(/\d+/) + ".key-notes-val").each(function (i, v) {
                        if ($(v).val() == "0")
                            kNotesDone = false;
                    });
                if (kNotesDone)
                    $("a[href=#" + p.attr("id") + "]").parent().addClass("completed");
                else
                    $("a[href=#" + p.attr("id") + "]").parent().removeClass("completed");

                recalculateStatementResultsKpa(p);
            } else {
            }
        } else {
            var c = p.find(".radio_js:checked");
            if (c.length == p.find(".radioWrapper").length) {
                c.each(function (i, v) {
                    arr.push($(v).val());
                });
                if (diagnostic_type == 1) {
                    tabType = 'kpa';
                    var activeKpa = $(sender).parents(".tab-pane").parents(".tab-pane").parents(".tab-pane").attr("id");
                }
                var s = calculateStatementResultKpa(arr, ratingScheme, tabType, arrJsRatings, arrSqRatings);
                k = s + 1;
                $("#" + activeKpa + " .grade-" + tabType + " .thescore").html(scoreToText[ratingScheme][s]).removeClass("score- score-1 score-2 score-3 score-4 score-5 score-6").addClass("score-" + k).data("score", s);
                $("a[href=#" + p.attr("id") + "]").parent().addClass("completed");
                recalculateStatementResultsKpa(p);
            }
        }
}
}

function calculateStatementResultKpa(res, ratingScheme, type, kpaJs, kpaSq) {
    valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0};//school review
    if (type == 'kpa') {
        if (!(kpaJs.length || kpaSq.length))
            return 0;
        valuesCount = {score1: 0, score2: 0, score3: 0};
        var numJs = kpaJs.length;
        for (i = 0; i < (kpaJs.length); i++) {
            valuesCount["score" + kpaJs[i]]++;
        }
        console.log(valuesCount);
        var percScore3 = 0;
        var percScore2 = 0;
        var percScore1 = 0;
        if (valuesCount['score3'] >= 1) {
            percScore3 = (valuesCount['score3'] * 100) / numJs;
        }
        if (valuesCount['score1'] >= 1) {
            percScore1 = (valuesCount['score1'] * 100) / numJs;
        }
        if (percScore3 >= 50 && percScore1 < 50) {
            return 3;
        } else if (percScore1 >= 50 && percScore3 < 50) {
            return 1;
        } else {
            return 2;
        }
    }

}
function calculateStatementResult(res, ratingScheme, type, kpaJs, kpaSq) {
    var valuesCount = null;
    switch (ratingScheme) {
        case 'scheme-5' :
        case 'scheme-1' :
            valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0};//school review
            for (i = 0; i < res.length; i++)
                valuesCount['score' + res[i]]++;

            if (valuesCount.score1 > 1)
                return 1;
            else if (valuesCount.score2 > 1)
                return 2;
            else if (valuesCount.score3 > 1)
                return 3;
            else if (valuesCount.score4 > 1)
                return 4;
            else if (valuesCount.score1 == 0)
                return 3;
            else if (valuesCount.score2 == 0)
                return 3;
            else if (valuesCount.score3 == 0)
                return 2;
            else if (valuesCount.score4 == 0)
                return 2;
            else
                return 0;
            break;
        case 'scheme-2' :
        case 'scheme-4' :
            if (type == 'coreQ') { //scheme 2 for teacher review
                valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0};
                for (i = 0; i < res.length; i++)
                    valuesCount['score' + res[i]]++;
                if ((valuesCount.score3 + valuesCount.score4) == 3)//3 mostly/always->exceptional
                    return 5;
                else if ((valuesCount.score3 + valuesCount.score4) == 2)//2mostly/always->proficient
                    return 4;
                else if ((valuesCount.score3 + valuesCount.score4) == 1)//1 mostly/always->developing
                    return 3;
                else if (valuesCount.score2 >= 2)//2 or more mostly-> emerging
                    return 2;
                else if (valuesCount.score1 >= 2)//2 rarely->foundation
                    return 1;
                else
                    return 0;

            } else if (type == 'keyQ') {
                //return 6;
                if (ratingScheme == 'scheme-4') {
                    valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0, score5: 0};
                    for (i = 0; i < res.length; i++)
                        valuesCount['score' + res[i]]++;

                    var tot = 0;
                    var ftot = 0;
                    tot = 1 * valuesCount.score1 + 2 * valuesCount.score2 + 3 * valuesCount.score3 + 4 * valuesCount.score4 + 5 * valuesCount.score5;
                    ftot = tot / 3;
                    ftot = ftot.toFixed();
                    return ftot;

                } else {
                    return 6;
                }

            } else if (type == 'kpa' && ratingScheme == 'scheme-2') {
                if (!(kpaJs.length || kpaSq.length))
                    return 0;
                valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0};
                for (i = 0; i < (kpaJs.length); i++)
                    valuesCount["score" + kpaJs[i]]++;

                var sqValuesCount = {score1: 0, score2: 0, score3: 0, score4: 0, score5: 0};
                for (i = 0; i < (kpaSq.length); i++)
                    sqValuesCount["score" + kpaSq[i]]++;

                if ((valuesCount.score4 + valuesCount.score3) >= 19)//mostly and/or always rating 	
                    return 5;
                else if ((valuesCount.score4 + valuesCount.score3) >= 10 && (valuesCount.score4 + valuesCount.score3) <= 18) {//mostly and/or always rating								
                    if ((sqValuesCount.score4 + sqValuesCount.score5) >= 4)
                        return 4;
                    else
                        return 3;
                } else if ((valuesCount.score4 + valuesCount.score3) >= 6 && (valuesCount.score4 + valuesCount.score3) <= 9)	//mostly and/or always rating
                    return 3;
                else if ((valuesCount.score4 + valuesCount.score3) >= 3 && (valuesCount.score4 + valuesCount.score3) <= 5)//mostly and/or always rating
                    return 2;
                else if ((valuesCount.score4 + valuesCount.score3) >= 0 && (valuesCount.score4 + valuesCount.score3) <= 2)//mostly and/or always rating
                    return 1;
                else
                    return 0;
            } else if (type == 'kpa' && ratingScheme == 'scheme-4') {
                valuesCount = {score1: 0, score2: 0, score3: 0, score4: 0, score5: 0};//student review
                for (i = 0; i < res.length; i++)
                    valuesCount['score' + res[i]]++;

                var sqValuesCount = {score1: 0, score2: 0, score3: 0, score4: 0, score5: 0};

                for (i = 0; i < (kpaSq.length); i++)
                    sqValuesCount["score" + kpaSq[i]]++;

                var tot = 0;
                var ftot = 0;
                tot = 1 * valuesCount.score1 + 2 * valuesCount.score2 + 3 * valuesCount.score3 + 4 * valuesCount.score4 + 5 * valuesCount.score5;
                ftot = tot / 3;
                ftot = ftot.toFixed();

                if (sqValuesCount.score5 > 3) {
                    return 5;
                } else if (valuesCount.score5 > 1) {
                    return 5;
                } else if (sqValuesCount.score4 > 3) {
                    return 4;
                } else if (valuesCount.score4 > 1) {
                    return 4;
                } else if (ftot > 0) {
                    return ftot;
                } else {
                    return 0;
                }

            }
            break;
    }
}

function keyNoteChanged(id, type, tablinkname) {
    var KeyNDone = true, kNobj = $("." + type + "#" + type + "_" + id), p = $(".key-notes-val." + type + "#" + type + "_" + id).closest("li.item");
    var filled_js_in_type = $("." + type + "#" + tablinkname + id + " .radio_js:checked").length;
    var total_js_in_type = $("." + type + "#" + tablinkname + id + " .radioWrapper").length;
    if ($(kNobj).val() == "1" && filled_js_in_type == total_js_in_type)
        KeyNDone = true;
    else
        KeyNDone = false;

    if (KeyNDone)
        p.addClass("completed");
    else
        p.removeClass("completed");

    checkFormCompletion();
}
var m = '';
function init_AssessmentForm() {
    var allowedExt1 = ["jpeg", "png", "gif", "jpg", "avi", "mp4", "mov", "doc", "docx", "txt", "xls", "xlsx", "pdf", "cvs", 'xml', 'pptx', 'ppt', 'cdr', 'mp3', 'wav'];
    var uploadQueue = [];
    $('.uploadBtn').on("change", function () {
        var uploadBtnId = $(this).attr('id');
        if (uploadBtnId == 'profile_resume') {
            var allowedExt = ["doc", "docx", "txt"];
        } else {
            var allowedExt = allowedExt1;
        }
        if ($(this)[0].files == undefined || typeof FileReader === "undefined") {
            alert("Sorry your browser does not support HTML5 file uploading");
        } else {
            var files = $(this)[0].files;
            m = files;
            var fWrap = $(this).parents(".judgementS").find(".filesWrapper");
            for (var i = 0; i < files.length; i++) {
                var nArr = files[i].name.split(".");
                var ext = nArr.pop().toLowerCase();
                if (files[i].size > 1048576000) {
                    alert("File too big. Max. 100MB allowed");
                } else if (nArr.length > 0 && allowedExt.indexOf(ext) != -1) {
                    fWrap.append('<div class="filePrev waiting"><span class="delete fa"></span><div class="inner"></div></div>');
                    uploadQueue.push({file: files[i], elem: fWrap.find(".filePrev").last(), ext: ext});
                } else {
                    alert(files[i].name + " : file type not allowed. Only " + allowedExt.join(", ") + " type of files are allowed");
                }
            }
            if (isFileUploading == false && uploadQueue.length > 0) {
                uploadFile(uploadBtnId);
            }
        }
        return false;
    });
    $('.uploadResumeBtn').on("change", function () {
        var allowedExt1 = ["jpeg", "png", "gif", "jpg", "avi", "mp4", "mov", "doc", "docx", "txt", "xls", "xlsx", "pdf", "cvs", 'xml', 'pptx', 'ppt', 'cdr', 'mp3', 'wav'];
        var uploadBtnId = $(this).attr('id');
        if (uploadBtnId == 'profile_resume') {
            var allowedExt = ["doc", "docx", "txt"];
        } else {
            var allowedExt = allowedExt1;
        }
        if ($(this)[0].files == undefined || typeof FileReader === "undefined") {
            alert("Sorry your browser does not support HTML5 file uploading");
        } else {
            var file = this.files[0];
            name = file.name;
            size = file.size;
            type = file.type;
            var fWrap = $(this).parents(".judgementSResumes").find(".filesWrapperResumes");
            var nArr = file.name.split(".");
            var ext = nArr.pop().toLowerCase();
            if (file.size > 1048576000) {
                alert("File too big. Max. 100MB allowed");
            } else if (nArr.length > 0 && allowedExt.indexOf(ext) != -1) {
                fWrap.html('<div class="filePrev waiting"><span class="delete fa"></span><div class="inner"></div></div>');
                uploadQueue.push({file: file, ext: ext});
            } else {
                alert(file.name + " : file type not allowed. Only " + allowedExt.join(", ") + " type of files are allowed");
            }
            if (isFileUploading == false && uploadQueue.length > 0) {
                uploadFile(uploadBtnId);
            }
        }
        return false;
    });

    function uploadFile(uploadBtnId) {

        var fileElm;
        if (uploadQueue.length > 0) {
            isFileUploading = true;
            fileElm = uploadQueue.shift();
            if (fileElm.elem == undefined || fileElm.elem.parent().length == 0) {
                uploadFile(uploadBtnId);
            } else
                fileElm.elem.removeClass("waiting").addClass("uploading");

        } else {
            isFileUploading = false;
            return;
        }
        var fe = fileElm.elem;
        var formData = new FormData();
        formData.append("token", getToken());
        formData.append('filename', fileElm.file.name);
        formData.append('filetype', fileElm.file.type);
        formData.append('file', fileElm.file);
        $("input[type=file]").val('');

        $.ajax({
            url: "?controller=api&action=uploadFile",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (rData) {

                if (uploadBtnId == 'profile_resume') {

                    if (rData != undefined && rData != null && rData.status != undefined) {
                        if (rData.status == -1) {
                            isFileUploading = false;
                            if (!$("#login_popup_wrap").hasClass('active')) {
                                sessionExpired();
                            }

                            fe.removeClass("uploading").addClass("waiting");
                            uploadQueue.unshift(fileElm);
                            $("#login_popup_wrap").on("loggedIn", uploadFile);
                        } else if (rData.status == 1) {
                            $(".filePrev").attr("id", "file-resume-" + rData.id).removeClass("uploading").removeClass("waiting").addClass("uploaded vtip ext-" + rData.ext).data("id", rData.id).attr("title", rData.name);
                            $(".filePrev").append('<input type="hidden" value="' + rData.id + '" name="profile_resume" />');
                            $(".filePrev").find(".inner").html('<a target="_blank" href="' + rData.url + '"> </a>');
                            $("body").trigger('dataChanged');
                        } else {
                            alert("Error while uploading file '" + fileElm.file.name + "': " + rData.message);
                            fe.remove();
                        }
                    } else {
                        alert("Unknown error occurred while uploading file '" + fileElm.file.name + "'.");
                        fe.remove();
                        uploadFile(uploadBtnId);
                    }
                } else {
                    if (rData != undefined && rData != null && rData.status != undefined) {
                        if (rData.status == -1) {
                            isFileUploading = false;
                            if (!$("#login_popup_wrap").hasClass('active')) {
                                sessionExpired();
                            }

                            fe.removeClass("uploading").addClass("waiting");
                            uploadQueue.unshift(fileElm);
                            $("#login_popup_wrap").on("loggedIn", uploadFile);
                        } else if (rData.status == 1) {
                            fe.attr("id", "file-" + rData.id).removeClass("uploading").addClass("uploaded vtip ext-" + rData.ext).data("id", rData.id).attr("title", rData.name).append('<input type="hidden" value="' + rData.id + '" name="data[' + fe.parents(".kpa").data("id") + '-' + fe.parents(".keyQ").data("id") + '-' + fe.parents(".coreQ").data("id") + '-' + fe.parents(".judgementS").data("id") + '][files][]" />');
                            fe.find(".inner").html('<a target="_blank" href="' + rData.url + '"> </a>');
                            $("body").trigger('dataChanged');
                            uploadFile(uploadBtnId);
                        } else {
                            alert("Error while uploading file '" + fileElm.file.name + "': " + rData.message);
                            fe.remove();
                            uploadFile(uploadBtnId);
                        }
                    } else {
                        alert("Unknown error occurred while uploading file '" + fileElm.file.name + "'.");
                        fe.remove();
                        uploadFile(uploadBtnId);
                    }
                }
            },
            error: function (a, status) {
                var msg = "Error while connecting to server";
                if (status == "timeout")
                    msg = 'Request time out';
                else if (status == "parsererror")
                    msg = 'Unknown response from server';
                alert("Error occurred while uploading file '" + fileElm.file.name + "' : " + msg);
                fe.remove();
                uploadFile(uploadBtnId);
            },
            xhrFields: {
                onprogress: function (e) {
                    var perc = parseInt(e.loaded / e.total * 100, 10);
                }
            }
        });
    }
    $(document).on("click", ".filePrev.waiting .delete", function () {
        $(this).parent().remove();
    });
    $(document).on("click", ".filePrev.uploaded .delete", function () {
        $(this).parent().remove();
        $("p").remove("#vtip");
        var uploadDoc = 0;
        $('div', '.filesWrapper').each(function () {
            if ($(this).attr("id") != 'undefined' || $(this).attr("id") != '') {
                uploadDoc++;
            }

        });
        if (uploadDoc < 1) {
            $('#pd_personal_tab').removeClass('completed');
            $('#activeStatus_tab').addClass('completed');
        }
        var par = $(this).parent().attr('id');
        var arr = par.split('-');
        if (arr[1] == "resume") {
            $('#biography_tab').removeClass('completed');
            $('#activeStatus_tab').addClass('completed');
        }
        $("body").trigger('dataChanged');

    });

    $(document).on("change", "#replicate_self_review:checked", function () {
        var assessment_id = $(this).val();
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure to replicate self review ratings!<br>Note: This will overwrite your exising ratings, if any',
            buttons: {
                Yes: {
                    btnClass: 'btn-success',
                    action: function () {
                        var f = $("#assessmentWrapper");
                        var param = "assessment_id=" + assessment_id + "&token=" + getToken();
                        apiCall(f, "replicateAssessment", param, function (s, data) {
                            $('#replicate_self_review').attr('checked', false);
                            alert("Successfully Replicated");
                            var url = window.location.href;
                            if (url.indexOf('?') > -1 && url.indexOf('save_enable') == -1) {
                                url += '&save_enable=1'
                            } else if (url.indexOf('save_enable') == -1) {
                                url += '?&save_enable=1'
                            }
                            window.location.href = url;
                        }, function (s, msg) {
                            $.alert(msg);
                            $('#replicate_self_review').attr('checked', false);
                        });

                    }
                },
                No: {
                    btnClass: 'btn-blue',
                    action: function () {
                        $('#replicate_self_review').attr('checked', false);
                    }
                }
            }
        });
    });



    function ConfirmDialog(message) {
        $('<div></div>').prependTo('body')
                .html('<div><h6>' + message + '?</h6></div>')
                .dialog({
                    modal: true, title: 'Confirm Action', zIndex: 10000, autoOpen: true,
                    width: 'auto', resizable: false,
                    buttons: {
                        Yes: function () {
                            var f = $("#assessmentWrapper");
                            var assessment_id = $(this).val();
                            var param = "assessment_id=" + assessment_id + "&token=" + getToken();
                            apiCall(f, "replicateAssessment", param, function (s, data) {
                                alert("Successfully submitted");
                                window.location.reload();
                            }, function (s, msg) {
                                alert(msg);
                            });
                            $(this).dialog("close");
                        },
                        No: function () {
                            $(this).dialog("close");
                        }
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    }
                });
    }
    ;

    $(document).on("click", "#saveAssessment", function () {
        $(this).attr("disabled", "disabled");
        var f = $("#assessmentWrapper");

        var diagnostic_type = $("#diagnostic_type").val();
        if (diagnostic_type == 1) {
            var lang_id = $("#lang_id").val();
        } else {
            var lang_id = $("#lang-n option:selected").val();
        }
        var param = f.serialize() + "&lang_id=" + lang_id + "&token=" + getToken();
        apiCall(f, "saveAssessment", param, function (s, data) {
            $("#percProg").width(data.completedPerc + "%");
            if (data.submit == 0 && data.completedPerc == 100)
                if (data.leadAssessorStatus != 1)
                    isRevCompleteNtSubmitted = 1;
            if (data.leadAssessorStatus == 1 && data.completedPerc == 100 && data.submit != 1)
                window.location.reload();
        }, function (s, msg) {
            $("body").trigger('dataChanged');
            alert(msg);
        }, function (s, d) {
            $("body").trigger('dataChanged');
        });
    });
    $(document).on("click", "#submitAssessment", function () {
        if (isFileUploading || uploadQueue.length > 0) {
            alert("Few files are still uploading. Please wait..");
        } else if (confirm('Are you sure you want to submit this?')) {
            var f = $("#assessmentWrapper");
            if (diagnostic_type == 1) {
                var lang_id = $("#lang_id").val();
            } else {
                var lang_id = $("#lang-n option:selected").val();
            }
            var param = f.serialize() + "&submit=1&lang_id=" + lang_id + "&token=" + getToken();
            apiCall(f, "saveAssessment", param, function (s, data) {
                $("#percProg").width(data.completedPerc + "%");
                if (data.completedPerc == 100) {
                    $("#submitAssessment,#saveAssessment").remove();

                    isRevCompleteNtSubmitted = 0;
                    alert("Successfully submitted");
                    window.location.reload();
                } else {
                    alert('Some fields are still pending. Please fill them and submit again.');
                }
            }, function (s, msg) {
                alert(msg);
            });
        }
    });

    $("#assessmentWrapper").submit(function () {
        return false;
    });
    $(document).on("click", "#aKeyNotesAccepted", function () {
        $("#saveAssessment").removeAttr("disabled");
    });
    $(document).on("change", ".evidence-text", function () {
        $("body").trigger('dataChanged');
    });
    $(document).on("click", ".deleteKeyNote", function () {
        var sibling = $(this).closest(".keynote-wrap");
        sibling.remove();
        $("body").trigger('dataChanged');
    });
    $(document).on("click", ".addKeyNote", function () {
        var type = $(this).data('type');

        var frm = $("#popup-diagnostic_keyrecommendations.modal.fade.in");
        var type_q = $(frm).find("#id_level_name").val();
        var param = "assessment_id=" + $(frm).find("#assessment_id").val() + "&level_type=" + $(frm).find("#id_level_type").val() + "&instance_id=" + $(frm).find("#id_instance_id").val() + "&" + "type=" + type + "&type_q=" + $(frm).find("#id_level_name").val() + "&token=" + getToken();
        apiCall(this, "addKeyNote", param, function (s, data) {
            type != '' ? $(frm).find(".keynote-wrap." + type).last().after(data.content) : $(frm).find(".keynote-wrap").last().after(data.content);
            if (type_q == "kpa" && type == "recommendation") {
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

        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });
    $(document).on("submit", "#key_notes_frm", function () {
        var frm = $("#popup-diagnostic_keyrecommendations.modal.fade.in");
        var f = $(this);
        var lang_id = $("#lang-n option:selected").val();
        var param = f.serialize() + "&lang_id=" + lang_id + "&token=" + getToken();
        var id_level_name = frm.find("#id_level_name").val();
        var id_instance_id = frm.find("#id_instance_id").val();
        var id_tab_type_kn = frm.find("#id_tab_type_kn").val();
        ;
        var diagnostic_type = frm.find("#diagnostic_type").val();
        ;
        var isCom = true;
        var id_sourcetype = $(f).find('#id_sourcetype').val();
        $(f).find(".keynotes-text").each(function (j, v) {
            if (!isValidText($(v).val()))
                isCom = false;

        });
        if (!isCom) {
            alert("Please fill all the fields");
            return false;
        }
        apiCall(this, "saveKeyRecommendations", param, function (s, data) {
            $("#assessmentForm").find("." + id_tab_type_kn + "#" + id_tab_type_kn + "_" + id_instance_id).val(data.kncomplete);
            $("body").trigger("dataChanged");
            checktabTicks(diagnostic_type);

            checkFormCompletion();
            alert("Key Recommendations saved!");
            frm.modal('hide');
        }, function (s, msg) {
            alert(msg);
        });
        return false;
    });

    $(document).on("click", ".prevJs", function () {
        var e = $("#js-id-" + $(this).data('jsid'));
        if (e.length) {
            $(".modal").modal("hide");
            e.parents(".tab-pane").each(function (i, v) {
                $(v).parent().find("> .tab-pane.active").removeClass("active");
                $(v).addClass('active in');
                var a = $("a[href=#" + $(v).attr('id') + "]");
                a.parents("ul").first().find(".active").removeClass("active");
                a.parent().addClass('active');
            });
            e = e.find(".radio_js:checked");
            e = e.length ? e : e.find(".radio_js").first();
            e.focus();
        }
    });
    $(document).on("click", ".prevKn", function () {
        var e = $("#kn-id-" + $(this).data('knid'));
        if (e.length) {
            $(".modal").modal("hide");
            e.parents(".tab-pane").each(function (i, v) {
                $(v).parent().find("> .tab-pane.active").removeClass("active");
                $(v).addClass('active in');
                var a = $("a[href=#" + $(v).attr('id') + "]");
                a.parents("ul").first().find(".active").removeClass("active");
                a.parent().addClass('active');
            });
            e.focus();
        }
    });
}