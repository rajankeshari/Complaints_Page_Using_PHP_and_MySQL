/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function () {

    $("input[type='radio']").on('ifChecked', function (event) {
        //    alert(event.type + $(this).val());
        var s = $(this).val();
        var e = s.split("-");
        id = e[0];
        if (e[1] == "N") {
            $("#rule1" + e[0]).css({'display': 'none'});
            $("#rule2" + e[0]).css({'display': 'block'});
            $('#YN_caption' + e[0]).text("Guide");
            $('#YN_membr_sel_caption' + e[0]).text("One member");
            $("#member" + e[0] + "option:selected").removeAttr("selected");
            $("#verror-" + e[0]).html("");
            //   $('#chperson' + e[0]).val("");

        } else if (e[1] == "Y") {
            $("#rule1" + e[0]).css({'display': 'block'});
            $('#YN_caption' + e[0]).text("Chairperson/Guide");
            $('#YN_membr_sel_caption' + e[0]).text("Two members");
            $("#rule2" + e[0]).css({'display': 'none'});
            $("#member" + e[0] + "option:selected").removeAttr("selected");
            $("#verror-" + e[0]).html("");
            $('#chperson' + e[0]).val("");

        }

    });
    //var oTable = $('#printview').DataTable();    
    

});

function preselectedRadio(id, action, last_mem) {

    r = id.split('_');
    z = "<span id='sp-" + id + "'>&nbsp&nbsp&nbsp" + $('#' + id + " :selected").text() + "&nbsp&nbsp&nbsp</span>";
    var radiobutt = $("<input type='radio'>");
    radiobutt.attr("id", 'fac_radio_' + id);
    radiobutt.attr("name", 'fac_radio_' + r[2]);
    if (action == "approve") {
        if (last_mem != "") {        
            radiobutt.attr("disabled", "disabled");           
        } else {
            radiobutt.removeAttr("disabled", "disabled");
            radiobutt.attr("onclick", 'calSub("' + r[2] + '")');
        }
    } else {
             
            if($("#entry_type").val()=="backlog") {     // case of backlog             
             radiobutt.attr("disabled", false);
         }
           else{                  
            radiobutt.attr("disabled", true);
        }
    }
    radiobutt.attr("value", $('#' + id).val());
    if (last_mem != "") {
        if (last_mem == $('#' + id).val()) {
            radiobutt.attr('checked', true);
        }
    }
    $('#sp-' + id).remove();
    $('#fac_radio_' + id).remove();
    $('#radio_list' + r[2]).css({'display': 'block'});
    $('#radio_list' + r[2]).append(radiobutt);
    $('#radio_list' + r[2]).append(z);
}

function calSub(id) { //alert(id);
    $('#forward_butt_cont' + id).show();
    $('#approve_butt' + id).show();
    $('#reject_butt_adar' +id).show();
}
var flag = 0;// global

function deptselect(id) {
    flag = flag + 1;
    r = id.split('_');
    z = "<span id='sp-" + id + "'>&nbsp&nbsp&nbsp" + $('#' + id + " :selected").text() + "&nbsp&nbsp&nbsp</span>";
    var radiobutt = $("<input type='radio'>");
    radiobutt.attr("id", 'fac_radio_' + id);
    radiobutt.attr("onclick", 'calSub("' + r[2] + '")');
    radiobutt.attr("name", 'fac_radio_' + r[2]);
    radiobutt.attr("value", $('#' + id).val());
     if($("#entry_type").val()=="backlog"){      // case of backlog
       radiobutt.attr("disabled", false);
      }else{
       radiobutt.attr("disabled", true);
     }
    $('#sp-' + id).remove();
    $('#fac_radio_' + id).remove();
    $('#radio_list' + r[2]).append(radiobutt);
    $('#radio_list' + r[2]).append(z);

    if (flag == 3) {
        $('#forward_butt_cont' + r[2]).css({'display': 'block'});
        //$('#thirdmem_' + r[2]).text({'display': 'block'});
        flag = 0;
    }

}


