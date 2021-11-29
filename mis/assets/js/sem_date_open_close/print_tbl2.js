/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    
    $('#syr').val($('#selsyear').val());
    /*if($('#seletype').val()=='regular'){
     if (!$('#seldept option').filter(function () {return $(this).val() == 'comm';}).length)                            
     $('<option/>').val('comm').html('Common').appendTo("#seldept");                        
     }else{
     $("#seldept option[value='comm']").remove();    
     }*/
    //$('#branchlist').val('');
    //$('#courselist').val('');
    // $('#semlist').val('');

    if ($('#seletype').val() == 'Defaulter') {
        $('.fine_optional').css({'display': 'none'});
        $('.fine_optional_field').css({'display': 'none'});
        $(".fine_optional_field").val('');
    }
    else {
        $('.fine_optional').css({'display': 'block'});
        $('.fine_optional_field').css({'display': 'block'});
    }

    $('#sec').hide();
    if ($('#selmtype').val() == "all")
    {
        $("#divdept").hide();
        $("#divstudent").hide();
        $('#granual_row').css({'display': 'none'});
        // $("#lfine").hide();
    }
    else if ($('#selmtype').val() == "specific")
    {
        $("#divdept").show();
        $("#divstudent").hide();
        //$("#lfine").hide();
        $('#granual_row').css({'display': 'block'});
    }
    else if ($('#selmtype').val() == "indi_stu")
    {
        $("#divstudent").show();
        $("#divdept").hide();
        $('#granual_row').css({'display': 'none'});
    }


    $.ajax({
        url: site_url("attendance/attendance_ajax/get_session_year_exam"),
        data: {'sy':( $('#selsyear').val() == null ? $('#syr').val() : $('#selsyear').val())},
          beforeSend: function(xhr) {
    xhr.setRequestHeader("custom_header", "value");
  },
        success: function (result) {            
            $('.gS').html(result);



        },
         error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText+'_'+xhr.responseText);
                            alert(thrownError);
                        },
    });


    var session_year = $('#selsyear').val() == null ? $('#syr').val() : $('#selsyear').val();
    if ($("select[name='seldept']").val() == "comm") {
        $('#granual_row').css({'display': 'none'});
        $('#branchlist').val('');
        $('#courselist').val('');
        $('#semlist').val('');
        $('#sec').show();
        $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
            success: function (result) {
                $('#seclist').html(result);
                $('#seclist > option[value=""]').remove();
                /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                 $('#section_id').val('all');*/
            }});
    } else {
        $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_course_dept_csshow/" + $("select[name='seldept']").val()), 
            success: function (result) {

                $('#courselist').html(result);
                if ($("#seldept").val() != 'comm') {
                    $('#sec').hide();
                    $('#seclist').val('');
                    $('#courselist > option[value="honour"]').remove();
                    $('#courselist > option[value="capsule"]').remove();
                    $('#courselist > option[value="comm"]').remove();
                    if ($('#seletype').val() != 'regular') {
                        $('#courselist > option[value="minor"]').remove();
                    }
                    else {
                        if (!$('#courselist option').filter(function () {
                            return $(this).val() == 'minor';
                        }.length) && $("#seldept").val() != "")
                            $('<option/>').val('minor').html('Minor Course').appendTo("#courselist");
                    }
                    if ($("select[name='seletype']").val() == 'jrf' || $("select[name='seletype']").val() == 'jrf_spl') {
                        $("select[name='courselist']").empty();
                        $("select[name='courselist']").empty();
                        if (!$('#courselist option').filter(function () {
                            return $(this).val() == 'jrf';
                        }).length)
                            $('<option/>').val('jrf').html('JRF').appendTo("#courselist");
                        if (!$('#branchlist option').filter(function () {
                            return $(this).val() == 'jrf';
                        }).length)
                            $('<option/>').val('jrf').html('JRF').appendTo("#branchlist");
                        $("#semlist").empty();
                        if ($('.sem').show)
                            $('.sem').hide();
                        if ($('.sec').show)
                            $('.sec').hide();
                    }
                    else {
                        $('#courselist > option[value="jrf"]').remove();
                        $('#branchlist > option[value="jrf"]').remove();
                        if ($('.sem').hide) {
                            $('.sem').show();
                            $('.sec').hide();
                        }
                    }


                }
                //alert($("input[name='branchlist_hidden']").val());
                //$('#courselist').val($("input[name='courselist_hidden']").val());
                $("#courselist option").filter(function () {
                    return $(this).val() == $("input[name='courselist_hidden']").val()
                }).prop('selected', true);
            }});
    }
    // alert($("input[name='courselist_hidden']").val());
    //alert($("input[name='branchlist_hidden']").val() );
    //alert($("select[name='seldept']").val());
    var cid = $("select[name='courselist']").val();
    var did = $("select[name='seldept']").val();
    $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_branch_bycourse/" + $("select[name='courselist']").val() + "/" + $("select[name='seldept']").val()), 
        success: function (result) {
            $('#branchlist').html(result);
            $("#branchlist option").filter(function () {
                return $(this).val() == $("input[name='branchlist_hidden']").val()
            }).prop('selected', true);
        }});
    $.ajax({
        url: site_url('sem_date_open_close/semester_date_open_close/get_branch_by_course_dept'),
        type: "POST",
        data: {"dept_id": did, "course_id": cid},
        success: function (data)
        {

            // getting  multiple array's value of element 
            var selectedArr = (($("select[name='seldept']").val() != 'comm' ? $('input[name="semlist_hidden[]"]') : $('input[name="seclist_hidden[]"]'))).map(function () {
                return this.value; // $(this).val()
            }).get();

            // alert("You have selected  - " + selectedArr.join(", "));       

            //end
            //alert(data);
            //$("#semlist option:selected").each(function () {str += $(this).text() + " ";}); alert(str);
            var json = $.parseJSON(data);
            // alert(json.sem_list[0].duration);



            var tsess = $('#selsession').val();

            if ($("select[name='seldept']").val() != 'comm') {
                if (tsess == 'Monsoon') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    var selected = '';
                    options += ("<option value='' >Select Semester</option>");
                    for (var i = 1; i <= (json.sem_list[0].duration) * 2; i = i + 2) {

                        if (jQuery.inArray("" + i + "", selectedArr) > -1) {

                            selected = ' selected ';
                        } else {
                            selected = '';

                        }
                        options += '<option value="' + i + '"  ' + selected + '>' + i + '</option>';

                    }

                    $("select#semlist").html(options);
                }
                if (tsess == 'Winter') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    var selected = '';
                    options += ("<option value=''>Select Semester</option>");
                    for (var i = 2; i <= (json.sem_list[0].duration) * 2; i = i + 2) {
                        if (jQuery.inArray("" + i + "", selectedArr) > -1) {
                            selected = ' selected ';
                        }
                        else {
                            selected = '';
                        }
                        options += '<option value="' + i + '"  ' + selected + '>' + i + '</option>';
                    }
                    $("select#semlist").html(options);
                }
                if (tsess == 'Summer') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    var selected = '';
                    options += ("<option value='' >Select Semester</option>");
                    for (var i = 1; i <= (json.sem_list[0].duration) * 2; i = i + 1) {
                        if (jQuery.inArray("" + i + "", selectedArr) > -1) {
                            selected = ' selected ';
                        }
                        else {
                            selected = '';
                        }
                        options += '<option value="' + i + '"  ' + selected + '>' + i + '</option>';
                    }
                    $("select#semlist").html(options);
                }
            }// end  of !comm
            else {
                $('#seclist').html('');
                $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                    success: function (result) {
                        $('#seclist').html(result);
                        $('#seclist > option[value=""]').remove();
                        /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                         $('#section_id').val('all');*/
                        $("#seclist").val(selectedArr);
                    }});
            }

        }




    });



    /*  $.ajax({url: site_url("exam_absent_record/report_wef/get_course_drexam"),
     success: function (result) {
     $('#courselist').html(result);
     }});
     $.ajax({url: site_url("exam_absent_record/report_wef/get_branch_drexam"),
     success: function (result) {
     $('#branchlist').html(result);
     }});*/

    $('#seldept').on('change', function () {
        var session_year = $('#selsyear').val();
        if (this.value == "comm") {
            $('#granual_row').css({'display': 'none'});
            $('#branchlist').val('');
            $('#courselist').val('');
            $('#semlist').val('');
            $('#sec').show();
            $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#seclist').html(result);
                    $('#seclist > option[value=""]').remove();
                    /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                     $('#section_id').val('all');*/
                }});
        } else {
            $('#granual_row').css({'display': 'block'});
            $('#sec').hide();
            $('#seclist').val('');
            $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_course_dept_csshow/" + this.value),
                success: function (result) {
                    $('#courselist').html(result);
                    if ($("#seldept").val() != 'comm') {
                        $('#courselist > option[value="honour"]').remove();
                        $('#courselist > option[value="capsule"]').remove();
                        $('#courselist > option[value="comm"]').remove();
                        if ($('#seletype').val() != 'regular') {
                            $('#courselist > option[value="minor"]').remove();
                        }
                        else {
                            if (!$('#courselist option').filter(function () {
                                return $(this).val() == 'minor';
                            }.length) && $("#seldept").val() != "")
                                $('<option/>').val('minor').html('Minor Course').appendTo("#courselist");
                        }
                        if ($("select[name='seletype']").val() == 'jrf' || $("select[name='seletype']").val() == 'jrf_spl') {
                            $("select[name='courselist']").empty();
                            $("select[name='courselist']").empty();
                            if (!$('#courselist option').filter(function () {
                                return $(this).val() == 'jrf';
                            }).length)
                                $('<option/>').val('jrf').html('JRF').appendTo("#courselist");
                            if (!$('#branchlist option').filter(function () {
                                return $(this).val() == 'jrf';
                            }).length)
                                $('<option/>').val('jrf').html('JRF').appendTo("#branchlist");
                            $("#semlist").empty();
                            if ($('.sem').show)
                                $('.sem').hide();
                            if ($('.sec').show)
                                $('.sec').hide();
                        }
                        else {
                            $('#courselist > option[value="jrf"]').remove();
                            $('#branchlist > option[value="jrf"]').remove();
                            if ($('.sem').hide) {
                                $('.sem').show();
                                $('.sec').hide();
                            }
                        }

                    }
                    /* else {
                     $("#course1 option[value='']").remove();
                     if (!$('#course1 option').filter(function () {
                     return $(this).val() == 'comm';
                     }).length)
                     $('<option/>').val('comm').html('Common Course for 1st Year').appendTo("#course1");
                     $("select[name='branch1']").empty();
                     $('<option/>').val('comm').html('Common Course for 1st Year').appendTo("#branch1");
                     $('.sec').show();
                     $('.sem').hide();
                     $.ajax({url: site_url("exam_tabulation/exam_tabulation/get_section_common2/" + $('#selsyear').val()), type: "json",
                     success: function (result) {
                     $('#section_id').html(result);
                     $('#section_id > option[value=""]').remove();
                     $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                     $('#section_id').val('all');
                     }});
                     } */


                }});
        }


    });
    //$("#divdept").hide();
    //$("#divstudent").hide();



    $("#from").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#from").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#to").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#to").datepicker("option", "maxDate", selectedDate);
        }
    });
    $("#lfrom").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#lfrom").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#lto").datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        onClose: function (selectedDate) {
            $("#lto").datepicker("option", "maxDate", selectedDate);
        }
    });
    $('#selmtype').change(function ()
    {
        var id = $(this).val();
        if (id == "all")
        {
            $("#divdept").hide();
            $("#divstudent").hide();
            // $("#lfine").hide();
        }
        if (id == "specific")
        {
            $("#divdept").show();
            $("#divstudent").hide();
            //$("#lfine").hide();
        }
        if (id == "indi_stu")
        {
            $("#divstudent").show();
            $("#divdept").hide();
        }

    });
