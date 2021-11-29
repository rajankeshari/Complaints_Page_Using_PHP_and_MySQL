/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */  

 /* Module to save  Marks Finalization status 
  @mode: Ajax
  @actor:exam_dr
  @param:mapping key,subject key
  @des:Store YES/NO selected by the actor while allowing marks updation for faculty for chosen subject.\'Yes\' will disallow  marks update option for concerned Faulty & \'No\'  will allow the same. Ensuring one has already opened or need to open the date for marks update for the concerned  subject
  @return:true/false
 **/
 function foo(map_id,marks_master_id,subject_id,sub_name,sub_code){         
    if (confirm("Are you Sure?")){                  
        $("#Lform").removeData('validator'); // removing  All  form's rule   
        $("#Lform").validate();
         $("#status_"+map_id+"_"+subject_id).rules('add', {
            required: true,        
            messages: {
                required: 'choose status',                
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
     $("#remark_"+map_id+"_"+subject_id).rules('add', {
            required: true,
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
         if ($("#Lform").valid()) {      
              var  actionCaption='Marks Finalization Status ';             
                  var postdata = {marks_master_id:marks_master_id,map_id: map_id, subject_id: subject_id, status: $("#status_"+map_id+"_"+subject_id).val(),remark: $("#remark_"+map_id+"_"+subject_id).val(),sub_name:sub_name,sub_code:sub_code};
                    $.ajax({
                        url: site_url('exam_control/examControl/ajax_marks_final_status_change'),
                        type: "POST",
                        dataType: "json",
                        data: postdata,
                        success: function (jsonObj) {                            
                            $("#msg").html("");
                            $("#msg").show();                        
                            
                            if (jsonObj.result === "Successfully") {                                                                                                                                                                      
                                if(jsonObj.status=='Y'){                                        
                                   $("#status_row_"+map_id+"_"+subject_id).css({'background-color': '#dff0d8'});
                                   $("#status_caption_"+map_id+"_"+subject_id).html('YES');
                                   $("#status_" + map_id +"_"+subject_id).val('Y');                                   
                               }
                                  else if(jsonObj.status=='N'){
                                 $("#status_row_"+map_id+"_"+subject_id).css({'background-color': '#fcf8e3'});                                                                      
                                 $("#status_caption_"+map_id+"_"+subject_id).html('NO');
                                 $("#status_" + map_id +"_"+subject_id).val('N');
                             }
                                 d = new Date();
                                 var timestamp = d.timestamp();                                    
                                 $("#msg").removeClass().addClass("alert alert-success");
                                 $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-check'></i><strong>" + actionCaption + " done " + jsonObj.result + " </strong>for subject " + sub_name+"["+sub_code+"]"+ " on "+timestamp+"" );
                            } else {
                                $("#msg").removeClass().addClass("alert alert-danger");
                                $("#msg").html(" <a href='#' class='close' data-dismiss='alert'>&times;</a><i class='fa fa-exclamation'></i><strong>" + actionCaption + " " + jsonObj.result + "</strong> for subject " + sub_name+"["+sub_code+"]"+ " on "+timestamp+",<p>"+ jsonObj.error +"</p>");
                            }
                            $("#collapse_" + map_id +"_"+subject_id).css({'display': 'none'});
                             
                            setTimeout(function () {
                                $("#msg").hide();
                            }, 5000);
                            
                        /*    if ($("#collapse_" + map_id +"_"+subject_id).hasClass('collapse in'))
             $("#collapse_" + map_id +"_"+subject_id).removeClass("collapse in").addClass("collapse"); 
             $("#remark_" + map_id +"_"+subject_id).attr('disabled',true);
             $("#chnage_status_" + map_id +"_"+subject_id).attr('disabled',true);       */
                            
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
        }        
        }  
   function collapse_fix(map_id,sub_id){
          if ($("#collapse_" + map_id +"_"+sub_id).hasClass('collapse'))
             $("#collapse_" + map_id +"_"+sub_id).removeClass("collapse in").addClass("collapse"); 
            $("#remark_" + map_id +"_"+sub_id).attr('disabled',false);
             $("#chnage_status_" + map_id +"_"+sub_id).attr('disabled',false);             
     }
     
     
     $("srchform").submit(function(){
   if($('#dept').val() =='all'){ 
      $('.optional_th').show();
      $('.optional_td').show();
      
        }
        else{
      $('.optional_th').hide();      
        $('.optional_td').hide();			
       }
});


   $(document).ready(function () {
        $("#list").dataTable({
        "bPaginate": true, 
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave":true 
    });
    
       
        $.ajax({
        url: site_url("attendance/attendance_ajax/get_session_year_exam"),
        data: {'sy': $('#hsyear').val()},
        success: function (result) { 
            $('.gS').html(result);
        }
    });
    
        if ($('#dept').val() == "comm"|| $('#dept').val() == "prep") {		  	          
                
            $('#faclist').show();
         $('#faclist_label').show();
             $.ajax({
            url: site_url('exam_control/examControl/get_PREP_COMM_Guide_List/'),
            type: "POST",
            async: true,
            dataType: "json",
            data: {dept_id: $('#dept').val(), session_year: ($("#session_year_attendance").val()==null?$('#hsyear').val(): $("#session_year_attendance").val()),session: $("#session_attendance").val()},
            success: function (jsonObj) {                
                $('#faclist').append("<option value=''>-select faculty-</option>");
                $('#faclist').empty();
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                         $('#faclist').append('<option value="' + jsonObj[i].id + '" >' + jsonObj[i].salutation + ' ' + jsonObj[i].first_name + ' ' + jsonObj[i].middle_name + ' ' + jsonObj[i].last_name + '[' + jsonObj[i].id + ']</option>');
                    }   $('#faclist').val( $('#hfacsel').val());
                }
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        });  
        
        
       }else{     
       
           if($('#dept').val() =='all'){
            $('#faclist').val('');
            $('#faclist').hide();
            $('#faclist_label').hide();
            $("#srchform").validate({
                     ignore: ".ignore"
                           });            
						                                                   				                                                               
         }else{   
         $.ajax({
            url: site_url('fellowship/fellowshipProcess/get_DeptWise_Guide_List/'),
            type: "POST",
            async: true,
            dataType: "json",
            data: {dept_id: $('#dept').val()},
            success: function (jsonObj) {                
                $('#faclist').append("<option value=''>-select faculty-</option>");
                $('#faclist').empty();
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                         $('#faclist').append('<option value="' + jsonObj[i].id + '" >' + jsonObj[i].salutation + ' ' + jsonObj[i].first_name + ' ' + jsonObj[i].middle_name + ' ' + jsonObj[i].last_name + '[' + jsonObj[i].id + ']</option>');
                    } $('#faclist').val( $('#hfacsel').val());
                }                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        });
             $('#faclist').show();
             $('#faclist_label').show();
                                                              				
    } 
    }        
    
                     
       if(!$('#dept option').filter(function () {return  $(this).val()=='all' }).length ) {
            $('<option/>').val('all').html('ALL').appendTo("#dept");            
       }
           
    
      /* if($('#dept option').filter(function () {return  $(this).val()=='ss' }).length)                                        
           $('#dept option').eq('ss').remove();
       if($('#dept option').filter(function () {return  $(this).val()=='comm' }).length)                                        
           $('#dept option').eq('comm').remove();
       if ($('#dept option').filter(function () {return  $(this).val()=='prep' }).length)                                        
           $('#dept option').eq('prep').remove();    */
    $('#dept').on('change', function () {          
        if (this.value == "comm"|| this.value == "prep") {
            
         $('#faclist').show();
         $('#faclist_label').show();
             $.ajax({
            url: site_url('exam_control/examControl/get_PREP_COMM_Guide_List/'),
            type: "POST",
            async: true,
            dataType: "json",
            data: {dept_id: $(this).val(), session_year: $("#session_year_attendance").val(),session: $("#session_attendance").val()},
            success: function (jsonObj) {                
                $('#faclist').append("<option value=''>-select faculty-</option>");
                $('#faclist').empty();
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                         $('#faclist').append('<option value="' + jsonObj[i].id + '" >' + jsonObj[i].salutation + ' ' + jsonObj[i].first_name + ' ' + jsonObj[i].middle_name + ' ' + jsonObj[i].last_name + '[' + jsonObj[i].id + ']</option>');
                    }
                }                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        }); 
       
       }else{
         if(this.value =='all'){
             $('#faclist').val('');
            $('#faclist').hide();
            $('#faclist_label').hide();
            $("#srchform").validate({
                     ignore: ".ignore"
                           });
                      
         }   
        else{
            $('#faclist').show();
            $('#faclist_label').show();
                                                                     				
         $.ajax({
            url: site_url('fellowship/fellowshipProcess/get_DeptWise_Guide_List/'),
            type: "POST",
            async: true,
            dataType: "json",
            data: {dept_id: $(this).val()},
            success: function (jsonObj) {                
                $('#faclist').append("<option value=''>-select faculty-</option>");
                $('#faclist').empty();
                if (jsonObj.length > 0) {
                    for (var i = 0; i < jsonObj.length; i++) {
                         $('#faclist').append('<option value="' + jsonObj[i].id + '" >' + jsonObj[i].salutation + ' ' + jsonObj[i].first_name + ' ' + jsonObj[i].middle_name + ' ' + jsonObj[i].last_name + '[' + jsonObj[i].id + ']</option>');
                    }
                }                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.statusText);
                alert(thrownError);
            },
        });
          
    } 
    } 
    });       
    
    
    
   /*     if($('#dept').val() =='all'){ 
      $('.optional_th').show();
      $('.optional_td').show();
      
        }
        else{
      $('.optional_th').hide();      
        $('.optional_td').hide();			
       }
     */  
       
       
//re-initialize data table
 if ($.fn.DataTable.isDataTable("#list")) {         
      $('#list').dataTable().fnDestroy();     
 }  
  var  table = $('#list').DataTable( {
       
   
      
       //var table = $("#list").DataTable({
        /*"bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave": false,*/
        //dom: 'Bfrtip',
       // buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        //dom : 'l<"#add">frtip',
       
       /*"columnDefs": [
    { "visible": false, "targets": [9] }
  ],*/ 
      //  "bServerSide": true,
          /*fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },*/
      "sScrollX": "100%",
      "sScrollXInner": "165%",
      "bScrollCollapse": true,       
      "scrollX":true,     
     
      //fixedHeader: true,
       initComplete: function () {
           field=$('#user_auth').val()=='11'? [1,2,3,4,5]:[0,1,2,3,4];
            this.api().columns(field).every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
               column.data().unique().sort().each( function ( d, j ) {
    if(column.search() === '^'+d+'$'){
        select.append( '<option value="'+d+'" selected="selected">'+d+'</option>' )
    } else {
        select.append( '<option value="'+d+'">'+d+'</option>' )
    }
} );
            } );
     
        },
        
       
      });  
  
//$('#lsit tfoot tr').appendTo('#lsit thead');

field2=$('#user_auth').val()=='11'?[1,2,3,4,5,6,7,8,9,10]:[0,1,2,3,4,5,6,7,8,9]; 
 new $.fn.dataTable.Buttons( table, {
    buttons: [
        {
            extend: 'excelHtml5',
              message: $('#colspan_text').text(),
            text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
            autoPrint: false,         
                orientation: 'landscape',
                pageSize: 'A3',
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
              
            },
               /*customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                var numrows = 1;
                var clR = $('row', sheet);

                //update Row
                clR.each(function () {
                    var attr = $(this).attr('r');
                    var ind = parseInt(attr);
                    ind = ind + numrows;
                    $(this).attr("r",ind);
                });

                // Create row before data
                $('row c ', sheet).each(function () {
                    var attr = $(this).attr('r');
                    var pre = attr.substring(0, 1);
                    var ind = parseInt(attr.substring(1, attr.length));
                    ind = ind + numrows;
                    $(this).attr("r", pre + ind);
                    
                });

                function Addrow(index,data) {
                    msg='<row r="'+index+'">'
                    for(i=0;i<data.length;i++){
                        var key=data[i].key;
                        var value=data[i].value;
                        msg += '<c t="inlineStr" r="' + key + index + '">';
                        msg += '<is>';
                        msg +=  '<t>'+value+'</t>';
                        msg+=  '</is>';
                        msg+='</c>';
                    }
                    msg += '</row>';
                    return msg;
                }
                                     

             },*/
                customize: function (xlsx) {
                   var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                  $('row c[r^="A"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    if ( $('is t', this).text()== 'NO') {
                         $(this).attr( 's', '20' );
                    }
                });
               var numrows = 1;
                var clR = $('row', sheet);

                //update Row
                clR.each(function () {
                    var attr = $(this).attr('r');
                    var ind = parseInt(attr);
                    ind = ind + numrows;
                    $(this).attr("r",ind);
                });

                // Create row before data
                $('row c ', sheet).each(function () {
                    var attr = $(this).attr('r');
                    var pre = attr.substring(0, 1);
                    var ind = parseInt(attr.substring(1, attr.length));
                    ind = ind + numrows;
                    $(this).attr("r", pre + ind);
                    
                });
        
         
  
                 var col = $('col', sheet);
        col.each(function () {
              $(this).attr('width', 40);
       });                
         $(col[2]).attr('width', 65);    
            }
             
   
 
 

            
            
           },
            {
            extend: 'csvHtml5',
            
            customize: function (csv) {
                  return  $('#colspan_text').text()+"\n\n"+  csv;
             },
            text: '<i class="fa fa fa-file-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to CSV"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
   
  
            },            
     
           },
              {
            extend: 'pdfHtml5',
            customize: function(doc) {
            for (var r=1;r<doc.content[2].table.body.length;r++) {
                var row = doc.content[2].table.body[r];
                for (c=1;c<row.length;c++) {
                    var exportColor = table
                                        .cell( {row: r-1, column: c} )
                                        .nodes()
                                        .to$()
                                        .attr('export-color');
                    if (exportColor) {
                        row[c].fillColor = exportColor;
                    }
                }
            }
        },
            message: $('#colspan_text').text(),
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
            orientation: 'landscape',
  pageSize: 'A4',
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },  
            
     
           },
             {
            extend: 'print',
             message: $('#colspan_text').text(),
            text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
               customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                             '<img src="../../../images/ism/ismlogo.png" style="position:absolute; top:600; left:600;" />'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }  
           },
            {
            extend: 'copy',
             message: $('#colspan_text').text(),
            text: '<i class="fa fa-files-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="copy to clipboard"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
     
           },
      
    ],
   
} ); 
table.buttons().container()
    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
    
/*$.fn.dataTableExt.ofnSearch['title-#marks finalization status'] = function ( sData ) {
   return sData.replace(/\n/g," ").replace( /<.*?>/g, "" );*
}*/
/*
  var table = $('#example').DataTable( {
    dom: 'Bfrtip',
    buttons: [
      {
        extend: 'columnToggle',
        text: 'Toggle Group1',
        columns: ':gt(0)' , //toDo
   
      }
    ]
  });

*/
    
});

