 $(document).ready(function () {  
       /* $("#state_repo").dataTable({
        "bPaginate": true, 
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave":true 
    });*/
    
 /*$('#state_repo').each(function() {
   id= $( this ).attr('id');
*/
 /*if ($.fn.DataTable.isDataTable('#state_repo')) {
  $('#state_repo').DataTable().clear().destroy();
}*/
 //$('#list').dataTable().fnDestroy();
    /*table = $('#'+id).DataTable({
        
        scrollX:        true,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   {
            leftColumns: 5,
            rightColumns: 1
        }
    });  */
table=  $('#state_repo').DataTable( {
	 /* scrollX:        true,
        scrollCollapse: true,
        paging:         true,*/
		 "bLengthChange": true,
		 "bAutoWidth": true,
		  "sScrollX": "300%",
      "sScrollXInner": "505%",
      "bScrollCollapse": true,       
      "scrollX":true,     
        /*initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value="">Filter</option></select>')
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
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        },*/
		
    } );
  id="state_repo";
    
      var prr=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];
 new $.fn.dataTable.Buttons( table, {
    buttons: [
        {
            extend: 'excel',
            message: 'Defaulter List',
            text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
            autoPrint: false,
            exportOptions: {
             "columns": prr
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
            message: $("#colspan_"+id).text(),
            text: '<i class="fa fa fa-file-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to CSV"></i>',            
            autoPrint: false,
            exportOptions: {
               "columns": prr,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
     
           },
              {
            extend: 'pdfHtml5',
                  message: $("#colspan_"+id).text(),
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
            
            orientation:'landscape',
            pageSize:'A4',
              exportOptions: {
               "columns": prr,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },         
            customize: function ( doc ) {
                 doc.defaultStyle.fontSize = 10;
            }
                
     
           },
             {
            extend: 'print',
                  message: $("#colspan_"+id).text(),
            text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
            autoPrint: false,
            exportOptions: {
              "columns": prr,
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
              "columns": prr,
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
    
//});