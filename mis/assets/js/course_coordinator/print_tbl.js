 $(document).ready(function () {  
      
    

 $('.rohit').each(function() {
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
            title: 'Grade_Statistics_Template_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
            message: 'Grade Statistics [Template_'+(id=="ex_mod_1"?1:(id=="ex_mod_2"?2:'Free'))+'] \n',
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

         /*customize: function () {
                //$(window.document.body).find('table').addClass('display').css('font-size', '9px');
                $( window.document.body).find('tr:nth-child(odd) td').each(function(index){
                    $(this).css('background-color','#dff0d8');
                });
                //$(win.document.body).find('h1').css('text-align','center');
            }*/
			
				 customize: function (xlsx) {
                   var sheet = xlsx.xl.worksheets['sheet1.xml'];
				   
				   
				   

                   //console.log(i);
				 //   alert(id);
				   
                  $('row c[r^="F"]', sheet).each( function () {
					  // alert(id);
                    // Get the value and strip the non numeric characters
                    //if ( parseFloat($('is t', this).text())<25) {
						if(id=='ex_mod_1' && (parseFloat($(this).text())>25   || parseFloat($(this).text())<15)) {						
                         $(this).attr( 's', '32' );
                    }
					else if(id=='ex_mod_2' && (parseFloat($(this).text())>50   || parseFloat($(this).text())<40)) {						
                         $(this).attr( 's', '32' );
                    }
                });
				
				
				$('row c[r^="H"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    //if ( parseFloat($('is t', this).text())<25) {
						if(id=='ex_mod_1' && (parseFloat($(this).text())>45   || parseFloat($(this).text())<35)) {						
                         $(this).attr( 's', '32' );
                    }
					else if(id=='ex_mod_2' && (parseFloat($(this).text())>50   || parseFloat($(this).text())<40)) {						
                         $(this).attr( 's', '32' );
                    }
                });
				
				$('row c[r^="J"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    //if ( parseFloat($('is t', this).text())<25) {
						if(id=='ex_mode_1' && (parseFloat($(this).text())>35   || parseFloat($(this).text())<25) ){						
                         $(this).attr( 's', '32' );
                    }
					else if(id=='ex_mod_2' && (parseFloat($(this).text())>15   || parseFloat($(this).text())<5)) {						
                         $(this).attr( 's', '32' );
                    }
                });
				
				$('row c[r^="L"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    //if ( parseFloat($('is t', this).text())<25) {
					if(id=='ex_mod_1' && (parseFloat($(this).text())>15   || parseFloat($(this).text())<5)) {						
                         $(this).attr( 's', '32' );
                    }
                });
				
				
              // var numrows = 1;
//var clR = $('row', sheet);

                //update Row
               /* clR.each(function () {
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
         $(col[2]).attr('width', 65);  */  
            }
             
   
 
			
			
			
           
           },
            {
            extend: 'csv',
            title: 'Grade_Statistics_Template_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
            message: 'Grade Statistics [Template_'+(id=="ex_mod_1"?1:(id=="ex_mod_2"?2:'Free'))+'] \n',
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
                  title: 'Grade_Statistics_Template_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
            message: 'Grade Statistics [Template_'+(id=="ex_mod_1"?1:(id=="ex_mod_2"?2:'Free'))+'] \n',
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
                title: 'Grade_Statistics_Template_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
            message: 'Grade Statistics [Template_'+(id=="ex_mod_1"?1:(id=="ex_mod_2"?2:'Free'))+'] \n',
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
 title: 'Grade_Statistics_Template_'+(id=='ex_mod_1'?1:(id=='ex_mod_2'?2:'Free')),
            message: 'Grade Statistics [Template_'+(id=="ex_mod_1"?1:(id=="ex_mod_2"?2:'Free'))+'] \n',
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