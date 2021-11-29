 $(document).ready(function () {  
      
    

 $('.sujit').each(function() {
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
      //  console.log(brr[i]);
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
                title: 'Overall Fellowsip Detail',
                message: 'Admission No. :- '+$("#admn_no").val()+' \n Student Name :- '+$("#stu_name").val()+' \n  Department Name :- '+$("#dept_name").val()+ '\n Category :- '+$("#category").val()+ '\n',
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
                title: 'Overall Fellowsip Detail',
                message: 'Admission No. :- '+$("#admn_no").val()+' \n Student Name :- '+$("#stu_name").val()+' \n  Department Name :- '+$("#dept_name").val()+ '\n Category :- '+$("#category").val()+ '\n',
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
            
            {
                extend: 'pdf',
                title: 'Overall Fellowsip Detail',
                message: 'Admission No. :- '+$("#admn_no").val()+' \t  Student Name :- '+$("#stu_name").val()+' \n  Department Name :- '+$("#dept_name").val()+' \n  Category :- '+$("#category").val()+ '\n',
                text: '<i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="Export to PDF"></i>',            
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
            
            {
                extend: 'print',
                title: 'Overall Fellowsip Detail',
                message: 'Admission No. :- '+$("#admn_no").val()+' \n Student Name :- '+$("#stu_name").val()+' \n  Department Name :- '+$("#dept_name").val()+ '\n Category :- '+$("#category").val()+ '\n',
                text: '<i class="fa fa-print fa-2x" aria-hidden="true" data-toggle="tooltip" title="PRINT"></i>',            
                autoPrint: false,
                autoPrint: false,
                pageSize: 'A4',
                orientation: 'landscape',
                exportOptions: {
                //   "columns": brr,
                columns: ':visible',
                   /*modifier: {
                        page: 'current'
                    }     ,*/
      
                },            
         
            },
            
            {
                extend: 'copy',
                title: 'Overall Fellowsip Detail_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
                message: 'Admission No. :- '+$("#admn_no").val()+' \n Student Name :- '+$("#stu_name").val()+' \n  Department Name :- '+$("#dept_name").val()+ '\n Category :- '+$("#category").val()+ '\n',
                text: '<i class="fa fa-files-o fa-2x" aria-hidden="true" data-toggle="tooltip" title="copy to clipboard"></i>',            
                autoPrint: false,
                exportOptions: {
                  // "columns":brr,
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