<?php
class Last_menuid_model extends CI_Model
{
	var $table = 'auth_menu_detail';
	function __construct()
	{
		parent::__construct();
	}
	// this function get the next menu id from the last_menu_id table
	function getmenu_id()
	{

		$query=$this->db->query("SELECT * FROM last_menu_id ");

		return $query->result()[1];
	}
// this function convert the menu id to number like A -> 1 ... AA -> 27 and return number +1 
	function num_to_letters($num, $uppercase = true) {
    $letters = '';
    while ($num > 0) {
        $code = ($num % 26 == 0) ? 26 : $num % 26;
        $letters .= chr($code + 64);
        $num = ($num - $code) / 26;
    }
    return ($uppercase) ? strtoupper(strrev($letters)) : strrev($letters);
}
// this function do the reverse process of num_to_letters
function letters_to_num($letters) {
    $num = 0;
    $arr = array_reverse(str_split($letters));

    for ($i = 0; $i < count($arr); $i++) {
        $num += (ord(strtolower($arr[$i])) - 96) * (pow(26,$i));
    }
    return $num + 1;
}
// it save the next menu id to the table
	function update_next_menu_ID($id)
	{
		$this->db->update('last_menu_id',array('next_menu_id'=>$id),array('sno' => 1));
		
	}
	function insert_menu($data)
	{
		$this->db->insert('menu_status',$data);
		if($this->db->affected_rows() >0)
		{
			$this->session->set_flashdata('flashSuccess','Record inserted ');
			redirect('auth_menu_details/auth_menu_details/insert_detail');
		}
		else
		{
			$this->session->set_flashdata('flashError','Record not inserted ');
			redirect('auth_menu_details/auth_menu_details/insert_detail');	
		}
	}
	// this function save the menu detail data filled in form of enter menu detail to the table auth_menu_detail
	function insert($data)
	{
		$this->db->insert('auth_menu_detail',$data);
		 if($this->db->affected_rows() > 0)
 		{
			$this->session->set_flashdata('flashSuccess','Record inserted ');
			 redirect('auth_menu_details/auth_menu_details/insert_detail');
		}
		else
		{
			$this->session->set_flashdata('flashError','Record not inserted ');
			redirect('auth_menu_details/auth_menu_details/insert_detail');	
		}
	}
	// this function give the all the menu detail entered under a particular authorization 
	function get_auth_menu($auth)
	{

		$query=$this->db->where('auth_id',$auth)->get($this->table);
		//$query=$this->db->query("SELECT * FROM auth_menu_detail where auth_id = $auth ");
		return $query->result();
	}
	// this function delete the menu from list on action of delete button
	function delete_auth_menu($menu_id)
	{
		$this->db->delete($this->table,array('menu_id'=>$menu_id));
	}
	function delete_menu($menu_id)
	{
		$this->db->delete('menu_status',array('menu_id'=>$menu_id));
	}
	function getdata()
	{
		$query=$this->db->query("SELECT * FROM menu_status");
		return $query->result();
	}
	// this function update the menu details 
	public function update_auth_menu($id, $submenu1,$submenu2,$submenu3=NULL,$submenu4=NULL,$link=NULL,$status ='Y')
	{    $data  = array(
               'submenu1' => $submenu1,
               'submenu2' => $submenu2,
               'submenu3' => $submenu3,
               'submenu4' => $submenu4,
               'link'     => $link,
               'status'  =>  $status
            );
	
		$query=$this->db->update($this->table, $data, array('menu_id' => $id));
		return $query ;
	}
}

?>