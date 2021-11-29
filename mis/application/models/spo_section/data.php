<?php

#################################### DATA CLASS #############################################
//-----------------------------------------------------------------------------------------

/**
*
* @copyright no copyright
* @author Prem Sagar
* This is a all purpose model for passing data to the views.
*
*/

class Data extends CI_Model{

	/**
	*The constructor calls the parent constructor and loads the basic model for help.
	* @param none
	* @return void
	*
	*/
	function __construct(){
		parent::__construct();
		$this->load->model("spo_section/basic");
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function gets data from supplier based on condition array
	* @param condition array 
	* @return the result object after the query is executed.

	*/
	public function getSupplier($cond=[]){
		$result = $this->basic->get("spo_supplier",$cond);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function inserts data to supplier 
	* @param data array 
	* @return boolean depicting whether the query executed perfectly or not.


	*/
	public function insertSupplier($data){
		$result = $this->basic->insert("spo_supplier",$data);
		return $result;
	}

	//-----------------------------------------------------------------------------------------

	/**
	*The function gets data from category based on condition array
	* @param condition array 
	* @return the result object after the query is executed.

	*/
	public function getCategory($cond=[]){
		$result = $this->basic->get("spo_category",$cond);
		return $result;
	}

	//-----------------------------------------------------------------------------------------

	/**
	*The function gets data from brand based on condition array
	* @param condition array 
	* @return the result object after the query is executed.

	*/
	public function getBrand($cond=[]){
		$result = $this->basic->get("spo_brands",$cond);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function gets data from item based on condition array
	* @param condition array 
	* @return the result object after the query is executed.

	*/
	public function getItem($cond=[]){
		$result = $this->basic->get("spo_items",$cond);

		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function inserts data to item 
	* @param data array 
	* @return boolean depicting whether the query executed perfectly or not.


	*/
	public function insertItem($data){
		$result = $this->basic->insert("spo_items",$data);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function updates item based on a particular ID
	* @param The data to be updated and the ID corresponding to which it is to be updated.
	* @return boolean whether the query completed perfectly.
	*/
	public function updateItem ($data,$id){
		$result = $this->basic->update('spo_items',$data,['id' => $id]);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function updates supplier based on a particular ID
	* @param The data to be updated and the ID corresponding to which it is to be updated.
	* @return boolean whether the query completed perfectly.
	*/
	public function updateSupplier ($data,$id){
		$result = $this->basic->update('spo_supplier',$data,['id' => $id]);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function deletes item based on a particular ID
	* @param The ID corresponding to which data is to be updated.
	* @return boolean whether the query completed perfectly.
	*/
	public function removeSupplier($id){
		 $res=$this->db->select('count(s_id) as total')->where('s_id',$id)->get('spo_stock')->result_array()[0]['total'];
		 if($res>0)
		 {
		 	$this->session->set_flashdata('flashInfo','Items have been purchased from this supplier, so cannot be removed');
			redirect(site_url('spo_section/supplier/view_supplier'));
		 }	
			$result = $this->basic->delete('spo_supplier',['id'	=>	$id]);
			return $result;

			
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function inserts stock to supplier 
	* @param data array 
	* @return boolean depicting whether the query executed perfectly or not.


	*/
	public function insertStock($data){
		$result = $this->basic->insert("spo_stock",$data);
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function is used to get data used for the purchase history of the stock purchase.
	* @param none
	* @return the result array containing the required data for the view.

	*/
	public function getPurchaseHistory(){
		$result = $this->basic->get("spo_stock");
		// prettyDump($result);
		foreach ($result as &$item) {
			//get items
			$cond = 
			[
				'id'	=>	$item['item_id'],
			];
			$item['item'] = $this->getItem($cond);
			//get supplier
			$cond = 
			[
				'id'	=>	$item['s_id'],
			];
			$item['supplier'] = $this->getSupplier($cond);
			//get Category
			$cond = 
			[
				'code'	=>	$item['item'][0]['cat_code'],
			];
			$item['category'] = $this->getCategory($cond);
			//get Brand
			$cond = 
			[
				'code'	=>	$item['item'][0]['brand_code'],
			];
			$item['brand'] = $this->getBrand($cond);
		}
		
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function for returning the data required the view of the new stock entry.
	* @param none
	* @return An assoc array containing all the categories, brands and suppliers available.
	*/
	public function getStockEntry(){
		$result['cat'] = $this->getCategory();
		$result['brand'] = $this->getBrand();
		$result['supp'] = $this->getSupplier();
		return $result;
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function returns the info of groups of which an admission number is part of.
	* @param Admission number
	* @return data of all the issues that were made in the group in which the admission number is in.
	*/
	public function getGroup($adm){
		$g_ids = $this->basic->get('spo_group_member',[
			'adm_no'	=>	$adm,
			'status'	=>	1,
		]);
		$inArr = [];
		foreach ($g_ids as $key) {
			$inArr[] = $key['group_id'];
		}
		$query = $this->db->where_in('group_id',$inArr)->get('spo_issue');
		$res = $query->result_array();
		// prettyDump($res);

	}
	//-----------------------------------------------------------------------------------------

	/**
	* This function returns data regarding all the issued items.
	* @param none
	* @return An assoc array containing the data regarding the issued items.
	*/
	public function get_all_issued_item($cond=array(),$wcond=array())
	{	
		$result=$this->db->select('S.group_id,S.adm_no,S.id,S.date_requested,C.code,C.name,concat(U.salutation," ",U.first_name," ",U.middle_name," ",U.last_name) as username',false)
		->from('spo_issue AS S')
		->where('S.status',0)
		->where($cond)
		->where_not_in('group_id',$wcond)
		->join('spo_items AS I', 'S.item_id = I.id', 'INNER')
		->join('spo_category AS C', 'C.code = I.cat_code', 'INNER')
		->join('user_details AS U','U.id=S.adm_no','INNER')
		->order_by('S.id')
		->get();
		// print_r($this->db->last_query());	
		return($result->result_array());				 		 
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function gets the data of items having a request id and having a status (issued,returned,etc) which is passed as a parameter.
	* @param The request ID and the status corresponding to the request ID.
	* @return An assoc array having the necessary data of the from issue table and names form category and brand.

	*/
	public function get_issued_item_by_reqid($req_id="",$status="")
	{
		if(!empty($req_id))
		{   
			$cond['S.id']=$req_id;
			if(!empty($status))
			{
				$cond['S.status']=$status;
			}
			// If the status variable is empty, then unset the $cond.
			$cond = array_filter($cond);


			$result=$this->db->select('S.item_id,S.group_id,S.quantity_issued,S.date_confirm,S.date_issue,S.adm_no,S.id,S.date_requested,S.quantity_requested,S.purpose,I.qty,C.code,I.brand_code,C.name,S.status,U.salutation,U.first_name,U.last_name,U.middle_name')
			->from('spo_issue AS S')
			->where($cond)
			->join('spo_items AS I', 'I.id = S.item_id', 'INNER')
			->join('spo_category AS C', 'C.code = I.cat_code', 'INNER')
			->join('user_details AS U ', 'U.id=S.adm_no','LEFT')
			->order_by('S.id')
			->get();
			$data = $result->result_array()[0];
			$data['username'] = $data['salutation']." ".$data['first_name']." ".$data['middle_name']." ".$data['last_name'];
			return $data;	
		}
		

	}

	public function get_issued_item_count($item_id='')
	{
		//print_r($item_id);
		if(!empty($item_id))
		{
			$res1=$this->db->select('sum(quantity_issued) as total',true)
							->where(['item_id'=>$item_id])
							->where_in('status',[1])
							->get('spo_issue')->result_array();
			$res2=$this->db->select('sum(quantity_requested) as total',true)
							->where(['item_id'=>$item_id])
							->where_in('status',[0])
							->get('spo_issue')->result_array();					
			
			
			if(!empty($res1[0]['total']) && !empty($res2[0]['total']))
			{
				return ($res1[0]['total']+$res[0]['total']);

			}
			else if(!empty($res1[0]['total']))
			{	
				return  $res1[0]['total'];
			}
		    else if(!empty($res2[0]['total']))	
		    {	
		    	return $res2[0]['total'];
		    }	
		}
		return 0;
	}

	public function get_approved_item_count($item_id='')
	{
		//print_r($item_id);
		if(!empty($item_id))
		{
			$res=$this->db->select('sum(quantity_issued) as total',true)
							->where(['item_id'=>$item_id])
							->where_in('status',[1])
							->get('spo_issue')->result_array();
		
			if(!empty($res[0]['total']))
				return $res[0]['total'];
		}
		return 0;
	}
	
	public function get_last_issue_detail($group_id='')
	{
		if(!empty($group_id))
		{
			$res=$this->db->select('adm_no,date_issue')->where(['group_id'=>$group_id,'status'=>3])->order_by('id','desc')->limit(1)->get('spo_issue')->result_array();
			if(count($res)>0)
			{
				return $res[0];
			}
		}
		return array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function gets the data of all the issued items issued by a particular admission number.
	* @param Admission number
	* @return An assoc array containing the relevant details of the issued items.

	*/
	public function get_all_issued_item_adm_no($adm)
	{	
		$result=$this->db->select('S.group_id,S.adm_no,S.remarks,S.id,S.date_requested,S.date_confirm,S.date_issue,C.code,C.name,I.name,S.quantity_issued,S.status')
		->from('spo_issue AS S')
		->where('S.adm_no',$adm)
		->join('spo_items AS I', 'S.item_id = I.id', 'INNER')
		->join('spo_category AS C', 'C.code = I.cat_code', 'INNER')
		->order_by('S.id')
		->get();	
		return($result->result_array());				 		 
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function returns the dues based on specific conditions like group ID or the admission number of a student.
	* @param Condition Array
	* @return An assoc array containing the relevant details of the dues.

	*/
	public function get_dues($cond)
	{	
		$result=$this->db->select('S.group_id,S.adm_no,C.code,C.name as cat_name,I.name,B.name as brand_name,S.dues,S.id')
		->from('spo_issue AS S')
		->where($cond)
		->join('spo_group_member as GM','GM.adm_no = S.adm_no')
		->join('spo_items AS I', 'S.item_id = I.id', 'INNER')
		->join('spo_category AS C', 'C.code = I.cat_code', 'INNER')
		->join('spo_brands AS B', 'I.brand_code = B.code','INNER')
		->order_by('S.id')
		->get();	
		return($result->result_array());				 		 
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to fetch the details of students and employees in order to show their names in the application.
	* @param Admission number or MIS ID of employee.
	* @return An assoc array containing the details of employee or student.

	*/
	public function get_user_detail($adm_no)
	{
		if(!empty($adm_no))
		{  
			// If the admission number is not an array, then convert to array (for multiple admission number support)
			if(!is_array($adm_no))
			{
				$adm_no=array($adm_no);
			}

			$query=$this->db->select('id,salutation,first_name,middle_name,last_name,photopath')
			->where_in('id',$adm_no)
			->get('user_details');
			return($query->result_array());					
		}
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function returns all the accepted items that have an issue ID. It serves the purpose to check whether the request with issue ID has been accepted or not. And if yes, get the details.
	* @param Issue ID
	* @return An assoc array containing the data of all the items that have issue ID if present.

	*/
	public function get_accepted_by_issueid($issue_id='')
	{
		if(!empty($issue_id)){
			$query = $this->db->select('ISS.id AS issue_id,ISS.adm_no,C.name,B.name,ISS.quantity_issued')
			->from('spo_issue AS ISS')
			->where([
				'ISS.status'	=>	1,
				'ISS.id'		=>	$issue_id,
			])
			->join('spo_items AS I','I.id = ISS.item_id','INNER')
			->join('spo_brands AS B','B.code = I.brand_code','INNER')
			->join('spo_brands AS C','C.code = I.cat_code','INNER')
			->get();
			// print_r($this->db->last_query());
			return($query->result_array());
		}
	}
	//-----------------------------------------------------------------------------------------

	/**
	*The function is to change the item quantity by passing the item ID and the quantity by which the quantity has to change.
	* @param Item ID and the quantity by which we have to change the quantity.
	* @return boolean depicting whether the query ran successfully or not.

	*/
	public function set_item_quantity($item_id="",$incr="")
	{
		if(!empty($item_id)&&!empty($incr))
		{
			// Check whether there are sufficient items in the stock.
			$qty_avail = $this->basic->get('spo_items',['id' => $item_id])[0]['qty'];
			if($qty_avail + $incr <0){
				return array("status"=>false);
			}

			// Update the table.
			$result=$this->db->set('qty','qty+'.$incr,FALSE)
			->where('id',$item_id)
			->update('spo_items');
			if($result)
			{
				return array("status"=>true);
			}		 
		}
		return array("status"=>false);
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function for getting details of issued items required for the view based on specific conditions.
	* @param Condition Array
	* @return Data regarding the items which have been issued 

	*/
	public function get_return_details($cond){
		$query = $this->db->select('ISS.adm_no, ISS.id AS issue_id,ISS.date_issue, C.name as cat_name,B.name as brand_name,ISS.quantity_issued,concat(U.salutation," ",U.first_name," ",U.middle_name," ",U.last_name) as username',false)
		->from('spo_issue AS ISS')
		->where([
			'ISS.status'	=>	3,
		])
		->where($cond)
		->join('spo_items AS I','I.id = ISS.item_id','INNER')
		->join('spo_brands AS B','B.code = I.brand_code','INNER')
		->join('spo_category AS C','C.code = I.cat_code','INNER')
		->join('user_details as U','U.id=ISS.adm_no','INNER')
		->get();
		 // print_r($this->db->last_query());
		return($query->result_array());

	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function which get details of groups which are either active or blocked
	* @param condition array.
	* @return returns the assoc array which contains information regarding such groups

	*/
	public function get_dissolve_details($cond=[])
	{
		if(!empty($cond)){
			$query = $this->db	->select('G.id,G.code,G.captain_adm_no as adm_no,C.name,C.code as cat_code,G.status')
			->from('spo_group_current as G')
			->where($cond)
			->where_in('status',array(1,4))
			->join('spo_category as C','C.code = G.cat_code')
			->get();

				//prettyDump($query->result_array()[0]);
			return($query->result_array()[0]);
		}
	}
	public function get_group_members($cond)
	{
		$result= $this->db->select('G.adm_no,concat(U.salutation," ",U.first_name," ",U.middle_name," ",U.last_name) as name',false)
		                ->from('spo_group_member as G')
		                ->where($cond)
		                ->join('user_details as U','U.id=G.adm_no','left')->get();
		                //print_r($this->db->last_query());
		   if($result)
		   	 return $result->result_array();
		   	return array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function which gets dues based on the group ID and additional conditions
	* @param group ID and optional additional conditions
	* @return An assoc array containing the details of the dues of the group

	*/
	public function get_group_dues($group_id="",$cond=[])
	{
		if(!empty($group_id)||$group_id===0){
			$query = $this->db	->select('ISS.adm_no,sum(ISS.quantity_issued) as dues,B.name,C.name as cat_name,C.code,ISS.group_id,ISS.id as issue_id,ISS.purpose')
			->from('spo_issue as ISS')
			->where([
				'ISS.group_id'	=>	$group_id,
				'ISS.status'	=>	3,
			])
			->where($cond)
			->join('spo_items as I','I.id = ISS.item_id')
			->join('spo_category as C','C.code = I.cat_code')
			->join('spo_brands as B','B.code = I.brand_code')
			->group_by('ISS.adm_no,ISS.item_id')
			->get();
				// prettyDump($query->result_array());
			return($query->result_array());
		}
	}
	//-----------------------------------------------------------------------------------------

	/**
	* The function which returns dues on the basis of admission number of the student
	* @param Admission  Number
	* @return An assoc array having the necessary data for displaying dues.

	*/
	public function get_adm_dues($adm_no="")
	{
		if(!empty($adm_no)){
			$query = $this->db	->select('ISS.group_id,C.code,sum(ISS.quantity_issued) as dues,B.name as brand_name,C.name as cat_name')
			->from('spo_issue as ISS')
			->where([
				'ISS.adm_no'	=>	$adm_no,
				'ISS.status'	=>	3,
			])
			->join('spo_items as I','I.id = ISS.item_id')
			->join('spo_category as C','C.code = I.cat_code')
			->join('spo_brands as B','B.code = I.brand_code')
			->group_by('ISS.item_id')
			->get();
				// prettyDump($query->result_array());
			return($query->result_array());
		}
	}

	//-----------------------------------------------------------------------------------------

	/**
	* A function which takes as input the values from SPO officer issue page.	
	* @param  The values from SPO officer issue page in form if an array
	* @return	void

	*/
	public function emp_req_item($values)
	{
		// Checking if sufficient items are present or not.
		$qty_avail = $this->basic->get('spo_items',['id'	=>	$values['item_id']])[0]['qty'];
		$qty = $values['quantity_requested'];

		if($qty_avail<$qty){
			$this->session->set_flashdata('flashWarning','There are not enough items');
			redirect(site_url('spo_section/employee'));
		}

		$this->db->trans_start();
		// Insert a new entry to issue table
		 $this->basic->insert('spo_issue',$values);
		


			if($this->db->trans_status()===FALSE){

				$this->db->trans_rollback();
				$this->session->set_flashdata('flashWarning','Please Try Again');
				redirect(site_url('spo_section/employee'));	

			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('flashSuccess','A Request for an Item has  been sent');
				redirect(site_url('spo_section/employee'));		
			}
	}

	//-----------------------------------------------------------------------------------------

	/**
	* A function which takes as input the values from SPO officer issue page.	
	* @param  The values from SPO officer issue page in form if an array
	* @return	void

	*/
	public function offi_issue_item($values)
	{
		// Checking if sufficient items are present or not.
		$qty_avail = $this->basic->get('spo_items',['id'	=>	$values['item_id']])[0]['qty'];
		$qty = $values['quantity_issued'];
		if($qty_avail<$qty){
			$this->session->set_flashdata('flashWarning','There are not enough items');
			redirect(site_url('spo_section/spo_officer/select_issue_item'));
		}

		// Insert a new entry to issue table
		$res = $this->basic->insert('spo_issue',$values);
		if($res){
			$itemRedData=
			[
				'qty'		=>	$qty_avail - $qty,
			];
			$cond = 
			[
				'id'	=>	$values['item_id'],
			];

			//update the quantity of items. 
			$res =$this->basic->update('spo_items',$itemRedData,$cond);

			$result=$this->db->where(['group_id'=>0])->order_by('id','desc')->limit(1)->get('spo_issue')->result_array()[0];

			if($res){
				$issue_id=$result['id'];
				$u_id=$result['adm_no'];
				$this->notification->notify($u_id,'emp','Sports Section','Sports item has been issued on your ID','','');
				$this->notification->notify($u_id,'stu','Sports Section','Sports item has been issued on your ID','','');
				$this->session->set_flashdata('flashInfo','Item can now be given');
				redirect(site_url('spo_section/spo_officer/print_receipt/'.$issue_id));				
			}
		}
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to get the item ID from category code and brand code
	* @param Category code and Brand code
	* @return Item ID

	*/
	public function get_item_id($cat_code='',$brand_code='')
	{
		$res = $this->basic->get('spo_items',[
			'cat_code'	=>	$cat_code,
			'brand_code'=>	$brand_code,
		]);
			//var_dump($this->db->last_query());
		return $res[0];
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to get the date for viewing the report of all issued items
	* @param none
	* @return All required data for viewing the report

	*/
	public function get_issue_report(){
		$result = $this->db
		->select('ISS.group_id,ISS.id as issue_id,ISS.status,ISS.adm_no,ISS.date_issue,R.return_date,B.name as brand_name,C.name as cat_name,ISS.purpose,ISS.remarks,ISS.quantity_issued,C.code')
		->from('spo_issue as ISS')
		->where_in('status',[0,1,2,3,4,5])
		->join('spo_items as I','ISS.item_id = I.id')
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->join('spo_return as R','ISS.id = R.issue_id','left')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to filter the data corresponding to the input provided in the form
	* @param Condition array
	* @return An assoc array containing the required data for viewing the report.

	*/
	public function filter_issue_report($cond=array())
	{
		$result = $this->db
		->select('ISS.group_id,ISS.id as issue_id,ISS.adm_no,ISS.status,ISS.date_issue,R.return_date,B.name as brand_name,C.name as cat_name,ISS.purpose,ISS.remarks,ISS.quantity_issued,C.code')
		->from('spo_issue as ISS')
		->where($cond)
		->where_in('status',[0,1,2,3,4,5])
		->join('spo_items as I','ISS.item_id = I.id')
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->join('spo_return as R','ISS.id = R.issue_id','left')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function for getting the information for making the report on the quantity of items available in the stock.
	* @param none
	* @return An assoc array containing all the data required for making the view.

	*/

	public function get_item_report()
	{
		$result=$this->db
		->select('I.id,I.qty,B.name as brand_name,C.name as cat_name')
		->from('spo_items I')
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to filter the data with respect to the inputs provided in the form of items report 
	* @param Condition array
	* @return An assoc array containing all the data required for making the view.

	*/
	public function filter_item_report($cond=array())
	{
		$result=$this->db
		->select('I.id,I.qty,B.name as brand_name,C.name as cat_name')
		->from('spo_items I')
		->where($cond)
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to get all the data required to make the report of all the stock entries in the database.
	* @param none
	* @return An assoc array that contains all the data required for making the view.

	*/
	public function get_stock_report(){
		$result = $this->db
		->select('ST.id,ST.date,S.gst_no,ST.indent_no,C.name as cat_name,B.name as brand_name,S.name as s_name,S.address,S.mobile,ST.rate,ST.unit,ST.gst,ST.total')
		->from('spo_stock as ST')
		->join('spo_items as I','ST.item_id = I.id')
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->join('spo_supplier as S','ST.s_id = S.id')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function that selectively filters the data for stock purchase report based on a condition array
	* @param A condition array
	* @return An assoc array that contains all the data required for making the view.
	*/
	public function filter_stock_report($cond)
	{
		$result = $this->db
		->select('ST.id,ST.date,S.gst_no,ST.indent_no,C.name as cat_name,B.name as brand_name,S.name as s_name,S.address,S.mobile,ST.rate,ST.unit,ST.gst,ST.total')
		->from('spo_stock as ST')
		->where($cond)
		->join('spo_items as I','ST.item_id = I.id')
		->join('spo_category as C','I.cat_code = C.code')
		->join('spo_brands as B','I.brand_code = B.code')
		->join('spo_supplier as S','ST.s_id = S.id')
		->get();
		return $result->result_array();
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function that selectively filters the data for group report based on a condition array
	* @param A condition array
	* @return An assoc array that contains all the data required for making the view.

	*/
	public function filter_group_report($cond='')
	{
		if(!empty($cond)){
			$query = $this->db
			->select('G.id,G.status,C.name,G.cat_code,G.captain_adm_no,G.create_date')
			->from('spo_group_current as G')
			->where($cond)
			->join('spo_group_member as M','M.group_id = G.id','left')
			->join('spo_category as C','G.cat_code = C.code')
			->get();
			return $query->result_array();
		}
	}
	//-----------------------------------------------------------------------------------------

	/**
	* A function to get all the data required to make the report of all the groups in the database.
	* @param none
	* @return An assoc array that contains all the data required for making the view.

	*/
	public function get_group_report()
	{
		$query = $this->db
		->select('G.id,G.status,C.name,G.cat_code,G.captain_adm_no,G.create_date')
		->from('spo_group_current as G')
		->join('spo_group_member as M','M.group_id = G.id and G.captain_adm_no=M.adm_no','left')
		->join('spo_category as C','G.cat_code = C.code')
		->get();
		return $query->result_array();
	}
	
	//-----------------------------------------------------------------------------------------

}
