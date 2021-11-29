 $(document).ready(function () {  
      
    

 $('.waive_fee_table').each(function() {
     id= $( this ).attr('id');


  if ($.fn.DataTable.isDataTable('#'+id)) {
    $('#'+id).DataTable().clear().destroy();
  }
   //$('#list').dataTable().fnDestroy();
      table = $('#'+id).DataTable();  




    var colCount = table.columns().header().length;// getting column count 
    console.log(colCount);
   
    i=0;
  

    var brr=[];

    var i=0;
    while(i<colCount)
    {
        brr.push(i);
       console.log(brr[i]);
        i++;
    }



  /*var buttonCommon = {
  init: function (dt, node, config) {
    var table = dt.table().context[0].nTable;
    if (table) config.title = $(table).data('export-title')
  },
  title: 'default title'
};*/
      
     new $.fn.dataTable.Buttons( table, {
        buttons: [
            {
                extend: 'excel',			
                title: 'Final Fee Detail Student List',
                message: '\n',
                text: '<i class="fa fa-file-excel-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to EXCEL"></i>',            
                autoPrint: false,
                pageSize: 'A4',
                orientation: 'landscape',
                exportOptions: {
                   //"columns": brr,
                   columns: ':visible',
                   /*modifier: {
                        page: 'current'
                    }     ,*/
      
                  }, 
               
            },
            
            {
                extend: 'csv',
                title: 'Final Fee Detail Student List',
                message: '\n',
                text: '<i class="fa fa fa-file-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to CSV"></i>',            
                autoPrint: false,
                  autoPrint: false,
                pageSize: 'A4',
                orientation: 'landscape',
                exportOptions: {
                   //"columns": brr,
                   columns: ':visible',
                   /*modifier: {
                        page: 'current'
                    }     ,*/
      
                  },            
         
            },
            
            

            'colvis',
        ]
      
       
    }); 

    table.buttons().container()
        .appendTo( $('.col-sm-6:eq(0)', table.table().container()));
  });
    
});