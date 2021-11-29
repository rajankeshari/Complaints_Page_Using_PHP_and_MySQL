

   $(document).ready(function () {
        $("#medicine_rpt").dataTable({
        "bPaginate": true, 
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave":true 
    });
    
  
      
    
      
//re-initialize data table
 if ($.fn.DataTable.isDataTable("#medicine_rpt")) {         
      $('#medicine_rpt').dataTable().fnDestroy();     
 }  
  var  table = $('#medicine_rpt').DataTable( {
      
       
      "sScrollX": "100%",
      "sScrollXInner": "165%",
      "bScrollCollapse": true,       
      "scrollX":true,     
     
              
       
      });  
  

var field2=[0,1,2,3,4,5,6];

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
               
              
            },
             
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
              
   
  
            },            
     
           },
              {
            extend: 'pdfHtml5',
  
            message: $('#colspan_text').text(),
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
            orientation: 'landscape',
  pageSize: 'A4',
            exportOptions: {
               "columns": field2,
              
  
            },  
            
     
           },
             {
            extend: 'print',
             message: $('#colspan_text').text(),
            text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": field2,
              
            },            
               
           },
            {
            extend: 'copy',
             message: $('#colspan_text').text(),
            text: '<i class="fa fa-files-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="copy to clipboard"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": field2,
               
  
            },            
     
           },
      
    ],
   
} ); 
table.buttons().container()
    .appendTo( $('.col-sm-6:eq(0)', table.table().container() ) );
    

    
});

