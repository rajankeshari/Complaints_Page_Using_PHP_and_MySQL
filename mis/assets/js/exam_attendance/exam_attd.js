


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
	
       $('#examplelimit').multiselect({
    selectAllValue: 'multiselect-all',
    enableCaseInsensitiveFiltering: true,
    enableFiltering: true,
    maxHeight: '300',
    buttonWidth: '235',
    onChange: function(element, checked) {
		 var nonSelectedOptions = $('#examplelimit option').filter(function () {
                return $(this).is(':selected');
            });
		//	console.log(nonSelectedOptions);	
			 var selected =''; var i=0;
			$(nonSelectedOptions).each(function(label, value){
				selected+= $(this).val()+    (nonSelectedOptions.length==(i+1)?'':','  )  ;
				i++;
			  });
				//console.log(selected);				
        
		 $('#examplelimit_hidden').val(selected);
		// console.log($('#example-limit_hidden').val());				
		
    }
}); 
    

	
     if($('#exm_type').val()=='regular'){
         if (!$('#dept option').filter(function () {return $(this).val() == 'comm';}).length)                            
             $('<option/>').val('comm').html('Common').appendTo("#dept");                        
          }else{
             $("#dept option[value='comm']").remove();    
          }
          if($('#granual_sel').val()==''||  $('#granual_sel').val()=='min'){     
	     $('#branch1').val('');$('#course1').val(''); $('#semester').val('');
             }
           else{
             $.ajax({url: site_url("exam_absent_record/report_wef/get_course_drexam"),
               success: function (result) {
                 $('#course1').html(result);
              }});
         $.ajax({url: site_url("exam_absent_record/report_wef/get_branch_drexam"),
        success: function (result) {
            $('#branch1').html(result);
        }});
           }  
          if($('#dept').val()!='comm')
               $('#sec').hide();	 
           else{
           $('#sec').show();	 
           $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + $('#hsyear').val()), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                  /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');*/
                }});
       }
          
           if ( $('#tab_for') == "regno") {
               $('#admn-no').show();               
               
            }else{
                $('#admn-no').hide();
                $('#admn_no').val(''); 
            }
         
	$.ajax({
		url : site_url("attendance/attendance_ajax/get_session_year_exam"),
		  data: {'sy': $('#hsyear').val()},
        success: function (result) { 
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
		if (this.value == "jrf"|| this.value == "jrf_spl" || this.value == "spl_jrf"  || (this.value =='prep' ) ){
		   $('#granual_sel_tab').css({'display': 'none'});
			 $('#granual_sel').val('');
			 $('#granual_row').css({'display': 'none'});
                         $("#attd_criteria_form").validate({
                         ignore: ".ignore"
                           });
                        $('#branch1').val('');
			$('#course1').val('');
			$('#semester').val('');
                            if($('#sec').is(':visible')){
                    $("select[name='cs2']").val('all');
             }
             else{
                $("select[name='cs1']").val('all');
            }
			
			
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
	  
      if (this.value == "comm"  ) {		  
	         $('#granual_sel_tab').css({'display': 'none'});
		$('#granual_sel').val('');
			 $('#granual_row').css({'display': 'none'});
                           $("#attd_criteria_form").validate({
                         ignore: ".ignore"
                           });
			 $("select[name='course1']").empty();
               $("select[name='branch1']").empty();      
            $('#branch1').val('');
            $('#course1').val('');
            $('#semester').val('');           
             $("select[name='cs1']").val('all');
                         
            $('#sec').show();
            $.ajax({url: site_url("result_declaration/result_declaration_drside/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                  /*  $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');*/
                }});
				
				
				
				  if($('#sec').is(':visible')){
                    $(".cs2").empty();
                $('.cs2').append('<option value="all">All</option>');
             }
             else{
                $(".cs1").empty();
                $('.cs1').append('<option value="all">All</option>');
            }
                         
                      $.ajax({url: site_url("exam_attendance/exam_attd/get_all_crs_structure/" +($("select[name='dept']").val()=='comm'?'comm':$("select[name='course1']").val()) + "/" + ($("select[name='branch1']").val()==''?null:encodeURIComponent($("select[name='branch1']").val()))+  "/" + ($("select[name='semester']").val()==''?null:$("select[name='semester']").val())+ "/" +$('#session_year_attendance').val()+"/"+ $('#session_attendance').val()+ "/" + $('#exm_type').val()   ), type: "json",
                success: function (result) {
                   var optionsArr=null;
                  // alert($('#sec').is(':visible'));
                   if($('#sec').is(':visible')){
                       // element is Visible
                       optionsArr =  $('.cs2 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                   $('.cs2').html(result);
                   $('.cs2 > option[value=""]').remove();
                   $('.cs2 > option[value="all"]').remove();
                    if (optionsArr ==null)
                   $('.cs2 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else
                   $('.cs2').append('<option value="all" selected>ALL</option>');
                   $('#cs2').val('all');
                   } 
                   else {
                       optionsArr =  $('.cs1 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                       
                       // element is Hidden
                   $('.cs1').html(result);
                   $('.cs1 > option[value=""]').remove();
                   $('.cs1 > option[value="all"]').remove();
                    if (optionsArr ==null)
                    $('.cs1 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else                
                    $('.cs1').append('<option value="all" selected>ALL</option>');
                   $('#cs1').val('all');
                   }                  
                }});
				
				
				
        } else {
            if( $('#exm_type').val()== "prep" || $('#exm_type').val()== "jrf" || $('#exm_type').val()== "jrf_spl"   ) {		  
	         $('#granual_sel_tab').css({'display': 'none'});
		         $('#granual_sel').val('');
			 $('#granual_row').css({'display': 'none'});
                           $("#attd_criteria_form").validate({
                         ignore: ".ignore"
                           });
			 $("select[name='course1']").empty();
               $("select[name='branch1']").empty();      
            $('#branch1').val('');
            $('#course1').val('');
            $('#semester').val('');           
             $("select[name='cs1']").val('all');
			 $('#sec').css({'display': 'none'});
			 // showing subjects 
		    
			
				  $.ajax({url: 
               				  site_url(   "exam_attendance/exam_attd/get_excusive_sub_json/"+ $("select[name='dept']").val()+"//////"+ $('#admn_no').val()+"///") 
							 
							  ,				  
				   type: "POST",
                   dataType: "json",
				   data:{section_name:$('#section_id').val(),exm_type:$('#exm_type').val(),session:$('#session_attendance').val(),session_year:$('#session_year_attendance').val(),dept:$("select[name='dept']").val(),count_stu_subjectwise:'1'},
                  success: function (jsonObj) {
					 // alert(jsonObj.length );
				/*	 var  resultHtml=null;
					    if(jsonObj.result!='Failed'){
                if (jsonObj.length > 0) {                 
                for (var i = 0; i < jsonObj.length; i++) {                   
                    resultHtml += "<option  value= '"+ jsonObj[i].subject_id+"#"+jsonObj[i].name+"' >"+ jsonObj[i].subject_id+"["+jsonObj[i].name+"]</option>"; 
                    
                      }
               }
           }*/
		$('#examplelimit').multiselect('refresh');			  
		//$('#example-limit').multiselect(resultHtml);
	/*	var data = [
    {label: "ACNP", value: "ACNP"},
    {label: "test", value: "test"}
];*/


  if(jsonObj.result!='Failed')
        if (jsonObj.length > 0) 
            $("#examplelimit").multiselect('dataprovider', jsonObj);
		
		
			
		 
         }});
		  
		// end 		 
			 
                    }
            else{
			$('#granual_sel_tab').css({'display': 'block'});
                         $('#granual_sel').val('');
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
        }
		   
		   
		  
		
		
	  
    });
        $('#granual_sel').on('change', function () {
        if (this.value == "min") {
            $('#granual_row').css({'display': 'none'});  
            $("select[name='course1']").empty();
               $("select[name='branch1']").empty();      
            $('#branch1').val('');
            $('#course1').val('');
            $('#semester').val('');           
             if($('#sec').is(':visible')){
                    $(".cs2").empty();
                $('.cs2').append('<option value="all">All</option>');
             }
             else{
                $(".cs1").empty();
                $('.cs1').append('<option value="all">All</option>');
            }
        }
        else{
               $('#granual_row').css({'display': 'block'});
               $("select[name='course1']").empty();
               $("select[name='branch1']").empty();          
               $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_course_dept_csshow/" +  $('#dept').val()),
               success: function (result) {
                $('#course1').html(result);
		}
              });
            if($('#sec').is(':visible')){
                    $(".cs2").empty();
                $('.cs2').append('<option value="all">All</option>');
             }
             else{
                $(".cs1").empty();
                $('.cs1').append('<option value="all">All</option>');
            }
            $.ajax({url: site_url("exam_absent_record/report_wef/get_branch_drexam"),
             success: function (result) {
            $('#branch1').html(result);
            }});
	
          }
          }); 
          
             $('#tab_for').on('change', function () {
            if (this.value == "regno") {
               $('#admn-no').show();               
               
            }else{
                $('#admn-no').hide();
                $('#admn_no').val(''); 
            }
      });
          
	$("select[name='course1']").on('change', function () {
        $.ajax({url: site_url("student_view_report/report_new_file_ajax/get_branch_bycourse/" + this.value + "/" + $("select[name='dept']").val()),
            success: function (result) {
                $('#branch1').html(result);
            }});
       if (this.value == "minor" || this.value =="honour") {
//            if ($('.sem').hide())
  //              $('.sem').show();
            $("#semester").empty();
            var numbers = [5, 6, 7, 8, 9];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }else if(this.value == "b.tech" || this.value == "be" || this.value =="dualdegree" ){
    //        if ($('.sem').hide())
    //            $('.sem').show();
            $("#semester").empty();
            var numbers =   (this.value == "b.tech"|| this.value == "be"?($('#exm_type').val()=='regular'?( ($("select[name='dept']").val()=='pe' &&this.value == "be")? [1,2,3,4, 5, 6, 7, 8]: [3, 4, 5, 6, 7, 8]): [1,2,3,4, 5, 6, 7, 8] ) : ($('#exm_type').val()=='regular'? [3, 4, 5, 6, 7, 8, 9,10] :[1,2,3, 4, 5, 6, 7, 8, 9,10])  ) ;
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }else if(this.value =="m.tech" || this.value =="m.sc"  ){ 
          //    if ($('.sem').hide())
          //      $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester"); 
        }else if( this.value =="m.sc.tech" || this.value =="exemtech" || this.value =="execmba"){ 
           //   if ($('.sem').hide())
          //      $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4,5,6];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
          }else if(this.value == "int.msc.tech" || this.value =="int.m.tech" || this.value =="int.m.sc" ){ 
             // if ($('.sem').hide())
            //    $('.sem').show();
            $("#semester").empty();
            var numbers = [1,2,3,4,5,6,7,8,9,10];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
          
        }else {
       //     if ($('.sem').hide())
        //        $('.sem').show();
            $("#semester").empty();
            var numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
            for (var i = 0; i < numbers.length; i++)
                $('<option/>').val(numbers[i]).html(numbers[i]).appendTo("#semester");
        }
        
           $('#semester').append('<option value="" selected>select</option>');
        var session_year = $('#hsyear').val();
        if (this.value == "comm") {
         //   $('.sec').show();
       //     $('.sem').hide();
            $.ajax({url: site_url("exam_tabulation/exam_tabulation/get_section_common2/" + session_year), type: "json",
                success: function (result) {
                    $('#section_id').html(result);
                    $('#section_id > option[value=""]').remove();
                    $('#section_id option').eq(0).before('<option value="all">ALL</option>');
                    $('#section_id').val('all');
                }});
            
             $.ajax({url: site_url("exam_attendance/exam_attd/get_all_crs_structure/" +($("select[name='dept']").val()=='comm'?'comm':$("select[name='course1']").val()) + "/" + ($("select[name='branch1']").val()==''?null:encodeURIComponent($("select[name='branch1']").val()))+  "/" + ($("select[name='semester']").val()==''?null:$("select[name='semester']").val())+ "/" +$('#session_year_attendance').val()+"/"+ $('#session_attendance').val() + "/" + $('#exm_type').val()  ), type: "json",
                success: function (result) {   
                    var optionsArr=null;
                  // alert($('#sec').is(':visible'));
                   if($('#sec').is(':visible')){
                       // element is Visible
                       optionsArr =  $('.cs2 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                   $('.cs2').html(result);
                   $('.cs2 > option[value=""]').remove();
                   $('.cs2 > option[value="all"]').remove();
                    if (optionsArr ==null)
                   $('.cs2 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else
                  $('.cs2').append('<option value="all" selected>ALL</option>');
                   $('#cs2').val('all');
                   } else {
                       optionsArr =  $('.cs1 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                       
                       // element is Hidden
                   $('.cs1').html(result);
                   $('.cs1 > option[value=""]').remove();
                   $('.cs1 > option[value="all"]').remove();
                    if (optionsArr ==null)
                    $('.cs1 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else                
                   $('.cs1').append('<option value="all" selected>ALL</option>');
                   $('#cs1').val('all');
                   }                  
                                  
                }});
            
        } else {
        //    $('.sec').hide();
         //   $('.sem').show();
        }

    });
	       
   $(".sec_or_sem").on('change', function () {    
      $.ajax({url: site_url("exam_attendance/exam_attd/get_all_crs_structure/" +($("select[name='dept']").val()=='comm'?'comm':$("select[name='course1']").val()) + "/" + ($("select[name='branch1']").val()==''?null:encodeURIComponent($("select[name='branch1']").val()))+  "/" + ($("select[name='semester']").val()==''?null:$("select[name='semester']").val())+ "/" +$('#session_year_attendance').val()+"/"+ $('#session_attendance').val()+ "/" + $('#exm_type').val()   ), type: "json",
                success: function (result) {   
                    var optionsArr=null;
                  // alert($('#sec').is(':visible'));
                   if($('#sec').is(':visible')){
                       // element is Visible
                       optionsArr =  $('.cs2 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                   $('.cs2').html(result);
                   $('.cs2 > option[value=""]').remove();
                   $('.cs2 > option[value="all"]').remove();
                    if (optionsArr ==null)
                   $('.cs2 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else
                   $('.cs2').append('<option value="all" selected>ALL</option>');
                   $('#cs2').val('all');
                   } else {
                       optionsArr =  $('.cs1 option')
                         .filter(function() {
                                            return !this.value || $.trim(this.value).length == 0;
                                              }).toArray();//alert(optionsArr);
                       
                       // element is Hidden
                   $('.cs1').html(result);
                   $('.cs1 > option[value=""]').remove();
                   $('.cs1 > option[value="all"]').remove();
                    if (optionsArr ==null)
                    $('.cs1 option').eq(0).before('<option value="all">ALL</option>');                                       
                   else                
                    $('.cs1').append('<option value="all" selected>ALL</option>');
                   $('#cs1').val('all');
                   }                  
                                  
                }});
	   });
           
     $(".cs1, .cs2").on('change', function () {
       	 // showing subjects 
		    
			
				  $.ajax({url: 
               				  
							   site_url(   "exam_attendance/exam_attd/get_excusive_sub_json/"+$("select[name='dept']").val()+"/"+($("select[name='dept']").val()=='comm'?'comm':$("select[name='course1']").val()) + "/" + ($("select[name='branch1']").val()==''?null:encodeURIComponent($("select[name='branch1']").val()))+  "/" + ($("select[name='semester']").val()==''|| $("select[name='semester']").val()=='none'?null:$("select[name='semester']").val()))
							                                                                                                                                                                                                                                                                                                             
							   ,				  
				   type: "POST",
                   dataType: "json",
				  data:{section_name:$('#section_id').val(),exm_type:$('#exm_type').val(),session:$('#session_attendance').val(),session_year:$('#session_year_attendance').val(),dept:$("select[name='dept']").val(),count_stu_subjectwise:'1', crs_strct:($(this).val()=='all'|| $(this).val()==''|| $(this).val()==null?'': encodeURIComponent($(this).val()))},
                  success: function (jsonObj) {
					 // alert(jsonObj.length );
				/*	 var  resultHtml=null;
					    if(jsonObj.result!='Failed'){
                if (jsonObj.length > 0) {                 
                for (var i = 0; i < jsonObj.length; i++) {                   
                    resultHtml += "<option  value= '"+ jsonObj[i].subject_id+"#"+jsonObj[i].name+"' >"+ jsonObj[i].subject_id+"["+jsonObj[i].name+"]</option>"; 
                    
                      }
               }
           }*/
		$('#examplelimit').multiselect('refresh');			  
		//$('#example-limit').multiselect(resultHtml);
	/*	var data = [
    {label: "ACNP", value: "ACNP"},
    {label: "test", value: "test"}
];*/


  if(jsonObj.result!='Failed')
        if (jsonObj.length > 0) 
            $("#examplelimit").multiselect('dataprovider', jsonObj);
		 
         }});
		  
		// end 		 
	 
     });	 
// start  custom  validation of tabulation 

   
       $.validator.addMethod("pattern_matching", function(value, element) {
                return this.optional(element) || /^[-,+\w\s]*$/.test(value);
            }, 'Put valid input.Only A-Z & 0-9 & comma (as separator) are allowed.');
            
      var  param= new Array();              
      $.validator.addMethod("admn_no_check", function(value, element) {      
      if( $("#admn_no").val()!="" && $('#session_year_attendance').val()!="" &&  $('#session_attendance').val()!="" && $('#exm_type').val()!="" && $("#dept").val()!="" ){
       var str=$("#admn_no").val();           
       var fbool=1;
       // console.log(str.indexOf(","));        
          if( str.indexOf(",")!= -1) { 
              arr= new Array();              
              arr=str.split(',');
               for(var j=0,k=0; j<arr.length;j++,k++){
               trimmed_admn_no= arr[j];
                 var bool;     
                 $.ajax({url: site_url("student_grade_sheet/dr_grade_sheet/check_student_exist/" + $("#dept").val()),
                 data: {admno:trimmed_admn_no.trim(), syear:  $('#session_year_attendance').val(), sess: $('#session_attendance').val(), et: $('#exm_type').val()},                    
                  type: "POST",
                  datatype:"json",
                  async:false,
                  success: function(r){                         
                       if (r=="false"){                            
                             bool=0;
                         }else{
                                bool=1;
                         }                             
                       }                
                });
                if(!Boolean(bool))param[k]=trimmed_admn_no.trim();
                 fbool= Boolean(fbool) && Boolean(bool);
                 
                 //if(!Boolean(bool))break;     
               } // end of loop
              //return Boolean(bool); 
                return Boolean(fbool);
            } // end of check whether single/multiple admission no
            else{
                      var bool;     
                     $.ajax({url: site_url("student_grade_sheet/dr_grade_sheet/check_student_exist/" + $("#dept").val()),
                         data: {admno:value, syear:  $('#session_year_attendance').val(), 
                                sess: $('#session_attendance').val(), et: $('#exm_type').val()},                    
                      type: "POST",
                      datatype:"json",
                      async:false,
                     success: function(r){                         
                       if (r=="false"){                            
                             bool=0;
                         }else{
                                bool=1;
                         }                             
                       }
                });                  
                //console.log(Boolean(bool));
                    return Boolean(bool);
            }
          }else{
              return false;
          }  
            }, function(p,element){
               if( $("#admn_no").val()!="" && $('#session_year_attendance').val()!="" &&  $('#session_attendance').val()!="" && $('#exm_type').val()!="" && $("#dept").val()!="" ){
                              if($(element).val().indexOf(",")!=-1){
                                    arr= new Array();
                                    var txt='';
                                     arr=$(element).val().split(',');
                                      console.log(" ARRAY ->"+arr.toString()); 
                                        for(var j=0; j<arr.length;j++){
                                         trimmed_admn_no= arr[j];
                                         console.log("COMPARED->"+trimmed_admn_no);
                                         if($.inArray( trimmed_admn_no.trim(), param )){
                                          txt+= trimmed_admn_no.trim()+",";                                                         
                                      }
                                       }
                                       txt+= 'do not exist';
                                       //console.log("param ARRAY ->"+param.toString()); 
                                  
                                }else{
                                    txt= "Admission No.  "+$("#admn_no").val()+" does not exist";                                                         
                                }  
                                return txt ;
                            }else{
                                  return "session/session yr/exam_type/dept missing";   
                             }
                      
            });      
// end

      
// start of tabulation  Form  validation
  //var error_txt=null;   
    $("#attd_criteria_form").validate({        
         ignoreTitle: true,
        rules: {
            admn_no: {
                required: function (element) {                  
                    return ($('#tab_for').val() === "regno" ? true : false);
                },
                minlength: function (element) {
                    return ($('#tab_for').val() === "regno" ? 10 : 0);
                },
                pattern_matching:true,
                admn_no_check:  false                 
            },
			  course1: {
                required: function (element) {                  
                    return ($('#granual_sel').val() === "max" ? true : false);
                },                
                                
            },
			 branch1: {
                required: function (element) {                  
                    return ($('#granual_sel').val() === "max" ? true : false);
                },                
                                
            },
			semester: {
                required: function (element) {                  
                    return ($('#granual_sel').val() === "max" ? true : false);
                },                
                                
            },
            session_year: {
                required: true
            },
            'session': {
                required: true
            },
            exm_type: {
                required: true
            },
            dept: {
                required: true
            }
        },
        tooltip_options: {
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
        },
       /*  submitHandler: function(form) {
              $(form).ajaxSubmit();
         }      */
    });
	
		
	
       

});
 