

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



#################################### CATEGORY_BRAND_MODEL CLASS START #############################################



//-------------------------------------------------------------------------------------
/**
  *
  * @author    MIS sport team (Prem Sagar)
  * @copyright no copyright
  * It cover all the manipulation (fetching of data from category and brand table) for category and brand
  *
  */

class Category_brand_model extends CI_Model
{
	
	/**
	 * 
	 * Includes Class Constructor
	 * @param   none
	 * @return	void
	 *
	 */

	function __construct()
	{
		parent::__construct();
	}

	//------------------------------------------------------------------------------------------------

	/**
	 *
	 * It fetch all the brands for a given category from spo_brands table.
	 * @param   string 		$cat_code      holds category code
	 * @return	2-d assoc array            holds the brands information (i.e code and name for each brand) 
	 *
	 */

	public function get_brand_by_catcode($cat_code='')
	{	

		// fetching all brands for category code $cat_code
		$query=$this->db->select('code,name')
						->where('cat_code',$cat_code)
						->get('spo_brands');

		// if query executed successfully				
		if($query)
		{	
			// return assoc array for brands fetched
			return $query->result_array();
		}             	
	}

	//------------------------------------------------------------------------------------------------
	
	/**
	 *
	 * It fetch single category for a given category from spo_category table.
	 * @param   string 		$cat_code      holds category code
	 * @return	2-d assoc array            holds the category information (i.e code and name given category) 
	 *
	 */

    public function get_category_by_catcode($cat_code) 
    {	
    	// fetching category details for given category code
   		$query=$this->db->select('*')
						->where('code',$cat_code)
						->get('spo_category');

		// if query executed successfully				
		if($query)
		{	
			// return assoc array for category fetched
			return $query->result_array();
		} 
    }

    //------------------------------------------------------------------------------------------------
    
   /**
    *
    * It fetch category name for a given category code from spo_category table.
    * @param   string 		$cat_code      holds category code
    * @return	string           holds the category name
    *
    */

	public function get_catname_by_catcode($cat_code)
	{	
		// fetching category name for given category code
		$query=$this->db->select('name')
						->where('code',$cat_code)
						->get('spo_category');

		// if query executed successfully				
		if($query)
		{	
			// return category name
			return $query->result_array()[0]['name'];
		}   
	}

	//------------------------------------------------------------------------------------------------
	
	/**
	 *
	 * It fetch  category code for a given category name from spo_category table.
	 * @param   string 		$cat_code      holds category code
	 * @return	string         holds the category code
	 *
	 */

	public function get_catcode_by_catname($cat_name)
	{	
		// fetching category code for given category name
		$query=$this->db->select('code')
						->where('name',$cat_name)
						->get('spo_category');

		// if query executed successfully				
		if($query)
		{	
			// return category code
			return $query->result_array()[0]['code'];
		}   
	}

	//------------------------------------------------------------------------------------------------
	
	/**
	 *
	 * It fetch  single brand detail for a given brand name from spo_brands table.
	 * @param   string 		$brand_code      holds brand code
	 * @return	2-d assoc array              holds brand detail
	 *
	 */

	public function get_brands_by_brandcode($brand_code)
	{	
		// fetching brand detail from brand code
		$query=$this->db->select('*')
						->where('code',$brand_code)
						->get('spo_brands');

		// if query executed successfully				
		if($query)
		{	
			// return assoc array containing the brand details
			return $query->result_array();
		}
	}

	//------------------------------------------------------------------------------------------------
	
	/**
	 *
	 * It fetch all category detail for a given category code list from spo_category table.
	 * @param   array		$cat_code      holds list of category code
	 * @return	2-d assoc array            holds the category information (i.e code and name given category) 
	 *
	 */

	public function get_Category_by_cat_id_arr($cat_code=array())
	{	
		// if $cat_code is array and not empty
		if(is_array($cat_code)&&!empty($cat_code))
		{	
			// fetching category detail for each category code in $cat_code array.
			$query=$this->db->where_in('code',$cat_code)
							->get('spo_category');

		    // return 2-d assoc array containing  category details					
		    return($query->result_array());					
		}
	}

	//-------------------------------------------------------------------------------------------------------

}


#################################### CATEGORY_BRAND_MODEL CLASS END HERE #############################################