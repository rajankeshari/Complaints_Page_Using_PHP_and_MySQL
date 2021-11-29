<?php if(!defined("BASEPATH")){ exit("No direct script access allowed"); }

class marks_common_menu_model extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

/*	function getMenu()
	{
		$menu=array();
		
		$menu['exam_dr']['Common Grading'] = site_url('marks_common/common_sem');
		return $menu;
	}
*/
function getMenu() {
        $menu = array();
        $menu['exam_dr']['Common Grading']['Common'] = site_url('marks_common/common_sem');
        $menu['exam_dr']['Common Grading']['Shared Subjects'] = site_url('marks_common/shared_paper_grading');
		$menu['exam_da1']['Common Grading']['Shared Subjects'] = site_url('marks_common/shared_paper_grading');
		$menu['exam_tab']['Common Grading']['Shared Subjects'] = site_url('marks_common/shared_paper_grading');
        return $menu;
    }
}