function check(id, dept_list, dept_name_list, last_memeber_list, memeber_same_dept_list, chperson, last_mem,remark_by_HOD, action, process) {
    if ($("#mom_collapse" + id).hasClass('collapse'))
        $("#mom_collapse" + id).removeClass("collapse in").addClass("collapse");
    if ($("#view_collapse" + id).hasClass('collapse'))
        $("#view_collapse" + id).removeClass("collapse in").addClass("collapse");
    if ($("#view" + id).hasClass("btn btn-primary disabled"))
        $("#view" + id).removeClass("btn btn-primary disabled").addClass("btn btn-primary");
    $('#YN_caption' + id).text("Chairperson/Guide");
    $('#YN_membr_sel_caption' + id).text("Two members");


    $('#example-limit' + id).multiselect({
        onChange: function (option, checked) {
            $.cookie("countclick_dsc" + id, 0);
            var selectedOptions = $('#example-limit' + id + ' option:selected');
            selectedvalue = "";
            if (checked)
            {
                getGuideListByDept(id, option.val(), option.text(), selectedvalue, action);
                if (selectedOptions.length >= 3) {
                    $(option > 'input').attr('checked', false);
                }
            }
            else {

                $("#label_" + option.val() + "_" + id).remove();
                $("#list_" + option.val() + "_" + id).remove();
                $("#fac_radio_list_" + option.val() + "_" + id).remove();
                $("#sp-list_" + option.val() + "_" + id).remove();

            }

            // Get selected options.                

            var nonSelectedOptions = $('#example-limit' + id + ' option').filter(function () {
                return !$(this).is(':selected');
            });

            if (selectedOptions.length >= 3) {
                //     $("#btnSelected" + id).css({'display': 'block'});
                // Disable all other checkboxes.                    
                var nonSelectedOptions = $('#example-limit' + id + ' option').filter(function () {
                    return !$(this).is(':selected');
                });
                var dropdown = $('#example-limit' + id).siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });

            }
            else {
                // Enable all checkboxes.                       
                var dropdown = $('#example-limit' + id).siblings('.multiselect-container');
                $('#example-limit' + id + ' option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
                //  $("#btnSelected" + id).css({'display': 'none'});
                //  $('#thirdmem_' + id).css({'display': 'none'});
                //$('#radio_list' + id).css({'display': 'none'});
                $('#button_container' + id).css({'display': 'none'});

            }
        },
        onDropdownShow: function (event) {
            var selectedOptions = $('#example-limit' + id + ' option:selected');
            if (selectedOptions.length >= 3) {
                //    $("#btnSelected" + id).css({'display': 'block'});
                // Disable all other checkboxes.                    
                var nonSelectedOptions = $('#example-limit' + id + ' option').filter(function () {
                    return !$(this).is(':selected');
                });
                var dropdown = $('#example-limit' + id).siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });

            }
            else {
                // Enable all checkboxes.                       
                var dropdown = $('#example-limit' + id).siblings('.multiselect-container');
                $('#example-limit' + id + ' option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
                //   $("#btnSelected" + id).css({'display': 'none'});
                //    $('#thirdmem_' + id).css({'display': 'none'});
                $('#radio_list' + id).css({'display': 'none'});
                $('#button_container' + id).css({'display': 'none'});

            }
        },
    });
    $('#member' + id).multiselect({
        onChange: function (option, checked) {
            // Get selected options.
            var selectedOptions = $('#member' + id + ' option:selected');
            var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
            var s = primarystatus;
            var e = s.split("-");
            if (e[1] == "Y") {
                selection_limit = 2;

            }
            else {
                selection_limit = 1;

            }
            //alert(selectedOptions.length);
            if (selectedOptions.length >= selection_limit) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#member' + id + ' option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#member' + id).siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            }
            else {
                // Enable all checkboxes.
                var dropdown = $('#member' + id).siblings('.multiselect-container');
                $('#member' + id + ' option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
        },
        onDropdownShow: function (event) {
            //alert('heello');
            var selectedOptions = $('#member' + id + ' option:selected');
            var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
            var s = primarystatus;
            var e = s.split("-");
            if (e[1] == "Y") {
                selection_limit = 2;

            }
            else {
                selection_limit = 1;

            }
            //alert(selectedOptions.length);
            if (selectedOptions.length >= selection_limit) {
                // Disable all other checkboxes.
                var nonSelectedOptions = $('#member' + id + ' option').filter(function () {
                    return !$(this).is(':selected');
                });

                var dropdown = $('#member' + id).siblings('.multiselect-container');
                nonSelectedOptions.each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', true);
                    input.parent('li').addClass('disabled');
                });
            }
            else {
                // Enable all checkboxes.
                var dropdown = $('#member' + id).siblings('.multiselect-container');
                $('#member' + id + ' option').each(function () {
                    var input = $('input[value="' + $(this).val() + '"]');
                    input.prop('disabled', false);
                    input.parent('li').addClass('disabled');
                });
            }
        }

    });


    if (action == "forward") {
       
        $("input[name=dsc_radio_" + id + "]").prop('disabled', true);
        var seldept_list = dept_list.split(",");
        var seldept_name_list = dept_name_list.split(",");
        var m_same_dept_list = memeber_same_dept_list.split(",");
        var l_m_list = last_memeber_list.split(",");
        var dptList = [seldept_list[0], seldept_list[1], seldept_list[2]];
        var dptNameList = [seldept_name_list[0], seldept_name_list[1], seldept_name_list[2]];
        var rto = "";
        var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
        var s = primarystatus;
        var e = s.split("-");
        if (e[1] == "Y") {
            rto = [m_same_dept_list[0], m_same_dept_list[1]];
            $("#chperson" + id).val("");
        }
        else if (e[1] == "N") {
            rto = [m_same_dept_list[0]];

            $("#chperson" + id).val(chperson);
            $('#chperson' + id + '').prop("disabled", true);
        }


        $("#member" + id + " option:selected").removeAttr("selected");
        $("#example-limit" + id + " option:selected").removeAttr("selected");
        _.each(rto, function (p) {
            // alert(p);
            $('#member' + id + '  > option[value=' + p + ']').prop('selected', true);
            $('#member' + id + '  > option[value=' + p + ']').attr('selected', 'selected');
            $('#member' + id + '  > option[value=' + p + ']').prop('disabled', true);
        });
        $('#member' + id).multiselect('refresh');

        var i = 0;

        _.each(dptList, function (q) {
            //   alert( l_m_list[i]);
            $('#example-limit' + id + '  > option[value=' + q + ']').prop('selected', true);
            $('#example-limit' + id + '  > option[value=' + q + ']').attr('selected', 'selected');
            $('#example-limit' + id + '  > option[value=' + q + ']').prop('disabled', true);

            getGuideListByDept(id, q, dptNameList[i], l_m_list[i], action);

            i++;
        });

        $('#example-limit' + id).multiselect('refresh');

        _.each(dptList, function (j) {
            preselectedRadio("list_" + j + "_" + id, action, last_mem);
        });
                            
        //$('#desc_remark_tab' + id).css({'display': 'none'});        
        if (process == 'before'){
            $('#forward_butt_cont' + id).css({'display': 'block'});
            $('#dsc_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-forward'></i>&nbsp;DSC Forwarding");            
            if(remark_by_HOD!=""){         
              $('#desc_remark_tab' + id).css({'display': 'block'});             
               
              if($('#dsc_remark' + id).val("")){
                $('#dsc_remark' + id).val(remark_by_HOD);   
              }
            }else{
                 if(!($('#dsc_remark' + id).val(""))){
                   $('#desc_remark_tab' + id).css({'display': 'block'});                     
                 }
              }          
         }
        else if (process == 'after') {
            $('#forward_butt_cont' + id).css({'display': 'none'});
            $('#dsc_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-eye'>Forwarded DSC");
        }

        $("#member" + id).multiselect('disable');
        $("#example-limit" + id).multiselect('disable');
    }

    else if (action == "approve") { 
         if ($("#dsc_collapse" + id).hasClass('in')){
                 $("#dsc_collapse" + id).removeClass("in");
             }else{
          
        $("#dsc_collapse" + id).addClass("in");
             } 

        $('#mom_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-thumbs-o-up'></i>&nbsp DSC Approval");
        //$("input[type=radio]").attr("disabled", "disabled");
        $("input[name=dsc_radio_" + id + "]").prop('disabled', true);
        var seldept_list = dept_list.split(",");
        var seldept_name_list = dept_name_list.split(",");
        var m_same_dept_list = memeber_same_dept_list.split(",");
        var l_m_list = last_memeber_list.split(",");
        var dptList = [seldept_list[0], seldept_list[1], seldept_list[2]];
        var dptNameList = [seldept_name_list[0], seldept_name_list[1], seldept_name_list[2]];
        var rto = "";
        var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
        var s = primarystatus;
        var e = s.split("-");
        if (e[1] == "Y") {
           
            rto = [m_same_dept_list[0], m_same_dept_list[1]];
            $("#chperson" + id).val("");
        }
        else if (e[1] == "N") {
            rto = [m_same_dept_list[0]];
            $("#chperson" + id).val(chperson);
            $('#chperson' + id + '').prop("disabled", true);

        }


        $("#member" + id + " option:selected").removeAttr("selected");
        $("#example-limit" + id + " option:selected").removeAttr("selected");
        _.each(rto, function (p) {
            //alert(p);
            $('#member' + id + '  > option[value=' + p + ']').prop('selected', true);
            $('#member' + id + '  > option[value=' + p + ']').attr('selected', 'selected');
            $('#member' + id + '  > option[value=' + p + ']').prop('disabled', true);
        });
        $('#member' + id).multiselect('refresh');

        var i = 0;

        _.each(dptList, function (q) {
               
            $('#example-limit' + id + '  > option[value=' + q + ']').prop('selected', true);
            $('#example-limit' + id + '  > option[value=' + q + ']').attr('selected', 'selected');
            $('#example-limit' + id + '  > option[value=' + q + ']').prop('disabled', true);

            getGuideListByDept(id, q, dptNameList[i], l_m_list[i], action);
            i++;
        });

        $('#example-limit' + id).multiselect('refresh');

        if ($.cookie("checkclick" + id) == "1") {
            _.each(dptList, function (j) {
                preselectedRadio("list_" + j + "_" + id, action, last_mem);
            });
            $.cookie("checkclick" + id, Number($.cookie("checkclick" + id)) + 1);
        }
        $('#desc_remark_tab' + id).css({'display': 'none'});
        if (process == 'before') {
            $('#forward_butt_cont' + id).css({'display': 'block'});
            $("input[name=fac_radio_" + id + "]").prop('disabled', false);
        }
        else if (process == 'after') {
            $('#forward_butt_cont' + id).css({'display': 'none'});
            $("input[name=fac_radio_" + id + "]").prop('disabled', true);
        }

        $("#member" + id).multiselect('disable');
        $("#example-limit" + id).multiselect('disable');
    }


    else if (action === "modify") {
        $('#mom_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-pencil-square-o'></i>&nbsp;DSC Modification");
        var seldept_list = dept_list.split(",");
        var seldept_name_list = dept_name_list.split(",");
        var m_same_dept_list = memeber_same_dept_list.split(",");
        var l_m_list = last_memeber_list.split(",");
        var rto = "";
        var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
        var s = primarystatus;
        var e = s.split("-");
        if (e[1] == "Y") {
            rto = [m_same_dept_list[0], m_same_dept_list[1]];
            //$('input[name=dsc_radio_' + id + ']:checked').val("Y") ;
            $("#rule2" + id).hide();
            $("#rule1" + id).show();
            $("#YN_membr_sel_caption" + id).text("Two members");

            $("#chperson" + id).val("");
        }
        else if (e[1] == "N") {
            rto = [m_same_dept_list[0]];
            //$('input[name=dsc_radio_' + id + ']:checked').val("N") ;
            $("#rule2" + id).show();
            $("#rule1" + id).hide();
            $("#YN_membr_sel_caption" + id).text("One member");
            //console.log("chperson"+chperson);            
            //   $("#chperson" + id).val(chperson);
        }
        var dptList = [seldept_list[0], seldept_list[1], seldept_list[2]];
        var dptNameList = [seldept_name_list[0], seldept_name_list[1], seldept_name_list[2]];
        //    $("#member" + id + " option:selected").removeAttr("selected");    
        //  $("#example-limit" + id + " option:selected").removeAttr("selected");    

        if ($.cookie("checkclick" + id) == "1") {
            $("#chperson" + id).val(chperson);
            if (rto[0] != "") {
                _.each(rto, function (p) {

                    $('#member' + id + '  > option[value=' + p + ']').removeAttr('disabled');
                    $('#member' + id + '  > option[value=' + p + ']').prop('selected', true);
                    $('#member' + id + '  > option[value=' + p + ']').attr('selected', 'selected');

                });
                $('#member' + id).multiselect('refresh');
                $("#thirdmemList_" + id).show();
            }
            if (dptList[0] != "") {

                var i = 0;
                _.each(dptList, function (q) {

                    $('#example-limit' + id + '  > option[value=' + q + ']').removeAttr('disabled');
                    $('#example-limit' + id + '  > option[value=' + q + ']').prop('selected', true);
                    $('#example-limit' + id + '  > option[value=' + q + ']').attr('selected', 'selected');

                    getGuideListByDept(id, q, dptNameList[i], l_m_list[i], action);
                    ++i;

                });
                $('#example-limit' + id).multiselect('refresh');
            }
            $.cookie("checkclick" + id, Number($.cookie("checkclick" + id)) + 1);
        }

         if (dptList[0] != "") {          
        //     alert(last_mem);
         _.each(dptList, function (j) {
         preselectedRadio("list_" + j+ "_" + id, action,last_mem);          
         });
         }

        $('#desc_remark_tab' + id).show();
        $('#forward_butt_cont' + id).show();
        $('#reforward_butt' + id).show();
        $('#forward_butt' + id).hide();
        //$("#fac_radio_" + id).attr("disabled", true);
        // $("input[name=fac_radio_" + id + "]").prop('disabled', false);
    }
    else if (action === "save") {
       
        $('#mom_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-plus-square'></i>&nbsp;DSC Preparation");

    }

}

function Mtg_Final_Cancel(id) {

    if ($("#mom_collapse" + id).hasClass('collapse'))
        $("#mom_collapse" + id).removeClass("collapse in").addClass("collapse");
    $("#upload_button" + id).show();
    $("#upload_section" + id).show();
    $("#sch_button_grp" + id).show();
    $("#smdate" + id).attr("disabled", false);
    $("input[name='mtg_radio_" + id + "']").iCheck('uncheck');

}

function mom(id,entry_type) {
    // on click of schedule meeting  link
    //alert($("input[name='mtg_radio_"+id+"']:checked").val());
    $('.sdate').datepicker({startDate: new Date()});
    if ($("#dsc_collapse" + id).hasClass('collapse'))
        $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");

    if ($("#view_collapse" + id).hasClass('collapse'))
        $("#view_collapse" + id).removeClass("collapse in").addClass("collapse");
    if ($("#view" + id).hasClass("btn btn-primary disabled"))
        $("#view" + id).removeClass("btn btn-primary disabled").addClass("btn btn-primary");
    if ($("input[name='mtg_radio_" + id + "']:checked").val() == "F") {
        $("#remark_eligible" + id).html("");
        $("#remark_eligible_tab" + id).show();
        $('#eligible' + id).show();
        $('#mtg_finalcancel' + id).show();
        $("#upload_button" + id).show();
        $("#upload_section" + id).show();
        $("#sch_button_grp" + id).show();
        $("#smdate" + id).attr("disabled", false);

    }
    else if ($("input[name='mtg_radio_" + id + "']:checked").val() == "Y") {
        $("#remark_eligible_tab" + id).hide();
        $('#eligible' + id).hide();
        $('#mtg_finalcancel' + id).hide();
        $("#upload_button" + id).hide();
        $("#upload_section" + id).hide();
        $("#sch_button_grp" + id).hide();
        $("#smdate" + id).attr("disabled", true);
        $("#thesis_title" + id).attr("disabled", true);
        $("#admissType"+id).attr('disabled',true);
        $("#qfd"+id).attr('disabled',true);
        $("#meeting_status" + id).hide();
        $("#subject_colm" +id).hide();
    }
    $("input[name='mtg_radio_" + id + "']").on('ifChecked', function (event) {
        if ($(this).val() == "Y") {
            $("#remark_eligible_tab" + id).hide();
        } else if ($(this).val() == "F") {
            $("#remark_eligible_tab" + id).show();
        }
        $("#upload_button" + id).hide();
        $("#upload_section" + id).hide();
        $("#sch_button_grp" + id).hide();
        $("#smdate" + id).attr("disabled", true);

    });

    // show_subjects(id,"parent",$("input[name='mtg_radio_" + id + "']:checked").val(),entry_type);   
    //end  

}
function saveMeeting(id, action, no_reschedule) {

    if (confirm("Are you Sure?")) {
        if (action == "postpone" || action == "reschedule") {
            $("#sch_mtg_remark_tab" + id).show();
        }

        $("#f_DSC_formation_form").removeData('validator') // removing  All  form's rule
        $("#f_DSC_formation_form").validate();
        $("#smdate" + id).rules('add', {
            required: true,
            messages: {
                required: 'Please Choose Schedule Date First'
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });
         
        
        
        $("#sch_mtg_remark" + id).rules('add', {
            required: function () {
                if (action == "schedule")
                {
                    return false;
                } else {
                    return true;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        if ($("#f_DSC_formation_form").valid()) {
            $.ajax({
                url: site_url('fellowship/fellowshipProcess/saveSchedule'),
                type: 'POST',
                dataType: "json",
                data: {'date': $('#mom_collapse' + id + ' .sdate').val(), 'id': id, action: action, remark: $("#sch_mtg_remark" + id).val()},
                success: function (jsonObj) {
                    if (jsonObj.result == 'Successfully') {
                          if($("#entry_type").val()=="backlog"){
                              if($("#admn_date" + id).val()==""){
                                $("#admn_date" + id).val($('#mom_collapse' + id + ' .sdate').val());
                            }
                          }
                        if (jsonObj.action == "postpone") {
                            $('#mom_upload_tab' + id).hide();
                            actionCaption = "Meeting Date has been Postponed";
                            showmsgclass = "alert alert-success";
                            showmsgicon = "check";
                            $('#mom_collapse' + id + ' .schsave').hide();
                            $('#mom_collapse' + id + ' .reschsave').show();
                            $('#mom_collapse' + id + ' .schpostpone').hide();
                            $("#mtg_status" + id).html("Meeting schedule Status:<b style='color:red'>Postponed</b>");
                            $("#smdate" + id).val("");
                            $("#mtg_badges" + id).html('<a href="#" data-toggle="modal" data-target="#mtg_history_modal" data-whatever="@twbootstrap"  ><small class="badge" data-toggle="tooltip"  title="Already ' + (Number(no_reschedule) + Number(1)) + ' times scheduled" onclick="javascript:getScheduleHistory(\'' + id + '\');">' + (Number(no_reschedule) + Number(1)) + '</small></a>');

                        }
                        else if (jsonObj.action == "reschedule") {
                            $('#mom_upload_tab' + id).show();
                            actionCaption = "Meeting Date has been Re-Scheduled";
                            showmsgclass = "alert alert-success";
                            showmsgicon = "check";
                            $('#mom_collapse' + id + ' .schsave').hide();
                            $('#mom_collapse' + id + ' .reschsave').show();
                            $('#mom_collapse' + id + ' .schpostpone').show();
                            $("#mtg_status" + id).html("Meeting schedule Status:<b style='color:green'>Re-Scheduled</b>");
                            $("#mtg_badges" + id).html('<a href="#" data-toggle="modal" data-target="#mtg_history_modal" data-whatever="@twbootstrap"  ><small class="badge" data-toggle="tooltip"  title="Already ' + (Number(no_reschedule) + Number(1)) + ' times scheduled" onclick="javascript:getScheduleHistory(\'' + id + '\');">' + (Number(no_reschedule) + Number(1)) + '</small></a>');
                        }
                        else if (jsonObj.action == "schedule") {
                            $('#mom_upload_tab' + id).show();
                            actionCaption = "Meeting Date has been Scheduled";
                            showmsgclass = "alert alert-success";
                            showmsgicon = "check";
                            $('#mom_collapse' + id + ' .schsave').hide();
                            $('#mom_collapse' + id + ' .reschsave').show();
                            $('#mom_collapse' + id + ' .schpostpone').show();
                            $("#mtg_status" + id).html("Meeting schedule Status:<b style='color:green'>Scheduled</b>");

                        }
                        $("#msg").show();
                        $("#msg").html("");
                        $("#msg").removeClass().addClass(showmsgclass);
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + showmsgicon + "'></i>\n\
                               <strong>" + actionCaption + " " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);

                    }
                    else {
                        if (jsonObj.action == "postpone") {
                            actionCaption = "Meeting Date has been Postponed";

                        }
                        else if (jsonObj.action == "reschedule") {
                            actionCaption = "Meeting Date has been Re-Scheduled";

                        }
                        else if (jsonObj.action == "schedule") {
                            actionCaption = "Meeting Date has been Scheduled";

                        }
                        $("#msg").show();
                        $("#msg").html("");
                        $("#msg").removeClass().addClass("alert alert-danger");
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i>\n\
                               <strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                    }
                }

            });

        } else {
            $("#msg").show();
            $("#msg").html("");
            $("#msg").removeClass().addClass("alert alert-danger");
            $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i>\n\
                               <strong>Scheduling of Meeting Date Failed</strong> for fellow having registration No. " + id + " .[Validation error]");
        }
        setTimeout(function () {
            $("#msg").hide();
        }, 5000);
    }
}

function saveMOM(id,entry_date) {
   // alert("entry_date:"+entry_date);
   // alert($("#smdate" + id).val());
    if (id.length > 1) {
        var data = new FormData();
        jQuery.each(jQuery('#mom_collapse' + id + ' #mom_upload')[0].files, function (i, file) {
            data.append('file-' + i, file);
        });
        data.append('id', id);
        data.append('entry_date', ((entry_date==null ||entry_date==""|| entry_date=='1970-01-01') ?$("#smdate" + id).val():entry_date));
      
        $.ajax({
            url: site_url('fellowship/fellowshipProcess/handle_upload'),
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            dataType: "json",
            success: function (jsonObj) {
                //alert(jsonObj);
                if (jsonObj.result == 'Successfully') {
                    actionCaption = "MOM uploaded";
                    showmsgclass = "alert alert-success";
                    showmsgicon = "check";
                    err = "";
                    $("#eligible_tab" + id).show();
                    $("#mom_status" + id).html("MOM(Minutes Of Meeting) Upload Status:<b style='color:green'>Uploaded</b>,&nbsp;<a href='" + base_url() + "assets/images/fellowship/mom/" + jsonObj.file_name + "' target='_blank' data-toggle='tooltip' title='view uploaded MOM' >&nbsp;<i class='fa fa-external-link'></i>&nbsp;View MOM</a>");
                } else {
                    actionCaption = "MOM uploading";
                    showmsgclass = "alert alert-danger";
                    showmsgicon = "exclamation";
                    err = "[error:" + jsonObj.error + "]";
                    //$("#mom_status"+id).show();
                    $("#mom_status" + id).html("MOM Upload Status:<b style='color:red'>Not Uploaded</b>");
                }
                $("#msg").show();
                $("#msg").html("");
                $("#msg").removeClass().addClass(showmsgclass);
                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + showmsgicon + "'></i>\n\
                                 <strong>" + actionCaption + " " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no + err);



            },
            error: function (xhr, ajaxOptions, thrownError) {
                // alert(xhr.statusText);
                // alert(thrownError);
            }
        });
        setTimeout(function () {
            $("#msg").hide();
        }, 5000);
    }


}
function dsc_put_eligibity(id, session_user_id, no_reschedule,actor) {
    // alert($("input[name='mtg_radio_"+id+"']:checked").val());
    if (confirm("Are you Sure?")) {
        $("#f_DSC_formation_form").removeData('validator'); // removing  All  form's rule   
        $("#f_DSC_formation_form").validate();
        $("#smdate" + id).rules('add', {
            required: true,
            messages: {
                required: 'Please Choose Schedule Date First'
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });
        $("#remark_eligible" + id).rules('add', {
            required: function () {

                if ($("input[name='mtg_radio_" + id + "']:checked").val() == "Y") {
                    return false;
                } else if ($("input[name='mtg_radio_" + id + "']:checked").val() == "F") {
                    return true;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });
        $("input[name='mtg_radio_" + id + "']").rules('add', {
            required: true,
            messages: {
                required: 'Please Check eleigibity First'
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });
         $("#thesis_title" + id).rules('add', {
            required: true,
            messages: {
                required: 'Please Choose title of thesis'
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        if ($("#f_DSC_formation_form").valid()) {
            //ajax call                
            var postdata = {stud_reg_no: id,actor:actor,admn_date: $("#smdate" + id).val(),remark: $("#remark_eligible" + id).val(),thesis_title:$("#thesis_title" + id).val(),qfd:$("#qfd" + id).val(),admissType:$("#admissType" + id).val(), logged_user: session_user_id, eligibleStatus: $("input[name='mtg_radio_" + id + "']:checked").val()};
            $.ajax({
                url: site_url('fellowship/fellowshipProcess/save_fellow_eligibility'),
                type: "POST",
                dataType: "json",
                data: postdata,
                success: function (jsonObj) {
                    $("#msg").html("");
                    $("#msg").show();
                    var actionCaption = "";
                    if (jsonObj.result === "Successfully") {
                        $('#eligible' + id).hide();
                        $('#mtg_finalcancel' + id).hide();
                        $("input[name='mtg_radio_" + id + "']").addClass("disable");
                        $("#remark_eligible" + id).addClass("disable");
                        $("#upload_button" + id).hide();
                        $("#upload_section" + id).hide();
                        $("#sch_button_grp" + id).hide();
                        $("#smdate" + id).addClass("disable");
                        if ($("#mom_collapse" + id).hasClass('collapse'))
                            $("#mom_collapse" + id).removeClass("collapse in").addClass("collapse");
                        if (jsonObj.elStatus == "Y") {
                            actionCaption = "Eligibility succeded ";
                            sign = "check";
                            $("#eligible_status" + id).html("Eligibility Status:<b style='color:green'>OK</b>");
                            $("input[name='mtg_radio_" + id + "']:checked").val("Y");
                            $("#fellow" + id).removeClass().addClass("success");                            
                            $("#status" + id).html("<strong>Completed</strong>");
                            $("#status" + id).removeClass().addClass('label label-success');
                            $("#meeting_status" + id).hide();
                              if($("#entry_type").val()=="backlog"){                                      
                                    $('#mom_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-clock-o'></i>&nbsp;Scheule Meeting");                                                                         
                                    $("#meeting" + id).removeClass("btn-default disabled").addClass("btn-primary");    
                              }else{
                            $("#meeting" + id).removeClass("btn-primary").addClass("btn-default disabled");                            
                             }
                            $("#after_meeting" + id).removeClass("btn-default disabled").addClass("btn-primary");
                            $("#msg").removeClass().addClass("alert alert-success");
                        }
                        else if (jsonObj.elStatus == "F") {
                            actionCaption = "Eligibility Failed ";
                            sign = "exclamation";
                            $("#eligible_status" + id).html("Eligibility Status:<b style='color:red'>Failed</b>");
                            $("input[name='mtg_radio_" + id + "']:checked").val("F");
                            $("#fellow" + id).removeClass().addClass("danger");
                            $("#status" + id).html("<strong>Initiated</strong>");
                            $("#status" + id).removeClass().addClass('label label-info');
                            $("#msg").removeClass().addClass("alert alert-danger");
                            $("#mtg_badges" + id).html('<a href="#" data-toggle="modal" data-target="#mtg_history_modal" data-whatever="@twbootstrap"  ><small class="badge" data-toggle="tooltip"  title="Already ' + (Number(no_reschedule) + Number(1)) + ' times scheduled" onclick="javascript:getScheduleHistory(\'' + id + '\');">' + (Number(no_reschedule) + Number(1)) + '</small></a>');
                        }
                          d = new Date();
                          var timestamp = d.timestamp();
                        $("#dt_label" + id).text(timestamp);
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i><strong>" + actionCaption + "</strong>for fellow having registration No." + jsonObj.stud_reg_no);
                    } else {
                        actionCaption = "Eligibility Finalisation";
                        $("#msg").removeClass().addClass("alert alert-danger");
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                    }
                    setTimeout(function () {
                        $("#msg").hide();
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                },
            })


        }
            Date.prototype.timestamp = function() {
            var yyyy = this.getFullYear().toString();
            var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = this.getDate().toString();
            var h = this.getHours().toString();
            var m = this.getMinutes().toString();
            var s = this.getSeconds().toString();
            return (dd[1]?dd:"0"+dd[0]) + "/" + (mm[1]?mm:"0"+mm[0]) + "/" + yyyy + "  " + ((h > 12) ? h-12 : h) + ":" + (m[1]?m:"0"+m[0]) + ":" + (s[1]?s:"0"+s[0]) +" "+((h > 12) ? "PM" : "AM");
        };
    } else {
        $("#upload_button" + id).show();
        $("#upload_section" + id).show();
        $("#sch_button_grp" + id).show();
        $("#smdate" + id).attr("disabled", false);
        $("input[name='mtg_radio_" + id + "']").iCheck('uncheck');


    }
}

function getScheduleHistory(id) {
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/getScheduleHistoryList'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id},
        success: function (jsonObj) {

            var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-history'></i>&nbsp;Meeting History</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                    "<thead>" +
                    "<tr>" +
                    "<th>Sl. No.</th>" +
                    "<th>Scheduled Meeting</th>" +
                    "<th>Scheduled Meeting Date</th>" +
                    "<th>Meeting Organised</th>" +
                    "<th>MOM Upload</th>" +
                    "<th>Re-Schedule Remark(before Meeting)</th>" +
                    "<th>Re-Schedule Remark(After Meeting)</th>" +
                    "<th>Thesis Title</th>" +
                    "<th>Eligibility Status</th>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";

            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                        resultHtml += "<tr>" +
                                "<td>" + Number((i + 1)) + "</td>" +
                                "<td>" + (jsonObj[i].dsc_meeting_scheduled != "" ? jsonObj[i].dsc_meeting_scheduled == "Y" ? "<b style='color:green'>" + jsonObj[i].dsc_meeting_scheduled + "</b>" : jsonObj[i].dsc_meeting_scheduled == "P" ? "<b style='color:red'>PD</b>" : "<b style='color:orange'>" + jsonObj[i].dsc_meeting_scheduled + "</b>" : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].dsc_meeting_date != null ? jsonObj[i].dsc_meeting_date : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].dsc_meeting_org != "" ? jsonObj[i].dsc_meeting_org == "Y" ? "<b style='color:green'>" + jsonObj[i].dsc_meeting_org + "</b>" : "<b style='color:orange'>" + jsonObj[i].dsc_meeting_org + "</b>" : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].dsc_mom_upload != "" ? jsonObj[i].dsc_mom_upload == "Y" ? "<b style='color:green'>" + jsonObj[i].dsc_mom_upload + "</b>" : "<b style='color:orange'>" + jsonObj[i].dsc_mom_upload + "</b>" : "N/A") + ",<b>MOM Uploaded Date:</b>" + (jsonObj[i].dsc_mom_upload_date != null ? jsonObj[i].dsc_mom_upload_date : "N/A") + "&nbsp;&nbsp;" + (jsonObj[i].dsc_mom_upload == "Y" ? "[<a href='" + base_url() + "assets/images/fellowship/mom/" + jsonObj[i].dsc_mom + "' target='_blank'  data-toggle='tooltip' title='view last uploaded MOM'><i class='fa fa-external-link'></i>View MOM</a>]" : "") + "</td>" +
                                "<td>" + (jsonObj[i].schedule_remark != "" ? jsonObj[i].schedule_remark : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].fellow_eligibility_remark != "" ? jsonObj[i].fellow_eligibility_remark : "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].thesis_title != "" ? jsonObj[i].thesis_title: "N/A") + "</td>" +
                                "<td>" + (jsonObj[i].fellow_eligibility != "" ? jsonObj[i].fellow_eligibility == "Y" ? "<b style='color:green'>" + jsonObj[i].fellow_eligibility + "</b>" : jsonObj[i].fellow_eligibility == "F" ? "<b style='color:red'>" + jsonObj[i].fellow_eligibility + "</b>" : "<b style='color:orange'>" + jsonObj[i].fellow_eligibility + "</b>" : "N/A") + "</td>" +
                                "</tr>";
                    }

                }
                resultHtml += "</table>";
                resultHtml += "</tbody>";
                resultHtml += "<div><i class='fa fa-hand-o-right'></i> &nbsp;F => Failed , PD => Postponed , P => Pending , Y => Yes , N => No , N/A => Not Applicable </div>";
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div  class='" + pclass + "'><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
            resultHtml += "</div></div>";
            $('#mtg_history_Header').text('Meeting Schedule History[#' + id + ']');
            $('#mtg_history_Content').html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });

}
function close_view_collapse(id) {

    if ($("#view_collapse" + id).hasClass('collapse'))
        $("#view_collapse" + id).removeClass("collapse in").addClass("collapse");
    $("#view" + id).removeClass("btn btn-primary disabled").addClass("btn btn-primary");
}


function view(id) {
    if ($("#mom_collapse" + id).hasClass('collapse'))
        $("#mom_collapse" + id).removeClass("collapse in").addClass("collapse");

    if ($("#dsc_collapse" + id).hasClass('collapse'))
        $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");
    $("#view" + id).removeClass("").addClass("disabled");
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/view_fgm_particular'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id},
        success: function (jsonObj) {
            txt = '';
            var resultHtml = "<div class='panel panel-info' id='dsc_mem_view'><a href='javascript:void(0)' onclick='close_view_collapse(\""+ id + "\");'  data-toggle='tooltip' title='close' class='close'  >&times;</a>" +
                    "<div class='panel-heading'><i class='fa fa-users'></i>&nbsp;DSC Memebers </div>" +
                    "<div class='table-responsive'><table class='table table-condensed'>";
            if (jsonObj.result != 'Failed') {

                if (jsonObj.length > 0) {
                    if (jsonObj[0].chpname != "") {
                        txt = "<tr> <th>Chairperson</th><td nowrap='nowrap'>" + (jsonObj[0].chpname != null ? jsonObj[0].chpname : "N/A") + "</td></tr>";
                        caption = "Guide";
                    } else {
                        caption = "Chairperson/Guide";
                    }
                    resultHtml += txt;
                    resultHtml +=
                            "<tr> <th>" + caption + "</th><td nowrap='nowrap'>" + (jsonObj[0].guide_name != null ? jsonObj[0].guide_name : "N/A") + "</td></tr>" +
                            "<tr><th>Co-Guide</th><td nowrap='nowrap'>" + (jsonObj[0].co_guide_name != null ? jsonObj[0].co_guide_name : "N/A") + "</td></tr>" +
                            "<tr><th>Members of same Deptt.</th><td nowrap='nowrap'>" + (jsonObj[0].f_m_same_dept != null ? jsonObj[0].f_m_same_dept : "N/A") + "," + (jsonObj[0].s_m_same_dept != null ? jsonObj[0].s_m_same_dept : "N/A") + "</td></tr>" +
                            "<tr><th>Last Members List(other Deptt.)</th><td nowrap='nowrap'><p>" + (jsonObj[0].f_m_other_dept != null ? jsonObj[0].f_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name1 != null ? jsonObj[0].sis_dept_name1 : "N/A") + "]</p>" +
                            "<p>" + (jsonObj[0].s_m_other_dept != null ? jsonObj[0].s_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name2 != null ? jsonObj[0].sis_dept_name2 : "N/A") + "]</p>" +
                            "<p>" + (jsonObj[0].th_m_other_dept != null ? jsonObj[0].th_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name3 != null ? jsonObj[0].sis_dept_name3 : "N/A") + "]</p>" +
                            "</td></tr>" +
                            "<tr><th>Choosen Last member</th><td>" + (jsonObj[0].last_member != "" ? jsonObj[0].last_member : "<b style='color:orange'>Not Choosen yet</b>") + "</td></tr>";
                }
            }
            else {
                if (jsonObj.error === "No Record Found") {
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<tr><td colspan='2' class='" + pclass + "'><div><a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div></td></tr>";
            }
            resultHtml += "</table></div></div>";
            //$('#ViewHeader').text('DSC Members[#' + id + ']');
            // $('#ViewContent').html(resultHtml);
            $("#view_collapse" + id).html(resultHtml);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });
}

function show_subjects(id,display_at){
  //  alert("display-"+display_at+"eligible_status-"+eligible_status+"entry_by-"+entry_by);
       $.ajax({
        url: site_url('fellowship/fellowshipProcess/show_subjects'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id},
        success: function (jsonObj) {
            var resultHtml = "";    
         
         if (jsonObj.length > 0) {                  
                       resultHtml +=
                            "<div class='panel panel-info' id='sub'><a href='#' data-target='#sub'  class='close' data-dismiss='alert'>&times;</a>" +
                            "<div class='panel-heading'><i class='fa fa-file-text-o'></i>&nbsp;Chosen Subjects</div>" +
                            "<div class='table-responsive'>" +
                            "<table class='table table-condensed'>" +
                            "<tr><th>Department</th><th>Course</th><th>Course Type</th><th>Branch</th><th>Semester</th><th>Subjects</th></tr>";
                            for (var i = 0; i < jsonObj.length; i++) {             
                            resultHtml +="<td>" + (jsonObj[i].dept != "" ? jsonObj[i].dept : "N/A") + "</td>\n\
                             <td>" + (jsonObj[i].course != "" ? jsonObj[i].course : "N/A") + "</td>\n\
                             <td>" + (jsonObj[i].type != "" ? jsonObj[i].type : "N/A") + "</td>\n\
                             <td>" + (jsonObj[i].branch != "" ? jsonObj[i].branch : "N/A") + "</td>\n\
                             <td>" + (jsonObj[i].semester!= 0 ? jsonObj[i].semester : "N/A") + "</td>\n\
                             <td>("+(jsonObj[i].subject_id!= null ? jsonObj[i].subject_id : "N/A")+ ")"+ (jsonObj[i].subject!= null ? jsonObj[i].subject : "N/A") + "</td></tr>";
                            }
                         resultHtml +="</table>" +
                       "</div></div>";                            
                }                                           
                else {
                if (jsonObj.error === "No Record Found") {                    
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {                   
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div class='" + pclass + "'><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
      
            $('#show_sub_div_'+display_at+id).html(resultHtml);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    }); 
}


function dsc_report(id, fellow_name, user_dept_name) {
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/dsc_report_view'),
        type: "POST",
        dataType: "json",
        data: {stud_reg_no: id},
        success: function (jsonObj) {
            var resultHtml = "";
            txt = '';
            if (jsonObj.result != 'Failed') {
                if (jsonObj.length > 0) {
                    if (jsonObj[0].chpname != "") {
                        txt = "<tr> <th>Chairperson:</th><td nowrap='nowrap'>" + (jsonObj[0].chpname != null ? jsonObj[0].chpname : "N/A") + "</td></tr>";
                        caption = "Guide";
                    } else {
                        caption = "Chairperson/Guide:";
                    }


                    resultHtml +=
                            "<div id='print-me'><div class='panel panel-info'>" +
                            "<div class='panel-heading'><i class='fa fa-user'></i>&nbsp;Fellow Information</div>" +
                            "<div class='table-responsive'>" +
                            "<table class='table table-condensed'>" +
                            "<tr><th>Name:</th><td nowrap='nowrap'>" + (fellow_name != null ? fellow_name : "N/A") + "</td></tr>" +
                            "<tr><th>Registration Number:</th><td nowrap='nowrap'>" + id + "</td></tr>" +
                            "<tr><th>Deparment:</th><td nowrap='nowrap'>" + (user_dept_name != null ? user_dept_name : "N/A") + "</td></tr>" +
                            "<tr><th>Thesis Title:</th><td nowrap='nowrap'>" + (jsonObj[0].thesis_title !=null || jsonObj[0].thesis_title != "" ? jsonObj[0].thesis_title : "N/A") + "</td></tr>" +
                            "</table>" +
                            "</div></div>" +
                            "<div class='panel panel-info'>" +
                            "<div class='panel-heading'><i class='fa fa-clock-o'></i>&nbsp;Process Control Status</div>" +
                            "<div class='table-responsive'>" +
                            "<table class='table table-condensed'>" +
                            "<tr><th>Guide Assigned:</th><td>" + (jsonObj[0].guide_assigned_status != "" ? jsonObj[0].guide_assigned_status == "Y" ? "<b style='color:green'>" + jsonObj[0].guide_assigned_status + "</b>" : "<b style='color:orange'>" + jsonObj[0].guide_assigned_status + "</b>" : "Pending") + ",<b>Assigned Date:</b>" + (jsonObj[0].assign_date != null ? jsonObj[0].assign_date : "N/A") + "</td></tr>" +
                            "<tr><th>DSC Prepared:</th><td>" + (jsonObj[0].dsc_formation != "" ? jsonObj[0].dsc_formation == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_formation + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_formation + "</b>" : "N/A") + ",<b>Prepared Date:</b>" + (jsonObj[0].dsc_formation_time != null ? jsonObj[0].dsc_formation_time : "N/A") + "</td></tr>" +
                            "<tr><th>DSC Forwarded To Assoc. Dean(A&R):</th><td>" + (jsonObj[0].dsc_forwarding_by_HOD != "" ? jsonObj[0].dsc_forwarding_by_HOD == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_forwarding_by_HOD + "</b>" : jsonObj[0].dsc_forwarding_by_HOD == "F" ? "<b style='color:red'>" + jsonObj[0].dsc_forwarding_by_HOD + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_forwarding_by_HOD + "</b>" : "N/A") + ",<b>Forwarded Date:</b>" + (jsonObj[0].dsc_forwarding_by_HOD_time != null ? jsonObj[0].dsc_forwarding_by_HOD_time : "N/A") + "</td></tr>" +
                            "<tr><th>DSC Approved:</th><td>" + (jsonObj[0].dsc_approval != "" ? jsonObj[0].dsc_approval == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_approval + "</b>" : jsonObj[0].dsc_approval == "F" ? "<b style='color:red'>" + jsonObj[0].dsc_approval + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_approval + "</b>" : "N/A") + ",<b>Approved Date:</b>" + (jsonObj[0].dsc_approval_date != null ? jsonObj[0].dsc_approval_date : "N/A") + "</td></tr>" +
                            "<tr><th>Meeting Scheduled:</th>\n\
                          <td>" + (jsonObj[0].dsc_meeting_scheduled != "" ? jsonObj[0].dsc_meeting_scheduled == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_meeting_scheduled + "</b>" : jsonObj[0].dsc_meeting_scheduled == "P" ? "<b style='color:red'>PD</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_meeting_scheduled + "</b>" : "N/A") + ",<b>Meeting date:</b>" + (jsonObj[0].dsc_meeting_date != null ? jsonObj[0].dsc_meeting_date : "N/A") + "\
                          </td>\n\
                         </tr>" +
                            "<tr><th>Course Chosen:</th><td>"   + (jsonObj[0].dsc_course_chosen != "" ? jsonObj[0].dsc_course_chosen == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_course_chosen + "</b>" : jsonObj[0].dsc_course_chosen == "F" ? "<b style='color:red'>" + jsonObj[0].dsc_course_chosen + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_course_chosen + "</b>" : "N/A") + ",<b>Choosen Date:</b>" + (jsonObj[0].dsc_course_chosen_date != null ? jsonObj[0].dsc_course_chosen_date : "N/A") + "</td></tr>" +
                            "<tr><th>No. Of Subjects:</th><td>" + (jsonObj[0].No_sub != null ? "<a id='show_subjects' href='javascript:void(0);' onclick='show_subjects(\""+id+"\",\"child\");'>" + jsonObj[0].No_sub+ "</a>" : jsonObj[0].error)+"</td></tr>" +
                            "<tr><th></th><td ><div id='show_sub_div_child"+id+"'></div></td>" 
                            "<tr><th>Meeting Organised:</th>\n\
                          <td>" + (jsonObj[0].dsc_meeting_org != null ? jsonObj[0].dsc_meeting_org == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_meeting_org + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_meeting_org + "</b>" : "N/A") + "\
                         </td>\n\
                      </tr>\n\
                     <tr><th>MOM Uploaded:</th>\n\
                         <td>" + (jsonObj[0].dsc_mom_upload != "" ? jsonObj[0].dsc_mom_upload == "Y" ? "<b style='color:green'>" + jsonObj[0].dsc_mom_upload + "</b>" : "<b style='color:orange'>" + jsonObj[0].dsc_mom_upload + "</b>" : "N/A") + ",<b>MOM Uploaded Date:</b>" + (jsonObj[0].dsc_mom_upload_date != null ? jsonObj[0].dsc_mom_upload_date : "N/A") + "&nbsp;&nbsp;" + (jsonObj[0].dsc_mom_upload == "Y" ? "[<a href='" + base_url() + "assets/images/fellowship/mom/" + jsonObj[0].dsc_mom + "' target='_blank' data-toggle='tooltip' title='view last uploaded MOM'><i class='fa fa-external-link'></i>View MOM</a>]" : "") + "</td>\n\
                      </tr>\n\
                     <tr><th>Fellow Eligibility:</th><td>" + (jsonObj[0].fellow_eligibility != "" ? jsonObj[0].fellow_eligibility == "Y" ? "<b style='color:green'>" + jsonObj[0].fellow_eligibility + "</b>" : jsonObj[0].fellow_eligibility == "F" ? "<b style='color:red'>" + jsonObj[0].fellow_eligibility + "</b>" : "<b style='color:orange'>" + jsonObj[0].fellow_eligibility + "</b>" : "N/A") + ",<b>Dated:</b>" + (jsonObj[0].fellow_eligibility_date != null ? jsonObj[0].fellow_eligibility_date : "N/A") + ",<b>Remark:</b>" + (jsonObj[0].fellow_eligibility_remark != "" ? jsonObj[0].fellow_eligibility_remark : "N/A") + "</th></tr>" +
                            "</table>" +
                            "</div></div>" +
                            "<div class='panel panel-info'>" +
                            "<div class='panel-heading'><i class='fa fa-users'></i>&nbsp;DSC Memebers</div>" +
                            "<div class='table-responsive'>" +
                            "<table class='table table-condensed'>";
                    resultHtml += txt;
                    resultHtml +=
                            "<tr> <th>" + caption + "</th><td nowrap='nowrap'>" + (jsonObj[0].guide_name != null ? jsonObj[0].guide_name : "N/A") + "</td></tr>" +
                            "<tr><th>Co-Guide:</th><td nowrap='nowrap'>" + (jsonObj[0].co_guide_name != null ? jsonObj[0].co_guide_name : "N/A") + "</td></tr>" +
                            "<tr><th>Members of same Deparment:</th><td nowrap='nowrap'>" + (jsonObj[0].f_m_same_dept != null ? jsonObj[0].f_m_same_dept : "N/A") + "," + (jsonObj[0].s_m_same_dept != null ? jsonObj[0].s_m_same_dept : "N/A") + "</td></tr>" +
                            "<tr><th valign='top'>Last Members List(other Department):</th><td nowrap='nowrap'><p>" + (jsonObj[0].f_m_other_dept != null ? jsonObj[0].f_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name1 != null ? jsonObj[0].sis_dept_name1 : "N/A") + "]</p>" +
                            "<p>" + (jsonObj[0].s_m_other_dept != null ? jsonObj[0].s_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name2 != null ? jsonObj[0].sis_dept_name2 : "N/A") + "]</p>" +
                            "<p>" + (jsonObj[0].th_m_other_dept != null ? jsonObj[0].th_m_other_dept : "N/A") + "[Deptt: " + (jsonObj[0].sis_dept_name3 != null ? jsonObj[0].sis_dept_name3 : "N/A") + "]</p>" +
                            "</td></tr>" +
                            "<tr><th>Choosen Last member:</th><td>" + (jsonObj[0].last_member != "" ? jsonObj[0].last_member : "<b style='color:orange'>Not Choosen yet</b>") + "</td></tr>" +
                            "</table>" +
                            "</div></div>" +
                            "<div><i class='fa fa-hand-o-right'></i> &nbsp; F => Failed , PD => Postponed , P => Pending , Y => Yes , N => No , N/A => Not Applicable </div></div>" +
                            "<div class='row'><div class='col-md-4 col-md-offset-6' ><a id='pdfprint' href='javascript:void(0);' onclick='printDiv(\"print-me\");'><i class='fa fa-print'></i>Print</a></div></div>";	
                }
                

                $('#dsc_report_Header').html('[#' + id + '] DSC Report Status : ' + (jsonObj[0].fellow_eligibility != "" ? jsonObj[0].fellow_eligibility == "Y" ? "<b style='color:green'><i class='fa fa-check'></i>Completed</b>" : jsonObj[0].fellow_eligibility == "F" ? "<b style='color:red'><i class='fa fa-exclamation'></i>Incomplete & Pending</b>" : "<b style='color:orange'><i class='fa fa-exclamation-triangle'></i>Incomplete</b>" : "N/A") + '');
            }
            else {

                if (jsonObj.error === "No Record Found") {
                    $('#dsc_report_Header').html("[#" + id + "] DSC Report Status :<b style='color:orange'></i>No Record Found</b>");
                    pclass = "alert alert-warning";
                    sign = "exclamation-triangle";
                } else {
                    $('#dsc_report_Header').html("[#" + id + "] DSC Report Status :<b style='color:red'></i>Error Found</b>");
                    pclass = "alert alert-danger";
                    sign = "exclamation";
                }
                resultHtml += "<div class='" + pclass + "'><i class='fa fa-" + sign + "'></i>" + jsonObj.error + "</div>";
            }
           
            
            $('#dsc_report_Content').html(resultHtml);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.statusText);
            alert(thrownError);
        }
    });
}



function getGuideListByDept(id, dept_id, dept_name, selectedvalue, actiontype) {
    //alert(selectedvalue);
    if ($.cookie("countclick_dsc" + id) < 3) {
        $.ajax({
            url: site_url('fellowship/fellowshipProcess/get_DeptWise_Guide_List'),
            type: "POST",
            async: true,
            dataType: "json",
            data: {dept_id: dept_id},
            success: function (jsonObj) {
                $("#radio_list" + id).css({'display': 'block'});
                $.cookie("countclick_dsc" + id, Number($.cookie("countclick_dsc" + id)) + 1);
                var sel = $("<select>");
                sel.attr("id", 'list_' + dept_id + '_' + id);
                sel.attr("name", 'list_' + dept_id + '_' + id);
                sel.attr("class", id);
                if (actiontype != "modify") {
                    sel.attr("onchange", 'deptselect("list_' + dept_id + '_' + id + '")');
                }
                sel.append("<option value=''>-select faculty-</option>");
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                        sel.append('<option value="' + jsonObj[i].id + '" >' + jsonObj[i].salutation + ' ' + jsonObj[i].first_name +' ' + jsonObj[i].middle_name + ' ' + jsonObj[i].last_name + '</option>');
                    }
                }
                if (selectedvalue != "") {
                    sel.val(selectedvalue);
                }

                $('#dept_member_list' + id).append('<span id="label_' + dept_id + '_' + id + '">' + dept_name + ':</span>');
                $('#dept_member_list' + id).append(sel);

                $('#dept_member_list' + id).append('<p>');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        });


    }
    if (actiontype === "forward" || actiontype === "approve") {
        $('#list_' + dept_id + '_' + id + '').prop("disabled", true);
    }
    else if (actiontype === "modify") {
        $('#list_' + dept_id + '_' + id + '').prop("disabled", false);
    }


}


function dsc_forward(id, admn_date,session_user_id, action,actor) {
    if (confirm("Are you Sure?")) {
        var last = "";

        $("#f_DSC_formation_form").removeData('validator'); // removing  All  form's rule   
        $("#f_DSC_formation_form").validate();

        var selected_last = $('#example-limit' + id + ' option:selected');
        selected_last.each(function () {
            $("#list_" + $(this).val() + "_" + id).rules('add', {
                required: true,
                messages: {
                    required: 'Choose members for the selected department',
                },
                invalidHandler: function (form) {

                    $("#msg").html("");
                    $("#msg").show();
                    $("#msg").addClass("alert alert-danger");
                    $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first 1.');
                    setTimeout(function () {
                        $("#msg").hide();
                    }, 5000);
                }
            });
        });


        $("#chperson" + id).rules('add', {
            required: function (element) {
                var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
                var s = primarystatus;
                var e = s.split("-");
                if (e[1] == "N") {
                    return true;
                }
                else {
                    return false;
                }
            },
            messages: {
                required: 'Please Choose Chairperson',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first chchch.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });
        $("#dsc_remark" + id).rules('add', {
            required: function (element) {
                if (action == "modify")                
                    return true;
                  else 
                    return false;
                
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first REMARK.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

             $("input[name='fac_radio_" + id + "']").rules('add', {
            required: function (element) {
                  if($("#entry_type").val()=="backlog")
                      return true;
                    else
                      return false;
                
            },
            messages: {
                required: 'Choose 1 member out of 3',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        if ($("#f_DSC_formation_form").valid()) {
            var primarystatus = $('input[name=dsc_radio_' + id + ']:checked').val();
            var s = primarystatus;
            var e = s.split("-");
            if (e[1] == "Y") {
                selection_limit = 2;
            }
            else {
                selection_limit = 1;
            }
            dept_limit = "3";
            var verrormsg = 'Select Atleast ' + selection_limit + ' Member(s)';
            var dept_v_errormsg = 'Select Atleast ' + dept_limit + ' departments';

            if ($("#member" + id).val() != null && $("#example-limit" + id).val() != null) {
                if ($("#member" + id).val().length != selection_limit) {
                    $("#verror-" + id).html(verrormsg);
                    return false;
                } else if ($("#example-limit" + id).val().length != dept_limit) {
                    $("#dept_v_error-" + id).html(dept_v_errormsg);
                    return false;
                }
                else {
                    $("#verror-" + id).html("");
                    var z = "";
                    var selected = $('#member' + id + ' option:selected');
                    selected.each(function () {
                        z += $(this).val() + ",";
                    });
                    var selectedmember = z.split(",");
                    // console.log(selectedmember[0] + "," + selectedmember[1]);
                    var last = "", x = 0, selectbox_id = "";
                    var selected_last = $('#example-limit' + id + ' option:selected');
                    selected_last.each(function () {
                        last += $(this).val() + ",";
                        //  selectbox_id += Number(++x) + ",";
                    });
                    var selectedlastmember = last.split(",");
                    //  var spltd_selectbox_id = selectbox_id.split(",");
                    //console.log(selectedlastmember[0] + "," + selectedlastmember[1] + "," + selectedlastmember[2]);
                    //ajax call
                     if($("#entry_type").val()=="backlog")
                       last_member= $("input[name='fac_radio_" + id + "']:checked").val();
                     else
                       last_member=null;
                  
                    var postdata = {stud_reg_no: id,admn_date:admn_date, guide: $("#guide_dsc_" + id).val(), co_guide: $("#co_guide_dsc_" + id).val(), chairperson: $("#chperson" + id).val(),
                        first_member_same_dept: selectedmember[0], second_member_same_dept: selectedmember[1], first_member_sis_dept: $('#list_' + selectedlastmember[0] + '_' + id).val(),
                        second_member_sis_dept: $('#list_' + selectedlastmember[1] + '_' + id).val(), third_member_sis_dept: $('#list_' + selectedlastmember[2] + '_' + id).val(),
                        first_sister_dept: selectedlastmember[0], second_sister_dept: selectedlastmember[1], third_sister_dept: selectedlastmember[2], created_by: session_user_id,
                        gdISch: $('#dsc_radio_' + id).val(), action: action,actor: actor, selectedlastmember: selectedlastmember, member_limit: $("#member" + id).val().length, remark: $("#dsc_remark" + id).val(),last_member:last_member, entry_type:$("#entry_type").val()};
                    $.ajax({
                        url: site_url('fellowship/fellowshipProcess/ajax_DSC_validate'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);            
                            $("#msg").html("");
                            $("#msg").show();
                            if (jsonObj.action === "save") {
                             var actionCaption = "DSC Saving & forwarding to HOD";   
                            }
                            else if (jsonObj.action === "modify") {
                                var actionCaption = "DSC Modification & forwarding to HOD";
                            }
                            if (jsonObj.result === "Successfully") {
                                
                                if (jsonObj.action === "save") {
                                    $("#view" + id).removeClass("btn-default disabled").addClass("btn-primary");                                    
                                    $("#edit" + id).removeClass("btn-default disabled").addClass("btn-primary"); 
                                    if($("#entry_type").val()=="backlog"){                                      
                                    $('#mom_collapse' + id).parent().find(".panel-heading").html("<i class='fa fa-clock-o'></i>&nbsp;Scheule Meeting");                                                                         
                                    $("#meeting" + id).removeClass("btn-default disabled").addClass("btn-primary");    
                                    }
                                    $("#DSC_formation" + id).removeClass("btn-primary").addClass("btn-default disabled");                                    
                                    $('#desc_remark_tab' + id).show();
                                    $('#forward_butt_cont' + id).show();
                                    $('#reforward_butt' + id).show();
                                    $('#forward_butt' + id).hide();
                                    $("input[name=fac_radio_" + id + "]").prop('disabled', true);

                                    if ($("#dsc_collapse" + id).hasClass('collapse'))
                                        $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");
                                }
                                else if (jsonObj.action === "modify") {
                                    // $("#DSC_formation"+ id).hide();
                                    $("#view" + id).show();
                                    $("#edit" + id).show();
                                    // $('#forward_butt_cont' + id).css({'display': 'none'}); 
                                    $("#DSC_formation" + id).removeClass("btn-primary").addClass("btn-default disabled");
                                    if ($("#dsc_collapse" + id).hasClass('collapse'))
                                        $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");
                                }
                                $("#dept_v_error-" + id).text("");
                                $("#verror-" + id).text("");

                                $("#fellow" + id).removeClass().addClass("warning");
                                $("#status" + id).html("<strong>Initiated</strong>");
                                /*if( action==="modify"){
                                 $("#remark_tab" + id).css({'display': 'none'});                            
                                 $.cookie("countclick"+id, '0');                    
                                 $("#status" + id).html('<strong>Re-Assigned</strong><a href="#" data-toggle="collapse" data-target="#collapse' + id + '" data-whatever="@twbootstrap"  ><small class="badge" data-toggle="tooltip"  title="Already ' + (Number(noOprN)+1) + ' times re-assigned from before"  onclick="javascript:getAssignedList(\'' +id+ '\');">' + (Number(noOprN)+1) + '</small></a>  <div  id="collapse' +id+ '" class="collapse" >  <div id="result' +id+ '"></div></div>');
                                 
                                 }else{  
                                 $("#status" + id).html("<strong>Assigned</strong>");
                                 }*/
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                            } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                            }
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            alert(xhr.statusText);
                            alert(thrownError);
                        },
                    })
                }
            } else {
                if ($("#member" + id).val() == null)
                    $("#verror-" + id).html(verrormsg);
                else if ($("#example-limit" + id).val() == null)
                    $("#dept_v_error-" + id).html(dept_v_errormsg);
                return false;
            }

        }
    }
}


function dsc_Action_By_HOD(id, session_user_id, action) {

    if (confirm("Are you Sure?")) {
        if (action == "reject") {
            $('#desc_remark_tab' + id).show();

        }
        else {
            $('#dsc_remark' + id).val("");
            $('#desc_remark_tab' + id).hide();

        }

        $("#f_DSC_formation_form").removeData('validator'); // removing  All  form's rule   
        $("#f_DSC_formation_form").validate();
        $("#dsc_remark" + id).rules('add', {
            required: function (element) {
                if (action === "reject")
                {
                    return true;
                } else {
                    return false;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        if ($("#f_DSC_formation_form").valid()) {
            //ajax call          
            var postdata = {stud_reg_no: id, logged_user: session_user_id, action: action, remark: $("#dsc_remark" + id).val()};
            $.ajax({
                url: site_url('fellowship/fellowshipProcess/ajax_DSC_forward'),
                type: "POST",
                dataType: "json",
                data: postdata,
                success: function (jsonObj) {
                    $("#msg").html("");
                    $("#msg").show();
                    $('#dsc_remark' + id).val("");
                    var actionCaption = "forwarding";
                    if (jsonObj.result === "Successfully") {
                        if (jsonObj.action === "reject") {
                             
                            actionCaption = "DSC Rejection";
                          /*  $('#reject_HOD' + id).show();
                            $('#approve_HOD' + id).show();*/
                              $('#forward_butt_cont' + id).css({'display': 'block'});
                            $("#fellow" + id).removeClass().addClass("alert alert-danger");
                            $('#after_fd_label' + id).text('Rejected');
                             $('#after_fd_label' + id).removeClass().addClass("dangerlabel label-danger");
                            $('#forward_butt_cont' + id).css({'display': 'block'});
                            $("#desc_remark_tab" + id).show();
                            $("#dsc_remark" + id).show();
                            
                        } else if (jsonObj.action === "forward") {
                            actionCaption = "DSC forwarding";
                           /* $('#reject_HOD' + id).hide();
                            $('#approve_HOD' + id).hide();*/
                            $("#fellow" + id).removeClass().addClass("alert alert-success");
                            $('#forward' + id).hide();
                            $('#afterfd' + id).show();
                            $("#afterfd" + id).removeClass("btn-default disabled").addClass("btn-primary");
                            $('#after_fd_label' + id).text('Forwarded');
                            $('#after_fd_label' + id).removeClass().addClass("label label-success");
                              $('#forward_butt_cont' + id).css({'display': 'none'});
                        }
                          d = new Date();
                          var timestamp = d.timestamp();
                          $("#fd_dt_label" + id).text(timestamp);
                      
                        if ($("#dsc_collapse" + id).hasClass('collapse'))
                            $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");
                        $("#msg").html("");
                        $("#msg").show();
                        $("#msg").removeClass().addClass("alert alert-success");
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                    } else {
                        $("#msg").removeClass().addClass("alert alert-danger");
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                    }
                    setTimeout(function () {
                        $("#msg").hide();
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                },
            })
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
}


function dsc_Action_By_ADAR(id, session_user_id, action) {
    
     if (confirm("Are you Sure?")) {
        if (action == "reject") {
            $('#desc_remark_tab' + id).show();

        }
        else {
            $('#dsc_remark' + id).val("");
            $('#desc_remark_tab' + id).hide();

        }
        $("#f_DSC_formation_form").removeData('validator'); // removing  All  form's rule   
        $("#f_DSC_formation_form").validate();
         $("#dsc_remark" + id).rules('add', {
            required: function (element) {
                if (action === "reject")
                {
                    return true;
                } else {
                    return false;
                }
            },
            minlength: 5,
            maxlength: 200,
            messages: {
                required: 'Put Remark here',
                minlength: 'remark must be min. 5 character long',
                maxlength: 'remark must be max. 200 character long',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        $("input[name='fac_radio_" + id + "']").rules('add', {
            required: true,
            messages: {
                required: 'Choose 1 member out of 3',
            },
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                setTimeout(function () {
                    $("#msg").hide();
                }, 5000);
            }
        });

        if ($("#f_DSC_formation_form").valid()) {
            //ajax call            
            var postdata = {stud_reg_no: id, logged_user: session_user_id, action: action, remark: $("#dsc_remark" + id).val(), last_member: $("input[name='fac_radio_" + id + "']:checked").val()};
            $.ajax({
                url: site_url('fellowship/fellowshipProcess/ajax_DSC_approve'),
                type: "POST",
                dataType: "json",
                data: postdata,
                success: function (jsonObj) {
                    $("#msg").html("");
                    $("#msg").show();
                    var actionCaption = "DSC Approval";
                    if (jsonObj.result === "Successfully") {
                         actionCaption = "DSC Rejection";
                        $('#forward_butt_cont' + id).css({'display': 'none'});                        
                        if ($("#dsc_collapse" + id).hasClass('collapse'))
                            $("#dsc_collapse" + id).removeClass("collapse in").addClass("collapse");
                         if (jsonObj.result === "reject") {
                         $("#fellow"+ id).removeClass().addClass("alert alert-danger");
                         $('#approve'+ id).show();
                         $('#afterapprove'+ id).show();
                         $("#afterapprove"+ id).removeClass("").addClass("btn-default disabled");
                         
                         $('#after_approve_label'+ id).text('Rejected');                         
                            $('#after_approve_label' + id).removeClass().addClass("label label-danger");
                         }
                         else if (jsonObj.result === "approve") {  
                         actionCaption = "DSC Approval";
                        $("#fellow" + id).removeClass().addClass("alert alert-success");
                        $('#approve' + id).hide();
                        $('#afterapprove' + id).show();
                        $("#afterapprove" + id).removeClass("btn-default disabled").addClass("btn-primary");
                         $('#after_approve_label'+ id).text('Approved');                         
                            $('#after_approve_label' + id).removeClass().addClass("label label-success");
                        $("input[name=fac_radio_" + id + "][val=" + jsonObj.last_member + "]").attr("checked", true);   
                        $('#forward_butt_cont' + id).css({'display': 'none'});
                         }
                             d = new Date();
                          var timestamp = d.timestamp();
                          $("#appv_dt_label" + id).text(timestamp);
                        $("#msg").removeClass().addClass("alert alert-success");
                        $("input[name=fac_radio_" + id + "]").prop('disabled', true);
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                    } else {
                        $("#msg").removeClass().addClass("alert alert-danger");
                        $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                    }
                    setTimeout(function () {
                        $("#msg").hide();
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                },
            })
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
}

 function getFellowDetails(admn_no){      
   window.location.href = site_url('fellowship/fellowshipProcess/fellow_view/'+admn_no+'');
  //window.open (site_url('fellowship/fellowshipProcess/fellow_view/'+admn_no+''),'_blank' );
 }


    
function getPdf(id){
  
 $.ajax({
     url: site_url('fellowship/fellowshipProcess/Ajax_pdf_print'),
     type: "POST",
     data: {txt : $('#dsc_report_Content').html()},
     success: function (data)
     {
         
             var blob=new Blob([data]);
            var link=document.createElement('a');
            link.href=window.URL.createObjectURL(blob);
            link.download="DSC_REPORT_"+id+".pdf";
            link.click();         
  
     }
 
     
   });

}