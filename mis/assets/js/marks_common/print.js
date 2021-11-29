/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */  
   $(document).ready(function () { print();});        
 function print(cuurtable){     
//alert();
    var field2=[];  
   // console.log( 'curr'+$.fn.dataTable.tables()[0].id ); // getting  current table id 
    var curr=  (cuurtable==null?$.fn.dataTable.tables()[0].id:cuurtable) ;
    if ($.fn.DataTable.isDataTable("#" +curr)) 
       $("#" +curr).dataTable().fnDestroy();     
        var  table = $("#" +curr).DataTable( {
       //"sScrollX": "100%",
      //"sScrollXInner": "165%",
     // "bScrollCollapse": true,       
      "scrollX":true,                             
      });    
      //console.log(table.columns().header().length);/ getting header/colun count
      for(i=0; i< table.columns().header().length;i++)
	  field2.push(i);	         
 new $.fn.dataTable.Buttons( table, {
    buttons: [
        {
            extend: 'excelHtml5',
              message: $('#colspan_text').text(),
            text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
            autoPrint: false,         
              //  orientation: 'landscape',
                pageSize: 'A3',
            exportOptions: {
               "columns": field2,
               /*modifier: {
                    page: 'current'
                }     ,*/
              
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
               /*modifier: {
                    page: 'current'
                }     ,*/
   
  
            },            
     
           },
              {
            extend: 'pdfHtml5',            
            message: $('#colspan_text').text(),
            text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
            autoPrint: false,
              pageSize: 'A2',
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

 }  
//});

