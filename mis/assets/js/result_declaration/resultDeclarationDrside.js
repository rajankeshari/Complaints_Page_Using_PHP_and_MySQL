/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    if ($("#exm_type").val() == 'prep') {
        if (!$('#dept option').filter(function () {
            return $(this).val() == 'all'
        }).length) {
            $('<option/>').val('all').html('All').appendTo("#dept");
        } else
            $("#dept option[value='all']").remove();
        $('#dept').val('all');
    } else {
        $("#dept option[value='all']").remove();
    }
    if ($('#exm_type').val() == 'regular' || $('#exm_type').val()=='other') {
        if (!$('#dept option').filter(function () {
            return $(this).val() == 'comm'
        }).length) {
            $('<option/>').val('comm').html('Common').appendTo("#dept");
        } else
            $("#dept option[value='comm']").remove();
    } else {
        $("#dept option[value='comm']").remove();
    }
    if ($('#hdeptid').val() != 'comm'){
        $('#sec').hide();
		$('#sec').val('');
		$('#hsec_name').val('');
		$('#section_id').val('');
		
	}
    else {
        $('#sec').show();
        $("#dept").append('<option value="comm">Common</option>');
        $('#dept').val('comm');
        $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + $('#hsyear').val()), type: "json",
            success: function (result) {
                $('#section_id').html(result);
                $('#section_id > option[value=""]').remove();
                $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                $('#section_id').val($('#hsec_name').val());
            }});
    }
    $.ajax({
        url: site_url("attendance/attendance_ajax/get_session_year_exam"),
        data: {'sy': $('#hsyear').val()},
        success: function (result) {
            $('.gS').html(result);
            if ($('#session_year_attendance option').filter(function () {
                    return $(this).val() == '2015-2016'
                }).length){ 
                    $("#session_year_attendance option[value='2015-2016']").remove();
                }
                  if ($('#session_year_attendance option').filter(function () {
                    return $(this).val() == '2016-2017'
                }).length){ 
                    $("#session_year_attendance option[value='2016-2017']").remove();
                }
        }
    });
    $('#exm_type').on('change', function () {
        var exists = false;
        var exists1 = false;
        $('#dept option').each(function () {
            if (this.value == 'comm') {
                exists = true;
            }
            else if (this.value == 'all') {
                exists1 = true;
            }
        });
        if (this.value == "regular" || this.value == 'other') {
            if (exists == true)
                $("#dept option[value='comm']").remove();
            else
                $("#dept").append('<option value="comm">Common</option>');
            if (exists1 == true)
                $("#dept option[value='all']").remove();
        }
        else if (this.value == "prep") {
            if (exists1 == true)
                $("#dept option[value='all']").remove();
            else
                $("#dept").append('<option value="all">All</option>');
            if (exists == true)
                $("#dept option[value='comm']").remove();
            $('#dept').val('all');
        }
        else {
            if (exists1 == true)
                $("#dept option[value='all']").remove();
            if (exists == true)
                $("#dept option[value='comm']").remove();
        }
    });
    $('#dept').on('change', function () {
        var session_year = $('#session_year_attendance').val();
        if (this.value == "comm") {
            $('#sec').show();
            $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                    $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');
                }});
        } else {
            $('#sec').hide();	$('#sec').val('');$('#hsec_name').val('');$('#section_id').val('');
        }
    });
    //loading only req. informative tab    content
    var sy = $("#hsyear").val();
    var sess = $("#hsess").val();
    var et = $("#hetype").val();
    var did = $("#hdeptid").val();
    var sec = $("#hsec_name").val();
    var i = 0;
    var oTable = $("#ex_mod").dataTable({
        "bPaginate": false, // no paginaion req.
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": true,  // no paginaion req.
		  "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
        "iDisplayLength": 10,
    });
    // logic if  want to show  count of prewithhold+postwithhold & show coressponding rows color into red (if count>0 ie, there is still result pending either pre-withheld or post-withheld)
    //   on  the page before page load 
    $(oTable.fnGetNodes()).each(function () {
        var tot = 0;
        if ($("#re_pub_id_" + i).val() == '' || $("#re_pub_id_" + i).val() == null) {
            foo_pre_post_ctr_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#rowid-" + i).val(), null, function (data) {
                tot = data.ctr;
            });
            if ((tot) < 1) {
                //   $('#tot_identity_' + i).hide();
            } else {
                
                //sTitle = "<b class='badge badge-warning'>" +tot+ "</b> students' either pre-withold or post-wthhold or both,need re-decalaration results' for those.";                                 
                sTitle = "" +tot+ " students' either pre-withold or post-wthhold or both,need re-decalaration results' for those.";                                 
                $(this).removeClass().addClass("danger");
                $('td:last', this).append('<p><i style="color:red">[' + tot + ' remains]</i>');               
                //this.setAttribute( 'title',$.parseHTML(sTitle));
                this.setAttribute( 'title',sTitle);
            }
        }
        i++;
    });
    
    oTable.$('tr').tooltip( {
        "delay": 0,
        "track": true,
        "fade": 250        
    } );
    // end
    
    // logic if  want to show  count of prewithhold/postwithhold/prewithhold+postwithhold/formal appealed count/actual appealed count on page before page load(however logis slow down th page load)
    /*$(oTable.fnGetNodes()).each(function () {
     foo_formal_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), function (data) {
     
     $('#formal_identity_icon_' + i).html(data.ctr);
     });
     foo_actual_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#published-" + i).val(), $("#actual_published-" + i).val(), function (data) {
     
     $('#actual_identity_icon_' + i).html(data.ctr);
     $('#actual2_identity_icon_' + i).html(data.ctr);
     
     });
     foo_pre_post_ctr_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#rowid-" + i).val(), 'pre', function (data) {
     if ((data.ctr) < 1)
     $('#pre_identity_' + i).hide();
     else
     $('#pre_identity_icon_' + i).html(data.ctr);
     });
     foo_pre_post_ctr_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#rowid-" + i).val(), 'post', function (data) {
     if ((data.ctr) < 1)
     $('#post_identity_' + i).hide();
     else
     $('#post_identity_icon_' + i).html(data.ctr);
     });
     foo_pre_post_ctr_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#rowid-" + i).val(), null, function (data) {
     if ((data.ctr) < 1)
     $('#tot_identity_' + i).hide();
     else
     $('#tot_identity_icon_' + i).html(data.ctr);
     
     });
     var x = 0, y = 0;
     foo_formal_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), function (data) {
     x = data.ctr;
     //     alert(x);
     });
     foo_pre_post_ctr_spl(sy, sess, et, did, sec, $("#hcid-" + i).val(), $("#hbid-" + i).val(), $("#hsem-" + i).val(), $("#rowid-" + i).val(), 'pre', function (data) {
     y = data.ctr;
     //  alert(sy);
     });     
     $('#actual_pending_identity_icon_' + i).html((x - y));
     $('#actual_pending_identity_title_' + i).html((x - y));
     $('#actual_pending2_identity_icon_' + i).html((x - y));
     $('#actual_pending2_identity_title_' + i).html((x - y));             
     i++;
     });*/
    
    //end  
    
    //re-initialize data table
    $('#ex_mod').dataTable().fnDestroy();    // destroy properties of data table no data
    $("#ex_mod").dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": true,
			  "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "All"]],
        "iDisplayLength": 10,
    });
    
})
var repeater = '';
$('#viewreport2').on('hidden.bs.modal', function () {
    $('link[title="dec_stu_list"]').prop('disabled', true);
})
$(".viewreport2").dialog({
    close: function (event, ui) {
        $('link[title="dec_stu_list"]').prop('disabled', true);
        //$('link[rel="stylesheet"]').remove(); 
    }
});
function my_fun_edit(row) {
    var sy = $("#hsyear").val();
    var sess = $("#hsess").val();
    var et = $("#hetype").val();
    var did = $("#hdeptid").val();
    var sec = $("#hsec_name").val();
    var cid = $("#hcid-" + row).val();
    var bid = $("#hbid-" + row).val();
    var sem = $("#hsem-" + row).val()
    //    alert(sy);alert(sess);alert(et);alert(did);alert(sec);alert(cid);alert(bid);alert(sem);
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/date_show'),
        type: "POST",
        async: false,
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem},
        success: function (data) {
            $('#reportresult').html(data);
            $('#viewreport1').modal({
                show: false
            });
            $('#viewreport2').modal({
                show: false
            });
        }
    });
}
function  pdeclare(row, row_id, decId, rdec_type, published_on, actual_published_on) {
    var sy = $("#hsyear").val();
    var sess = $("#hsess").val();
    var et = $("#hetype").val();
    var did = $("#hdeptid").val();
  //  var sec = $("#hsec_name").val();
  var sec = $("#hsec_name-" + row).val();
    var cid = $("#hcid-" + row).val();
    var bid = $("#hbid-" + row).val();
    var sem = $("#hsem-" + row).val();
	
	//console.log ($("#hsec_name-" + row).val());
    $('#viewreport1 .modal-content').html("<h2>Please Wait...</h2>");
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/stulist_with_date_show'),
        type: "POST",
        async: false,
        data: {"vsy": sy, "vsess": sess, published: published_on, actual_published: actual_published_on, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, row_id: row_id, decid: decId, rdec_type: rdec_type, status: $("#rd_type").val()},
        success: function (data) {
            $('#viewreport1 .modal-content').html(data);
            $('#viewreport2').modal({
                show: false
            });
            $('#viewreport').modal({
                show: false
            });
        }
    });
}
function my_fun_undo(id) {
    var r = confirm("Do You Really Want to Undo the Declaration");
    if (r == true) {
        $.ajax({
            url: "<?php echo site_url('result_declaration/result_declaration_drside/undo_record') ?>",
            type: "POST",
            async: false,
            data: {"rid": id},
            success: function (data) {
                if (data) {
                    alert("Undo Done Successfully");
                }
            }
        });
    }
    else {
        return false;
    }
}
function my_fun_view(row, published_on, actual_published_on) {
    var sy = $("#hsyear").val();
    var sess = $("#hsess").val();
    var et = $("#hetype").val();
    var did = $("#hdeptid").val();
    //var sec = $("#hsec_name").val();
	var sec = $("#hsec_name-" + row).val();
    var cid = $("#hcid-" + row).val();
    var bid = $("#hbid-" + row).val();
	
    var sem = $("#hsem-" + row).val();
	
	//console.log(bid);
	
    $('#viewdataone').html("<h2>Please Wait...</h2>");
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/show_data_for_view'),
        type: "POST",
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, published: published_on, actual_published: actual_published_on, status: ($("#param").val()=='cbcs'?'F':$("#rd_type").val()) ,param:$("#param").val()   },
        success: function (data) {
            $('#viewdataone').html(data);
            $('#viewreport1').modal({
                show: false
            });
            $('#viewreport').modal({
                show: false
            });
        }
    });
}
function my_fun_view_redeclare(id, published_on, actual_published_on, row) {
    var sy = $("#hsyear").val();
    var sess = $("#hsess").val();
    var et = $("#hetype").val();
    var did = $("#hdeptid").val();
   // var sec = $("#hsec_name").val();
	var sec = $("#hsec_name-" + row).val();
    var cid = $("#hcid-" + row).val();
    var bid = $("#hbid-" + row).val();
    var sem = $("#hsem-" + row).val();
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/show_data_for_view_redeclare'),
        type: "POST",
        data: {"id": id, "published": published_on, actual_published: actual_published_on, "vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, status: $("#rd_type").val()},
        success: function (data) {
            $('#viewdataone').html(data);
            $('#viewreport1').modal({
                show: false
            });
            $('#viewreport').modal({
                show: false
            });
        }
    });
}
function foo_actual_spl(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, published_on, actual_published_on, callback) {
    //$('#actual').html("");
    return  $.ajax({
        url: site_url('result_declaration/result_declaration_drside/count_tot_stu_acatually_to_dec'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester, "published": published_on, "actual_published": actual_published_on, "status": $("#rd_type").val()},
    })
            .done(callback)
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            });
}
function foo(i, element_caption_initial) {
    // alert($('#' + element_caption_initial + '_identity_icon_' + i).html());
    $('#' + element_caption_initial + '_identity_title_' + i).html($('#' + element_caption_initial + '_identity_icon_' + i).html());
}

