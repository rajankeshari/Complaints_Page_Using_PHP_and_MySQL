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
   static $tot_rows_in_page='3';
   static $tot_rows_in_page_other='1';
   static $core_col_range_start='D';
   static $M_col_range_end='I';
   static $M_col_range_start='C';


 // FIX  UPTO 13 PAPERS SHOW    // upto 10 papers & 3 hons papers (upt to 13 papers) [default setting]

   static $gutter ='1.75';
   static $core_col_range_end='M';
   static $H_col_range_start='N';
   static $H_col_range_end='P';

   static $total_with_hon_start='Q';
   static $total_with_hon_end='U';

   static $total_start='Q';
   static $total_end='U';



//extend core  range  even  with hons   // upto 11 core papers &   2 hons. papers (upt to 13 papers)[extended setting with hons]
   /*static $gutter ='1.75';

   static $core_col_range_end='N';
   static $H_col_range_start='O';
   static $H_col_range_end='P';

   static $total_with_hon_start='Q';
   static $total_with_hon_end='U';

   static $total_start='Q';
   static $total_end='U';
  */
   // end


   //extend core  range  even  without hons   // upt to 14 papers[extended setting without hons]

  /*static $gutter ='1.0';
    static $core_col_range_end='Q';
    static $H_col_range_start='O';
    static $H_col_range_end='Q';

   static $total_with_hon_start='R';
   static $total_with_hon_end='V';

    static $total_start='R';
    static $total_end='V';
 */
 // end

  static $curr_sem='6';
  static $start_session_yr_MIS='2015-2016';

  static $start_session_MIS='Monsoon';
  static $start_rel_carry_session_yr='2018-2019';
  static $start_rel_carry_session='Monsoon';

  static $start_project_discard_session='Monsoon';
  static $start_project_discard_session_yr='2018-2019';
  static $start_project_discard_exception=array('16je002020','16je002104','16je002391','15je001466','15je001002', '15je001288','16je002456','14je000294','16je002703','16je001929', '14je000370','16je002104','14je000072'
/*'15je001806',
'15je001813',
'15je001814',
'15je001822',
'15je001823',
'2013je1080'*/
);
  static $start_project_discard_sem_exception=array('3','5','4','6');

  static  $jrf_relative_start_session_yr='2018-2019';            // if grading for jrf is  relative  then true else false

  static  $shared_relative_heighest_session_yr='2018-2019';      // if grading for jrf is  relative  then true else false
  static  $shared_relative_heighest_session_yr_grade_sheet='2018-19';      // if grading for jrf is  relative  then true else false  work  for grade sheet  its same as shared_relative_heighest_session_yr


  static $start_sessional_marks_no_override_session_yr='2018-2019';

  static $minor_aggr_fail_status=false;

  static $cbcs_start='2019-2020';


   // to be changed to true or false , false means it will write in marks_foil table of database and true will not allow to write any thing
   static $freeze_spl=	false  ;
   static $freeze_other=false;
   static $freeze_reg=false ;
   static $freeze_espl=false;
  // end
   // to be changed to true or false , false means it will not check result dec validation ie, tabulation update is allowed. else vice versa
static  $result_dec_chk_spl_validation_permsn=true;
static  $result_dec_chk_espl_validation_permsn=true;
static  $result_dec_chk_reg_validation_permsn= true  ;//(1)//Default-true     **********************
static  $result_dec_chk_other_validation_permsn=true;




   static $Template_header1 = array(1=>'Sub Code' , 2=>'L-T-P' , 3=>'Cr. Hr.' , 4=>'Theory' , 5=>'Sessional' , 6=>'Total' ,7=>'Grade' , 8=>'Cr. Pts.', 9=>'Remark');
   static $Template_headerI = array(1=>'TCH' , 2=>'TCP' , 3=>'SGPA' , 4=>'CGPA' ,5=>'Remark');

   static $Template_header2 = array(1=>'Total Credit Hours' , 2=>'Total Credit Points' , 3=>'Grade Point Average' , 4=>'CGPA  /  Ranks  /  Position' ,5=>'Remark');
   static $Template_header5 = array(1=>'Cumm. Credit Hours' , 2=>'Cumm. Credit Points' );
   static $Template_header3 = array(1=>'No. of Candidates' , 2=>'Absent' , 3=>'Passed' , 4=>'Failed' ,5=>'Pass Percentage');
   static $Signature_header=  array(1=> 'Eligibility Verified by',2=>'Signature of Tabulator',3=>'Chairman Moderation Board', 4=>'For Chairman Examination Board');
   static $fixed_header = array(1=>'Result Moderated' , 2=>'Result Approved  and may be Published');



   static $cbcs_Template_header1 = array(1=>'Sub Code' , 2=>'L-T-P' , 3=>'Cr. Hr.' , 4=>'Total' ,5=>'Grade' , 6=>'Cr. Pts.');
   static $cbcs_Template_header2 = array(1=>'TCH' , 2=>'TCP' , 3=>'SGPA' , 4=>'CGPA' ,5=>'Remark');
   static $cbcs_Template_header5 = array(1=>'Cumm. CH' , 2=>'Cumm. CP' );


  static $hons_separately_show=false;


	static $mask_write_validation = false   ; //(1) //Default-false// need to true if write irrespective of a constraints  posed (update foils table  even no data changes  in particular  criteria fields of parent table[final_semwise_final_foil]),'TRUE' deals in the case  where child table  have had missing records however parent table has been written  perfectly
  //**************
    static $mask_final_status_remark_validation =false;//need to false if  cgpa need not to be updated

    static $mask_update_foil_master_reg = true; // always  true

	static $mask_write_validation_freeze= false; //(1) always  false(1) must be true if updation required aftrer result declaration to final_foil_freeze
   //******
// get def to show or not
static $mask_defaulter = true; // default:  true , false means it will  mask the dafualter and marks will come even after defaulter, true means it will not  mask defaulter
static $improvement_allowed =true ;         // set to true to improvemt write
static $failure_improvement_allowed =true ;         // set to true to improvemt write
static  $final_sem_overwrite_allowed=false;  //need to be set to true to overwrite final semester student
static  $hons_withdraw_allowed=true;



}
