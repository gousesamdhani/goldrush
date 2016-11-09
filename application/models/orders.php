<?php
/**
 * Api_model Model
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    Api_Model.php
 * @package     Models
 * @author      Satya Raj.Ch <chsatyaraj93.ch@gmail.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/
 * @dateCreated 10/28/2015  MM/DD/YYYY
 * @dateUpdated 10/28/2015  MM/DD/YYYY 
 * @functions   01
 */
/**
 * Api_model.php
 *
 * @category Api_model.php
 * @package  Models
 * @author   Satya Raj.Ch <chsatyaraj93.ch@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     http://local.sureify.com/api_model
 */
class Orders extends CI_Model {

	protected $users = "users";
	protected $materials_master = "materials_master";
	protected $dates = "dates";
	protected $order_materials = "order_materials";
	protected $orders = "orders";
	protected $type_values = "type_values";	
	protected $user_sessions = "user_sessions";
	protected $states = "states";
	protected $districts = "districts";
	protected $area = "area";
	protected $cancelled = 2;

	/**
     * Construct
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */  
	function __construct() {
		parent::__construct();
		$this->load->model('materials_master');
		$this->load->database();
	}

	/**
     * Truncates leaderboard cofederates table data
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function truncateTables()
	{
		    $date=date('Y-m-d');
            $this->db->query('DELETE FROM leaderboard_confederates_data WHERE DATE_FORMAT(captured_date,"%Y-%m-%d")="'.$date.'"');
	        //$this->db->query('truncate table leaderboard_confederates_timeslots_data');
	        
    }
    public function getAllOrders($order_number = null, $where_array) {
    	try {
    		$this->db->select('o.id, o.order_number, user_id, state_id, district_id, area_id, address, deal_status, o.row_status, mm.name');
			$this->db->from($this->orders." o");
			$this->db->join('order_materials om', 'o.order_number=om.order_number', 'left');
			$this->db->join('materials_master mm', 'mm.id=om.material_id', 'left');
			if($order_number != null)
				$where_array['order_number'] = $order_number;
			$where_array['o.row_status'] = 1;
			if(count($where_array)>0) {
               $this->db->where($where_array);
            }
            $this->db->order_by("deal_status", "asc");
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			 $result=array();
			 $res = $query->result();
			 $result=objectToArray($res);
			 if(count($result)>0)
				 return $result;
			else 
				return false;	
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}
    }

    public function cancelOrder($order_number) {

    	try {
    		$order_data['deal_status'] = $this->cancelled;
    		$this->db->where('order_number', $order_number);
    		$this->db->update('orders', $order_data);
    		return true;
    	} catch ( Exception $e) {
    		return false;
    	}
    }

    public function insertOrder($order_data){
    	try {
    		$order_data['created_time'] = date('Y-m-d H:m:s');
    		$order_data['deal_status'] = 0;
    		$this->db->insert('orders', $order_data);
    		return true;
	    	
    	} catch ( Exception $e) {
    		return false;
    	}
    }

    public function updateOrder($where_array, $what_array){
    	try {

    		$this->db->where($where_array);
			$this->db->update($this->orders, $what_array); 
			return true;
		} catch(Exception $e){
			return false;
		}
    }


}
