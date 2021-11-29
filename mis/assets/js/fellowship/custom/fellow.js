/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function(){
  $("#fw_id").change(function(){
            if ($(this).val()!='') { 
                
                window.location.href=$(this).val();
                
            } 
        });              
          
   }); 
function confirmation(confirm_for,id, regNo, session_user_id,noOprN,actor) {   
    //bootbox.confirm("Are you sure?", function(result) {Example.show("Confirm result: "+result);}); 
    if (confirm("Are you Sure?")) {        
        $("#f_g_mapping_form").removeData('validator') // removing  All  form's rule
        $("#f_g_mapping_form").validate();
        $("#guide" + id).rules('add', {
            required: true,
            messages: {
                required: 'Choose Guide First'
            },            
            
            invalidHandler: function (form) {
                $("#msg").html("");
                $("#msg").show();
                $("#msg").addClass("alert alert-danger");
                $("#msg").html(' <a href="#" class="close" data-dismiss="alert">&times;</a><i class="fa fa-exclamation"></i><strong>Action Failed.</strong> Required column(s) are missing.Need to fill first.');
                 setTimeout(function(){ $("#msg").hide(); }, 5000);
            }
        });
         $("#remark" + id).rules('add', {
            required: function(element) {
                if( confirm_for==="modify")
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
                 setTimeout(function(){ $("#msg").hide(); }, 5000);
            }
        });
        
         if ($("#f_g_mapping_form").valid()) {           
            var postdata = {stud_reg_no: regNo, guide: $("#guide" + id).val(), admn_date: $("#admn_date" + id).val(),co_guide: $("#co_guide" + id).val(), target_degree: 'jrf', guide_assigned_by: session_user_id,action:confirm_for,remark:$("#remark" + id).val(),yr:$("#hidd_yr").val(),actor:actor};                                                
            $.ajax({
                url:site_url('fellowship/fellowshipProcess/ajax_fgm_validate'),
                type: "POST",
                dataType: "json",
                data: postdata,
                success: function (jsonObj) {
                    //  var jsonObj = $.parseJSON(data);            
                    $("#msg").html("");
                    $("#msg").show();
                    if(jsonObj.action==="assign"){
                        var actionCaption="Assignation";
                    }
                    else if(jsonObj.action==="modify"){
                        var actionCaption="Re-Assignation";
                    }
                    if(jsonObj.result === "Successfully") {       
                        $("#old_guide" + id).css({'display': 'none'});    
                        $("#old_co_guide" + id).css({'display': 'none'});   
                        $("#edit" + id).css({'display': 'none'});
                        $("#reset" + id).css({'display': 'block'});
                        $("#assign" + id).css({'display': 'none'});
                        $("#fellow" + id).removeClass().addClass("success");                                               
                        $("#guide" + id).attr("disabled", "disabled");
                        $("#status" + id).removeClass().addClass('label label-success');
                         if( confirm_for==="modify"){
                        $("#remark_tab" + id).css({'display': 'none'});                      
                        //$.cookie("countclick"+id, '0');                    
                        $("#log" + id).html('<a href="#" data-toggle="modal" data-target="#re_assign_modal" data-whatever="@twbootstrap"  ><small class="badge" data-toggle="tooltip"  title="Already ' + (Number(noOprN)+1) + ' times re-assigned from before"  onclick="javascript:getAssignedList(\'' +id+ '\');">' + (Number(noOprN)+1) + '</small></a>');
                        $("#status" + id).html('<strong>Re-Assigned</strong>');
                        }else{  
                         $("#status" + id).html("<strong>Assigned</strong>");
                         }
                        $("#co_guide" + id).attr("disabled", "disabled");
                          d = new Date();
                          var timestamp = d.timestamp();
                        $("#dt_label" + id).text(timestamp);
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
        }// End of if (valid form)
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
function clr(id){ 
   if (confirm("Are you Sure?")) {
    $("#old_guide" + id).css({'display': 'block'});    
    $("#old_co_guide" + id).css({'display': 'block'});    
    $("#old_guide" + id ).text("Last Saved : "+$( "#guide"+id+" option:selected" ).text());   
    $("#old_co_guide" + id ).text("Last Saved : "+$("#co_guide"+id+" option:selected" ).text());
    $("#guide" + id ).val("");
    $("#co_guide" + id ).val("");
    $("#guide" + id).attr("disabled", false);
    $("#co_guide" + id).attr("disabled", false);    
    $("#fellow" + id).removeClass().addClass("warning");               
    $("#remark_tab" + id).css({'display': 'block'});    
  //  $("#remark" + id).css({'display': 'block'});        
    $("#reset" + id).css({'display': 'none'});    
    $("#edit" + id).css({'display': 'block'});    
    $("#status" + id).html("<strong>Pending</strong>");    
  }
}
function getAssignedList(id){
   if(id!=""){            
    
             $.ajax({
                url: site_url('fellowship/fellowshipProcess/ajax_fgm_getReAssignListByStudId'),
                type: "POST",
                dataType: "json",
                data: {stud_reg_no:id,yr:$("#hidd_yr").val()},
                success: function(jsonObj) {
               var resultHtml = "<div class='panel panel-info'>" +
                    "<div class='panel-heading'><i class='fa fa-history'></i>&nbsp;Action Log</div>" +
                    "<div class='table-responsive'><table class='table table-condensed'  id ='printview'>" +
                   
                    "<tr>" +          
                    "<th>S.No.</th>" +
                    "<th>Processing Year</th>" +
                    "<th>Guide</th>" +
                    "<th>Co-Guide</th>" +
                    "<th>Assigned Date</th>" +                    
                    "<th>Remark</th>" +                    
                 
                    "</tr>";
                if(jsonObj.result!='Failed'){
                if (jsonObj.length > 0) {                 
                for (var i = 0; i < jsonObj.length; i++) {                   
                    resultHtml += "<tr>" +                          
                    "<td nowrap='nowrap'>" +  (i+1) + "</td>" +
                    "<td nowrap='nowrap'>" + (jsonObj[i].processing_yr != null ? jsonObj[i].processing_yr : "N/A") + "</td>" +
                    "<td nowrap='nowrap'>" + (jsonObj[i].guide_name != null ? jsonObj[i].guide_name : "N/A") + "</td>" +
                    "<td nowrap='nowrap'>" + (jsonObj[i].co_guide_name != null ? jsonObj[i].co_guide_name : "N/A") + "</td>" +
                    "<td nowrap='nowrap'>" + (jsonObj[i].assigned_date != null ? jsonObj[i].assigned_date : "N/A") + "</td>" + 
                    "<td nowrap='nowrap'>" + (jsonObj[i].reassign_remark != null ? jsonObj[i].reassign_remark : "N/A") + "</td>" + 
                    
                    
                    "</tr>";                                     
                }
               }
           }
           else{
               if(jsonObj.error==="No Record Found"){
                  pclass ="warning";
               }else{
                  pclass ="danger";
               }          
               resultHtml += "<tr><td colspan='4' class='"+pclass+"'>"+jsonObj.error+"</td></tr>";                  
             } 
            resultHtml +="</table></div></div>";           
          //  alert(resultHtml);
            
           // $.cookie("resultHtml"+id,resultHtml);
            //alert($.cookie("resultHtml"+id));
            //console.log($.cookie("resultHtml"+id));  
           // $('#result'+id).html(resultHtml);  
               $('#re_assign_Header').text('Action Logs');
              $('#re_assign_Content').html(resultHtml);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                },
            })  
            
           
    
    }
  }


  