//=========================================================================================================
    $('#courselist').change(function () {

        var cid = $(this).val();
        var did = $('#seldept').val();
        $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_branch_bycourse/" + this.value + "/" + $("select[name='seldept']").val()),
            success: function (result) {
                $('#branchlist').html(result);
            }});
        $.ajax({
            url: site_url('sem_date_open_close/semester_date_open_close/get_branch_by_course_dept'),
            type: "POST",
            data: {"dept_id": did, "course_id": cid},
            success: function (data)
            {
                //alert(data);
                var json = $.parseJSON(data);
                // alert(json.sem_list[0].duration);

                var tsess = $('#selsession').val();
                if (tsess == 'Monsoon') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    options += ("<option value='' >Select Semester</option>");
                    for (var i = 1; i <= (json.sem_list[0].duration) * 2; i = i + 2) {
                        options += '<option value="' + i + '">' + i + '</option>';
                    }
                    $("select#semlist").html(options);
                }
                if (tsess == 'Winter') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    options += ("<option value='' >Select Semester</option>");
                    for (var i = 2; i <= (json.sem_list[0].duration) * 2; i = i + 2) {
                        options += '<option value="' + i + '">' + i + '</option>';
                    }
                    $("select#semlist").html(options);
                }
                if (tsess == 'Summer') {
                    $('#semlist').html('');
                    // $('#semlist').append("<option value='none' selected='selected'>Select Semester</option>");
                    var options = '';
                    options += ("<option value='' >Select Semester</option>");
                    for (var i = 1; i <= (json.sem_list[0].duration) * 2; i = i + 1) {
                        options += '<option value="' + i + '">' + i + '</option>';
                    }
                    $("select#semlist").html(options);
                }
            }
        });
    });

    $('#seletype').change(function () {

        if ($(this).val() == 'Defaulter') {
            $('.fine_optional').css({'display': 'none'});
            $('.fine_optional_field').css({'display': 'none'});
            $(".fine_optional_field").val('');
        }
        else {
            $('.fine_optional').css({'display': 'block'});
            $('.fine_optional_field').css({'display': 'block'});
        }
    });
