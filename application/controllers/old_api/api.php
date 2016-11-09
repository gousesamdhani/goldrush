<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
        
    function __construct()
    {
        parent::__construct();
        $this->load->model('users');
        $this->load->helper('url');
   
    } 
   
 

    /**
       * Screens in Android and iOS : Login
       * Method : POST
       * validates login details
       * @param $email, $password, $device_id, $device_token
       * @return $user_id, $first_name, $last_name, $email, $photo
    */
    function login_post()
        {

            /*
                Sent : user_id, first_name, last_name, email, photo
            */
            $email=trim($this->input->post('email'));
            $password=trim($this->input->post('password'));
            $device_id=trim($this->input->post('device_id'));
            $device_token=trim($this->input->post('device_token'));
            

            //checking for empty values START
            $validate_array=array('email'=>$email,'password'=>$password);
            
            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }
            //checking for empty values END
            //$user_session_data['timezone'] =($this->input->post('timezone')!="")?trim($this->input->post('timezone')):""; 


            $where_array=array('u.email'=>$email,'u.password'=>$password);
            $where_array['u.rec_status']=1;
            $user = $this->user->getUser( $where_array );
            //echo "<pre>";print_r(objectToArray($user));exit;
            if($user || is_array($user))
            {
                if(count($user)>0)
                {
                    $user_session_data['user_id']=$user->user_id;
                    //$user_session_data['device_token']=mysql_real_escape_string($device_token);
                    $usr_data = $this->user->AddUserSession( $user_session_data );
                    if($usr_data) {
                        $response_data=objectToArray($user);
                        $response_data['user_session_id']=$usr_data['user_session_id'];
                        //$response_data['status']=1;
                    
                        $this->response(array('status' => 1, 'message' => $response_data), 200); // 200 being the HTTP response code
                    }
                    else
                    {
                        $this->response(array('status'=>0,'error' => 'Operation Failed'), 404);
                    }
                }
                else
                {
                    $this->response(array('status'=>0,'error' => 'Invalid Email or Password'), 200);
                }
            }
            else
            {
               $this->response(array('status'=>0,'error' => 'Operation Failed'), 404);
            }
        }


    /**
       * sends email for resetting password
       * @param $email, $password, $device_id, $device_token
       * @return $user_id, $first_name, $last_name, $email, $photo
    */
    function sendEmail($email){

        $this->load->library('email');
        $this->email->from(SERVICE_EMAIL, 'Sureify Dashboard');
        $this->email->to($email);
        $this->email->subject('Sureify Password Reset');
        //$config['crlf'] = "\n";
        //$this->load->library('email');
        $message = 'We have reset your password as per your request!.<br>';
        $message .= '<b>Please find the credentials below to log in:</b><br>';
        $message .= '<div style="width:40%;"><b><hr></b><br/>';
        $message .= '<b>Email: </b><br/>';
        $message .= '<b>Password: </b><br/><br/>';
        $message .= '<b><hr></b><br/></div>';
        $message .= 'Thanks,<br/><br/>';
        $message .= '<i>Sureify Website</i><br/>';
        // $message .= '<a href="'.base_url().'"><img src="'.base_url().'assets/images/stanford_login_logo.png"></a><br>';
        $this->email->message($message);
        $this->email->set_newline("\r\n");
            
        $this->email->send();

        $this->response(array(
                    'status' => 1,
                    'message' => 'Email successfully sent') , 200);
            
    }

    /**
       * Screens in Android and iOS : Can't Login Screen
       * Method : POST
       * @param $email
       * @return void
    */
    function forgotpassword_post(){

        $email=trim($this->input->post('email'));
        $where_array['email'] = $email;
        //$emailfromModel = $this->api_model->getEmail($email);
        //$email = $emailfromModel[0];
        
        if(!$this->user->checkEmailExist($email))
        {
            $this->sendEmail($email);
            
        }else{
            $this->response(array(
                'status' => 0,
                'error' => "Enter valid email id") , 404);
            //        $this->response(array('status'=>0,'error' => $email), 404);
        }
    }


    /**
       * Screens in Android and iOS : Slide Menu
       * Method : POST
       * @param $user_session_id
       * @return void
    */
    function logout_post()
    {
            $user_data['user_session_id'] = trim($this->input->post('user_session_id'));
            $user_data['device_id'] = trim($this->input->post('device_id'));
            // checking for empty values START

            if (check_empty_values($user_data))
            {
                $this->response(array(
                    'status' => 0,
                    'error' => 'Some Fields are missing'
                ) , 400);
            }

            // checking for empty values END

            $user_logout = $this->user->updateUserSessionLogoutTime($user_data['user_session_id']);
            if ($user_logout)
            {
                $this->response(array(
                    'status' => 1,
                    'error' => 'Logged out Successfully'
                ) , 200); // 200 being the HTTP response code
            }
              else
            {
                $this->response(array(
                    'status' => 0,
                    'error' => 'Unable to logout, Please try again'
                ) , 404);
            }
    }

        
    function userdetails_get(){

        /*
            Sending all the useful data of the requested user 
            that is to be displayed on the screen.
            
            In Android and iOS : My Policy Page
            
            We will get the id of the user from the header
        */

        $data = getallheaders();
        if(!$data['user_id'])
            $this->response(array('status' => 0, 'error' => 'Invalid id'),200);

        //$this->response($data['id']);
        $where_array['u.id'] = $data['user_id'];
        //$this->response(get_headers('http://local.sureify.com/index.php/api/api/getuserdetails/id'));
        $this->response(array('status' => 1, 'message' => $this->user->getuser($where_array) ), 200);

    }

    /**
       * Screens in Android and iOS : My Profile
       * Method : POST
       * Updates user details
       * @param $location, $mobile
    */
    function edituser_post()
        {

            $user_id=trim($this->input->post('user_id'));
            $location=trim($this->input->post('location'));
            $mobile=trim($this->input->post('mobile'));  

            //checking for empty values START
            $validate_array=array('user_id'=>$user_id, 'location'=>$location,'mobile'=>$mobile);
            
            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }

            $where_array=$validate_array;
            $user = $this->user->updateProfileApi( $where_array );
            $this->response(array('status' => 1, 'message' => 'Data successfully updated'), 200);
            
        }

    function weights_get(){

        /*
            Sending all weights data of the requested user 
            that is to be displayed on the screen.
            
            In Android and iOS : Aria Scale Page
            
            We will get the id of the user from the header
        */

        $data = getallheaders();

        $current_date = date("d");
        $current_month = date("m");
        $current_year = date("Y");

        $data['month'] = $current_month;
        $data['year'] = $current_year;

        //$this->response($data, 200);

        if(!$data['user_id'] || !$data['month'] || !$data['year'])
            $this->response(array('status' => 0, 'error' => 'Some fields are missing'),200);

        $where_array = $data;
        $month_data = $this->user->getMonthWeightsApi($where_array);
        $weights = $month_data['month_weights'];
        $range = $month_data['range'];
        $previous_weight = $month_data['previous_weight'];

        $most_recent_weight = 0;
         $reverse_weights = array_reverse(array_splice($weights,0,$current_date));
         $weights_length = count($reverse_weights);
         for($k=0; $k<$weights_length; $k++)  {
            if($reverse_weights[$k]['weight'] != null){
                $most_recent_weight = $reverse_weights[$k]['weight'];
                break;
            }
         }
         if($most_recent_weight == 0){
            $res['most_recent_weight'] = $res['previous_weight'];
         }else{
            $res['most_recent_weight'] = $most_recent_weight;
         }


       // $most_recent_weight = $month_data['most_recent_weight'];

        //$this->response($month_data, 200);
        
        $this->response(array('status' => 1,  'previous_weight' => $previous_weight, 'most_recent_weight' => $most_recent_weight,'range' => $range, 'weights' => $reverse_weights), 200);

    }

    function steps_get(){

        /*
            Sending all steps data of the requested user 
            that is to be displayed on the screen.
            
            In Android and iOS : Charge HR Page
            
            We will get the id of the user from the header
        */


        $data = getallheaders();
        $current_date = date("d");
        $current_month = date("m");
        $current_year = date("Y");

        $data['month'] = $current_month;
        $data['year'] = $current_year;  

        if(!$data['user_id'] || !$data['month'] || !$data['year'])
            $this->response(array('status' => 0, 'error' => 'Some fields are missing'),200);

        $where_array = $data;
        $month_data = $this->user->getMonthStepsApi($where_array);
        $steps = $month_data['month_steps'];
        $range = $month_data['range'];
       // $this->response($month_data, 200);

        $current_date = date("d");

        $month_data_count = count($steps);
        
        $this->response(array('status' => 1, 'cheats' => $this->user->getCheats($where_array), 'days_to_go' => $month_data_count - $current_date,'steps' => array_reverse(array_splice($steps,0,$current_date)),'range' => $range), 200);


    }

    function savings_get(){
        /*
            Sending all the data to display graphs.

            In Android and iOS : Dashboard My Savings Page

        */

        $data = getallheaders();

        
        if(!$data['user_id'])
            $this->response(array( 'status' =>  0, 'message' => 'Some fields are missing'), 400);
        $user_data = $this->user->getUser(array('u.id'=>$data['user_id']));
        //echo "<pre>";print_r($user_data);exit;

        //get user savings monthly START
        $where_array = array('us.user_id'=>$data['user_id']);
        $group_by_array = array();
        $order_by_array = array(array('field'=>'us.year','sorting_order'=>'DESC'),array('field'=>'us.month','sorting_order'=>'DESC'));   
        $user_savings = $this->user->getUserSavings($where_array,$group_by_array,$order_by_array);
        //echo "<pre>";print_r($user_savings);exit;
        //get user savings monthly END
        
        $user_monthly_savings = getUserMonthlyPremium($user_data, $user_savings);
        $user_monthly_savings = array_slice(array_reverse($user_monthly_savings), null, 12);
        //echo "<pre>";print_r($user_monthly_savings);exit;

        $user_lifetime_savings=getUserLifetimeSavings($user_data, $user_savings); 
        //$xy['user_data'] = $user_data;
        $response_data['user_monthly_savings'] = $user_monthly_savings;
        $response_data['user_lifetime_savings'] = $user_lifetime_savings;
        $this->response(array('status' => 1, 'message' => $response_data) ,200);


    }

    function getactivity_get(){
        /*
            Sending all the data to display graphs.

            In Android and iOS : Dashboard Activity Page

        */
            
    }

    /**
     * Change user profile photo
     *
     * @return json
     */
    public function changephoto_post() {
        //$data = $_POST;

        $id=trim($this->input->post('user_id'));
        //echo "<pre>";print_r($data);
        $config['upload_path'] = './assets/uploadedfiles/profile_pictures/';
        $config['allowed_types'] = 'png|jpeg|jpg';
        $config['max_size'] = '5000000000';
        $this->load->library('upload', $config);
        //var_dump($_FILES);exit;
        $file_link = $_FILES['file']['name'];

        if ($file_link != '' && !$this->upload->do_upload('file')) {
            echo json_encode(array('success' => 0, 'msg' => 'Image uploading failed'));
        } else {
            //echo json_encode(array('success' => 1,'msg' => 'Profile photo changed'));
            $data = array('upload_data' => $this->upload->data());
            $zfile = $data['upload_data']['full_path']; // get file path
            chmod($zfile, 0777);
            $this->response($this->user->updatePhoto($data['upload_data']['file_name'], $id));
            if ($status == 1) {
                echo json_encode(array('success' => 1, 'msg' => 'Profile photo changed'));
            } else {
                echo json_encode(array('success' => 0, 'msg' => 'Change photo failed'));
            }
        }
    }

    /**
       * Screens in Android and iOS : Sync fitbit 
       * Method : POST
       * Sync user fitbit data
       * @param $user_id
       * @return $user_id, $first_name, $last_name, $email, $photo
    */
    function syncfitbit_post()
        {
            /*
                Sent : user_id
            */
            $user_id=trim($this->input->post('user_id'));
            
            //checking for empty values START
            $validate_array=array('user_id'=>$user_id);
            
            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }
            //checking for empty values END

            $get_user_data = $this->user->getUsers(array('u.id'=>$user_id));
            if(count($get_user_data) > 0)
            {
                $user_data = $get_user_data[0]; 
                //echo "<pre>";print_r($user_data);exit;
                $result=$this->sync->syncUserFitbit($user_data);
            }  

            if($result==1)
            {
               $status=1;
               $msg="Synced Successfully";
               $status_code="200";
            }
            else
            {
               $status=0;
               $msg="Operation Failed";
               $status_code="404";
            }   
     
          
            $this->response(array(
                    'status' => $status,
                    'error' => $msg
            ) , $status_code); 
            exit;

        }      



        /**
           * Screens in Android and iOS : get user added card 
           * Method : POST
           * Get User Credit Card Data
           * @param $user_id,$card_number,$zip_code,$expiry_month,$expiry_year,$security_code
           * @return $card_data
        */
        function getusercard_post()
        {   
            /*
                Sent : user_id
            */
            $user_id=trim($this->input->post('user_id'));
            
            //checking for empty values START
            $validate_array=array('user_id'=>$user_id);
            
            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }
            //checking for empty values END

            $card_data=array();

            $user_card_data = array();
            $user_card_data_array = $this->user->getUserCards(array('user_id' => $user_id));


            if( count($user_card_data_array) > 0 )
            {
                $user_card_data = $user_card_data_array[0]; 
                $stored_card_array=  json_decode($user_card_data['stripe_card_response'],true);
                $card_data['card_number'] = "************".$stored_card_array['last4'];
                $card_data['expiry_month'] = $stored_card_array['exp_month'];
                $card_data['expiry_year'] = $stored_card_array['exp_year'];
                $card_data['zip_code'] = $user_card_data['zip_code'];
                $card_data['security_code'] = "***";
                $status = 1;
                $msg = "";
            }
            else
            {
                $status = 1;
                $msg = "No Cards Added";
            } 

            $this->response(array(
                    'status' => $status,
                    'error' => $msg,
                    'card_data'=>$card_data
            ) , $status_code); 
            exit;   
        }

        /**
           * Screens in Android and iOS : get user added card 
           * Method : POST
           * save User Credit Card Data
           * @param $user_id,$card_number,$zip_code,$expiry_month,$expiry_year,$security_code
           * @return $card_data
        */
        function saveusercard_post()
        {
            /*
                Sent : user_id
            */
            $card_data['user_id'] = trim($this->input->post('user_id'));
            $card_data['card_number'] = trim($this->input->post('card_number'));
            $card_data['zip_code'] = trim($this->input->post('zip_code'));
            $card_data['expiry_month'] = trim($this->input->post('expiry_month'));
            $card_data['expiry_year'] = trim($this->input->post('expiry_year'));
            $card_data['security_code'] = trim($this->input->post('security_code'));
            $card_data['make_auto_payment'] = trim($this->input->post('make_auto_payment'));
            $user_id = $card_data['user_id'];
            //checking for empty values START
            //$validate_array=array('user_id'=>$user_id);
            $validate_array=$card_data;

            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }
            //checking for empty values END

            $return_status = $this->user->saveCard( $card_data , $user_id );
            $status_code=200;
            $stored_card_array=array();
       
            if( $return_status['status'] == 1 )
            {       
                    $user_card_data_array = $this->user->getUserCards(array('user_id' => $user_id));
                    //echo "<pre>";print_r($user_card_data_array);exit;

                    if( count($user_card_data_array) > 0 )
                    {
                        $user_card_data = $user_card_data_array[0]; 
                        
                        $stored_card_array=  json_decode($user_card_data['stripe_card_response'],true);
                        //echo "<pre>";print_r($user_card_data);exit;
                        $stored_card_array['make_auto_payment']=$user_card_data['make_auto_payment'];
                    }
                    else
                    {
                        $return_status['status'] = 0;
                        $return_status['msg'] = "Update failed, Please try again ";
                    }   
            } 
            
            $this->response(array("status"=>$return_status['status'],"msg"=>$return_status['msg'],"stored_card_array" => $stored_card_array)
                             , $status_code); 
            exit; 

        }



        /**
           * Screens in Android and iOS : payment api 
           * Method : POST
           * Pay using credit card
           * @param $user_id,$pay_amount
           * @return $card_data
        */
        function transferpayment_post()
        {
            $user_id = $this->input->post('user_id');
            $pay_amount = $this->input->post('pay_amount'); 
        
            $validate_array=array('user_id' => $user_id , 'pay_amount' => $pay_amount);

            if(check_empty_values($validate_array))
            {
                $this->response(array('status'=>0,'error' => 'Some Fields are missing'), 400);
            }
            //checking for empty values END

            $return_status=array("status" => 0 , "msg" => ""); 
            $status_code=200;

            $user_data = $this->user->getUser(array('u.id' => $user_id));
            if(count($user_data) == 0 )
            {
                $return_status=0;
                $return_msg="Specified User does not exist ";
            }  
            else {
                $stripe_customer_id= $user_data->stripe_customer_id;
                if( $stripe_customer_id != "" && $pay_amount > 0 )
                {
                        //echo $pay_amount;exit; 
                        $return_status=$this->stripeapi->makePayment( $stripe_customer_id , $pay_amount );
                        //echo "<pre>"; print_r( $return_status );exit;
                }    
            }

            $this->response(array(
                                "status"=>$return_status['status'],
                                "msg"=>$return_status['msg']
                                )
                             , $status_code); 
            exit;
        }    

}