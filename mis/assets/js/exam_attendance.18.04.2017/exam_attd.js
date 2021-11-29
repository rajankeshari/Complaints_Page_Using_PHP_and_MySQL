


/* Attendance sheet generation for exam (All js function required for the sub-module)
 * Copyright (c) ISM dhanbad * 
 * @category   PHPExcel
 * @package    exam_attendance
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #6/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
*/

$(document).ready(function(){
     if($('#exm_type').val()=='regular'){
                        if (!$('#dept option').filter(function () {return $(this).val() == 'comm';}).length)                            
                             $('<option/>').val('comm').html('Common').appendTo("#dept");                        
                  }else{
                          $("#dept option[value='comm']").remove();    
                     }
              
	  $('#branch1').val('');
				 $('#course1').val('');
				 $('#semester').val('');

     $('#sec').hide();
	$.ajax({
		url : site_url("attendance/attendance_ajax/get_session_year_exam"),
		success:function(result){
			$('.gS').html(result);
		}
	});
	/*$('#session_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year),
				success : function (result) {
					$('#subject_attendance').html(result);
				}});
	});
        $('#session_attendance_honour').on('change', function() {
		var session_year = $('#session_year_attendance_h').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year+"/honour"),
				success : function (result) {
					$('#subject_attendance_h').html(result);
				}});
	});
        $('#session_attendance_minor').on('change', function() {
		var session_year = $('#session_year_attendance_m').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject/"+this.value+"/"+session_year+"/minor"),
				success : function (result) {
					$('#subject_attendance_m').html(result);
				}});
	});
	$('#subject_attendance').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance').html(result);
		}});

	});
        
        $('#subject_attendance_h').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance_h').html(result);
		}});

	});
        $('#subject_attendance_m').on('change', function() {
		$.ajax({url:site_url("attendance/attendance_ajax/get_branch/"+this.value),
			success : function(result) {
				$('#branch_attendance_m').html(result);
		}});

	});
        
	$('#branch_attendance').on('change', function() {
		var session_year = $('#session_year_attendance').val();
		var session = $('#session_attendance').val();
		var subject = $('#subject_attendance').val();
	
		$.ajax({url : site_url("attendance/attendance_ajax/get_course1"),
			type:"POST",
			data:{"branch":this.value,"session_year":session_year,"session":session,"subject":subject},
				success : function (result) {
					$('#course_attendance').html(result);
				}});
	});
	
	$('#session_attendance_c').on('change', function() {
		var session_year = $('#session_year_attendance_c').val();  
		$.ajax({url : site_url("attendance/attendance_ajax/get_subject_common/"+this.value+"/"+session_year),
			success : function (result) {
				$('#subject_attendance_c').html(result);
				}});
	});

	$('#subject_attendance_c').on('change',function() {
		var session_year = $('#session_year_attendance_c').val();
		var session = $('#session_attendance_c').val();
		$.ajax({url : site_url("attendance/attendance_ajax/get_section_common1/"+this.value+"/"+session+"/"+session_year),
			success : function (result) {
				$('#section_id').html(result);
			}});
	});
*/

        /*$('#exm_type').on('change',function() {
            var exists = false;          
            var exists1 = false;          
             $('#dept option').each(function(){
                  if (this.value == 'comm') {
                   exists = true;                                    
                 }
                 else if (this.value == 'all') {
                   exists1 = true;                                    
                 }
               });
               
              
                if(this.value=="regular" ){         
                    if(exists==true)
                       $("#dept option[value='comm']").remove();
                   else
                      $("#dept").append('<option value="comm">Common</option>'); 
                    if(exists1==true)
                      $("#dept option[value='all']").remove();
                  }
                  else if(this.value=="prep"){         
                    if(exists1==true)
                        $("#dept option[value='all']").remove();
                   else
                      $("#dept").append('<option value="all">All</option>');  
                  
                    if(exists==true)
                       $("#dept option[value='comm']").remove();    
                   $('#dept').val('all');
                }                
            
               else{
                      if(exists1==true)
                      $("#dept option[value='all']").remove();
                   if(exists==true)
                       $("#dept option[value='comm']").remove();
                }
               
	   
	});*/
	$('#exm_type').on('change', function () {
		if (this.value == "jrf"|| this.value == "jrf_spl"  || (this.value =='prep' ) ){
		   $('#granual_sel_tab').css({'display': 'none'});
			 $('#granual_sel').val('');
			 $('#granual_row').css({'display': 'none'});
			 $("#attd_criteria_form").validate({
                         ignore: ".ignore"
                           });

			 $('#branch1').val('');
			$('#course1').val('');
			$('#semester').val('');
		}
         else{
			 $('#granual_sel_tab').css({'display': 'block'});
		 } 

		
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
        if (this.value == "regular") {
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
	
        
        
        
	/*$('#dept').on('change',function() {
		var session_year=$('#session_year_attendance').val();
                if(this.value=="comm"){
                    $('#sec').show();
		$.ajax({url : site_url("exam_attendance/exam_attd/get_section_common2/"+session_year),type:"json",
			success : function (result) {
				$('#section_id').html(result);
				 $("#section_id").append('<option value="all">All</option>');  
			}});
                } else{
                        $('#sec').hide();
                }
	});*/
	$('#dept').on('change', function ()  {
      var session_year = $('#session_year_attendance').val();
	  
      if (this.value == "comm") {		  
	         $('#granual_sel_tab').css({'display': 'none'});
			// $('#granual_sel').val('');
			 $('#granual_row').css({'display': 'none'});
			 $('#branch1').val('');
			$('#course1').val('');
			$('#semester').val('');
            $('#sec').show();
            $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                  /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');*/
                }});
				
        } else {
			$('#granual_sel_tab').css({'display': 'block'});
            $('#sec').hide();		   
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
                        if( $("select[name='exm_type']").val()=='jrf' || $("select[name='exm_type']").val()=='jrf_spl'){
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
       $('#granual_sel').on('change', function () {
             if (this.value == "min") {                
				 $('#granual_row').css({'display': 'none'});
                 $('#branch1').val('');
				 $('#course1').val('');
				 $('#semester').val('');
			 }				 
                 else                 
			 $('#granual_row').css({'display': 'block'});
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
            var numbers =   (this.value == "b.tech"|| this.value == "be"?[3, 4, 5, 6, 7, 8] :[3, 4, 5, 6, 7, 8, 9,10])   ;
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
            var numbers = [1,2,3,4,5,6,7,8,9,10];
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
 