/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){
   
    if (!$('#dept option').filter(function () {
                                return $(this).val() == 'all';
                            }).length)
                                $('<option/>').val('all').html('All').appendTo("#dept");
                                      
     
    if ( ( (  $('#course1').val()==''|| $('#course1').val()==null || $('#course1').val()=='0') ||  ($('#branch1').val()==''|| $('#branch1').val()==null  || $('#branch1').val()=='0'   ) || ($('#semester').val()==''|| $('#semester').val()==null|| $('#semester').val()=='none')) &&  $('#granual_sel').val() == 'min'){
        
      if (!$('#cat_sel option').filter(function () {
                                return $(this).val() == 'HM';
                            }).length)
                                $('<option/>').val('HM').html('Honours & Minor').appendTo("#cat_sel");
                                      
    }else{
          if ($('#cat_sel option').filter(function () {
                                return $(this).val() == 'HM';
                            }).length)             
                             $('#cat_sel > option[value="HM"]').remove();
  }     
    
    
   $('#granual_sel').on('change', function () {
    if ((  (   $('#course1').val()==''|| $('#course1').val()==null || $('#course1').val()=='0') ||  ($('#branch1').val()==''|| $('#branch1').val()==null  || $('#branch1').val()=='0'   ) || ($('#semester').val()==''|| $('#semester').val()==null|| $('#semester').val()=='none')) &&  $(this).val() == 'min'){
      if (!$('#cat_sel option').filter(function () {
                                return $(this).val() == 'HM';
                            }).length)
                                $('<option/>').val('HM').html('Honours & Minor').appendTo("#cat_sel");
                                      
    }else{
          if ($('#cat_sel option').filter(function () {
                                return $(this).val() == 'HM';
                            }).length)             
                             $('#cat_sel > option[value="HM"]').remove();
    }
  });  
  
   $('#dept').on('change', function () {
            if($(this).val() == 'all'){
                 $('#granual_row').hide();
                 $('#course1').val('');
                 $('#branch1').val('');
                 $('#semester').val('');
                   if ($('#granual_sel option').filter(function () {
                                return $(this).val() == 'max';
                            }).length)
                                  $('#granual_sel > option[value="max"]').remove();
                 
            }else{
                if (!$('#granual_sel option').filter(function () {
                                return $(this).val() == 'max';
                            }).length)
                                $('<option/>').val('max').html('max').appendTo("#granual_sel");
                                      
     
                
                
                if(('#granual_sel').val()=='min'){
                 $('#granual_row').hide();
                 $('#course1').val('');
                 $('#branch1').val('');
                 $('#semester').val('');
             }
               else
                  $('#granual_row').show(); 
            }
                                      
      });  
      
      $('#exm_type').on('change', function () {
             if (!$('#dept option').filter(function () {
                                return $(this).val() == 'all';
                            }).length)
                                $('<option/>').val('all').html('All').appendTo("#dept");
                                      
      });  
       
      
     

      
  
});

