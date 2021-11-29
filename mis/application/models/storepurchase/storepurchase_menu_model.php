

<?php

class  Storepurchase_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		// Assistant Registrat General Account
		$menu['acc_ar_ga']=array();
		$menu['acc_ar_ga']['Store Purchase']=array();
		$menu['acc_ar_ga']["Store Purchase"]["Dept. Budget"] = site_url('storepurchase/budget');
                
                //Dealing Assistant Account Section
                $menu['pns_da1']=array();
		$menu['pns_da1']['Store Purchase']=array();
            //    $menu['pns_da1']["Store Purchase"]["Budget"]["Dept. Budget"] = site_url('storepurchase/budget_da');
                $menu['pns_da1']["Store Purchase"]["Category"]["Category"] = site_url('storepurchase/add_category');
                $menu['pns_da1']["Store Purchase"]["Category"]["Sub-Category"] = site_url('storepurchase/add_sub_category');
               // $menu['pns_da1']["Store Purchase"]["Category"]["Sub-Category-Type"] = site_url('storepurchase/add_sub_sub_category');
                $menu['pns_da1']["Store Purchase"]["Item"]["Add Item"] = site_url('storepurchase/spadditem');
             //   $menu['pns_da1']["Store Purchase"]["Item"]["view Item All"] = site_url('storepurchase/view_item_master');
                $menu['pns_da1']["Store Purchase"]["Item"]["Issue Item"] = site_url('storepurchase/issue_item');
                $menu['pns_da1']["Store Purchase"]["Item"]["Return Issued Item"] = site_url('storepurchase/view_return_issued_item');
               //  $menu['pns_da1']["Store Purchase"]["Item"]["Add Item-1"] = site_url('storepurchase/spadditem1');
               // $menu['pns_da1']["Store Purchase"]["Item"]["Add Item Dept. Wise"] = site_url('storepurchase/spadditem_deptwise');
               // $menu['pns_da1']["Store Purchase"]["Mapping"]["Item-Supplier"] = site_url('storepurchase/item_supp_mapping');
                $menu['pns_da1']["Store Purchase"]["Mapping"]["Dept-Location"] = site_url('storepurchase/dept_location_mapping');
                $menu['pns_da1']["Store Purchase"]["Mapping"]["Category-Supplier"] = site_url('storepurchase/category_supp_mapping');
                
                $menu['pns_da1']["Store Purchase"]["Stock Register"]["Add"] = site_url('storepurchase/stock_register');
                $menu['pns_da1']["Store Purchase"]["Stock Register"]["View"] = site_url('storepurchase/view_stock_register');
                $menu['pns_da1']["Store Purchase"]["Stock Register"]["Edit"] = site_url('storepurchase/edit_stock_register');
                
                $menu['pns_da1']["Store Purchase"]["Supplier"]["Add Supplier"] = site_url('storepurchase/add_supplier');
                 $menu['pns_da1']["Store Purchase"]["Supplier"]["View Supplier"] = site_url('storepurchase/view_supplier_details');
                 $menu['pns_da1']["Store Purchase"]["Supplier"]["Edit Supplier"] = site_url('storepurchase/edit_supplier_details');
                 
                  $menu['pns_da1']["Store Purchase"]["Report"]["Item Wise"] = site_url('storepurchase/view_item_particular');
               //   $menu['pns_da1']["Store Purchase"]["Report"]["Dept Wise"] = site_url('storepurchase/view_item_particular_dept');
                  $menu['pns_da1']["Store Purchase"]["Report"]["Emp Wise"] = site_url('storepurchase/view_item_issued_emp');
                  
                  $menu['pns_da1']["Store Purchase"]["Report"]["Report All"] = site_url('storepurchase/report_single_page');
                
                
                //Employee (Intender person)
                //$menu['emp']=array();
		//$menu['emp']['Store Purchase']=array();
		//$menu['emp']["Store Purchase"]["Intend"] = site_url('storepurchase/intend');
                 
                  $menu['dept_pns']=array();
		  $menu['dept_pns']['Store Purchase']=array();
		 // $menu['dept_da5']["Store Purchase"]["Stock Register"] = site_url('storepurchase/stock_register');
                  $menu['dept_pns']["Store Purchase"]["Stock Register"]["Add"] = site_url('storepurchase/stock_register');
                  $menu['dept_pns']["Store Purchase"]["Stock Register"]["View"] = site_url('storepurchase/view_stock_register');
                  $menu['dept_pns']["Store Purchase"]["Mapping"]["Dept-Location"] = site_url('storepurchase/dept_location_mapping');
               
		
		return $menu;
	}
}
?>