//=======================================================================================================

    /* $('#selsession').change(function () {
     if ($('#seldept').val() != "none") {
     $("#seldept").val($("#seldept option:first").val());
     $("#courselist").val($("#courselist option:first").val());
     $("#branchlist").val($("#branchlist option:first").val());
     $("#semlist").val($("#semlist option:first").val());
     }
     });*/

    /*$('#seldept').change(function () {
     $("#courselist").val($("#courselist option:first").val());
     $("#branchlist").val($("#branchlist option:first").val());
     $("#semlist").val($("#semlist option:first").val());
     
     });*/


    //=========================================
    var newRow = $(".addrows").clone();
    $(".datepicker").datepicker();
    $("#addButton").on("click", function () {

        newRow.clone().appendTo("#TextBoxesGroup tbody").find(".datepicker").datepicker();
    });
    $('#removeButton').click(function () {
//$('#TextBoxesGroup').remove('<tr><td>&nbsp;</td></tr>');
//$('#TextBoxesGroup tr:last').remove();
        var rowCount = $('#TextBoxesGroup >tbody >tr').length;
        if (rowCount > 1) {
            $('#TextBoxesGroup tr:last').remove();
        }
        else
        {
            alert("Sorry!! Can't remove first row!");
        }


    })
    
    function myFunction(id) {
    mywindow = window.open("<?php echo base_url() ?>index.php/sem_date_open_close/semester_date_open_close/view/"+id, "_blank", "toolbar=no, scrollbars=yes, resizable=no, top=50, left=500, width=550, height=350");
	
	}
    
    //=========================================


    /*$("#tab").tabs({
     
     activate: function(event, ui) {
     if (ui.newPanel.selector == "#all_date") {
     //       alert("all_date selected");
     
     $('#categ').val('all_date_table');
     //foo($('#categ').val(),5);
     }
     else if (ui.newPanel.selector == "#ind_date") {
     //      alert("ind_date selected");
     
     $('#categ').val('ind_date_table');
     //  foo($('#categ').val(),7);
     }
     else if (ui.newPanel.selector == "#spe_date") {
     //    alert("spe_date selected");
     
     $('#categ').val('spe_date_table');
     //  foo($('#categ').val(),10);
     }       
     
     },
     
     
     });*/

    $('.date_o_c_cat').each(function () {
        id = $(this).attr('id');
        //  console.log('id'+id);
        if ($.fn.DataTable.isDataTable('#' + id)) {
            $('#' + id).DataTable().clear().destroy();
        }


// iterate every row  of table  and  do color validation for  row   based on element's(td) value of corresponding  row before  drwing  data table 
        $('#' + id + ' tbody tr').each(function () {

            var nTds = $('td', this);
            var cl_time = Date.parse($(nTds[4]).text()).getTime() / 1000;
            var st_time = Date.parse($(nTds[3]).text()).getTime() / 1000;
            var curr_time = Date.parse('today').getTime() / 1000;
      //      console.log(st_time + '-' + curr_time + '-' + cl_time);
            if (cl_time >= curr_time && curr_time >= st_time) {
                $(this).removeClass().addClass("success");
                $('td:last', this).append('<i style="color:green">[Activated]</i>');
            }
            else if (cl_time > curr_time && curr_time < st_time) {
                $(this).removeClass().addClass("info");
                $('td:last', this).append('<i style="color:blue">[Yet not Active]</i>');
            }
            else {
                $(this).removeClass().addClass("danger");
                $('td:last', this).append('<i style="color:red">[Expired]</i>');
            }


        });
        // end 

        // Assign & declare  html table  as  data table 

        var table = $('#' + id).DataTable(
                {"bPaginate": true,
                    "bLengthChange": true,
                    "bFilter": true,
                    "bSort": false,
                    "bInfo": true,
                    "bAutoWidth": true,
                    "bStateSave": true
                }
        );
        // end 

        if (id == 'all_date_table')
            upto = 5;
        else if (id == 'spe_date_table')
            upto = 9;
        else if (id == 'ind_date_table')
            upto = 6;
        var brr = [];
        for (var i = 0; i <= upto; i++) {
            brr.push(i);
        }


        new $.fn.dataTable.Buttons(table, {
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',
                    autoPrint: false,
                    exportOptions: {
                        "columns": brr,
                        /*modifier: {
                         page: 'current'
                         }     ,*/


                    },
                },
                {
                    extend: 'csvHtml5',
                    customize: function (csv) {
                        return $("#colspan_" + id).text() + "\n\n" + csv;
                    },
                    text: '<i class="fa fa fa-file-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to CSV"></i>',
                    autoPrint: false,
                    exportOptions: {
                        "columns": brr,
                        /*modifier: {
                         page: 'current'
                         }     ,*/

                    },
                },
                {
                    extend: 'pdf',
                    message: $("#colspan_" + id).text(),
                    text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',
                    autoPrint: false,
                    exportOptions: {
                        "columns": brr,
                        /*modifier: {
                         page: 'current'
                         }     ,*/

                    },
                },
                {
                    extend: 'print',
                    message: $("#colspan_" + id).text(),
                    text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',
                    autoPrint: false,
                    exportOptions: {
                        "columns": brr,
                        /*modifier: {
                         page: 'current'
                         }     ,*/

                    },
                },
                {
                    extend: 'copy',
                    text: '<i class="fa fa-files-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="copy to clipboard"></i>',
                    autoPrint: false,
                    exportOptions: {
                        "columns": brr,
                        /*modifier: {
                         page: 'current'
                         }     ,*/

                    },
                },
            ],
        });
        table.buttons().container()
                .appendTo($('.col-sm-6:eq(0)', table.table().container()));
    });
    // insert your code here 
    $.validator.addMethod("date_check", function (value, element) {
        return (Date.parse($('#from').val()) > Date.parse($('#to').val()) ? false : true);
    }, 'Closing Date must be after the opening Date.');

   $.validator.addMethod("pattern_matching", function(value, element) {
                return this.optional(element) || /^[-+\w\s]*$/.test(value);
            }, 'Put valid input.Only A-Z & 0-9 & comma (as separator) are allowed.');

    $("#opencloseform").validate({
        //ignoreTitle: true,
        rules: {
            spe_reason: {
                required: function (element) {
                    return ($('#selmtype').val() == 'specific' ? true : false);
                },
                minlength: function (element) {
                    return ($('#selmtype').val() == 'specific' ? 10 : 0);
                },
                      
                maxlength: function (element) {
                    return ($('#selmtype').val() == 'specific' ? 200 : 0);
                },
                //  pattern_matching:true,
                date_check: false,
            },
            reasonstu: {
                required: function (element) {
                    return ($('#selmtype').val() == 'indi_stu' ? true : false);
                },
                minlength: function (element) {
                    return ($('#selmtype').val() == 'indi_stu' ? 10 : 0);
                },
                   maxlength: function (element) {
                    return ($('#selmtype').val() == 'indi_stu' ? 200 : 0);
                },
                //  pattern_matching:true,
                date_check: false,
            },
            courselist: {
                required: function (element) {
                    return ($('#selmtype').val() == 'specific' && $('#seldept').val() != 'comm' ? true : false);
                },
                date_check: false,
            },
            "branchlist": {
                "required": function (element) {
                    return ($('#selmtype').val() == 'specific' && $('#seldept').val() != 'comm' ? true : false);
                },
                date_check: false,
            },
            "semlist[]": {
                "required": function (element) {
                    return ($('#selmtype').val() == 'specific' && $('#seldept').val() != 'comm' ? true : false);
                },
                date_check: false,
            },
            "seclist[]": {
                "required": function (element) {
                    return ($('#selmtype').val() == 'specific' && $('#seldept').val() == 'comm' ? true : false);
                },
                date_check: false,
            },
            selsyear: {
                required: true,
                date_check: false,
            },
            selsession: {
                required: true,
                date_check: false,
            },
            selmtype: {
                required: true,
                date_check: false,
            },
            seldept: {
                required: true,
                date_check: false,
            },
            from: {
                required: true,
            },
            to: {
                required: true,
                date_check: true,
            },
             admn_no: {
                required : function (element) {
                    return ($('#selmtype').val() == 'indi_stu' && $('#seldept').val() == '' ? true : false);
                },                
                minlength: 8,
                maxlength :10,
                pattern_matching: true,
                 date_check: false,
            },
            
        },
         messages: {
         admn_no: {         
         minlength: 'Admission No required of lenght of 8 characters only',
         maxlength: 'Admission No required of lenght of 10 characters only',
         pattern_matching: 'Put valid input.Only A-Z & 0-9  are allowed',                  
         },
         spe_reason:{
         minlength: 'Reason lenght of minimum 10 characters only',
         maxlength: 'Reason lenght of maximum 200 characters only',
         },
         reasonstu:{
         minlength: 'Reason lenght of minimum 10 characters only',
         maxlength: 'Reason lenght of maximum 200 characters only',
         },
         
     } ,
        /*stooltip_options: {
         admn_no: {placement: 'top'},
         session_year: {placement: 'top'},
         session: {placement: 'top'},
         exm_type: {placement: 'top'},
         dept: {placement: 'top'},          
         course1: {placement: 'top'} ,         
         branch1: {placement: 'top'},          
         semester: {placement: 'top'}          
         },
         messages: {
         admn_no: {
         required: 'Put comma separated Admission No.(s) here without any space',
         minlength: 'Atleast 1 Admission No required of lenght of 10 characters only',
         //pattern_matching: 'Put valid input.Only A-Z & 0-9 & comma (as separator) are allowed',
         
         
         },
         session_year: {
         required: 'Please choose session year',
         },
         'session': {
         required: 'Please choose session',
         },
         exm_type: {
         required: 'Please choose Type of Exam',
         },
         dept: {
         required: 'Please choose Department',
         },
         course1: {required: 'Please choose Course'} ,         
         branch1: {required: 'Please choose Branch'}  ,        
         semester: {required: 'Please choose Semester'}         
         },*/


        submitHandler: function (form) {
            //$(form).ajaxSubmit();
            //  alert('sem'+$('.semlist').val());
            // alert('sec'+$('.seclist').val());

            if (($('.semlist').val() == 'none' || $('.semlist').val() == null) && $('.seclist').val() != null) {
                //   alert('test');
                $("input[name='ifseclist_hidden']").val($('.seclist').val());
                $("input[name='sec_or_sem']").val('sec');
                //alert($("input[name='ifseclist_hidden']").val()); 
            } else {
                $("input[name='sec_or_sem']").val('sem');
                $("input[name='ifseclist_hidden']").val('');
                //alert($('.semlist').val());  alert('test1');
            }

            //form.submit();
            $(form).ajaxSubmit();

        }
    });







});

    function myFunction(id,seletype) {
    mywindow = window.open(base_url_custom()+"/index.php/sem_date_open_close/semester_date_open_close/view/"+id+"/"+seletype, "_blank", "toolbar=no, scrollbars=yes, resizable=no, top=50, left=500, width=550, height=350");
	
	}
        
   function base_url_custom() {
    var pathparts = location.pathname.split('/');     
    if (location.host == 'localhost') {
        var url = location.origin+'/'+pathparts[1].trim('/')+'/'+pathparts[2].trim('/')+'/'; // http://localhost/gitprog/mis
    }else{
        var url = location.origin; 
    }
    return url;
    } 