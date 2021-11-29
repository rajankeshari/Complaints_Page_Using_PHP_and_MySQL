/* result declaration process
 * Copyright (c) ISM dhanbad * 
 * @category   
 * @package    exam_tabulation
 * @copyright  Copyright (c) 2014 - 2015 Ism dhanbad
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    ##0.1##, #26/11/15#
 * @Author     Ritu raj<rituraj00@rediffmail.com>
 */

// Module to save  declaraed result though ajax only
 function dsc_Action_By_ExamSec(id, session_user_id, action,session,session_yr,course,branch,dept,semester,exam_type,defaulter_type){  
    if (confirm("Are you Sure?")){                         
        //ajax call                   
     if (action == "reject") {
            $('#desc_remark_tab' + id).show();
        }
        else {
            $('#dsc_remark' + id).val("");
            $('#desc_remark_tab' + id).hide();
        }
        $("#DLform").removeData('validator'); // removing  All  form's rule   
        $("#DLform").validate();
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
       if ($("#fyears").valid()) {      
       var postdata = { stud_reg_no: id, logged_user: session_user_id, action: action, remark: $("#dsc_remark" + id).val(), session: session,session_yr:session_yr,course:course, branch:branch, semester:semester, exam_type:exam_type,defaulter_type:defaulter_type};
                    }                    
              $.ajax({
                        url: site_url('result_declaration/result_declaration_drside/save_data'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,                       
                        success: function (jsonObj) {
                            //  var jsonObj = $.parseJSON(data);                                         
                             $('#viewreport').modal('hide');                             
                             $("#msg").show();                                   
                              $("#msg").html("");                                                                                
                            if (jsonObj.result === "Successfully") {                                                                                                                                                                                     
                                $("#msg").removeClass().addClass("alert alert-success");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for candidates </strong>for <b>Session_yr:</b>" + $('#hsyear').val() + ",<b>Session:</b>" + $('#hsess').val() + ",<b>Exam type:</b>" +  $('#hetype').val() + ",<b>Department:</b>" + $('#dptname').val() + " " + (($('#hsec_name').val()) ? ",<b>Section:</b>" + $('#hsec_name').val() + "" : "") + ",<b>Course:</b>" + $('#crs_name').val() + ",<b>Branch:</b>" +  $('#brn_name').val() + ",<b>Semseter:</b>" +  $('#hsem').val()  + "" );                                                                
                             } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for candidates </strong>for <b>Session_yr:</b>" + $('#hsyear').val() + ",<b>Session:</b>" + $('#hsess').val() + ",<b>Exam type:</b>" +  $('#hetype').val() + ",<b>Department:</b>" + $('#dptname').val() + " " + (($('#hsec_name').val()) ? ",<b>Section:</b>" + $('#hsec_name').val() + "" : "") + ",<b>Course:</b>" + $('#crs_name').val() + ",<b>Branch:</b>" +  $('#brn_name').val() + ",<b>Semseter:</b>" +  $('#hsem').val() + ",<p>"+ jsonObj.error +"</p>");
                            }                                                                                              
                                d = new Date();
                                var timestamp = d.timestamp();                                   
                                $("#msg").append(" on ");
                                $("#msg").append(timestamp); 
                                $("#msg").append(" By DR-Exam ");                                
                                setTimeout(function (){
                                if (jsonObj.result === "Successfully"){  
                                  window.location.href = site_url('result_declaration/result_declaration_drside/show_details/'+$('#hsyear').val()+'/'+$('#hsess').val()+'/'+$('#hetype').val()+'/'+$('#hdeptid').val()+'/F/'+$('#hsec_name').val());                                                       
                                 }                             
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
      }else
      return false;
   
  }
  
 