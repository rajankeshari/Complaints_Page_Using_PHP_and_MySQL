/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */  

 
 
 

$(document).ready(function () {                     
    $("#ex_mod").dataTable({
        "bPaginate": true, 
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": true,
        "bStateSave":true 
    });
    
       
//re-initialize data table
 if($.fn.DataTable.isDataTable("#ex_mod")){         
    $('#ex_mod').dataTable().fnDestroy();     
 }  
  //table = $('#ex_mod').DataTable();  
  
  
 table=  $('#ex_mod').DataTable( {
        initComplete: function () {
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
        }
    } );
  
  
var prr= [0,1,2,3,4];
 new $.fn.dataTable.Buttons( table, {
    buttons: [
        {
            extend: 'excelHtml5',
            text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
            autoPrint: false,         
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: {
              "columns": prr,
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
         
            }
             
   
 
 

            
            
           },
            {
            extend: 'csvHtml5',
            
            customize: function (csv) {
                  return $("#colspan").text()+"\n\n"+  csv;
             },
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
           /* customize: function(doc) {
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
        },*/
            message:$("#colspan").text(),
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
            orientation: 'landscape',
            
  pageSize: 'A3',
            exportOptions: {
               "columns":  prr,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },  
            
     
           },
             {
            extend: 'print',
              message:$("#colspan").text(),
            text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
            autoPrint: false,
            exportOptions: {
              "columns":  prr,
               /*modifier: {
                    page: 'current'
                }     ,*/
  
            },            
               customize: function ( win ) {
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<img src="../../images/ism/ismlogo.png" style="position:absolute; top:600; left:600;" />'
                        );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
                }  
           },
            {
            extend: 'copy',
              message:$("#colspan").text(),
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


 