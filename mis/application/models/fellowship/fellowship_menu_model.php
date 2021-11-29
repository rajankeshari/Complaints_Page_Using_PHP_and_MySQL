<?php
/* 
   Author @Ritu Raj
   Module: Menu List Creator
   Description: Menu list for fellowship module to be appeared for respective actors  
 */
class Fellowship_menu_model extends CI_Model
{
	function __construct()
	{	
		parent::__construct();
	}
	function getMenu()
	{
		$menu=array();	
		
		/* 24-10-2019
	 //$auth ==> dda        // Menu to be appeared  for actor "Dept. Dealing Asst."
		$menu['dept_da5']=array();
		$menu['dept_da5']["Fellowship"]['Fill Fellows Attd.']= site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/dept_da');
        $menu['dept_da5']["Fellowship"]['Assign Guide(Backlog Entry)']= site_url('fellowship/fellowshipProcess/get_fellow_list/dept_da');
        $menu['dept_da5']["Fellowship"]['DSC Preparation & Forwarding(Backlog Entry)'] = site_url('fellowship/fellowshipProcess/get_fgm_List/dept_da');          		
		//$auth ==> FIC     //  Menu to be appeared  for actor " Dept. Faculty In Charge"
		$menu['fic_jrf']=array();
		$menu['fic_jrf']["Fellowship"]['Verify Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/fic_jrf');      
        $menu['fic_jrf']["Fellowship"]['Assign Guide(Backlog Entry)']= site_url('fellowship/fellowshipProcess/get_fellow_list/fic_jrf');
        $menu['fic_jrf']["Fellowship"]['DSC Preparation & Forwarding(Backlog Entry)'] = site_url('fellowship/fellowshipProcess/get_fgm_List/fic_jrf');          		
                
        //$auth ==> Dean(associate dean A&R)      // Menu to be appeared  for actor "Dean"
		$menu['adar']=array();
		$menu['adar']["Fellowship"]=array();
		$menu['adar']["Fellowship"]['DSC Approval'] = site_url('fellowship/fellowshipProcess/get_fgm_List/adar');          		
                       
                
        //$auth ==> HOD      // Menu to be appeared  for actor "Head of Dept."
		$menu['hod']=array();
		$menu['hod']["Fellowship"]=array();
		$menu['hod']["Fellowship"]['Assign Guide'] = site_url('fellowship/fellowshipProcess/get_fellow_list');          		
        $menu['hod']["Fellowship"]['Forward DSC'] = site_url('fellowship/fellowshipProcess/get_fgm_List/hod');
        $menu['hod']["Fellowship"]['Approve Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/hod');          
             
                
        //$auth ==> GUIDE      // Menu to be appeared  for actor "Head of Dept."
		$menu['gd']=array();
		$menu['gd']["Fellowship"]=array();
		$menu['gd']["Fellowship"]['DSC Preparation & Forwarding'] = site_url('fellowship/fellowshipProcess/get_fgm_List/gd');          		
        $menu['gd']["Fellowship"]['Approve Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/gd');          		                
                
                //$auth ==> ACAD_DA5   //  Menu to be appeared  for actor "Academic Dealing Asst."
		$menu['acad_da5']=array();
		$menu['acad_da5']["Fellowship"]=array();
		$menu['acad_da5']["Fellowship"]['Forward Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/acad_da');          
                 //$auth ==> ACAD_AR   //  Menu to be appeared  for actor "Academic  Asst. Registrar"
		$menu['acad_ar']=array();
		$menu['acad_ar']["Fellowship"]=array();
		$menu['acad_ar']["Fellowship"]['Approve Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/acad_ar');  
		//$menu['acad_ar']["Fellowship"]['Assign Guide'] = site_url('fellowship/fellowshipProcess/get_fellow_list');  //by anuj
   
		//$auth ==> ACC_DA5   //  Menu to be appeared  for actor "Accounts Dealing Asst."
		$menu['acc_da5']=array();
		$menu['acc_da5']["Fellowship"]=array();
		$menu['acc_da5']["Fellowship"]['Forward Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/acc_da');          
        $menu['acc_da5']["Fellowship"]['Forward Fellows Bill'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_bill_list/acc_da'); 
        $menu['acc_da5']["Fellowship"]['View Forwarded Fellows Bill'] = site_url('fellowship/fellowshipProcess/view_final_fellow_bill/acc_da');  
        $menu['acc_da5']["Fellowship"]['Generate & Forward Pay-Order'] = site_url('fellowship/fellowshipProcess/generate_payorder/acc_da');
        $menu['acc_da5']["Fellowship"]['view Forwarded Pay-Order'] = site_url('fellowship/fellowshipProcess/payorder_generated_list/acc_da');
                
        //$auth ==> ACC_AR_GA   //  Menu to be appeared  for actor "Accounts  Asst. Registrar"
		$menu['acc_ar_ga']=array();
		$menu['acc_ar_ga']["Fellowship"]=array();
		$menu['acc_ar_ga']["Fellowship"]['Approve Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_list/acc_ar_ga');  
        $menu['acc_ar_ga']["Fellowship"]['Process Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_final_fellow_attd_list/acc_ar_ga');  
        $menu['acc_ar_ga']["Fellowship"]['View Forwarded Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_blocked_transfr_attd_list/acc_ar_ga');  
		$menu['acc_ar_ga']["Fellowship"]['Forward Fellows Bill'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_bill_list/acc_ar_ga');  
        $menu['acc_ar_ga']["Fellowship"]['View Forwarded Fellows Bill'] = site_url('fellowship/fellowshipProcess/view_final_fellow_bill/acc_ar_ga');
        $menu['acc_ar_ga']["Fellowship"]['Approve Pay-Order'] = site_url('fellowship/fellowshipProcess/generate_payorder/acc_ar_ga');  
        $menu['acc_ar_ga']["Fellowship"]['view Approved Pay-Order'] = site_url('fellowship/fellowshipProcess/payorder_generated_list/acc_ar_ga');  
                
          //$auth ==> ACC_DA4   //  Menu to be appeared  for actor "Accounts Dealing Asst.(Cash Section)"
		$menu['acc_da4']=array();
		$menu['acc_da4']["Fellowship"]=array();		
        $menu['acc_da4']["Fellowship"]['Fill Pay-Order'] = site_url('fellowship/fellowshipProcess/generate_payorder/acc_da4');
        $menu['acc_da4']["Fellowship"]['view Filled Pay-Order'] = site_url('fellowship/fellowshipProcess/payorder_generated_list/acc_da4');      
         //$auth ==> RG   //  Menu to be appeared  for actor "Registrar"
		$menu['rg']=array();
		$menu['rg']["Fellowship"]=array();
		//$menu['rg']["Fellowship"]['Approve Fellows Attd. List'] = site_url('fellowship/fellowshipProcess/get_final_fellow_attd_list/rg');  
        //$menu['rg']["Fellowship"]['View Approved Fellows Attd. List.'] = site_url('fellowship/fellowshipProcess/get_blocked_transfr_attd_list/rg');  
        $menu['rg']["Fellowship"]['Approve Fellowship Bill'] = site_url('fellowship/fellowshipProcess/get_eligible_fellow_bill_list/rg');  
        $menu['rg']["Fellowship"]['View Approved Fellows Bill'] = site_url('fellowship/fellowshipProcess/view_final_fellow_bill/rg');  
                */
		return $menu;
	}
}

