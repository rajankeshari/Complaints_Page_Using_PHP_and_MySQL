$(function(){
    
   $('#ssub').tagsinput({
    itemValue: 'value',
    itemText: 'text',
    allowDuplicates: false,    
});
  
    
    $('#course').on('change',function(){
       
            $.ajax({
                url:site_url('fellowship/fellowshipProcess/getSubjectByCourse'),
                type:'POST',
                data:{'id':$(this).val()},
                success:function(data){
                    if(data.length > 0){
                        var s ='<option value="">Select Semester</option>';
                        for(i=1; i<=data; i++){
                            s += '<option value="'+i+'">'+i+'</option>'; 
                        }
                        $('#sem').html(s);
                    }
                }
            });
   }); 
   
   $('#sem').on('change',function(){
      if($(this).val() == 1 || $(this).val() == 2){
          var s='<div class="col-sm-3"><div class="form-group"><label>Group</label><select name="group" id="group"  class="form-control input-sm"   data-toggle="tooltip"  title="Choose  Group"  ><option value="">-select Group-</option><option value="1">Group 1</option><option value="2">Group 2</option></select></div></div>';
          $('#extra').html(s);
       } else{
           $('#extra').html('');
       }
       
   });
   
   $('#getsub').on('click',function(){       
        $.ajax({
        url: site_url('fellowship/fellowshipProcess/getSubject'),
        type: "POST",
        data: {'course': $('#course').val(),'branch': $('#branch').val(),'semester': $('#sem').val(),'passing': $('#passing').val(),'group': $('#group').val() },
        success: function (data) {
           // alert(data);
            json = $.parseJSON(data);
            var r='';
            $.each(json ,function(key,value){
                r += '<tr><td>Post Graduate<input type="hidden" id="type_'+value.id+'" value="pg"></td><td><input type="checkbox" onclick="chcek(\''+value.id+'\',\''+value.name+'\')" name="sub" id="sub-'+value.id+'" value="'+value.id+'" /></td><td> ( '+value.subject_id+' ) '+value.name+' </td></tr>'
                
            });
            $('#subR tbody').html(r);
               if($('input[id="ssub"]').val()!=""){$("#save").show(); $('input[id="ssub"]').tagsinput('removeAll');}   
              
                
        }
   });
});
 
 
    $('#getsub_jrf').on('click',function(){       
        $.ajax({
        url: site_url('fellowship/fellowshipProcess/getJRFSubject'),
        type: "POST",
        data: {id:$('#dept_jrf').val()},
        success: function (data) {
           // alert(data);
            json = $.parseJSON(data);
            var r='';
            $.each(json ,function(key,value){
                r += '<tr><td>Junior Fellow Research<input type="hidden" id="type_'+value.subj_id+'" value="jrf"></td><td><input type="checkbox" onclick="chcek(\''+value.subj_id+'\',\''+value.subj_name+'\')" name="sub" id="sub-'+value.subj_id+'" value="'+value.subj_id+'" /></td><td> ( '+value.subj_id+'-'+value.name+' ) '+value.subj_name+'</td></tr>'
            });
            $('#subJRF tbody').html(r);
           
              // if($('input[id="ssub"]').val()!=""){$("#save").show(); $('input[id="ssub"]').tagsinput('removeAll');}   
              
                
        }
   });
});
 
   $('#save').on('click',function(){
        data2= [];
        data = $("#ssub").val();
        res = data.split(',');
         _.each(res, function (p) {
                   data2.push($('#type_'+p).val());                                     
                  // alert(p+"_"+$('#type_'+p).val());
                  });
        
       $.ajax({
        url: site_url('fellowship/fellowshipProcess/saveSubject'),
        type: "POST",
        dataType:"json",
        data: {subjects: res, type:data2, stud_reg_no: $('#stud_reg_no').val(),entry_type: $("#entry_type").val(),admn_date: $('#admn_date').val(),prev_chosen_sub:$('input[id="ssub"]').val(),
               'course': $('#course').val(),'branch': $('#branch').val(),'semester': $('#sem').val(),'passing': $('#passing').val(),'group': $('#group').val(),dept_id: $("#department_name").val(),jrf_dept_id: $("#dept_jrf").val()},
        success: function (jsonObj) {
                  
                     $("#subject_msg").html("");
                     $("#subject_msg").show();                                      
                     var actionCaption = "Subject Offering";
                    if (jsonObj.result === "Successfully") {                                                
                        //if($('input[id="ssub"]').val()==""){
                         $("#save").hide();                     
                        $("#subject_msg").removeClass().addClass("alert alert-success");
                        $("#subject_msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for fellow having registration No." + jsonObj.stud_reg_no);
                    } else {
                        $("#save").show();                        
                        $("#subject_msg").removeClass().addClass("alert alert-danger");
                        $("#subject_msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for fellow having registration No. " + jsonObj.stud_reg_no + "." + jsonObj.error);
                    }
                    setTimeout(function () {
                        $("#subject_msg").hide();
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                }, 
        
   });
    
   });
});
//var chooseSub = [];
function chcek(id,subId){
    if($('#sub-'+id).is(':checked')){
         $('#ssub').tagsinput('add', { "value": id, "text": subId });
         //chooseSub.push(id);
    }else{
        $('#ssub').tagsinput('remove', { "value": id, "text": subId });
//        chooseSub = jQuery.grep(chooseSub, function(value) {
//        return value != id;
//        });
    }
    
}

$('input').on('itemRemoved', function(event) {
  // event.item: contains the item  
   $('input[value="'+event.item+'"]').removeAttr('checked');  
  alert("removed");
});


 /*function getPrevChosenSubject(id,adm_dt)   {     
        $.ajax({
        url: site_url('fellowship/fellowshipProcess/show_subjects'),
        type: "POST",
        data: {'stud_reg_no': id,'field': 'custom'},
        success: function (data) {          
            jsonObj = $.parseJSON(data);          
           if (jsonObj.length > 0) {   
                for (var i = 0; i < jsonObj.length; i++) {                       
                  $('#ssub').tagsinput('add', { "value": ""+jsonObj[i].id+"", "text": ""+jsonObj[i].name+"" });
           }
               
           if($('input[id="ssub"]').val()!=""){$("#save").hide(); }   
         }
      }
    });  
 } 
 */
 function getPrevChosenSubject(id,entry_date)   {     
  
  $('#stud_reg_no').val(id);
  $('#admn_date').val(((entry_date==null ||entry_date==""|| entry_date=='1970-01-01') ?$("#smdate" + id).val():entry_date));
  $('input[id="ssub"]').tagsinput('removeAll');
  $('input:checkbox').removeAttr('checked');
 
  
    $.ajax({
        url: site_url('fellowship/fellowshipProcess/get_deptt_jrf'),
        type: "POST",
        data: {},
        success: function (data) {     
         $('#jrf_sub').html("");
            jsonObj = $.parseJSON(data);          
               var sel = $("<select>");
                sel.attr("id", 'dept_jrf');                                                
                sel.append("<option value=''>-select Department-</option>");
           if (jsonObj.length > 0) {   
                for (var i = 0; i < jsonObj.length; i++) {                       
               sel.append('<option value="' + jsonObj[i].offering_dept + '" >' + jsonObj[i].name + '</option>');
           }               
           
         }
          $('#jrf_sub').append('<label>Department Offering JRF Subjects</label>');
          $('#jrf_sub').append(sel);  
      },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.statusText);
                    alert(thrownError);
                }
    });  
 } 