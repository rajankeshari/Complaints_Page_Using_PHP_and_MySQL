 $(document).ready(function () {  
      
    

 $('.xx').each(function() {
     id= $( this ).attr('id');

 if ($.fn.DataTable.isDataTable('#'+id)) {
  $('#'+id).DataTable().clear().destroy();
}
 //$('#list').dataTable().fnDestroy();
    table = $('#'+id).DataTable({
        
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   {
            leftColumns: 4,
            rightColumns: 1
        }
    });  
  
      
 new $.fn.dataTable.Buttons( table, {
    buttons: [
        {
            extend: 'excel',
            message: 'I-card List',
            text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
            autoPrint: false,
            exportOptions: {
              // "columns": [1,2,3,4,5,6],
               /*modifier: {
                    page: 'current'
                }     ,*/
                //      format: {
                //     header: $("#colspan_"+id).text()
                // }   
  
            },            
         /*customize: function () {
                //$(window.document.body).find('table').addClass('display').css('font-size', '9px');
                $( window.document.body).find('tr:nth-child(odd) td').each(function(index){
                    $(this).css('background-color','#dff0d8');
                });
                //$(win.document.body).find('h1').css('text-align','center');
            }*/
           
           },
            {
            extend: 'csv',
            message: 'I-card List',
            text: '<i class="fa fa fa-file-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to CSV"></i>',            
            autoPrint: false,
            exportOptions: {
             //  "columns": [0,1,2,3,4,5,6],
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
     
           },
              {
            extend: 'pdf',
                  message: 'I-card List',
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
            exportOptions: {
             //  "columns": [0,1,2,3,4,5,6],
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
     
           },
             {
            extend: 'print',
                  message:'I-card List',
            text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
            autoPrint: false,
            exportOptions: {
              // "columns": [0,1,2,3,4,5,6],
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
     
           },
            {
            extend: 'copy',

            text: '<i class="fa fa-files-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="copy to clipboard"></i>',            
            autoPrint: false,
            exportOptions: {
              // "columns": [0,1,2,3,4,5,6],
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
    
});