/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/* result declaration process
 * Copyright (c) ISM dhanbad * 
 * @category   phpExcel
 * @package    exam_tabulation
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #26/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

    var oTablea = $("#ex_mod2").dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": true
    });

    var oTablea_pre = $("#ex_mod4").dataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": true
    });
    function view(admn_no,sess, syear,dept, course_id,branch_id, sem,section, name,exam_type){
        $.ajax({            
            url: site_url('result_declaration/result_declaration_drside/view_result'),
            type: "POST",
            async: false,
            data: {"admn_no": admn_no, "session": sess, "session_year": syear, "course_id": course_id, "branch_id": branch_id, "semester": sem, 'name': name,'dept':dept,'exam_type':exam_type,'section':section},
            success: function (r) {
                $('#slider3 .modal-body').html(r);
                viewshow();
            }
        });
    }
    function undo(id) {
        $.ajax({
            //result_declaration/result_declaration_drside/show_details
            url: site_url('result_declaration/result_declaration_drside/undo'),
            type: "POST",
            async: false,
            data: {"id": id},
            success: function (r) {
                r = $.parseJSON(r);
                if (Boolean(r.status) == true) {
                    $('#status' + id).html('<span class="label label-warning">Pending</span>');
                }
            }
        });
    }
    function saveRe(rdec_type) {
        //alert(rdec_type);
        if ((rdec_type == 'prerd' ? $('#datere_pre').val() : $('#datere').val()) == "") {
            (rdec_type == 'prerd' ? $("#rerror_pre") : $("#rerror")).html("Please Enter Redeclartion Date First !").show();
            setTimeout(function () {
                (rdec_type == 'prerd' ? $("#rerror_pre") : $("#rerror")).fadeOut();
            }, 1000);
            return false;
        } else if ((rdec_type == 'prerd' ? $("#rreason_pre").val() : $("#rreason").val()) == "") {
            (rdec_type == 'prerd' ? $("#rerror_pre") : $("#rerror")).html("Please Enter Redeclartion Reason !").show();
            setTimeout(function () {
                (rdec_type == 'prerd' ? $("#rerror_pre") : $("#rerror")).fadeOut();
            }, 1000);
            return false;
        } else {
            // alert('hello');
            var chk1 = '';
            var pre_chk_str = '';
            var post_chk_str = '';
            var id = $("#rowid").val();
            $("input:checked", (rdec_type == 'postrd' ? oTablea : oTablea_pre).fnGetNodes()).each(function () {
                chk1 += $(this).val() + "-";
                /*    if(rdec_type=='prerd')        
                 pre_chk_str += $(this).val();
                 else  if(rdec_type=='postrd')        
                 post_chk_str += $(this).val();*/
            });
            chk1 = chk1.slice(0, -1);
            /*if(pre_chk_str<>'')
             ('#pre_entry-fields').show();
             else if(post_chk_str<>'')
             ('#post_entry-fields').show();
             */
            $.ajax({
                //result_declaration/result_declaration_drside/show_details
                url: site_url('result_declaration/result_declaration_drside/save_re_declared'),
                type: "POST",
                async: true,
                data: {"rid": $('#rid').val(), "res_re_id": chk1, "date": (rdec_type == 'prerd' ? $("#datere_pre").val() : $("#datere").val()), "reason": (rdec_type == 'prerd' ? $("#rreason_pre").val() : $("#rreason").val()), rdec_type: rdec_type},
                success: function (r) {
                    r = $.parseJSON(r);
                    if (Boolean(r.status) == true) {
                        $("input:checked", (rdec_type == 'postrd' ? oTablea : oTablea_pre).fnGetNodes()).each(function () {
                            ad = $(this).val();
                            $('#tr' + ad).remove();
                            $('#abc' + ad).removeAttr('disabled');
                        });
                         $('#viewreport1').modal('hide');                                                      
                             $("#msg").show();                                    
                             $("#msg").html("");                                                                                
                             $("#msg").removeClass().addClass("alert alert-success");                                                                             
                        $('#msg').html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + (rdec_type == 'prerd' ? 'Pre-Withhold Redeclaration' : 'Post-Withhold Redeclaration ') + " done successfully for selected candidates");
                       } else {
                        if (rdec_type == 'postrd') {
                             $('#viewreport1').fadeOut();
                             $("#msg").removeClass().addClass("alert alert-danger");
                            //alert("Please Check !  Whether result modification(done after result declaration) have been  done for all the  selected student(s)? If not then first do the process  of result modification for all the selected student(s) then redeclare thier result(s)");
                              $("#msg").html("Please Check !  Whether result modification(done after result declaration) have been  done for all the  selected student(s)? If not then first do the process  of result modification for all the selected student(s) then redeclare thier result(s)").show();
                             setTimeout(function () {
                             $("#msg").hide();
                             }, 5000);
                             return false;
                        }
                    }
                                d = new Date();
                                 timestamp = d.timestamp();                                   
                                
                               $("#msg").append(" on ");
                               $("#msg").append(timestamp); 
                               $("#msg").append(" By DR-Exam ");                                
                                 setTimeout(function () {                                
                                $("#msg").hide();                                  
                            }, 5000);
                },
                     error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    });
           Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };
        }
    }
    function viewshow(rdec_type) {
        if (rdec_type == 'prerd')
            $("#slider4").hide();
        else if (rdec_type == 'postrd')
            $("#slider2").hide();
        $("#slider3").show().effect('slide', {}, 500);
    }
    function viewback(rdec_type) {
        $("#slider3").hide();
        if (rdec_type == 'prerd')
            $("#slider4").show().effect('slide', {}, 500);
        else if (rdec_type == 'postrd')
            $("#slider2").show().effect('slide', {}, 500);
    }
    function sback(rdec_type) {
        if (rdec_type == 'prerd') {
            $("#slider4").hide();
        }
        else if (rdec_type == 'postrd') {
            $("#slider2").hide();
        }
        $("#slider1").show().effect('slide', {}, 500);
    }
    function resave(id) {
        $('#form' + id).remove();
        $('#st' + id + ' a').remove();
        $('#st' + id).html("Date");
    }
    function sturedeclare(id, rdec_type) {
        //$('#radmn_no').val(admn_no);
        $('#rid').val(id);
        $("#slider1").hide();
        if (rdec_type == 'prerd') {
            $("#slider4").show().effect('slide', {}, 500);
            $("#slider2").hide();
            /*var pre_ctr=0;
             $("input:checked", (oTablea_pre).fnGetNodes()).each(function () {
             if($(this).val()) pre_ctr++;
             });       
             if(pre_ctr==0) $('#pre_entry-fields').hide(); else $('#pre_entry-fields').show();
             */
        }
        else if (rdec_type == 'postrd') {
            $("#slider2").show().effect('slide', {}, 500);
            $("#slider4").hide();
            /*  var post_ctr=0;
             $("input:checked", (oTablea).fnGetNodes()).each(function () {
             if($(this).val()) pre_ctr++;
             });       
             if(post_ctr==0) $('#post_entry-fields').hide(); else $('#post_entry-fields').show();
             */
        }
        //  $('#st'+id+' a').hide();
        // $('#form'+id).show();
        // $('#form'+id+' a').show();
    }
    $(document).ready(function () {
        $('#b_date, #datere, #datere_pre').datepicker({
            endDate: "+0d",
            autoclose: true
        });
        var oTable = $("#ex_mod1").dataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bSort": true,
            "bInfo": true,
            "bAutoWidth": true,
            "bStateSave": true
        });
        $('#save_all').on('click', function () {
            var bool = "";
            var sy = $("#hsyear").val();
            var sess = $("#hsess").val();
            var et = $("#hetype").val();
            var did = $("#hdeptid").val();
            var sec = $("#hsec_name").val();
            var cid = $("#hcid").val();
            var bid = $("#hbid").val();
            var sem = $("#hsem").val();
            var chk1 = '';
            var id = $("#rowid").val();
            $("input:checked", oTable.fnGetNodes()).each(function () {
                if (!$(this).is(':disabled')) {
                    chk1 += $(this).val() + "-";
                }
            });
            chk1 = chk1.slice(0, -1);
            // alert(chk1 ); 
            $.ajax({
                //result_declaration/result_declaration_drside/show_details
                url:  site_url('result_declaration/result_declaration_drside/save_admn_no_re_declared'),
                type: "POST",
                dataType: "json",
                async: false,
                //<!-- added for pre-with held condition implementation  "redec_type"  @9-dec-16-->
                data: {"rid": id, "admno": chk1, "vsy": sy, "vsess": sess, "vet": et, "vdid": did, "vsec": sec, "vcid": cid, "vbid": bid, "vsem": sem, "b_date": $("#b_date").val(), rdec_type: $("#rdec_type").val()},
                success: function (r) {
                    //alert(r.error);
                    if (Boolean(r.error)) {
                        bool = 1;
                    } else {
                        //  alert(r);
                        bool = 0;
                    }
                    $('#viewreport1').modal('toggle');
                }
            });
            //  console.log(Boolean(bool));                                                                                               
            $.ajax({
                url:  site_url('result_declaration/result_declaration_drside/show_details'),
                type: "POST",
                async: false,
                data: {"session_year": sy, "session": sess, "exm_type": et, "dept": did, "section_name": sec, "sem": sem, rdec_type: $("#rdec_type").val(), prevstate: Boolean(bool)},
            });
            // alert(Boolean(bool));
            //  console.log(Boolean(bool));                                                                                               
            if (Boolean(bool)) {
                $("#msg").removeClass().addClass("alert alert-success");
                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + ($("#rdec_type").val() == 'prerd' ? 'Pre-Withheld' : 'Post-Withheld') + " done successfully for selected candidates [" + chk1 + "]  </strong>for <b>Session_yr:</b>" + sy + ",<b>Session:</b>" + sess + ",<b>Exam type:</b>" + et + ",<b>Department:</b>" + $("#dptname").val() + " " + ((sec) ? ",<b>Section:</b>" + sec + "" : "") + ",<b>Course:</b>" + $("#crs_name").val() + ",<b>Branch:</b>" + $("#brn_name").val() + ",<b>Semseter:</b>" + sem + "");
            }
            else {
                $("#msg").removeClass().addClass("alert alert-danger");
                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + ($("#rdec_type").val() == 'prerd' ? 'Pre-Withheld' : 'Post-Withheld') + " Failed either due candidates were  already " + ($("#rdec_type").val() == 'prerd' ? 'Pre-Withhold' : 'Post-Withhold') + " from before or Internal error occured, for selected candidates</strong> for  <b>Session_yr:</b>" + sy + ",<b>Session:</b>" + sess + ",<b>Exam type:</b>" + et + ",<b>Department:</b>" + $("#dptname").val() + " " + ((sec) ? ",<b>Section:</b>" + sec + "" : "") + ",<b>Course:</b>" + $("#crs_name").val() + ",<b>Branch:</b>" + $("#brn_name").val() + ",<b>Semseter:</b>" + sem + "");
            }
        });
    });