function foo_formal_spl(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, callback) {
    return $.ajax({
        url: site_url('result_declaration/result_declaration_drside/count_tot_stu_formally_to_dec'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester},
    })
            .done(callback)
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            });
}
function foo_pre_post_ctr_spl(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, id, type, callback) {
    //alert(sy);
    return $.ajax({
        url: site_url('result_declaration/result_declaration_drside/pre_post_badges_cal'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {id: id, type: type, "vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester, "status": $("#rd_type").val()},
    })
            .done(callback)
            .fail(function (jqXHR, textStatus, errorThrown) {
                alert(errorThrown);
            });
}
function foo_actual(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, published_on, actual_published_on) {
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/count_tot_stu_acatually_to_dec'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester, "published": published_on, "actual_published": actual_published_on, "status": $("#rd_type").val()},
        success: function (data)
        {
            $('#actual').html(data.ctr);
        }
    });
}
function cal_actual_at_pending_state(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, id) {
    var x = 0, y = 0;
    foo_formal_spl(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, function (data) {
        x = data.ctr;
    });
    foo_pre_post_ctr(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, id, 'pre', function (data) {
        y = data.ctr;
    });
    $('#pre_actual' + id).html((x - y));
}

function foo_formal(sy, sess, et, deptid, sec_name, course_id, branch_id, semester) {
    //$('#actual').html("");
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/count_tot_stu_formally_to_dec'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {"vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester},
        success: function (data)
        {
            $('#formal').html(data.ctr);
        }
    });
}
function foo_pre_post_ctr(sy, sess, et, deptid, sec_name, course_id, branch_id, semester, id, type) {
    //$('#actual').html("");
    $.ajax({
        url: site_url('result_declaration/result_declaration_drside/pre_post_badges_cal'),
        type: "POST",
        dataType: "json",
        async: false,
        data: {id: id, type: type, "vsy": sy, "vsess": sess, "vet": et, "vdid": deptid, "vsec": sec_name, "vcid": course_id, "vbid": branch_id, "vsem": semester, "status": $("#rd_type").val()},
        success: function (data)
        {
            if (type == 'post')
                $('#post').html(data.ctr);
            else if (type == 'pre')
                $('#pre').html(data.ctr);
            else
                $('#tot').html(data.ctr);
        }

    });
}
/*function printDiv(div) {    
 var elem = document.getElementById(div);
 var domClone = elem.cloneNode(true);
 var $printSection = document.createElement("div");
 $printSection.id = "printSection";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
 $printSection.appendChild(domClone);
 document.body.insertBefore($printSection, document.body.firstChild);
 window.print();
 // Clean up print section for future use
 var oldElem = document.getElementById("printSection");
 if (oldElem != null) {
 oldElem.parentNode.removeChild(oldElem);
 }
 //oldElem.remove() not supported by IE
 return true;
 }*/