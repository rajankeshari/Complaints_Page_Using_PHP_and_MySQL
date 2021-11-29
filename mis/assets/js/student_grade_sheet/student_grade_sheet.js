/* Grade Sheet Bunch Printing Processing 
 * Copyright (c) ISM dhanbad * 
 * @category   phpPDF
 * @desc       Controller  for bucn grade sheet  printing 
 * @actor      exam_dr,exam_da5    
 * @package    student_grade_sheet
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #1/02/16#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */
$(document).ready(function () {
	
	/*if($('#exm_type').val()=='regular' || $('#exm_type').val()=='other'){
                        if (!$('#dept option').filter(function () {return $(this).val() == 'comm';}).length)                            
                             $('<option/>').val('comm').html('Common').appendTo("#dept");                        
                  }else{
                          $("#dept option[value='comm']").remove();    
                     }*/
	
	
    $.ajax({
        url: site_url("attendance/attendance_ajax/get_session_year_exam"),
        success: function (result) {
            $('.gS').html(result);
        }
    });
    /*$.ajax({url : site_url("exam_absent_record/report_wef/get_wef"),
     success : function (result) {
     $('#effective').html(result);
     }});*/
    $.ajax({url : site_url("exam_absent_record/report_wef/get_dept_for_drexam"),  
        success: function (result) {
            $('#dept').html(result);
        }});
    $.ajax({url: site_url("exam_absent_record/report_wef/get_course_drexam"),
        success: function (result) {
            $('#course1').html(result);
        }});
    $.ajax({url: site_url("exam_absent_record/report_wef/get_branch_drexam"),
        success: function (result) {
            $('#branch1').html(result);
        }});

    $("select[name='branch1']").on('change', function () {
        $.ajax({url: site_url("exam_absent_record/report_wef/get_wef/" + this.value + "/" + $("select[name='dept']").val() + "/" + $("select[name='course1']").val()),
            success: function (result) {
                $('#effective').html(result);
            }});
    });


    $("select[name='effective']").on('change', function () {

        $.ajax({url: site_url("exam_absent_record/report_wef/get_sub/" + this.value + "/" + $("select[name='semester']").val()),
            success: function (result) {

                $('#subject').html(result);
            }});// end of ajax
    }); //end of effective
    $("select[name='exm_type']").on('change', function () {        
        $('#dept').val("");
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
        if (this.value == "regular"|| this.value == "other") {
            /*if (exists == true)
                $("#dept option[value='comm']").remove();			
            else
                $("#dept").append('<option value="comm">Common</option>');
            if (exists1 == true)
                $("#dept option[value='all']").remove();*/
			
			if (!$('#dept option').filter(function () {return ($(this).val() == 'comm');}).length)
				$("#dept").append('<option value="comm">Common</option>');
			else
                $("#dept option[value='comm']").remove();    
                     
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

    $("select[name='dept']").on('change', function () {
        $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_course_dept_csshow/" + this.value),
            success: function (result) {
                $('#course1').html(result);
                if ($("#dept").val() != 'comm') {
                    $('#course1 > option[value="honour"]').remove();
                    $('#course1 > option[value="capsule"]').remove();
                    $('#course1 > option[value="comm"]').remove();
                    if ($('#seletype').val() != 'regular' ) {
                        $('#course1 > option[value="minor"]').remove();
                    }
                    else {
                        if (!$('#course1 option').filter(function () {
                            return $(this).val() == 'minor';
                        }.length) &&   $("#dept").val()!="")
                            $('<option/>').val('minor').html('Minor Course').appendTo("#course1");
                    }
                        if( $("select[name='exm_type']").val()=='jrf'){
                    $("select[name='course1']").empty();
                    $("select[name='branch1']").empty();              
                    if (!$('#course1 option').filter(function () {return $(this).val() == 'jrf';}).length)                            
                      $('<option/>').val('jrf').html('JRF').appendTo("#course1");
                     if (!$('#branch1 option').filter(function () {return $(this).val() == 'jrf';}).length)                            
                      $('<option/>').val('jrf').html('JRF').appendTo("#branch1");   
                    $("#semester").empty();
                    if($('.sem').show)$('.sem').hide();
                    if($('.sec').show)$('.sec').hide();
               }
             else{
                  $('#course1 > option[value="jrf"]').remove();
                  $('#branch1 > option[value="jrf"]').remove();
                  if($('.sem').hide) {$('.sem').show();$('.sec').hide();}                  
             }            
                    
                }
                else {
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
                }                 
            
                
            }});
    });
    $("select[name='course1']").on('change', function () {
        $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_branch_bycourse/" + this.value + "/" + $("select[name='dept']").val()),
            success: function (result) {
                $('#branch1').html(result);
            }});
        if (this.value == "minor") {
            if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = [5, 6, 7, 8, 9];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }else if(this.value == "b.tech" || this.value == "be" || this.value =="dualdegree" ){
            if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = (this.value == "b.tech"|| this.value == "be"?[3, 4, 5, 6, 7, 8] :[3, 4, 5, 6, 7, 8, 9,10]);
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }else if(this.value =="m.tech" || this.value =="m.sc"  ){ 
              if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester"); 
        }else if( this.value =="m.sc.tech" || this.value =="exemtech" || this.value =="execmba"){ 
              if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4,5,6];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
          }else if(this.value == "int.msc.tech" || this.value =="int.m.tech" || this.value =="int.m.sc" ){ 
              if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4,5,6,7, 8, 9,10];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
          
        }else {
            if ($('.sem').hide())
                $('.sem').show();
            $("#semester").empty();
            var numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }
        var session_year = $('#selsyear').val();
        if (this.value == "comm") {
            $('.sec').show();
            $('.sem').hide();
            $.ajax({url: site_url("exam_tabulation/exam_tabulation/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                    $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');
                }});
        } else {
            $('.sec').hide();
            $('.sem').show();
        }

    });

});





      