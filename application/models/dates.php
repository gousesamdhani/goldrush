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
class Api_Model extends CI_Model {

	protected $users = "users";
	protected $app_users = "app_users";
	protected $user_weights = "user_weights";
	protected $user_steps = "user_steps";
	protected $user_sessions = "user_sessions";
	protected $goals = "goals";
	protected $veg_items = "veg_items";
	protected $veg_logging = "veg_logging";
	protected $veglogging_timings="veg_logging_timings";
	protected $challenges = "challenges";
	protected $challenge_vegitems = "challenge_vegitems";
	protected $user_pushed_challenges = "user_pushed_challenges";
	protected $user_challenges = "user_challenges";
	protected $user_challenge_vegitems = "user_challenge_vegitems";
	protected $user_points = "user_points";
	protected $user_favourites = "user_favourites";
	protected $notifications="notifications";
	protected $user_notifications="user_notifications";
	protected $advices_liked="advices_liked";
	protected $user_pushed_advices="user_pushed_advices";
	protected $leaderboard="leaderboard";
	protected $user_page_views="user_page_views";
	protected $leaderboard_confederates_data="leaderboard_confederates_data";
	protected $leaderboard_confederates_timeslots_data="leaderboard_confederates_timeslots_data";
    protected $subject_leaderboard_data="subject_leaderboard_data";
    protected $js_tools="js_tools";
    protected $subject_confederates_means="subject_confederates_means";
    protected $confederate_names="confederate_names";
    protected $user_confederates="user_confederates";
	/**
     * Construct
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */  
	function __construct() {
		parent::__construct();
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
    /**
     * Gets all app users
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getAllAppUsers()
    {
    	try {
 
	    	 $query =$this->db->query("select us.id as user_id,us.gender,us.consume_servings from users us ");
	        
	         //echo $this->db->last_query();exit;
	         if (!$query) {
			    throw new Exception();
			 }
	         $result=array();
			 $res = $query->result();
			 $result=objectToArray($res);
			 return $result;
		}
		catch (Exception $e)
		{
			echo $this->db->_error_message();exit;
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}
    }   
    /**
     * Get all users details
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getAllUsers()
    {
    	try {
 
	    	 $query =$this->db->query("select us.id, us.user_id, us.device_id, us.device_token from user_sessions us join (select MAX( id ) AS id	from user_sessions group by device_id) us1 ON us.id = us1.id order by us.id desc,us.user_id desc");
	        
	         //echo $this->db->last_query();exit;
	         if (!$query) {
			    throw new Exception();
			 }
	         $result=array();
			 $res = $query->result();
			 $result=objectToArray($res);
			 return $result;
		}
		catch (Exception $e)
		{
			echo $this->db->_error_message();exit;
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}
    }
    /**
     * Gets all user veg logg
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getAllUserVegLogging($where_array)
    {
    	 try{
			$this->db->select('user_id,veglog_date,SUM(servings_count) as servings');
			$this->db->from($this->veg_logging);
            if(count($where_array)>0) {
               $this->db->where($where_array);
            }	

			$this->db->group_by(array("user_id", "veglog_date"));
			$this->db->order_by('user_id asc, veglog_date desc'); 
			
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			 $result=array();
			 $res = $query->result();
			 $result=objectToArray($res);
			 return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}

    }

    /**
     * Get user data from app users table
      * @param  array $where_array where array
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getUserData($where_array)
    {
    	 try{
			$this->db->select('u.id,u.first_name,u.last_name,u.email,u.mobile,u.policy_id,u.policy_number,u.age,u.sex,u.height,u.weight,u.term_length,u.initial_premium_rate,u.photo,u.device_id,u.rec_status');
			$this->db->from($this->app_users.' as u');
            if(count($where_array)>0) {
               $this->db->where($where_array);
            }	

		
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			 $result=array();
			 $res = $query->row();
			 $result=objectToArray($res);
			 return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return $this->db->_error_message();
		    //echo "<pre>";print_r($e);exit;
		}

    }
    /**
     * Gets user weight data
      * @param  array $where_array where array
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getWeightsData($where_array)
    {
    	 try{
			$this->db->select('u.id,u.bmi,u.weight_date,u.weight_time,u.weight');
			$this->db->from($this->user_weights.' as u');
            if(count($where_array)>0) {
               $this->db->where($where_array);
            }	
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			 $result=array();
			 $res = $query->row();
			 $result=objectToArray($res);
			 return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return $this->db->_error_message();
		    //echo "<pre>";print_r($e);exit;
		}

    }
    /**
     * Gets user steps data
      * @param  array $where_array where array
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getStepsData($where_array)
    {
    	 try{
			$this->db->select('u.id,u.steps_date,u.steps');
			$this->db->from($this->user_steps.' as u');
            if(count($where_array)>0) {
               $this->db->where($where_array);
            }	
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			 $result=array();
			 $res = $query->row();
			 $result=objectToArray($res);
			 return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return $this->db->_error_message();
		    //echo "<pre>";print_r($e);exit;
		}

    }
    /**
     * Get email
     * @param  string $email email
     * @return object
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
   	public function getEmail($email) {

   		try {

   			//$where = "name='Joe' AND status='boss' OR status='active'";

   			//$where_or = "email='".$email."' OR policy_number='".$email."'";
   			$where_or = "email = '".$email."' OR policy_number = '".$email."'";

   			$this->db->select('email');
   			$this->db->from($this->app_users);
   			$this->db->where($where_or);
   			$query = $this->db->get();

   			if(!$query) {
			  throw new Exception();
			}
			//$result=array();
			$res = $query->row();
			//$result=objectToArray($res);
			return $res;
			
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return $this->db->_error_message();
		    //echo "<pre>";print_r($e);exit;
		}

   	}
   	 /**
     * Check user
     * @param  array $where_array where array
     * @param string $code code
     * @return object
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function checkUser($where_array='',$code='')
	{
	    try{
			$this->db->select('id as user_id,email,first_name,last_name,photo');
			$this->db->from($this->app_users);
			$this->db->where($where_array); 
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
			if(!$query) {
			  throw new Exception();
			}
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}
	
	}
	
	
	 /**
     * Checks user exits or not
      * @param  string $email email
     * @return object
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function checkUserExists($email)
	{
	    try{
			$this->db->select('id,name,email,username,created_date,modified_date');
			$this->db->from($this->users);
			
			$this->db->where('email', $email);
			$this->db->where('rec_status',1);
			
			$query = $this->db->get();
            if(!$query) {
			  throw new Exception();
			}
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
    	}
	}
///Method For Stanford code exist starts
	 /**
     * Checks standard code exits or not
      * @param  string $code code
     * @return object
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function checkCodeExists($code)
	{
	    try{
			$this->db->select('id');
			$this->db->from('stanford_codes');
			
			$this->db->where('code',$code); 
			
			$query = $this->db->get();
            if(!$query) {
			  throw new Exception();
			}
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
    	}
	}
	
///Method For Stanford code exist Ends	


///Method For Stanford code exist starts
	/**
     * Checks standard code for an user exits or not
      * @param  string $code code
     * @return object
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function checkCodeUserExists($code)
	{
	    try{
			
			$this->db->select('id');
			$this->db->from('users');
			
			$this->db->where('stanford_code',$code); 
			
			$query = $this->db->get();
            if(!$query) {
			  throw new Exception();
			}
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
    	}
	}
	
///Method For Stanford code exist Ends	
	/**
     * Add a new user
     * @param  array $user_data data array
     * @param  array $user_session_data session data array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function AddUser($user_data,$user_session_data)
	{
	     try {
                $this->db->trans_begin();
                $user_data['created_date']=date("Y-m-d H:i:s");
				$insert_user=$this->db->insert($this->users, $user_data);
				$user_id=$this->db->insert_id();
				$user_session_data['user_id']=$user_id;
				$user_session_data['login_time']=date("Y-m-d H:i:s");
				$insert_user_session=$this->db->insert($this->user_sessions, $user_session_data);
				$user_session_id=$this->db->insert_id();
				
				//echo $this->db->_error_message();
		        if ($this->db->trans_status() === FALSE)  {
		            $this->db->trans_rollback();
		            return 0;
		        } else {
		            $this->db->trans_commit();
		            return array('user_id'=>$user_id,'user_session_id'=>$user_session_id);
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	
	}
	
	/**
     * Add user session
     * @param  array $user_session_data session data array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function AddUserSession($user_session_data)
	{
	      try {
                $this->db->trans_begin();
                $user_session_data['login_time']=date("Y-m-d H:i:s");
				$insert_user_session=$this->db->insert($this->user_sessions, $user_session_data);
				$user_session_id=$this->db->insert_id();
				//echo $this->db->last_query();
		        if ($this->db->trans_status() === FALSE) {
		            $this->db->trans_rollback();
		            return 0;
		        } else {
		            $this->db->trans_commit();
		            return array('user_session_id'=>$user_session_id);
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	}
	
	/**
     * Checks standard code exits or not
     * @param  array $where_array where array
     * @param  int $datecheck flag
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function checkGoals($where_array,$datecheck="")
	{  
	    try
		{ 
		 	$this->db->select('id as goal_id,user_id,quantity,kinds,baseline,goal_date,created_date,modified_date');
			$this->db->from($this->goals);
			
			
            //$this->db->where($where_array); 
            if ($datecheck==1) {
              $this->db->where('goal_date',$where_array['goal_date']);
            } elseif ($datecheck==2) {
              $this->db->where('goal_date <=',$where_array['goal_date']);
            }  

            $this->db->where('user_id',$where_array['user_id']); 
            $this->db->order_by('goal_date','desc'); 
            $this->db->limit(1);

			
			$query = $this->db->get();
            if (!$query) {
			  throw new Exception();
			}
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		}
	}

	/**
     * Get user goals
     * @param  array $where_array where array
     * @param  string $dates goal date
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getUserGoals($where_array,$dates="")
	{  
	    try
		{ 
		 	$this->db->select('id as goal_id,user_id,quantity,kinds,baseline,goal_date,created_date,modified_date');
			$this->db->from($this->goals);
			
			$this->db->where($where_array); 
			if ($dates!="") {
			    $this->db->where_in('goal_date', $dates);
			}
			$query = $this->db->get();
            if (!$query) {
			  throw new Exception();
			}
			$result=array();
			$res = $query->result();
			$result=objectToArray($res);
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		}
	}
	

    public function getNotifications($where_array)
	{  
	    try
		{ 
		 	$this->db->select('id,notifications_name,notifications_text,created_date');
			$this->db->from($this->notifications);
			
			if (count($where_array)>0) {	
				$this->db->where($where_array); 
			}
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
            if (!$query) {
			  throw new Exception();
			}
			$result=array();
			$res = $query->result();
			$result=objectToArray($res);
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		}
	}

	/**
     * Gets user notifications
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function getUserNotifications($where_array)
	{  
	    try
		{ 
		 	$this->db->select('id,user_id,notification_id,notified_date');
			$this->db->from($this->user_notifications);
			
			if (count($where_array)>0) {	
				$this->db->where($where_array); 
			}
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
            if (!$query) {
			  throw new Exception();
			}
			$result=array();
			$res = $query->result();
			$result=objectToArray($res);
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		}
	}
	/**
     * Add notification to user
     * @param  array $insert_array insert array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function AddNotificationsUser($insert_array)
	{
	      try {
                $this->db->trans_begin();
                //$insert_user_session=$this->db->insert_batch($this->user_notifications, $insert_array);
				//$insert_id=$this->db->insert_id();
				if (count($insert_array)>0) {	
	                foreach ($insert_array as $insert_data) {
	                	$insert_data['created_date']=date("Y-m-d H:i:s"); 
	                    $insert_user_session=$this->db->insert($this->user_notifications, $insert_data);
	       				$insert_id=$this->db->insert_id();
	                }
                }	


		        if ($this->db->trans_status() === FALSE) {
		            $this->db->trans_rollback();
		            return 0;
		        } else {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	}
	/**
     * Gets average goal
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function getAverageGoal($where_array)
	{  
	    try
		{
			$this->db->select('user_id,ROUND(AVG(quantity)) AS average_quantity,ROUND(AVG(kinds)) AS average_kinds');
			$this->db->from($this->goals);
			$this->db->where($where_array); 
			$this->db->group_by('goal_date');
			$query = $this->db->get();
			if (!$query) {
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$result = $query->row();
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	/**
     * Insert goals data
     * @param  array $goal_data goal data array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function insert_goals($goal_data)
	{
	      try {
                $this->db->trans_begin();
                $goal_data['created_date']=date("Y-m-d H:i:s");
				$insert_user_session=$this->db->insert($this->goals, $goal_data);
				$last_insert_id=$this->db->insert_id();
				
		        if ($this->db->trans_status() === FALSE) {
		            $this->db->trans_rollback();
		            return 0;
		        } else {
		            $this->db->trans_commit();
		            return array('goal_id'=>$last_insert_id);
		        }
            } catch (Exception $e) {
			     log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	}
	
	/**
     * Update goals data
     * @param  array $goal_data goal data array
     * @param  int $record_id record id
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function update_goals($goal_data,$record_id)
	{   
	    
		try {
                $this->db->trans_begin();
				$this->db->where('id',$record_id);
                $this->db->set('baseline', 'baseline+1', FALSE);
				$goal_data['modified_date']=date("Y-m-d H:i:s");
				$update_goal=$this->db->update($this->goals, $goal_data);
								
		        if ($this->db->trans_status() === FALSE) {
		            $this->db->trans_rollback();
		            return 0;
		        } else {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	   
	
	  
	}
	/**
     * Get user challenges data
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function getUserChallengeIds($where_array)
	{
	    try
		{
			$this->db->select('uc.id as user_challenge_id,uc.user_id,uc.challenge_id,uc.challenge_date,uc.challenge_status');
			$this->db->from($this->user_challenges.' as uc');
			$this->db->where($where_array);
			$query = $this->db->get();
			if (!$query) {
			  throw new Exception();
			}
			$result=$query->result();
			$chl_ids=array();
			if (count($result)>0) {
			  foreach ($result as $r) {
				$chl_ids[]=$r->challenge_id;
			  }
			}
			return $chl_ids;
	
	    }
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	//get User Accepted Challenges START
	/**
     * Get user challenges
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function getUserChallenges($where_array)
	{  
	    try
		{
				$this->db->select('uc.id as user_challenge_id,uc.user_id,uc.challenge_id,uc.challenge_date,uc.challenge_status,ch.challenge_name,ch.default_challenge,ch.bonus_points,ch.time,ch.challenge_type,ch.items_compare,ch.kinds,ch.servings,ch.operator_type');
				$this->db->from($this->user_challenges.' as uc');
				$this->db->join($this->challenges.' as ch', 'uc.challenge_id = ch.id');
				$this->db->where($where_array);
				$query = $this->db->get();
				if (!$query) {
				  throw new Exception();
				}
				//echo $this->db->last_query();exit;
				$user_accepted_challenges=array();
				$user_accepted_challenges_data=$query->result();
				$user_accepted_challenges=objectToArray($user_accepted_challenges_data);
				
				
				
			    //get challenge default items start
				$this->db->select('uc.challenge_id,cvg.veg_item_id,cvg.quantity');
				$this->db->from($this->user_challenges.' as uc');
				$this->db->join($this->challenges.' as ch', 'uc.challenge_id = ch.id');
				$this->db->join($this->challenge_vegitems.' as cvg', 'uc.challenge_id = cvg.challenge_id','left');
				$this->db->where('ch.items_compare',1);
				$this->db->where($where_array);
				$query = $this->db->get();
				if (!$query) {
				  throw new Exception();
				}
				//echo $this->db->last_query();exit;
				$challenge_default_items=array();
				$challenge_default_items_data=$query->result();
				$challenge_default_items=objectToArray($challenge_default_items_data);
				//get challenge default items end
				
				
				//get challenge user items start
				$this->db->select('uc.challenge_id,cvi.veg_item_id,cvi.quantity');
				$this->db->from($this->user_challenges.' as uc');
				$this->db->join($this->challenges.' as ch', 'uc.challenge_id = ch.id');
				$this->db->join($this->challenge_vegitems.' as cvi','cvi.veg_item_id=uc.user_challenge_vegid AND cvi.challenge_id=uc.challenge_id','left');
				$this->db->where('ch.items_compare',2);
				$this->db->where($where_array);
				$query = $this->db->get();
				if (!$query) {
				  throw new Exception();
				}
				//echo $this->db->last_query();exit;
				$challenge_user_items=array();
				$challenge_user_items_data=$query->result();
				$challenge_user_items=objectToArray($challenge_user_items_data);
				//get challenge user items end
				
				
				if (count($user_accepted_challenges)>0) {
				   for ($i=0;$i<count($user_accepted_challenges);$i++) {
				      if (count($challenge_default_items)>0) {
					     foreach ($challenge_default_items as $cdi) {
						    if ($user_accepted_challenges[$i]['challenge_id']==$cdi['challenge_id'])
							$user_accepted_challenges[$i]['challenge_items'][]=$cdi;
						 }
					  }
					  if (count($challenge_user_items)>0) {
					     foreach ($challenge_user_items as $cui) {
						    if ($user_accepted_challenges[$i]['challenge_id']==$cui['challenge_id'])
							$user_accepted_challenges[$i]['challenge_items'][]=$cui;
						 }
					  }
				   }
				}
				
				/*echo "<pre>";print_r($user_accepted_challenges);
				echo "<pre>";print_r($challenge_default_items);
				echo "<pre>";print_r($challenge_user_items);exit;*/
				
				return $user_accepted_challenges;
				
		}
		catch (Exception $e)
		{
			 //echo $this->db->_error_message();exit;
		    log_message('error', $this->db->_error_message());
		    return false;
		   	//echo "<pre>";print_r($e);exit;
		}	
	}
	
	//get User Accepted Challenges END
	
	/**
     * Get user challenges item
     * @param  array $where_array where array
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function getUserChallengesItems($where_array)
	{  
	    try
		{
				$this->db->select('uc.id as user_challenge_id,uc.user_id,uc.challenge_id,uc.challenge_date,uc.challenge_status,ch.challenge_name,cvg.veg_item_id,cvg.quantity,ch.bonus_points');
				$this->db->from($this->user_challenges.' as uc');
				$this->db->join($this->challenges.' as ch', 'uc.challenge_id = ch.id');
				$this->db->join($this->challenge_vegitems.' as cvg', 'uc.challenge_id = cvg.challenge_id');
				
				$this->db->where($where_array);
				$query = $this->db->get();
				if (!$query) {
				  throw new Exception();
				}
				$num_rows=$query->num_rows();
				//echo $this->db->last_query();exit;
				$result=array();
				$res=$query->result();
				
				//echo "<pre>";print_r($res);exit;
				$ch_array=array(); 
					if (count($res)>0) {
						$result=objectToArray($res);
						$challenge_id=$result[0]['challenge_id'];
						$firstrun=1;
						$challenge_array=array();		
						foreach ($result as $ucg) {
						  if ($firstrun==1) {
							$challenge_array=array(
												'user_challenge_id'=>$ucg['user_challenge_id'],
												'user_id'=>$ucg['user_id'],
												'challenge_id'=>$ucg['challenge_id'],
												'challenge_name'=>$ucg['challenge_name'],
												'challenge_date'=>$ucg['challenge_date'],
												'challenge_status'=>$ucg['challenge_status'],
												'bonus_points'=>$ucg['bonus_points'],
												'challenge_items'=>array(array('veg_item_id'=>$ucg['veg_item_id'],'quantity'=>$ucg['quantity']))
											  );
						  } elseif ($challenge_id!=$ucg['challenge_id']) {
							  $ch_array[]=$challenge_array;
							  $challenge_array=array(
												'user_challenge_id'=>$ucg['user_challenge_id'],
												'user_id'=>$ucg['user_id'],
												'challenge_id'=>$ucg['challenge_id'],
												'challenge_name'=>$ucg['challenge_name'],
												'challenge_date'=>$ucg['challenge_date'],
												'challenge_status'=>$ucg['challenge_status'],
												'bonus_points'=>$ucg['bonus_points'],
												'challenge_items'=>array(array('veg_item_id'=>$ucg['veg_item_id'],'quantity'=>$ucg['quantity']))
											  );
								  
						   } else {
						   
							 $challenge_array['challenge_items'][]=array('veg_item_id'=>$ucg['veg_item_id'],'quantity'=>$ucg['quantity']);
							 
						   }
							
						  $challenge_id=$ucg['challenge_id'];
						  $firstrun=0;
						}
						$ch_array[]=$challenge_array;
					}
				 
				
				return $ch_array;
				
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	/**
     * Get all challenges
     * @param  array $where_array where array
     * @param  int $datecheck flag
     * @return array
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
	public function getAllChallenges($where_array)
	{
	   try 
	   {
			$this->db->select('ch.id as challenge_id,ch.challenge_name,ch.challenge_description,ch.image,ch.bonus_points,ch.default_challenge');
			$this->db->from($this->challenges.' as ch');
			$this->db->where($where_array); 
			
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=array();
			$res=$query->result();
			$result=objectToArray($res);
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	public function assignUserDefaultChallenges($user_id,$participated_date,$participated_date_time,$user_session_id)
	{
	    $where_array=array('uc.user_id'=>$user_id,'uc.challenge_date'=>$participated_date,'uc.rec_status'=>1);
	    $user_challenge_ids=$this->getUserChallengeIds($where_array); 
		$default_challenges=$this->getAllChallenges(array('ch.default_challenge'=>1,'ch.rec_status'=>1));
	    if(count($default_challenges)>0)
		{
		   foreach($default_challenges as $dch)
		   {
		       if(!in_array($dch['challenge_id'],$user_challenge_ids))
			   {
			       $user_challenge_data=array('user_id'=>$user_id,'challenge_id'=>$dch['challenge_id'],'challenge_date'=>$participated_date_time,'challenge_status'=>0,'user_session_id'=>$user_session_id);
				   $user_challenge_data['created_date']=date("Y-m-d H:i:s");
			       $this->db->insert($this->user_challenges, $user_challenge_data);
			   }
		   
		   }
		}
	
	}
	
    public function getVegLogging($where_array)
	{  
	    try
		{
			$this->db->select('vl.id as veglog_id,vl.user_id,vl.veg_item_id,vl.servings_count,vl.veglog_date,vl.user_session_id,vl.created_date,vl.modified_date,vi.image,vi.image_color');
			$this->db->from($this->veg_logging.' as vl');
			$this->db->join($this->veg_items.' as vi', 'vi.id=vl.veg_item_id', 'left');
			$this->db->where($where_array);
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$num_rows=$query->num_rows();
			
			$result=array();
			if($num_rows>0)
			{
			   //if($num_rows>1)
			   //{
			   $result=$query->result(); 
			   //}
			   //else
			   //{
			   //$result = $query->row();
			   //}
			}		
			
			return $result;
		}
		catch (Exception $e)
		{
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	
	public function getVegLoggingCount($where_array,$group_by="",$order_by="",$multi_array=0,$dates="")
	{  
	    try
		{
			$this->db->select('veglog_date,count(DISTINCT(vl.veg_item_id)) AS total_kinds,SUM(vl.servings_count) AS total_servings');
			$this->db->from($this->veg_logging.' as vl');
			$this->db->where($where_array);

			if($dates!="")
			{
			    $this->db->where_in('veglog_date', $dates);
			}

			if($group_by!="")
			{
			$this->db->group_by($group_by);
			}
			if($order_by!="")
			{
			$this->db->order_by($order_by['field'],$order_by['sorting_order']);
			} 		
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$num_rows=$query->num_rows();
			
			$result=array();
			if($num_rows>0)
			{
			   if($num_rows>1 || $multi_array==1)
			   {
			   $result=$query->result(); 
			   }
			   else
			   {
			   $result = $query->row();
			   }
			}		
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	public function getUserPoints($where_array)
	{
	    try
		{
			$this->db->select('id,user_id,category,points,participated_date');
			$this->db->from($this->user_points);
			$this->db->where($where_array);
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=array();
			$result=$query->result(); 
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	
	public function insertUserPoints($data)
	{
	     $insert_user_points=$this->db->insert_batch($this->user_points, $data);
		 //echo $this->db->last_query();exit;
	}
	
	public function updateUserPoints($data,$where_array)
	{
	     $this->db->where($where_array);
		 $data['modified_date']=date("Y-m-d H:i:s");
	     $update_user_points=$this->db->update($this->user_points, $data);
	}
	
	public function updateUserChallenge($data,$where_array)
	{
	     $this->db->where($where_array);
	     $data['challenge_status_datetime']=date("Y-m-d H:i:s");
		 $data['modified_date']=date("Y-m-d H:i:s");
	     $update_user_points=$this->db->update($this->user_challenges, $data);
	}


    public function addUserFavouriteItem($favourite_item_data)
	{
        try {
                $this->db->trans_begin();
                $user_favourite_veggies=$this->getUserFavourites(array('user_id'=>$favourite_item_data['user_id'],'rec_status'=>1));
				$this->db->insert($this->user_favourites, $favourite_item_data);
								
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}	

	}
	
	public function getUserFavourites($where_array)
	{
	    try
		{
			$this->db->select('id,user_id,veg_item_id,rec_status');
			$this->db->from($this->user_favourites);
			$this->db->where($where_array);
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=$query->result();
			$result_array=array();
			if(count($result)>0)
			{
			  foreach($result as $r)
			  {
				 $result_array[]=$r->veg_item_id;
			  }
			} 		
			return $result_array;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	public function addUserFavourites($user_id,$user_session_id,$input_veg_items)
	{
	    $user_favourite_veggies=$this->getUserFavourites(array('user_id'=>$user_id,'rec_status'=>1));
		$delete_favourites=array();
		$add_favourites=array();
	    if(count($input_veg_items)>0)
		{
		   foreach($input_veg_items as $ivg)
		   {
		      if(in_array($ivg['veg_id'],$user_favourite_veggies) && $ivg['favourite']==0)
		      {
			     $delete_favourites[]=$ivg['veg_id'];
			  }
			  elseif(!in_array($ivg['veg_id'],$user_favourite_veggies) && $ivg['favourite']==1)
			  {
			     $add_favourites[]=array('user_id'=>$user_id,'veg_item_id'=>$ivg['veg_id'],'user_session_id'=>$user_session_id,'created_date'=>date("Y-m-d H:i:s"));
			  }
		   }
		}
		
           //echo "<pre>";print_r($delete_favourites);
		   //echo "<pre>";print_r($add_favourites);exit;
		  if(count($delete_favourites)>0)
		  {
		      $delete_record=array('rec_status'=>0,'modified_date'=>date("Y-m-d H:i:s"));
		      $this->db->where('user_id', $user_id);
		      $this->db->where_in('veg_item_id', $delete_favourites);
			  
			  $this->db->update($this->user_favourites, $delete_record);
		  }
		  
		  if(count($add_favourites)>0)
		  {
		      $this->db->insert_batch($this->user_favourites, $add_favourites);
		  }
		  
	}


	public function insertVegloggingTimings($data)
	{
	     $insert=$this->db->insert($this->veglogging_timings, $data);
		 //echo $this->db->last_query();exit;
	}
	
   
    public function getVegloggingTimings($where_array)
	{
	    try
		{
			$this->db->select('DISTINCT(servings)');
			$this->db->from($this->veglogging_timings);
			$this->db->where($where_array);
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=array();
			$res=$query->result();

			if(count($res)>0)
			{
              foreach($res as $r)
              {
                 $result[]=$r->servings; 
              }
			}
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}


	public function save_veglogging($insert_array,$update_array,$acheived_challenges,$failed_challenges,$points_insert_data,$points_update_data,$user_id,$user_session_id,$input_veg_items,$vegloggingtiming_data){
	      
		  	try {
                $this->db->trans_begin();
				
				
    			/*echo "<pre><b>Insert Array:</b>";print_r($insert_array);
				echo "<pre><b>Update Array:</b>";print_r($update_array);
				echo "<pre><b>Achieved Challenges Array:</b>";print_r($acheived_challenges); 			
				echo "<pre><b>Failed Challenges Array:</b>";print_r($failed_challenges); 	
			    echo "<pre>Points Insert data<br/>";print_r($points_insert_data);		
				echo "<pre>Points Update data<br/>";print_r($points_update_data);exit;*/
				
				
				  //Add favourites START
				   $this->addUserFavourites($user_id,$user_session_id,$input_veg_items);
				  //Add favourites END
				
				  //Insert veg loggings START
				  if(count($insert_array)>0)
				  {
				  $insert_veglogging=$this->db->insert_batch($this->veg_logging, $insert_array);
				  }
				  //Insert veg loggings end

				  //Inserting veglogging timings with kinds and servings START
                  if(count($vegloggingtiming_data)>0)
				  {
					  $insert_vegloggingtiming= $this->insertVegloggingTimings($vegloggingtiming_data);
				  }
				  //Inserting veglogging timings with kinds and servings END
				  
				  //Update quantity of already existing vegloggings START
				  if(count($update_array)>0)
				  {
					  foreach($update_array as $update_data)
					  {
						 $this->db->where('id',$update_data['id']);
						 $update_data['update_data']['modified_date']=date("Y-m-d H:i:s");
						 $update_user_points=$this->db->update($this->veg_logging, $update_data['update_data']);
					  }
				  }
				  //Update quantity of already existing vegloggings END
				
				  
				  //Updating status of challenge when it is acheived START
				  if(count($acheived_challenges)>0)
				  {
				     foreach($acheived_challenges as $ach)
					 {
					    $ach_update_data=array('challenge_status' => 1);
					    $this->updateUserChallenge($ach_update_data,array('id'=>$ach['user_challenge_id']));
					 }
				  }
				  //Updating status of challenge when it is acheived END
				
				  
				  //Updating status of challenge when it is failed START
				  if(count($failed_challenges)>0)
				  {
				     foreach($failed_challenges as $fch)
					 {
					    $fch_update_data=array('challenge_status' => 0);
					    $this->updateUserChallenge($fch_update_data,array('id'=>$fch['user_challenge_id']));
					 }
				  
				  }
				  //Updating status of challenge when it is failed END
				  
				  
				  //Inserting points with category(veg,kind,challenge) START
                  if(count($points_insert_data)>0)
				  {
					   $this->insertUserPoints($points_insert_data);
				  }
				  //Inserting points with category(veg,kind,challenge) END
				  
				  //Updating points if category(veg,kind,challenge) exists START
                   if(count($points_update_data)>0)
				  {
					  foreach($points_update_data as $pdata)
					  {
						  $this->updateUserPoints($pdata['update_data'],array('id'=>$pdata['id']));
					  }
				  }
				  //Updating points if category(veg,kind,challenge) exists END
				  
								
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	}
	

	
	public function getAllVegItemsCount($user_id,$veglog_date)
	{
	    try
		{
			$this->db->select('vi.id,vi.item_title,vi.item_description,vi.image,vi.image_color,vi.points_quantity,vi.points_kind,vl.user_id,(CASE WHEN (vl.id IS NULL OR vl.id="") THEN 0 ELSE vl.id END) AS veglog_id,(CASE WHEN (vl.veg_item_id IS NULL OR vl.veg_item_id="") THEN 0 ELSE vl.veg_item_id END) AS veg_item_id,(CASE WHEN (vl.servings_count IS NULL OR vl.servings_count="")  THEN 0 ELSE vl.servings_count END) AS servings_count,vl.veglog_date,(CASE WHEN (fav.veg_item_id IS NULL OR fav.veg_item_id="")  THEN 0 ELSE 1 END) AS favourite');
			$this->db->from($this->veg_items.' as vi');
			$this->db->join($this->veg_logging.' as vl', 'vi.id=vl.veg_item_id AND vl.rec_status=1 AND  vl.veglog_date="'.$veglog_date.'" AND vl.user_id='.$user_id, 'left');
			$this->db->join($this->user_favourites.' as fav', 'vi.id=fav.veg_item_id AND fav.rec_status=1 AND fav.user_id='.$user_id, 'left');
			$this->db->where(array('vi.rec_status'=>1));
			 $this->db->order_by('favourite','DESC');
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=$query->result(); 
			return $result;
		}
		catch (Exception $e)
		{
		   log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}

    public function getVeglogsByDates($where_array,$group_by="",$order_by="",$dates="")
	{
	    try
		{
			$this->db->select('vl.user_id,vl.veg_item_id,vl.servings_count,vl.veglog_date');
			$this->db->from($this->veg_logging.' as vl');
			
			$this->db->where($where_array);
			if($dates!="")
			{
			    $this->db->where_in('vl.veglog_date', $dates);
			}
		    if($group_by!="")
			{
			$this->db->group_by($group_by);
			}
			if($order_by!="")
			{
			$this->db->order_by($order_by['field'],$order_by['sorting_order']);
			} 

			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=array();
			$res=$query->result();
			//echo $this->db->last_query();exit;
			$result=objectToArray($res); 
			return $result;
		}
		catch (Exception $e)
		{
		   log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}


   //get challenge status for past n days START
    public function getChallengesStatusByDates($where_array,$group_by="",$order_by="",$dates="")
	{
	    try
		{
			$this->db->select('MAX(uc.challenge_status) as attendance_challenge_status');
			$this->db->from($this->user_challenges.' as uc');
			
			$this->db->where($where_array);
			if($dates!="")
			{
			    $this->db->where_in('uc.challenge_date', $dates);
			}
		    if($group_by!="")
			{
			$this->db->group_by($group_by);
			}
			if($order_by!="")
			{
			$this->db->order_by($order_by['field'],$order_by['sorting_order']);
			} 

			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			$result=array();
			$res=$query->row();
			$attendance_challenge_status=($res->attendance_challenge_status>0)?$res->attendance_challenge_status:0;

			return $attendance_challenge_status;
		}
		catch (Exception $e)
		{
		   log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
    //get challenge status for past n days END

	
	public function getLeaderBoardUsers($where_array)
	{
	    try
		{
			$this->db->select('up.user_id, u.name, SUM( up.points ) AS tot_points');
			$this->db->from($this->user_points.' as up');
			$this->db->join($this->users.' as u', 'up.user_id = u.id', 'left');
			$this->db->where($where_array);
			$this->db->group_by('up.user_id');
			$this->db->order_by('tot_points', 'desc');
            $this->db->limit(10);			
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$result=$query->result(); 
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return 0;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	
	public function getAcheivedChallenges($where_array)
	{ 
	    try
		{
			$this->db->select('uc.user_id, count(uc.id) as acheived_challenges_count');
			$this->db->from($this->user_challenges.' as uc');
			$this->db->where($where_array);
			$this->db->group_by('uc.user_id');
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$result= $query->row(); 
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	public function getAcheivedChallengesPoints($where_array)
	{ 
	    try
		{
			$this->db->select('up.user_id,SUM( up.points ) AS tot_points');
			$this->db->from($this->user_points.' as up');
			$this->db->where($where_array);
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$result= $query->row(); 
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	
	public function getUserPointsBetweenDates($where_array,$group_by="",$order_by="",$limit="")
	{ 
	    try
		{
			$this->db->select('up.user_id,up.participated_date,SUM( up.points ) AS tot_points');
			$this->db->from($this->user_points.' as up');
			$this->db->where($where_array);
			if($group_by!="")
			{
			$this->db->group_by($group_by);
			}
			if($order_by!="")
			{
			$this->db->order_by($order_by['field'],$order_by['sorting_order']);
			} 
			if($limit!="")
			{
			$this->db->limit($limit);
			} 		
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;

			$result=$query->result();  
			return $result;
		}
		catch (Exception $e)
		{
		   log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}	
	}
	
	public function getUserDefaultChallenges($where_array,$data)
	{  
	    try
		{
			$this->db->select('ch.id,ch.challenge_name,ch.challenge_description,ch.image,ch.r_rgb,ch.g_rgb,ch.b_rgb,ch.time,ch.bonus_points,ch.default_challenge,(CASE WHEN uch.id IS NULL THEN 0 ELSE uch.id END) as user_challenge_id,(CASE WHEN uch.challenge_status IS NULL THEN 0 ELSE uch.challenge_status END) as user_challenge_status');
			$this->db->from($this->challenges.' as ch');
			$this->db->join($this->user_challenges.' as uch','ch.id=uch.challenge_id AND uch.user_id='.$data['user_id'].' AND uch.challenge_date="'.$data['veglog_date'].'"','left');
			$this->db->where($where_array);
				
			$query = $this->db->get();
			//echo $this->db->last_query();exit;
            if(!$query)
			{
			  throw new Exception();
			}
			$num_rows=$query->num_rows();
			
			if($num_rows>0)
			$result=$query->result(); 
			else
			$result=array();
			
			
			return $result;
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	
	
	   	
	}
	
	
	public function getChallengesbywhere($where_array,$group_by="",$order_by="",$limit="",$dates="")
	{  
	   try
	   {
			$this->db->select('ch.id,ch.challenge_name,ch.challenge_description,ch.time,ch.items_compare,(CASE WHEN ch.items_compare=2 THEN vi.image_color ELSE ch.image END) AS image,ch.r_rgb,ch.g_rgb,ch.b_rgb,vi.item_title as veg_item_title,cvi.quantity,ch.challenge_type,ch.items_compare,ch.bonus_points,ch.default_challenge,(CASE WHEN uch.id IS NULL THEN 0 ELSE uch.id END) as user_challenge_id,(CASE WHEN uch.challenge_status IS NULL THEN 0 ELSE uch.challenge_status END) as user_challenge_status');
			$this->db->from($this->challenges.' as ch');
			$this->db->join($this->user_challenges.' as uch','ch.id=uch.challenge_id','left');
			$this->db->join($this->veg_items.' as vi','vi.id=uch.user_challenge_vegid','left');
			$this->db->join($this->challenge_vegitems.' as cvi','cvi.veg_item_id=uch.user_challenge_vegid AND cvi.challenge_id=uch.challenge_id','left');
			
			$this->db->where($where_array);
			if($dates!="")
				{
				  $this->db->where_in('uch.challenge_date', $dates);
				}
			if($group_by!="")
			{
			$this->db->group_by($group_by);
			}
			if($order_by!="")
			{
			$this->db->order_by($order_by['field'],$order_by['sorting_order']);
			} 
			if($limit!="")
			{
			$this->db->limit($limit);
			} 		
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$num_rows=$query->num_rows();
			
			if($num_rows>0)
			$result=$query->result(); 
			else
			$result=array();
			
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	}
	
	public function getChallengeVegItems($where_array)
	{
	    try
	   {
			$this->db->select('cvi.challenge_id,cvi.veg_item_id,cvi.quantity,vi.item_title as veg_item_title,vi.image,vi.image_color');
			$this->db->from($this->challenge_vegitems.' as cvi');
			$this->db->join($this->veg_items.' as vi','cvi.veg_item_id=vi.id','left');
			$this->db->where($where_array);
			
			$query = $this->db->get();
			if(!$query)
			{
			  throw new Exception();
			}
			//echo $this->db->last_query();exit;
			$num_rows=$query->num_rows();
			
			if($num_rows>0)
			$result=$query->result(); 
			else
			$result=array();
			
			return $result;
		
		}
		catch (Exception $e)
		{
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo $this->db->_error_message();exit;
			//echo "<pre>";print_r($e);exit;
		}
	
	}
	
	
	public function getUserChallengeVegItems($where_array,$dates="")
	{
        try
		{
				$this->db->select('ucv.user_id,ucv.challenge_id,ucv.veg_item_id,vi.item_title as veg_item_title,vi.image,vi.image_color,cvi.quantity,ch.bonus_points');
				$this->db->from($this->user_challenge_vegitems.' as ucv');
				$this->db->join($this->veg_items.' as vi','ucv.veg_item_id=vi.id','left');
				$this->db->join($this->challenges.' as ch','ucv.challenge_id=ch.id','left');
				$this->db->join($this->challenge_vegitems.' as cvi','cvi.veg_item_id=ucv.veg_item_id AND cvi.challenge_id=ucv.challenge_id','left');
				$this->db->where($where_array);
				if($dates!="")
				{
				  $this->db->where_in('DATE_FORMAT(ucv.selected_date,"%Y-%m-%d")', $dates);
				}
				$query = $this->db->get();
				//echo "hi".$this->db->last_query();exit;
				if(!$query)
				{
				  throw new Exception();
				}
				
				
				$res=$query->row();
				$result=objectToArray($res);
				//echo "<pre>";print_r($res);exit;
				return  $result;
				
		}
		catch (Exception $e)
		{
			//echo $this->db->_error_message();exit; 
		    log_message('error', $this->db->_error_message());
		    return false;
		    //echo "<pre>";print_r($e);exit;
		}	 
     
	}
	
	public function insert_challengeFavouriteItem($favitem_data)
	{
	      try {
                $this->db->trans_begin();
       
				$insert=$this->db->insert($this->user_challenge_vegitems,$favitem_data);
				$last_insert_id=$this->db->insert_id();
				
				//echo "hi".$this->db->last_query();exit;
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            throw new Exception();
		            return false;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return true;
		        }
            } catch (Exception $e) {
			    //echo $this->db->_error_message();exit; 
			     log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return false;
        	}
	 
	}
	
	public function insert_dailyveg($data)
	{  
	    $result=array();
	    
	    $res= $this->db->insert_batch('tbl_daily_veg_list',$data);
		
    	return $res;
	}

	public function update_dailyveg($data,$id,$date)
	{  
	    $result=array();
	    $this->db->where('device_id', $id);
	    $this->db->where('created_at', $date);
	    $this->db->delete('tbl_daily_veg_list'); 

	    $res= $this->db->insert_batch('tbl_daily_veg_list',$data);
		
    	return $res;
	}
	
	public function getItems()
	{  
	    $result=array();
	    $this->db->select('id,item_name,item_image,created_at');
		$this->db->from('veg_items i');
				
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}
	

	public function getAdvices($where_array=array(),$user_id="",$random_advice_ids=array())
	{  
	    $result=array();
	    $this->db->select('a.id,a.advice_title,a.advice_description,a.created_date,(CASE WHEN al.rec_status IS NULL OR al.rec_status="" THEN 0 ELSE al.rec_status END) AS liked');
		$this->db->from('advice_cards as a');
		$this->db->join($this->advices_liked." as al","a.id=al.advice_id AND al.user_id=".$user_id, 'left');
		
        if(count($where_array)>0)
        {
			 $this->db->where($where_array);	
		}
		
        if(count($random_advice_ids)>0)
        {
			 $this->db->where_in('a.id',$random_advice_ids);	
		}

		//$this->db->order_by('a.id', 'RANDOM');
        //$this->db->limit(4);
		$query = $this->db->get();
        //echo $this->db->last_query();exit;
		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}



	public function getGoalsdate($deviceid,$sessionid,$date)
	{  
	    $result=array();
	    $this->db->select('*');
		$this->db->from('goals');
				$this->db->where('user_id', $deviceid); 
				$this->db->where('user_session_id', $sessionid); 
				$this->db->where('created_date', $date); 
				
				$this->db->limit(1);

		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}




	public function getGoalsById($id)
	{  
	    $result=array();
	    $this->db->select('*');
		$this->db->from('tbl_goals');
				$this->db->where('id', $id); 
				
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}


	public function getGoalsDevice($deviceid)
	{  
	    $result=array();
	    $this->db->select('id,user_id,quantity,kinds,base_line,created_at');
		$this->db->from('tbl_goals');
				$this->db->like('user_id', $deviceid, 'both'); 
				$this->db->order_by("id", "desc");
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}

	public function getChallenges()
	{  
	    $result=array();
	    $this->db->select('*');
		$this->db->from('tbl_challenges');
		$this->db->order_by('id', 'RANDOM');
				$this->db->limit(3);
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}

	public function updateUserSessionLogoutTime($user_session_id)
	{
     		try {
                $this->db->trans_begin();
				$this->db->where('id',$user_session_id);
				   $update_data['logout_time']=date("Y-m-d H:i:s");
				   $update_user_session=$this->db->update($this->user_sessions,$update_data);
								
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
                $this->db->trans_rollback();
                return 0;
        	}
	}

	public function insert_dailychallenge($data)
	{  
	    $result=array();
	   $res= $this->db->insert('tbl_user_challenges',$data);
		
    	return $res;
	}

	public function countUsers($device_id,$email)
	{  
	    $query = $this->db->get_where('users', array('user_id'=>$device_id,'username'=>$email));
$count = $query->num_rows(); 
		 
    	return $count;
	}


    public function insertUser($data)
	{  
	    $result=array();
	   $res= $this->db->insert('users',$data);
		
    	return $res;
	}




	public function daywiseveglist($table,$date,$device_id)
	{  
	    $result=array();
	    $query = $this->db
    ->select('v.id,v.item_name,v.item_image,dv.`count`,dv.`gid`,dv.`gid`,dv.`created_at`')
    ->from('veg_items AS v')
    ->join("tbl_daily_veg_list AS dv", "v.id=dv.veg_id AND dv.created_at='2014-08-25' AND dv.device_id='asdf23Df'", 'left')
    ->order_by('v.id','asc')
    ->get();

		foreach ($query->result() as $row)
		{
		$result[]=$row;
		}
		 
    	return $result;
	}


	public function countveglist($date,$deviceid)
	{  $this->db->select('*');
		$this->db->from('tbl_daily_veg_list');
		$this->db->where('created_at <=', $date); 
		$this->db->like('device_id', $deviceid, 'both'); 
	    $query = $this->db->get();

$count = $query->num_rows(); 
		 
    	return $count;
	}

	public function get_data_betweendates($where){
            //print_r($where);exit;
            $date=$where['veglog_date'];
            $format='"%Y-%m-%d"';
            $date_format="DATE_FORMAT( participated_date,'%Y-%m-%d')";
            $user_id=$where['user_id'];
            $query=$this->db->query("select user_id,hour(participated_date) participate_date_hour,DATE_FORMAT( participated_date, '%Y-%m-%d' ) participate_date,SUM(points) point from user_points where DATE_FORMAT( participated_date, '%Y-%m-%d' ) <= '".$date."' AND DATE_FORMAT( participated_date, '%Y-%m-%d' ) >= DATE_SUB('".$date."', INTERVAL 14 DAY ) AND user_id='".$user_id."' group by user_id,DATE_FORMAT( participated_date, '%Y-%m-%d' ) order by DATE_FORMAT( participated_date, '%Y-%m-%d' ) desc");
            /*$this->db->select("user_id,participated_date,SUM(points)");
            $this->db->from('user_points');
            $this->db->group_by("user_id,".$date_format);
            $this->db->where("DATE_FORMAT( participated_date, '%Y-%m-%d' ) < '".$date."'");
            $this->db->where("DATE_FORMAT( participated_date, '%Y-%m-%d' ) > DATE_SUB('".$date."', INTERVAL 14 DAY )");
            $this->db->where('user_id', $user_id);
            //$query = $this->db->get();  */
        //echo $this->db->last_query(); exit;
        $result=array(); 
        $res=$query->result();   
        $result=objectToArray($res);
         
        return $result;           
        }
       
        //old leader algorithm START 
        public function update_leaderboardtabledata($leaderboardtableupdate_data)
		{   
		    
			try {
	                $this->db->trans_begin();
	                
	                if(count($leaderboardtableupdate_data)>0)
	                {
	                    foreach($leaderboardtableupdate_data as $lbd)
	                    {
	                        $this->db->where('id',$lbd['id']);
	                    	$update=$this->db->update($this->leaderboard, $lbd['update_columns_data']);
	                    }

	                }
								
			        if ($this->db->trans_status() === FALSE)
			        {
			            $this->db->trans_rollback();
			            return 0;
			        }
			        else
			        {
			            $this->db->trans_commit();
			            return 1;
			        }
	            } catch (Exception $e) {
				    log_message('error', $this->db->_error_message());
	                $this->db->trans_rollback();
	                return 0;
	        	}
		   
		
		  
		} 



		public function getLeaderBoardData()
	    {
	    	try{
	 
		    	    $this->db->select();
					$this->db->from($this->leaderboard);
					$this->db->order_by('id asc'); 

					$query = $this->db->get();
					//echo $this->db->last_query();exit;
		            if(!$query)
					{
					  throw new Exception();
					}
					$result=array();
					$res = $query->result();
					$result=objectToArray($res);
					return $result;
			}
			catch (Exception $e)
			{
				echo $this->db->_error_message();exit;
			    log_message('error', $this->db->_error_message());
			    return false;
			    //echo "<pre>";print_r($e);exit;
			}
	    }

	    //get confederate means START
         public function getConfederatesMeans($mean_deviation_data,$confederates)
         {
             $confederates_means=array();
               
             $norm_data=$this->getNormalDistribution(count($confederates),$mean_deviation_data['grand_mean'],$mean_deviation_data['sd_across']);
	            for($i=1;$i<7;$i=$i+1)
	            {
	            	 $mk=$norm_data[$i];
	                 $confederates_means[$confederates[$i]]=(($mk<1)?1:$mk);
	            }	
                //echo "<pre>";print_r($confederates_means);
             //generate confederates scores
             return $confederates_means; 
          }
         //get confederate means end
	    //old leader algorithm END



	    //NEW LEADERBOARD ALGORITHM METHODS START
         
          //get all grand means and standard deviations START
          public function getAllGrandMeansAndDeviations()
		  {
				   $data_array=array();	 
				   $data_array[1]=array('servings'=>1,'grand_mean'=>3,'sd_across'=>1,'sd_within'=>1);
				   $data_array[2]=array('servings'=>2,'grand_mean'=>5,'sd_across'=>1,'sd_within'=>1);
				   $data_array[3]=array('servings'=>3,'grand_mean'=>7,'sd_across'=>2,'sd_within'=>1);
				   $data_array[4]=array('servings'=>4,'grand_mean'=>12,'sd_across'=>2,'sd_within'=>1);
				   $data_array[5]=array('servings'=>5,'grand_mean'=>17,'sd_across'=>2,'sd_within'=>1.5);
				   $data_array[6]=array('servings'=>6,'grand_mean'=>21,'sd_across'=>3,'sd_within'=>1.5);
				   $data_array[7]=array('servings'=>7,'grand_mean'=>24,'sd_across'=>3,'sd_within'=>1.5);
				   $data_array[8]=array('servings'=>8,'grand_mean'=>25,'sd_across'=>4,'sd_within'=>2);
				   $data_array[9]=array('servings'=>9,'grand_mean'=>26,'sd_across'=>5,'sd_within'=>2);
				   $data_array[10]=array('servings'=>10,'grand_mean'=>27,'sd_across'=>6,'sd_within'=>2);
				   return $data_array;
		  }  
	     //get all grand means and standard deviations END

         //get grand mean and deviation from the servings START
         public function getServingsGrandMeanDeviation($servings)
		 {
		 	 $data_array=$this->getAllGrandMeansAndDeviations();
             $servings_data=$data_array[$servings];
             //echo "<pre>";print_r($servings_data);
             return  $servings_data;
		 }
		 //get grand mean and deviation from the servings END

		 //normal distribution START
         public function getNormalDistribution($value,$mean,$standard_deviation)
         {
               $M=$value;
               $N=$mean;
               $O=$standard_deviation;
               $total_count=$value+1;  

               exec("Rscript norm.R $M $N $O",$response);
 
               $data_array=explode(" ",$response[0]);
               
               if(count($data_array)==$total_count)
               {    
                  //echo "<pre>";print_r($data_array);exit;
                  return $data_array;
               }
               else
               {
                   return $this->getNormalDistribution($value,$mean,$standard_deviation);
               }
               return $data_array;    
         } 
         //normal distribution END 


          //get subject confederates means START
          public function getSubjectConfederatesMeansData($user_id)
		    {
		    	try{
		 
			    	    $this->db->select('scm.id,scm.subject_user_id,scm.confederate,scm.mean');
						$this->db->from($this->subject_confederates_means.' scm');
						$this->db->where('scm.subject_user_id',$user_id); 
                       
						$query = $this->db->get();
						//echo $this->db->last_query();exit;
			            if(!$query)
						{
						  throw new Exception();
						}
						$result=array();
						$res = $query->result();
						$result=objectToArray($res);
						return $result;
				}
				catch (Exception $e)
				{
					echo $this->db->_error_message();exit;
				    log_message('error', $this->db->_error_message());
				    return false;
				    //echo "<pre>";print_r($e);exit;
				}
		    }

          //get subject confederates means END 
             

          //Add Subject confederates means START
		   public function AddSubjectConfederatesMeans($subject_user_id,$servings,$confederates_data)
		   {
		      try {
                      $mean_deviation_data=$this->getServingsGrandMeanDeviation($servings);     
                      $norm_data=$this->getNormalDistribution(count($confederates_data),$mean_deviation_data['grand_mean'],$mean_deviation_data['sd_across']); 
 	                   
                      $confederates_means=array();
                      for($i=1;$i<=count($confederates_data);$i++)
                      {
                         $confederates_means[]=array('subject_user_id'=>$subject_user_id,'confederate'=>$i,'mean'=>$norm_data[$i]);
                      }
                      //echo "<pre>";print_r($confederates_means);exit; 
	                  $this->db->trans_begin();
	                  $insert=$this->db->insert_batch($this->subject_confederates_means, $confederates_means);
					  //echo $this->db->last_query();exit;
					
				        if ($this->db->trans_status() === FALSE)
				        {
				            $this->db->trans_rollback();
				            return 0;
				        }
				        else
				        {
				            $this->db->trans_commit();
				            return 1;
				        }
	            } catch (Exception $e) {
				    log_message('error', $this->db->_error_message());
	                $this->db->trans_rollback();
	                return 0;
	        	}
		 
		   }  

          //get sd_within from mean START
          public function getSDwithinFromMean($mean)
          {
             $means_and_deviations=$this->getAllGrandMeansAndDeviations();
             foreach($means_and_deviations as $md)
             {
                if($mean<=$md['grand_mean'])
                return $md['sd_within'];
             }
             return 1;  
          }
          //get sd_within from mean END


          //get confederate end of the day scores START
          public function getConfederatesEodScores($confederates_means,$mean_deviation_data)
          {
             $sd_within=$mean_deviation_data['sd_within'];

             $confederates_eod_scores=array();

             foreach($confederates_means as $user=>$cm)
             {
               	//$norm_data=$this->getNormalDistribution(1,$cm,$sd_within); 
               	//$ek=$norm_data[1];
               	$ek=$cm+(rand(0,100)/100)*$sd_within;
                $confederates_eod_scores[$user]=(($ek<0)?0:(($ek>38)?38:$ek));  
             }
             //echo "<pre>";print_r($confederates_eod_scores);exit;	
             return $confederates_eod_scores;
         }
         //get confederate end of the day scores END


         //get timeslots hour probabilities START
         public function getTimeslotHourProbabilities()
         {
         	$timeslots=array('0.42','0.83','1.25','1.67','2.08','2.50','8.50','14.50','20.50','26.50','32.50','35.00','45.00','55.00','65.00','65.83','66.67','67.50','77.50','87.50','97.50','100.00','102.50','105.00');
            //$timeslots=array('0.42','0.83','1.25','1.67','2.08','2.50','4.00','5.50','7.00','8.50','10.00','20.00','30.00','40.00','50.00','51.67','53.33','55.00','68.33','81.67','95.00','96.67','98.33','100.00');
            return $timeslots;
         }
         //get timeslots hour probabilities END

         //get timeslots minutes probabilities START
         public function getTimeslotMinutesProbabilities()
         {
         	$minutes_probabilities=array();
         	$probability=0.016666667;
            for($i=0;$i<60;$i++)
            {
            	$key=($i<10)?'0'.$i:$i;
                $minutes_probabilities[$key]=$probability*100;
                $probability=$probability+0.016666667;
            }	
            return $minutes_probabilities;

         }
         //get timeslots minutes probabilities END


         //get random timeslots hours START
         public function getRandomTimeslotsHours($t,$veglog_hour="")
         { 
         	$random_timeslots=array();
         	$timeslots=$this->getTimeslotHourProbabilities();
         	for($i=0;$i<$t;$i++)
         	{
         	  /* if($i<5)
         	   {	
         	     $rand_value=rand(0,35);
               }
               elseif($i<10)
         	   {	
         	     $rand_value=rand(35,66);
               }
               elseif($i<15)
         	   {	
         	     $rand_value=rand(66,100);
               }*/
               $rand_value=rand(0,100);
               //echo $rand_value."<br/>"; 
         	   $found=0;	 
         	   foreach($timeslots as $key=>$value)
         	   {
         	     if($rand_value<=$value && $found==0)
         	     {
                    $found=1;
                    $slot=($key<10)?'0'.$key:$key;
                    $random_timeslots[]=$slot;	     
           	     }	
         	   }	
               
         	}
         	
         	sort($random_timeslots);
         	//echo "<pre>";print_r($random_timeslots);exit;
         	return $random_timeslots;	
         }

         //get random timeslots hours END

         //get random timeslots minutes START
         public function getRandomTimeslotsMins($t)
         { 
         	$random_timeslots=array();
         	$timeslots=$this->getTimeslotMinutesProbabilities();
         	//echo "<pre>";print_r($timeslots);exit;
         	for($i=0;$i<$t;$i++)
         	{
         	   $rand_value=rand(1,100);
         	   //echo $rand_value."<br/>"; 
         	   $found=0;	 
         	   foreach($timeslots as $key=>$value)
         	   {
         	     if($rand_value<=$value && $found==0)
         	     {
                    $found=1;
                    $random_timeslots[]=$key;	     
           	     }	
         	   }	

         	}
         	return $random_timeslots;	
         }

         //get random timeslots hours END 


         //get random timeslots START
         public function getTimeslots($t,$veglog_hour="")
         {
         	$timeslots=array();
            $random_timeslotshours=$this->getRandomTimeslotsHours($t);
            $random_timeslotsmins=$this->getRandomTimeslotsMins($t);
            for($i=0;$i<$t;$i++)
            {
               $timeslots[]=$random_timeslotshours[$i].":".$random_timeslotsmins[$i];
            }	

            return $timeslots;
         }
         //get random timeslots END

         //assign timeslots to confederates START
         public function assignConfederatesTimeslots($confederates,$t)
         {
         	  $new_confederates=array(); 
              $timeslots=$this->getTimeslots($t);
        

              for($i=0;$i<$t;$i++)
              {
                   $random_confederate=array_rand($confederates); 
                   $new_confederates[$random_confederate]['timeslots'][]=$timeslots[$i];
              }

              foreach($confederates as $key=>$c)
              {
                  $new_confederates[$key]['ek']=0;
                  $timeslot_count=0;
                  if(array_key_exists('timeslots',$new_confederates[$key]) && count($new_confederates[$key]['timeslots'])>0)
                  {
                     $new_confederates[$key]['ek']=$c;
                     $timeslot_count=count($new_confederates[$key]['timeslots']); 
                  }	
                  
                  if($timeslot_count>0)
                  {	
                  $new_confederates[$key]['increment']=$new_confederates[$key]['ek']/$timeslot_count;
                  }
                  else
                  {
                  $new_confederates[$key]['increment']=0;
                  }	
              }	
              //echo "<br><br><pre>";print_r($new_confederates);
              return $new_confederates; 
         } 
         //assign timeslots to confederates END


          //get confederates data START
         public function getConfederatesData($confederates_means,$mean_deviation_data,$veglog_hour="")
         {
         	 $confederates_eod_scores=$this->getConfederatesEodScores($confederates_means,$mean_deviation_data,$veglog_hour);
             //echo "<pre>";print_r($confederates_eod_scores);
             $k=6;
             $t=15;

             $timeslots=$this->getTimeslots($t);

             $conf_ts=$this->assignConfederatesTimeslots($confederates_eod_scores,$t,$veglog_hour);


             $vegpoints_greaterthan3=0;
             foreach($conf_ts as $ct)
             {
                 $breakfast_date_time=date("Y-m-d")." 11:00:00";
			     $breakfast_date_timestamp=strtotime($breakfast_date_time);
			     $timeslots_morning_count=0;
			     if(array_key_exists('timeslots',$ct) && count($ct['timeslots'])>0)
			     {	
				     foreach($ct['timeslots'] as $ts)
				     {
				         $datetime=date("Y-m-d")." ".$ts.":00";
				         $datetimestamp=strtotime($datetime);
				         if($datetimestamp<=$breakfast_date_timestamp)
	                     $timeslots_morning_count++;
				     }
			     }
                 $veg_points=$timeslots_morning_count*$ct['increment'];
                 if($veg_points>16)
                 {
                   $vegpoints_greaterthan3=1;
                 }  

              }	
              //echo "<pre>";print_r($conf_ts);exit;

              if($vegpoints_greaterthan3==1)
              {
               $conf_ts=$this->getConfederatesData($confederates_means,$mean_deviation_data);
              }	
             return $conf_ts;
         }
         //get confederates data END
   



	    //NEW LEADERBOARD ALGORITHM METHODS END



       //Capture User pushed challenges START
       public function AddUserPushedChallenges($data)
	   {
	      try {
                $this->db->trans_begin();
                $data['created_date']=date("Y-m-d H:i:s");
				$insert=$this->db->insert($this->user_pushed_challenges, $data);
				$insert_id=$this->db->insert_id();
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   }    


	   //get pushed advices START
	   public function getPushedAdvices($where_array=array(),$user_id="")
	   {  
		    $result=array();
		    $this->db->select('upd.advice_id');
			$this->db->from($this->user_pushed_advices." as upd");
			
	        if(count($where_array)>0)
	        {
				 $this->db->where($where_array);	
			}
			
	        $query = $this->db->get();
	        //echo $this->db->last_query();exit;
			foreach ($query->result() as $row)
			{
			$result[]=$row->advice_id;
			}
			 
	    	return $result;
	    }

       //capture user pushed advices START
	    public function AddUserPushedAdvices($data)
	   {
	      try {
                $this->db->trans_begin();
                $insert=$this->db->insert_batch($this->user_pushed_advices, $data);
				//echo $this->db->last_query();exit;
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   }  


	   //capture user page views START
	    public function AddUserPageViews($data)
	   {
	      try {
                $this->db->trans_begin();
                $insert=$this->db->insert($this->user_page_views, $data);
				//echo $this->db->last_query();exit;
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   } 

       
       //get leaderboard confederates data START
       public function getLeaderboardConfederatesData($where_array=array())
	   {  
		    $result=array();
		    $this->db->select('lcd.id,lcd.user_id,lcd.servings,lcd.confederate,lcd.ek_score,lcd.increment_value,lcd.captured_date,ltd.master_record_id,ltd.timeslot');
			$this->db->from($this->leaderboard_confederates_data." as lcd");
			$this->db->join($this->leaderboard_confederates_timeslots_data." as ltd","lcd.id=ltd.master_record_id","left");
						
	        if(count($where_array)>0)
	        {
				 $this->db->where($where_array);	
			}
			
	        $query = $this->db->get();
	        //echo $this->db->last_query();exit;
	        $result_data=$query->result();
			$result=objectToArray($result_data);
            //echo "<pre>";print_r($result);exit;   

            $result_formatted=array();
	        if(count($result)>0)
	        {
	        	$current_record=0;
                for($i=0;$i<count($result);$i++)
	        	{
	        		if($i==0 || $current_record!=$result[$i]['id'])
	        	  	{	
	                  $result_formatted[$result[$i]['id']]=array(
	                                          'id'=>$result[$i]['id'],
	                                          'user_id'=>$result[$i]['user_id'],
	                                          'servings'=>$result[$i]['servings'],
	                                          'confederate'=>$result[$i]['confederate'],
	                                          'ek_score'=>$result[$i]['ek_score'],
	                                          'increment'=>$result[$i]['increment_value'],
	                                          'captured_date'=>$result[$i]['captured_date'],
	                                          'master_record_id'=>$result[$i]['master_record_id']
	                 	              );
	                  if($result[$i]['timeslot']!="")
	                  {
	                  $result_formatted[$result[$i]['id']]['timeslots'][]=$result[$i]['timeslot'];
	                  }
	                }
	                else
	                {
                      $result_formatted[$result[$i]['id']]['timeslots'][]=$result[$i]['timeslot'];   
                    }
                    $current_record=$result[$i]['id']; 	               
	        	}	
	        }
	         
	        return $result_formatted;

	    }
       //get leaderboard confederates data END

	   //update leaderboard confederates data START
	   public function AddLeaderboardConfederatesData($confederates_data,$servings)
	   {
	      try {
                $this->db->trans_begin();

                if(count($confederates_data)>0)
                {
                	foreach($confederates_data as $cd)
                	{
                		$insert_confederates_data=array('user_id'=>$cd['user_id'],'servings'=>$cd['servings'],'confederate'=>$cd['confederate'],'ek_score'=>$cd['ek_score'],'increment_value'=>$cd['increment_value'],'captured_date'=>$cd['captured_date'],'created_date'=>date("Y-m-d H:i:s"));
                        $insert=$this->db->insert($this->leaderboard_confederates_data, $insert_confederates_data);
                        $last_insert_id=$this->db->insert_id();
                        if(array_key_exists('timeslots',$cd) && count($cd['timeslots'])>0)
                        {
                        	 foreach($cd['timeslots'] as $ts)
                        	 {
                        	 	$insert_confederates_timeslots_data=array('master_record_id'=>$last_insert_id,'timeslot'=>$ts,'created_date'=>date("Y-m-d H:i:s"));
                        	    $insert=$this->db->insert($this->leaderboard_confederates_timeslots_data, $insert_confederates_timeslots_data);
                        	 }	

                        }

                	}	
                }	

                //echo $this->db->last_query();exit;
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   }  


	   //capture leaderboard snapshot START
	    public function AddSubjectLeaderboard($data)
	   {
	      try {
                $this->db->trans_begin();
                $insert=$this->db->insert_batch($this->subject_leaderboard_data, $data);
				//echo $this->db->last_query();
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   } 


	  


	   //get js tools START
	   public function getJSTools($where_array=array())
	   {  
	   	  try {
			    $result=array();
			    $this->db->select('jst.tool_access_id');
				$this->db->from($this->js_tools." as jst");
				
		        if(count($where_array)>0)
		        {
					 $this->db->where($where_array);	
				}
				
		        $query = $this->db->get();
		        //echo $this->db->last_query();exit;
				/*foreach ($query->result() as $row)
				{
				$result[]=$row->tool_access_id;
				}*/

				$row_data=$query->row();
				$result=$row_data->tool_access_id; 
		    	return $result;
	    	 } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	    }


	    public function getRandomUserConfederates($where_array=array(),$random_ids=array())
		{  
		    try
			{ 
			 	$this->db->select('id,confederate_name');
				$this->db->from($this->confederate_names." as cn");
				
				   if(count($where_array)>0)
			       {
						 $this->db->where($where_array);	
				   }
					
			       if(count($random_ids)>0)
			       {
						 $this->db->where_in('cn.id',$random_ids);	
				   }
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
	            if(!$query)
				{
				  throw new Exception();
				}
				$result=array();
				$res = $query->result();
				$result=objectToArray($res);
				return $result;
			
			}
			catch (Exception $e)
			{
			    log_message('error', $this->db->_error_message());
			    return false;
			}
		}


		public function getUserConfederates($where_array=array(),$random_ids=array())
		{  
		    try
			{ 
			 	$this->db->select('confederate_name');
				$this->db->from($this->user_confederates." as uc");
				
				   if(count($where_array)>0)
			       {
						 $this->db->where($where_array);	
				   }
					
			     
				$query = $this->db->get();
				//echo $this->db->last_query();exit;
	            if(!$query)
				{
				  throw new Exception();
				}
				$result=array();
				$res = $query->result();
				$result=objectToArray($res);
				return $result;
			
			}
			catch (Exception $e)
			{
			    log_message('error', $this->db->_error_message());
			    return false;
			}
		}


	    public function AddUserConfederates($data)
	    {
	      try {
                $this->db->trans_begin();
                $insert=$this->db->insert_batch($this->user_confederates, $data);
				//echo $this->db->last_query();exit;
				
		        if ($this->db->trans_status() === FALSE)
		        {
		            $this->db->trans_rollback();
		            return 0;
		        }
		        else
		        {
		            $this->db->trans_commit();
		            return 1;
		        }
            } catch (Exception $e) {
			    log_message('error', $this->db->_error_message());
                $this->db->trans_rollback();
                return 0;
        	}
	 
	   } 

	   public function updateSubjectLeaderboardConfederates($data,$where_array)
	   {
		     $this->db->where($where_array);
			 $update=$this->db->update($this->subject_leaderboard_data, $data);
	   }

	   public function getDevicesNames() {
	   		try {
	   			$this->db->select("*");
	   			$this->db->from("devices_master");
	   			$query = $this->db->get();
	   			$result = $query->result_array();
	   			return $result;
	   		} catch (Exception $e) {
	   			log_message('error', $this->db->_error_message());
                return 0;
	   		}
	   }
}
