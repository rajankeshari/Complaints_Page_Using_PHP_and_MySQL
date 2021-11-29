

<?php

class  Department_library_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	function getMenu()
	{
		$menu=array();

		
                //Dealing Assistant Account Section
                $menu['lib_da1']=array();
		//$menu['lib_da1']['Dept. Library']=array();
              //  $menu['lib_da1']["Dept. Library"]["Book"]["Add"] = site_url('department_library/add_book');
              //  $menu['lib_da1']["Dept. Library"]["Category"]["Sub-Category"] = site_url('storepurchase/add_sub_category');
             
               
               // $menu['lib_da1']["Book"]["Add"] = site_url('department_library/add_book');
                $menu['lib_da1']["Book"]["Add"] = site_url('department_library/add_book_new');
                $menu['lib_da1']["Book"]["View/Edit"] = site_url('department_library/view_book_new');
                $menu['lib_da1']["Book"]["Map"] = site_url('department_library/book_map');
                $menu['lib_da1']["Book"]["Issue"] = site_url('department_library/issue_book');
                $menu['lib_da1']["Book"]["Return"] = site_url('department_library/return_book');
             //     $menu['lib_da1']["Mapping"]["Dept-Subject"] = site_url('department_library/add_subject');
             //   $menu['lib_da1']["Mapping"]["Dept-Location"] = site_url('department_library/add_location');
                $menu['lib_da1']["Mapping"]["Dept-Subject"] = site_url('department_library/view_subject');
                $menu['lib_da1']["Mapping"]["Dept-Location"] = site_url('department_library/view_location');
                $menu['lib_da1']["Publication"]["View/Add"] = site_url('department_library/view_publication');
                $menu['lib_da1']["History"] = site_url('department_library/view_history');
                $menu['lib_da1']["No Dues"] = site_url('department_library/view_nodues');
                $menu['lib_da1']["Report"] = site_url('department_library/view_report');
                
                
                //$menu['emp']["Library-History"] = site_url('department_library/view_history_empstu');
              //  $menu['stu']["Library-History"] = site_url('department_library/view_history_empstu');
               
		
		return $menu;
	}
}
?>


