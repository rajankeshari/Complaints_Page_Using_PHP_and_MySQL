<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
*/


class Exam_tabulation_config{ 
   static $bottom_page_margin_row='16';
   static $top_page_margin_row = '8';
   static $dynamic_header_row='6';
   static $each_offset='9';
   static $gutter ='1.75';
   static $tot_rows_in_page='3';
   
   static $core_col_range_start='E'; 
   static $core_col_range_end='L';
   static $H_col_range_start='M';
    static $H_col_range_end='P';
   
    
   static $Template_header1 = array(1=>'Sub Code' , 2=>'L-T-P' , 3=>'Cr. Hr.' , 4=>'Theory' , 5=>'Sessional' , 6=>'Total' ,7=>'Grade' , 8=>'Cr. Pts.', 9=>'Remark');
   static $Template_header2 = array(1=>'Total Credit Hours' , 2=>'Total Credit Points' , 3=>'Grade Point Average' , 4=>'OGPA/Ranks/Position' ,5=>'Remark');
   static $Template_header3 = array(1=>'No. of Candidates' , 2=>'Absent' , 3=>'Passed' , 4=>'Failed' ,5=>'Pass Percentage');
   static $Signature_header=  array(1=> 'GPA & OGPA verified by',2=>'Signature of Tabulator',3=>'Chairman Moderation Board', 4=>'For Chairman Examination Board') ;                              
  
   
   
   
   
}

