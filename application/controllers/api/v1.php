<?php
require (APPPATH . '/libraries/REST_Controller.php');
class v1 extends REST_Controller {
function __construct() {
	parent::__construct ();
	$this->load->model ( 'users' );
	$this->load->model ( 'sales' );
	$this->load->model ( 'stock' );
    $this->load->model ('user_sessions');
	$this->load->model ( 'materials_master' );
	$this->load->helper ( 'url' );
	$this->load->model ( 'states' );
	$this->load->model ( 'districts' );
	$this->load->model ( 'area' );
	$this->load->model ( 'orders' );
	$this->load->model('order_materials');

}

function getCurrentTime($operator, $hours, $minutes) {
	$timezone = '%%operator%%%%hours%% hours %%minutes%% minutes';
	$timezone = str_replace ( "%%operator%%", $operator, $timezone );
	$timezone = str_replace ( "%%hours%%", $hours, $timezone );
	$timezone = str_replace ( "%%minutes%%", $minutes, $timezone );
	date_default_timezone_set ( 'UTC' );
	$date = date ( 'Y-m-d H:i:s', strtotime ( $timezone ) );
	/*
	 * echo date_default_timezone_get();
	 * echo($date);exit();
	 */
	return $date;
}


function newAuth_post() {
	$phone = trim ( $this->input->post('phone'));
	$username = trim ( $this->input->post('username'));
	// $email = trim ( $this->input->post ( 'email' ) );
	$password = trim ( $this->input->post ( 'password' ) );
	//$device_id = trim ( $this->input->post ( 'device_id' ) );
	$user_type = trim ( $this->input->post('user_type'));
	$validate_array = array (
			//'email' => $email,
			'password' => $password,
			'username' => $username,
			'phone' => $phone
	);
	/*$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $validate_array
				), SUCCESS_OK );;exit;
	*/
	if (check_empty_values ( $validate_array )) {
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	}
	//$validate_array['device_id'] = $device_id;
	$validate_array['user_type'] = 2;
	$validate_array['created_at'] = date('Y-m-d H:m:i');
	//echo $this->users->AddUser($validate_array);
	$result = $this->users->AddUser($validate_array);
	if($result == false)
		$this->generateErrorMessage(BAD_REQUEST, "User already exists.");
	else
		$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $result
				), SUCCESS_OK );
	//echo $user_session_id;
}

function sales_post() {
    //capture data from post request
    //check whether access_toke is in header or body
    $headers = getallheaders();
    $access_token = trim( $headers['access_token'] );
	//$access_token = trim ( $this->input->post('access_token'));
        
 
    //check whether access_token is valid or not
    //if not raise badrequest error
    if(!$this->user_sessions->checkAccessTokenExist($access_token))
        $this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");

	//$sold_by = trim ( $this->input->post('sold_by')); //check this column contains user_id of sold_by merchant ensure this exist in db
        //if sold_by or sold_to values are not provided, get the values of each using email and phone of both
        //write two methods to get info of by and to

	$sold_to = trim ( $this->input->post('sold_to')); //check this colum contains user_id of sold_to merchant ensure this exist in db
	$quantity = trim ( $this->input->post('quantity'));
	$metal = trim ( $this->input->post ( 'metal' ) ); //check this column maps to groups table, ensure its record is already present
	$purity = trim ( $this->input->post ( 'purity' ) );
	$amount_paid = trim ( $this->input->post ( 'amount_paid' ) );
	$amount_pending = trim ( $this->input->post ( 'amount_pending' ) );
	$payment_type = trim ( $this->input->post ( 'payment_type' ) ); //check this column maps to group tables, ensure its existence
	$return_item = trim ( $this->input->post ( 'return_item' ) );
	
    // to reterive userid using phone or email, method is already written,so using that method
    //this can be done using access_token as well 
    //use your preferred method
    $phone = trim ( $this->input->post ( 'phone' ) );
	$email = trim ( $this->input->post ( 'email' ) );
    $access_array['access_token'] = $access_token;
    $access_array['email'] = $email;


    //construct an array with obtained fields and default fields
	$validate_array = array (
			'sold_to' => $sold_to,
			'quantity' => $qunatity,
			'metal' => $metal,
			'purity' => $purity,
			'amount_paid' => $amount_paid,
			'amount_pending' => $amount_pending,
			'payment_type' => $payment_type,
			'return_item' => $return_item
	);
	/*$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $validate_array
				), SUCCESS_OK );;exit;
	*/
	if (check_empty_values ( $validate_array )) {
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	}
	//$validate_array['device_id'] = $device_id;
	$validate_array['created_at'] = date('Y-m-d H:m:i');
	$validate_array['updated_at'] = date('Y-m-d H:m:i');
	$validate_array['row_status'] = 1;

        
	//echo $this->users->AddUser($validate_array);
        //call add_sale method present in sales file with validate_array and access_token as arguments
	$result = $this->sales->add_sale($validate_array, $access_token, $access_array);
	if($result == false)
		$this->generateErrorMessage(BAD_REQUEST, "Unable to add sale row");
	else
		$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $result
				), SUCCESS_OK );
	//echo $user_session_id;
}



function stock_post() {
        //capture data from post request
        //check whether access_toke is in header or body
        $headers = getallheaders();
        $access_token = trim( $headers['access_token'] );
        //$access_token = trim ( $this->input->post('access_token'));


        //check whether access_token is valid or not
        //if not raise badrequest error
        if($this->users->checkAccessTokenExist($access_token))
                $this->response ( array (
                                                'type' => 'states',
                                                'id' => 'id',
                                                'data' => $this->states->getAllStates()
                                ), SUCCESS_OK );
        else
                $this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
        

        $phone = trim ( $this->input->post ( 'phone' ) );
	$email = trim ( $this->input->post ( 'email' ) );
        $access_array['phone'] = $phone;
        $access_array['email'] = $email;

	$user_id = trim ( $this->input->post('user_id')); //check this colum contains user_id of sold_to merchant ensure this exist in db
	$stock = trim ( $this->input->post('stock'));
	$metal = trim ( $this->input->post ( 'metal' ) ); //check this column maps to groups table, ensure its record is already present
	$validate_array = array (
			'user_id' => $user_id,
			'stock' => $stock,
			'metal' => $metal,

        );
        if (check_empty_values ( $validate_array )) {
                $this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
        }


	$validate_array['created_at'] = date('Y-m-d H:m:i');

	$validate_array['updated_at'] = date('Y-m-d H:m:i');
	$validate_array['row_status'] = 1;

	$result = $this->stocks->add_stock($validate_array, $access_token,$access_array);
	if($result == false)
		$this->generateErrorMessage(BAD_REQUEST, "Unable to add stock row");
	else
		$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $result
				), SUCCESS_OK );
}






public function transact_post() {
	$headers = getallheaders();
	$access_token = trim( $headers['access_token'] );

	if($this->users->checkAccessTokenExist($access_token))
		$this->response ( array (
						'type' => 'states',
						'id' => 'id',
						'data' => $this->states->getAllStates()
				), SUCCESS_OK );
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");

}

public function doPurchase_post() {

}

public function getMaterials_get() {
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );

	if($this->users->checkAccessTokenExist($access_token))
		$this->response ( array (
						'type' => 'materials',
						'id' => 'id',
						'data' => $this->materials_master->getAllMaterials()
				), SUCCESS_OK );
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}


public function getStates_get(){
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );

	if($this->users->checkAccessTokenExist($access_token))
		$this->response ( array (
						'type' => 'states',
						'id' => 'id',
						'data' => $this->states->getAllStates()
				), SUCCESS_OK );
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}

public function getDistricts_get($state_id = null){
	//echo $state_id;exit;
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	//$state_id = trim($this->input->post('state_id'));
	if($this->users->checkAccessTokenExist($access_token))
		$this->response ( array (
						'type' => 'districts',
						'id' => 'id',
						'data' => $this->districts->getAllDistricts($state_id)
				), SUCCESS_OK );
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}

public function getAreas_get($state_id = null, $district_id = null){
	//echo $state_id.",".$district_id;
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	//$state_id = trim($this->input->post('state_id'));
	if($this->users->checkAccessTokenExist($access_token))
		$this->response ( array (
						'type' => 'area',
						'id' => 'id',
						'data' => $this->area->getAllAreas($state_id, $district_id)
				), SUCCESS_OK );
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}


public function orderDetails_get($order_id = null){
	//echo 1;exit;
	//echo $state_id.",".$district_id;
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	//$state_id = trim($this->input->post('state_id'));
	$user_id = $this->users->getUserIDFromAccessToken($access_token);
	if($this->users->checkAccessTokenExist($access_token)){
		//$where_array['user_id'] = $user_id;
		$res = $this->order_materials->getOrderMaterials($order_id);
		if($res){
			$this->response ( array (
							'type' => 'orders',
							'id' => 'id',
							'data' => $res
					), SUCCESS_OK );
		} else {
			$this->generateErrorMessage(BAD_REQUEST, "No Orders Found");
		}	
	}
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}

public function cancelOrder_get($order_id = null) {
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	$user_id = $this->users->getUserIDFromAccessToken($access_token);
	if($this->users->checkAccessTokenExist($access_token)){
		$res = $this->orders->cancelOrder($order_id);
		if($res){
			$this->response ( array (
							'type' => 'orders',
							'id' => 'id',
							'data' => $res
					), SUCCESS_OK );
		} else {
			$this->generateErrorMessage(BAD_REQUEST, "No Order Found");
		}	
	}
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}

public function orders_get($order_id = null){
	//echo $state_id.",".$district_id;
	$headers = getallheaders();
	print_r($headers);
	$access_token = trim( $headers['Accesstoken'] );
	//$state_id = trim($this->input->post('state_id'));
	$user_id = $this->users->getUserIDFromAccessToken($access_token);
	if($this->users->checkAccessTokenExist($access_token)){
		$where_array['user_id'] = $user_id;
		$res = $this->orders->getAllOrders($order_id, $where_array);
		if($res){
			$this->response ( array (
							'type' => 'orders',
							'id' => 'id',
							'data' => $this->shrink($res, 'order_number', 'name')
					), SUCCESS_OK );
		} else {
			$this->generateErrorMessage(BAD_REQUEST, "No Orders Found");
		}	
	}
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");

}

public function shrink($res, $primary_key, $field) {
	$o = [];
	$i=0;
	foreach ($res as $key => $jsons) { 
		$order_number = $jsons[$primary_key];
		if( array_key_exists($order_number, $hash) ) {
			$o[$hash[$order_number]][$field] = $o[$hash[$order_number]][$field]. ', '.$jsons[$field];
		} else {
			$o[] = $jsons;
			$hash[$order_number] = $i++;
		}		}
	return $o;
}

public function orders_post() {
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	$order_data['user_id'] = $this->users->getUserIDFromAccessToken($access_token);
	if($order_data['user_id'] == null)
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");

	//order_number, user_id, state_id, district_id, area_id, address, deal_status, user_session_id

	$order_data['user_session_id'] = $this->users->getUserSessionFromAccessToken($access_token);

	$order_data['state_id'] = trim ( $this->input->post('state_id'));
	$order_data['district_id'] = trim($this->input->post('district_id'));
	$order_data['area_id'] = trim($this->input->post('area_id'));
	
	foreach ($order_data as $key => $value) {
		# code...
		//echo $value.' ';
		$order_data['order_number'] = $order_data['order_number'].$value;
	}
	$order_data['order_number'] = 'OD'.strtoupper((substr($access_token, 0, 4))).$order_data['order_number'];
	$order_data['order_number'] = $order_data['order_number'].strtotime(date('Y-m-d H:m:s'));
	$order_data['address'] = trim($this->input->post('address'));

	//print_r($order_data);
	//exit;
	
	if(!$this->orders->insertOrder($order_data))
		$this->generateErrorMessage(BAD_REQUEST, "Something went wrong.");
		

	$order_materials_data['materials'] = trim($this->input->post('materials'));
	//'{"type":"steps","id":"id","data":{"steps":[{"steps":1111,"steps_date":"2015-11-21 12:17:33"}, {"steps":2222,"steps_date":"2015-11-22 12:17:33"}, {"steps":3333,"steps_date":"2015-11-23 12:17:33"}]}}';


	$materials_data = objectToArray(json_decode($order_materials_data["materials"]));
	
	//print_r($materials_data);
	$materials_in_data = array();
	$final_order_data = array();
	foreach($materials_data as $materials){
		//print_r($materials);
		//echo "string";
		$materials_in_data['order_number'] = $order_data['order_number'];
		$materials_in_data["material_id"] = $materials["material_id"];
		$materials_in_data["material_qty"] = $materials["material_qty"];
		$materials_in_data['user_session_id'] = $order_data['user_session_id'];
		$materials_in_data["price"] = $materials["price"];
		$materials_in_data["created_time"] = date('Y-m-d H:m:s');
		array_push($final_order_data, $materials_in_data);
	}

	#print_r($final_order_data);exit;
	
	if(!$this->order_materials->insertOrderMaterials($final_order_data)) {
		$where_array['order_number'] = $order_data['order_number'];
		$what_array['row_status'] = 0;
		$this->orders->updateOrder($where_array, $what_array);
		$this->order_materials->updateOrderMaterials($where_array, $what_array);
		$this->generateErrorMessage(BAD_REQUEST, "Something went wrong.");

	}
	else {
		$this->response ( array (
						'type' => 'orders',
						'id' => 'id',
						'data' => array('order_number' => $order_data['order_number']) 
				), SUCCESS_OK );
	}
}

function sendMail($from, $to, $sub, $msg) {
    try {
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
        $mail->isHTML(true);
            $mail->Host         = "localhost";
            $mail->WordWrap     = 50;
            $mail->SMTPAuth     = true;
            $mail->SMTPSecure   = "ssl";
            $mail->Port         = 465;
            $mail->Username     = $from; //"order-confirm@scrapout.in";
            $mail->Password     = "avNk@SO92#";
            $mail->Subject      = $sub;
            $mail->SMTPDebug = 1;
            $mail->setFrom("order-confirm@scrapout.in", "ScrapOut");
            $mail->AddAddress($to);
            $mail->MsgHTML($msg);
            print_r($mail);
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message sent!";
            }               
         } catch (Exception $ex) {
              print_r($ex);
         }
     return "sent";
}



public function orders_put() {


	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );

	$order_data['user_id'] = $this->users->getUserIDFromAccessToken($access_token);
	if($order_data['user_id'] == null)
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");

	//order_number, user_id, state_id, district_id, area_id, address, deal_status, user_session_id

	$order_data['user_session_id'] = $this->users->getUserSessionFromAccessToken($access_token);

	$order_data['state_id'] = trim ( $this->put('state_id'));
	$order_data['district_id'] = trim($this->put('district_id'));
	$order_data['area_id'] = trim($this->put('area_id'));
	$where_array['order_number'] = trim( $this->put('order_number'));


	if($where_array['order_number'] == null)
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Order Number");

	if(!$this->orders->getAllOrders($where_array['order_number'], null))
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Order Number");
	
	$where_array['row_status'] = 1;
	$order_data['address'] = trim($this->put('address'));

	if(!$this->orders->updateOrder($where_array, $order_data))
		$this->generateErrorMessage(BAD_REQUEST, "Something went wrong.");
		

	$order_materials_data['materials'] = trim($this->put('materials'));
	//'{"type":"steps","id":"id","data":{"steps":[{"steps":1111,"steps_date":"2015-11-21 12:17:33"}, {"steps":2222,"steps_date":"2015-11-22 12:17:33"}, {"steps":3333,"steps_date":"2015-11-23 12:17:33"}]}}';


	$materials_data = objectToArray(json_decode($order_materials_data["materials"]));
	
	//print_r($materials_data);
	$materials_in_data = array();
	$final_order_data = array();
	foreach($materials_data as $materials){
		$materials_in_data['order_number'] = $where_array['order_number'];
		$materials_in_data["material_id"] = $materials["material_id"];
		$materials_in_data["material_qty"] = $materials["material_qty"];
		$materials_in_data['user_session_id'] = $order_data['user_session_id'];
		$materials_in_data["price"] = $materials["price"];
		$materials_in_data['row_status'] = 1;
		array_push($final_order_data, $materials_in_data);
	}

	//print_r($final_order_data);exit;
	
	if(!$this->order_materials->updateOrderMaterials(null,$final_order_data)) {
		$where_array['order_number'] = $order_data['order_number'];
		$what_array['row_status'] = 0;
		$this->orders->updateOrder($where_array, $what_array);
		$this->order_materials->updateOrderMaterials($where_array, $what_array);

		$this->generateErrorMessage(BAD_REQUEST, "Something went wrong.");

	}
	else
		$this->response ( array (
						'type' => 'orders',
						'id' => 'id',
						'data' => "Order Updated Successfully" 
				), SUCCESS_OK );
	
}
public function orders_delete(){

	$where_array['order_number'] = trim($this->delete('order_number'));
	$where_array['row_status'] = 0;
	$what_array['row_status'] = 0;
	//print_r($where_array);
	//echo $state_id.",".$district_id;
	$headers = getallheaders();
	$access_token = trim( $headers['Accesstoken'] );
	//$state_id = trim($this->input->post('state_id'));
	if($this->users->checkAccessTokenExist($access_token)) {
		$this->orders->updateOrder($where_array, $what_array);
		$this->order_materials->deleteOrderMaterials($where_array);
		
		$this->response ( array (
						'type' => 'orders',
						'id' => 'id',
						'data' => "Order Deleted Successfully"
				), SUCCESS_OK );
	}
	else
		$this->generateErrorMessage(BAD_REQUEST, "Invalid Access Token");
}


/**
 * Screens in Android and iOS : Login Screen
 * Method : POST
 *
 * @param $email, $password        	
 * @return user_id, user_session_id, user_details
 */
function auth_post() {
	$phone = trim ( $this->input->post('phone') );
	$password = trim ( $this->input->post('password') );
	//$device_id = trim ( $this->input->post ( 'device_id' ) );
	
	$validate_array = array (
			'phone' => $phone,
			'password' => $password
	);
	
	if (check_empty_values ( $validate_array )) {
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	}
	
	$where_array = array (
			'phone' => $phone,
			'password' => $password,
			'row_status' => 1	
	);
	$user = $this->users->authentication ( $where_array );
	//print_r($user);
	//print_r($user);exit;
	if ($user || is_array ( $user )) {
		if (count ( $user ) > 0) {
			$user_session_data = array (
					'user_id' => $user->id
					//'OS' => getOS (),	
					//'device_id' => $device_id 
			);
			//print_r($user);exit;
			$usr_data = $this->users->AddUserSession ( $user_session_data );
			//print_r($usr_data);exit;
			if ($usr_data) {
				
				// $response_data = objectToArray ( $user );
				$user_data = objectToArray($user);
				//unset($user_data['password']);
				$response_data ['access_token'] = $usr_data ['access_token'];
				$response_data [ 'email' ] = $user_data['email'];
				$response_data [ 'username' ] = $user_data['username'];
				$response_data [ 'phone' ] = $user_data['phone'];
				//$response_data [ 'referral_id' ] = $user_data['referral_id'];
				$this->response ( array (
						'type' => 'auth',
						'id' => 'id',
						'data' => $response_data 
				), SUCCESS_OK );
			} else {
				$this->generateErrorMessage ( BAD_REQUEST, "Invalid Phone or Password" );
			}
		} else {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Phone or Password" );
		}
	} else {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Phone or Password" );
	}
}

/**
 * sends email for resetting password
 *
 * @param $email, $password,
 *        	$device_id, $device_token
 * @return $user_id, $first_name, $last_name, $email, $photo
 */
function sendEmail($email, $password) {
	$this->load->library ( 'email' );
	$this->email->from ( SERVICE_EMAIL, 'Sureify Dashboard' );
	$this->email->to ( $email );
	$this->email->subject ( 'Sureify Password Reset' );
	
	$message = 'We have reset your password as per your request!.<br>';
	$message .= '<b>Please find the credentials below to log in:</b><br>';
	$message .= '<div style="width:40%;"><b><hr></b><br/>';
	$message .= '<b>Email: </b>' . $email . '<br/>';
	$message .= '<b>Password: </b>' . $password . '<br/><br/>';
	$message .= '<b><hr></b><br/></div>';
	$message .= 'Thanks,<br/><br/>';
	$message .= '<i>Sureify Website</i><br/>';
	
	$this->email->message ( $message );
	$this->email->set_newline ( "\r\n" );
	$this->email->send ();
	
	$this->response ( array (
			'type' => 'recovery',
			'id' => 'id',
			'data' => array (
					'status' => SUCCESS_OK,
					'detail' => "Email successfully sent" 
			) 
	), SUCCESS_OK );
}

/**
 * Screens in Android and iOS : Can't Login Screen
 * Method : POST
 *
 * @param
 *        	$email
 * @return void
 */
function recovery_post() {
	$email = trim ( $this->input->post ( 'email' ) );
	$where_array ['email'] = $email;
	
	if (! $this->user->checkEmailExist ( $email )) {
		
		$password = generatePassword ( 7 );
		$update = $this->user->updatePassword ( $email, $password );
		
		if ($update) {
			$this->sendEmail ( $email, $password );
		} else {
			$this->generateErrorMessage ( BAD_REQUEST, "Failed to update password" );
		}
	} else {
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid Email" );
	}
}

/**
 * Screens in Android and iOS : Dashboard Slide Menu Screen
 * Method : POST
 *
 * @param
 *        	$user_session_id
 * @return
 *
 */
function logout_post() {
	// $user_id = trim($this->input->post('user_id'));
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$user_session_id = $this->user->getSessionIDFromAccessToken ( $user_access_token );
	if ($user_session_id == null)
		$this->generateErrorMessage ( BAD_REQUEST, "Operation Failed" );
		// echo $user_session_id;exit();
	$where_array ['id'] = $user_session_id;
	
	if ($this->user->checkUserSession ( $where_array )) {
		// echo 1;exit();
		if ($this->user->updateUserSessionLogoutTime ( $user_session_id )) {
			$this->response ( array (
					'type' => 'auth',
					'id' => 'id',
					'data' => array (
							'message' => 'Logged out Successfully' 
					) 
			), SUCCESS_OK );
		} else {
			$this->generateErrorMessage ( BAD_REQUEST, "Operation Failed" );
		}
	} else {
		// echo 1;exit();
		$this->generateErrorMessage ( BAD_REQUEST, "No Session Exists" );
	}
}

/**
 * Screens in Android and iOS : Update PIN Screen
 * Method : PUT
 *
 * @param
 *        	$user_access_token
 * @return
 *
 */
function config_put() {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$pin = trim ( $this->put ( 'pin' ) );
	
	if ($result == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$where_array ['id'] = $result;
		if ($this->user->updateUserPIN ( $where_array, $pin ))
			$this->response ( array (
					'type' => 'config',
					'id' => 'id',
					'data' => array (
							'message' => 'PIN Configured Successfully' 
					) 
			), SUCCESS_OK );
		else
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	}
}

/**
 * Screens in Android and iOS : My Account Edit
 * Method : PUT
 *
 * @param $location, $phone,
 *        	$email
 * @return
 *
 */
function editUsers_put() {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$location = trim ( $this->put ( 'location' ) );
	$phone = trim ( $this->put ( 'phone' ) );
	$email = trim ( $this->put ( 'email' ) );
	
	if ($location == "" || $phone == "" || $email == "")
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid details" );
	if ($result == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$where_array ['id'] = $result;
		
		$user_edit_data = array (
				'location' => $location,
				'phone_number' => $phone,
				'email' => $email 
		);
		// print_r($user_edit_data);exit();
		if ($this->user->updateUserInfo ( $where_array, $user_edit_data ))
			$this->response ( array (
					'type' => 'config',
					'id' => 'id',
					'data' => array (
							'message' => 'Updated Successfully' 
					) 
			), SUCCESS_OK );
		else
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	}
}

/**
 * Screens in Android and iOS : Ask For Pin
 * Method : POST
 *
 * @param
 *        	$pin
 * @return Success / Failure
 */
function config_post() {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$pin = trim ( $this->input->post ( 'pin' ) );
	
	if ($result == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$where_array ['user_id'] = $result;
		// echo $this->user->returnUserPIN($where_array);exit();
		if ($this->user->returnUserPIN ( $where_array ) == $pin)
			$this->response ( array (
					'type' => 'config',
					'id' => 'id',
					'data' => array (
							'message' => 'PIN Authenticated' 
					) 
			), SUCCESS_OK );
		else
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid PIN" );
	}
}

/**
 * Screens in Android and iOS : Challenges
 * Method : GET
 *
 * @param
 *        	$challenge_id
 * @return Success / Failure
 */
function challenges($challenge_id = null) {
	$headers = getallheaders ();
	
	// checks the existance of headers
	if (! array_key_exists ( "access_token", $headers ))
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	if ($result == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$dates ['user_id'] = $result;
		$dates ['month'] = date ( "m" );
		$dates ['year'] = date ( "Y" );
		
		/*
		 * $where_array ['uc.user_id'] = $result;
		 * if($challenge_id != null && $challenge_id > 0)
		 * $where_array ['uc.id'] = $challenge_id;
		 */
		$where_array ['cm.row_status'] = 1;
		// $where_array ['uc.row_status'] = 1;
		
		$result = $this->user->challengesData ( $where_array, $dates );
		$this->response ( array (
				'type' => 'challenges',
				'id' => 'id',
				'data' => $result 
		), SUCCESS_OK );
	}
}

/**
 * Screens in Android and iOS : Challenges
 * Method : POST
 *
 * @param
 *        	$challenge_id
 * @return Success / Failure
 */
function start_challenge() {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$user_id = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$user_session_id = $this->user->getSessionIDFromAccessToken ( $user_access_token );
	if ($this->input->post ( 'challenge_id' ) == false) {
		$this->response ( array (
				'errors' => array (
						array (
								'status' => BAD_REQUEST,
								'detail' => "Enter valid challenge id" 
						) 
				) 
		) );
	}
	$challenge_id = trim ( $this->input->post ( 'challenge_id' ) );
	
	if ($user_id == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$user_details ['user_id'] = $user_id;
		$user_details ['challenge_id'] = $challenge_id;
		$user_details ['status'] = 0;
		$user_details ['row_status'] = 1;
		$user_details ['user_session_id'] = $user_session_id;
		$user_details ['created_time'] = date ( "Y-m-d H:m:i" );
		
		// echo $this->user->returnUserPIN($where_array);exit();
		$result = $this->user->startChallenge ( $user_details );
		if (result == true) {
			$this->response ( array (
					'type' => 'challenges',
					'id' => 'id',
					'data' => array (
							'message' => 'Challenge Accepted' 
					) 
			), SUCCESS_OK );
		} else {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Challenge_id" );
		}
	}
}

/**
 * Screens in Android and iOS : Challenges
 * Method : Delete
 *
 * @param
 *        	$challenge_id
 * @return Success / Failure
 */
function delete_challenge($challenge_id = null) {
	$headers = getallheaders ();
	
	// checks the existance of headers
	if (! array_key_exists ( "access_token", $headers ))
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result_id = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	if ($result_id == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$where_array ['user_id'] = $result_id;
		$where_array ['challenge_id'] = $challenge_id;
		$where_array ['row_status'] = 1;
		
		$result = $this->user->deleteChallenge ( $where_array );
		
		if ($result >= 1) {
			$this->response ( array (
					'type' => challenges,
					'id' => 'id',
					'data' => array (
							'message' => 'We\'re sorry you\'re ending this challenge. Be sure to try out another to save money and stay healthy!' 
					) 
			), SUCCESS_OK );
		} else {
			$this->response ( array (
					'type' => challenges,
					'id' => 'id',
					'data' => array (
							'message' => 'Failed to quit the challenge' 
					) 
			), SUCCESS_OK );
		}
	}
}

/**
 * Screens in Android and iOS : Challenges
 * Method : Delete
 *
 * @param
 *        	$challenge_id
 * @return Success / Failure
 */
function delete_card($card_id = null) {
	$headers = getallheaders ();
	
	// checks the existance of headers
	if (! array_key_exists ( "access_token", $headers ))
		$this->generateErrorMessage ( BAD_REQUEST, "Some fields are missing" );
	
	$user_access_token = $headers ['Accesstoken'];
	// echo trim ( $this->put ( 'pin' ) );exit();
	$result_id = $this->user->getStripeIDFromAccessToken ( $user_access_token );
	
	if ($result_id == null) {
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
	} else {
		
		$result = $this->stripeapi->deleteSavedCard ( $result_id, $card_id );
		
		if (array_key_exists ( "id", $result )) {
			$this->response ( array (
					'type' => cards,
					'id' => 'id',
					'data' => array (
							'message' => 'Card successfully removed' 
					) 
			), SUCCESS_OK );
		} else {
			
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Card details" );
		}
	}
}
function users_put($type = null, $type_id = -1) {
	$headers = getallheaders ();
	if (array_key_exists ( 'Accesstoken', $headers )) {
		$user_access_token = $headers ['Accesstoken'];
		// echo trim ( $this->put ( 'pin' ) );exit();//$user_access_token;exit();
		$result = $this->user->checkAccessTokenExist ( $user_access_token );
		// echo $result;exit();
		if ($result == null) {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
		} else {
			
			switch ($type) {
				case 'weights' :
					// code...
					// $this->weights_get ( $user_id );
					break;
				case 'steps' :
					// code...
					break;
				case 'payments' :
					// code...
					break;
				case 'challenges' :
					// code...
					break;
				case 'goals' :
					// code...
					break;
				case 'config' :
					// code...
					$this->config_put (); // $user_access_token, $pin);
					break;
				case 'edit' :
					// code...
					break;
				default :
					// code...
					$this->editUsers_put ();
					break;
			}
			$this->response ( array (
					$user_id . " " . $type . " " . $type_id 
			), BAD_REQUEST );
			
			if (! $this->get ( 'id' )) {
				$this->response ( NULL, BAD_REQUEST );
			}
			
			$user = array (
					'returned: ' . $this->get ( 'id' ) 
			);
			
			if ($user) {
				$this->response ( $user, SUCCESS_OK );
			} else {
				$this->response ( NULL, PAGE_NOT_FOUND );
			}
		}
	} else
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
}
function users_post($type = null, $type_id = -1) {
	$headers = getallheaders ();
	if (array_key_exists ( 'Accesstoken', $headers )) {
		$user_access_token = $headers ['Accesstoken'];
		// echo trim ( $this->put ( 'pin' ) );exit();//$user_access_token;exit();
		$result = $this->user->checkAccessTokenExist ( $user_access_token );
		// echo $result;exit();
		if ($result == null) {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
		} else {
			// $this->response ( "gfhgvhm", SUCCESS_OK );
			
			switch ($type) {
				case 'weights' :
					// code...
					// $this->weights_get ( $user_id );
					break;
				case 'steps' :
					// code...
					break;
				case 'payments' :
					// code...
					break;
				case 'challenges' :
					// code...
					$this->start_challenge ();
					break;
				case 'goals' :
					// code...
					break;
				case 'config' :
					// code...
					$this->config_post (); // $user_access_token, $pin);
					break;
				case 'edit' :
					// code...
					break;
				case 'usercards' :
					$this->savecard ();
					break;
				case 'syncfitbit' :
					$this->syncfitbit ();
					break;
				case 'payment' :
					$this->payment ();
					break;
				case 'logout' :
					// code...
					$this->logout_post ();
					break;
				case 'photo' :
					$this->photo_post ();
					break;
				default :
					// code...
					break;
			}
			$this->response ( array (
					$user_id . " " . $type . " " . $type_id 
			), BAD_REQUEST );
			
			if (! $this->get ( 'id' )) {
				$this->response ( NULL, BAD_REQUEST );
			}
			
			$user = array (
					'returned: ' . $this->get ( 'id' ) 
			);
			
			if ($user) {
				$this->response ( $user, SUCCESS_OK );
			} else {
				$this->response ( NULL, PAGE_NOT_FOUND );
			}
		}
	} else
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
}
function users_get($type = null, $type_id = -1, $subtype = null) {
	$headers = getallheaders ();
	if (array_key_exists ( 'Accesstoken', $headers )) {
		$user_access_token = $headers ['Accesstoken'];
		// echo $user_access_token;exit();
		$result = $this->users->checkAccessTokenExist ( $user_access_token );
		
		if ($result == false) {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
		} else {
			// $this->response ( $result->logout_time, SUCCESS_OK );
			
			switch ($type) {
				case null :
					$this->accountDetails ( true );
					// code...
					break;
				case 'discounts' :
					$this->discounts ();
					break;
				case 'policies' :
					if ($subtype == null) {
						$this->accountDetails ( false );
					} else if ($subtype == 'payments') {
						$this->paymentHistory ( $type_id );
					}
					// code...
					break;
				case 'weights' :
					// code...
					$this->weights ( $type_id );
					break;
				case 'steps' :
					// code...
					$this->steps ( $type_id );
					break;
				case 'livedata' :
					// code...
					$this->livedata ( $type_id );
					break;
				case 'payments' :
					// code...
					$this->paymentHistory ( $type_id );
					break;
				case 'challenges' :
					// code...
					if ($type_id == - 1)
						$this->challenges ( $type_id );
					else {
						$result = $this->user->rulesData ( $type_id );
						$this->response ( array (
								'type' => 'rules',
								'id' => 'id',
								'data' => $result 
						), SUCCESS_OK );
					}
					
					break;
				case 'goals' :
					// code...
					break;
				case 'config' :
					// code...
					// $this->config_get($user_access_token);
					break;
				case 'edit' :
					// code...
					break;
				case 'usercards' :
					// code...
					$this->usercards ();
					break;
				default :
					// code...
					break;
			}
			$this->response ( array (
					$user_id . " " . $type . " " . $type_id 
			), BAD_REQUEST );
			
			if (! $this->get ( 'id' )) {
				$this->response ( NULL, BAD_REQUEST );
			}
			
			$user = array (
					'returned: ' . $this->get ( 'id' ) 
			);
			
			if ($user) {
				$this->response ( $user, SUCCESS_OK );
			} else {
				$this->response ( NULL, PAGE_NOT_FOUND );
			}
		}
	} else
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
}

/**
 * Deletes selected user details of $user_id
 *
 * @param array $type,
 *        	$type_id, $subtype
 *        	@deletes the detail
 */
function users_delete($type = null, $type_id = -1, $subtype = null) {
	$headers = getallheaders ();
	
	if (array_key_exists ( 'Accesstoken', $headers )) {
		$user_access_token = $headers ['Accesstoken'];
		// echo $user_access_token;exit();
		$result = $this->user->checkAccessTokenExist ( $user_access_token );
		
		if ($result == false) {
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
		} else {
			// $this->response ( $result->logout_time, SUCCESS_OK );
			
			switch ($type) {
				
				case 'challenges' :
					// code...
					$this->delete_challenge ( $type_id );
					break;
				case 'cards' :
					// code...
					$this->delete_card ( $type_id );
					break;
				default :
					// code...
					break;
			}
			$this->response ( array (
					$user_id . " " . $type . " " . $type_id 
			), BAD_REQUEST );
			
			if (! $this->get ( 'id' )) {
				$this->response ( NULL, BAD_REQUEST );
			}
			
			$user = array (
					'returned: ' . $this->get ( 'id' ) 
			);
			
			if ($user) {
				$this->response ( $user, SUCCESS_OK );
			} else {
				$this->response ( NULL, PAGE_NOT_FOUND );
			}
		}
	} else
		$this->generateErrorMessage ( BAD_REQUEST, "Invalid Access Token" );
}

/**
 * Retrives user details of $user_id
 *
 * @param array $user_id        	
 * @return s user details
 */
function accountDetails($getPerson) {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	if (! $data ['user_id'])
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid user id" );
	
	$where_array = $data;
	if ($getPerson)
		$person_data = $this->user->getUserDetails ( $where_array );
	$policy_data = $this->user->getPolicyDetails ( $where_array );
	
	for($i = 0; $i < count ( $policy_data ); $i ++) {
		$policy_data [$i] ['plan_name'] = ucwords ( $policy_data [$i] ['plan_name'] );
		$policy_data [$i] ['first_name'] = ucwords ( $policy_data [$i] ['first_name'] );
		$policy_data [$i] ['last_name'] = ucwords ( $policy_data [$i] ['last_name'] );
	}
	
	$this->response ( array (
			'type' => 'users',
			'id' => 'id',
			'data' => array (
					'person' => $person_data,
					'policy' => $policy_data 
			) 
	), SUCCESS_OK );
}

/**
 * Retrives the steps of $user_id
 *
 * @param array $user_id        	
 * @param array $type_id        	
 * @return steps and ranges
 */
function steps($type_id) {
	$headers = getallheaders ();
	// $this->response($headers);
	if (array_key_exists ( 'current-date', $headers )) {
		$user_current_date = $headers ['current-date'];
	} else
		$this->response ( array (
				'status' => 0,
				'error' => 'Invalid date' 
		), 400 );
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	$current_date = date ( "d", strtotime ( $user_current_date ) );
	$current_month = date ( "m", strtotime ( $user_current_date ) );
	$current_year = date ( "Y", strtotime ( $user_current_date ) );
	
	$data ['date'] = $current_date;
	$data ['month'] = $current_month;
	$data ['year'] = $current_year;
	
	if (! $data ['user_id'] || ! $data ['month'] || ! $data ['year'])
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid user id" );
	
	$where_array = $data;
	// $this->response($where_array);
	// $datesRanges = $this->user->datesRanges ( $where_array );
	// $response_data ['range'] = $datesRanges ['range'];
	$month_data = $this->user->userSteps ( $where_array );
	// $this->response($current_date);
	$reverse_steps = array_reverse ( array_splice ( $month_data ['month_steps'], 0, $current_date ) );
	// $reverse_steps = array_reverse ( $month_data ['month_steps'] );
	$response_data ['steps'] = $reverse_steps;
	$response_data ['range'] = $month_data ['range'];
	$response_data ['days_to_go'] = $month_data ['days_to_go'];
	
	if ($type_id > 0 && $type_id <= count ( $reverse_steps )) {
		$this->response ( $response_data ['steps'] [$type_id - 1] );
	}
	
	$this->response ( array (
			'type' => 'steps',
			'id' => 'id',
			'data' => $response_data 
	), SUCCESS_OK );
}

/**
 * Retrives the weights of $user_id
 *
 * @param array $user_id        	
 * @param array $type_id        	
 * @return weights and ranges
 */
function weights($type_id) {
	$headers = getallheaders ();
	
	if (array_key_exists ( 'current-date', $headers )) {
		$user_current_date = $headers ['current-date'];
	} else
		$this->response ( array (
				'status' => 0,
				'error' => 'Invalid date' 
		), 400 );
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	$current_date = date ( "d", strtotime ( $user_current_date ) );
	$current_month = date ( "m", strtotime ( $user_current_date ) );
	$current_year = date ( "Y", strtotime ( $user_current_date ) );
	
	$data ['date'] = $current_date;
	$data ['month'] = $current_month;
	$data ['year'] = $current_year;
	
	// $this->response($data, SUCCESS_OK);
	
	if (! $data ['user_id'] || ! $data ['month'] || ! $data ['year'])
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid user id" );
	
	$where_array = $data;
	$datesRanges = $this->user->datesRanges ( $where_array );
	
	$month_data = $this->user->userWeights ( $where_array );
	// $this->response($month_data);
	// $previous_weight = $this->user->previousWeight($where_array);
	$response_data ['range'] = $datesRanges ['range'];
	// $response_data['previous_weight'] = $previous_weight['previous_weight'];
	
	$most_recent_weight = 0;
	$reverse_weights = array_reverse ( array_splice ( $month_data ['month_weights'], 0, $current_date ) );
	// $reverse_weights = array_reverse ($month_data ['month_weights']);
	$weights_length = count ( $reverse_weights );
	$response_data ['weights'] = $reverse_weights;
	
	for($k = 0; $k < $weights_length; $k ++) {
		
		if ($reverse_weights [$k] ['weight'] != null) {
			$most_recent_weight = $reverse_weights [$k] ['weight'];
			break;
		}
	}
	
	for($k = $weights_length - 1; $k != - 1; $k --) {
		
		if ($reverse_weights [$k] ['weight'] != null) {
			$least_recent_weight = $reverse_weights [$k] ['weight'];
			break;
		}
	}
	// $this->response($least_recent_weight);
	
	$last_month = date ( 'Y-m', strtotime ( "$user_current_date last month" ) );
	
	$previous_weight = $this->user->previousmonthweight ( $data ['user_id'], $last_month );
	// echo $previous_weight;exit;
	// $this->response($previous_weight);
	$response_data ['previous_weight'] = ( int ) $previous_weight;
	
	if ($response_data ['previous_weight'] == 0) {
		$response_data ['previous_weight'] = $least_recent_weight; // $res['previous_weight'];
	}
	
	if ($most_recent_weight == 0) {
		$response_data ['most_recent_weight'] = 127; // $res['previous_weight'];
	} else {
		$response_data ['most_recent_weight'] = $most_recent_weight;
	}
	
	if ($type_id > 0 && $type_id <= count ( $reverse_weights )) {
		$this->response ( $response_data ['weights'] [$type_id - 1] );
	}
	
	$this->response ( array (
			'type' => 'weights',
			'id' => 'id',
			'data' => $response_data 
	), SUCCESS_OK );
}
function user_post() {
	$result = $this->user_model->update ( $this->post ( 'id' ), array (
			'name' => $this->post ( 'name' ),
			'email' => $this->post ( 'email' ) 
	) );
	
	if ($result === FALSE) {
		$this->response ( array (
				'status' => 'failed' 
		) );
	} 

	else {
		$this->response ( array (
				'status' => 'success' 
		) );
	}
}
function paymentHistory($type_id) {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$data ['type_id'] = $type_id;
	
	if (! $data ['user_id'])
		$this->response ( array (
				'errors' => array (
						array (
								'status' => BAD_REQUEST,
								'detail' => "Enter valid user id" 
						) 
				) 
		) );
	
	$where_array = $data;
	$response_data = array_reverse ( $this->user->getPaymentHistory ( $where_array ) );
	
	$this->response ( array (
			'type' => 'payments',
			'id' => 'id',
			'data' => array (
					'payments' => $response_data 
			) 
	), SUCCESS_OK );
}
function generateErrorMessage($status, $message) {
	$this->response ( array (
			'errors' => array (
					array (
							'status' => $status,
							'detail' => $message 
					) 
			) 
	), $status);
}
public function queryBuilder_post() {
	$table ['steps'] = 'user_steps';
	$table ['weight'] = 'user_weights';
	$table ['cardio'] = 'user_cardio';
	$table ['age'] = 'age';
	
	$queryBuilder = array ();
	$queryBuilder ['noOfRules'] = $this->input->post ( 'noOfRules' );
	$queryBuilder ['field'] = $this->input->post ( 'field' );
	$queryBuilder ['relationship'] = $this->input->post ( 'relationship' );
	$queryBuilder ['goal'] = $this->input->post ( 'goal' );
	$queryBuilder ['arithmetic'] = $this->input->post ( 'arithmetic' );
	
	$queryBuilder ['user_id'] = $this->input->post ( 'user_id' );
	
	// array_push($queryBuilder, $noOfRules, $arith, $field, $table[$field], $relationship, $goal);
	print_r ( $queryBuilder );
	// exit();
	switch ($queryBuilder ['noOfRules']) {
		case "1" :
			$singleQuery = "SELECT %%arith%%(%%table_name%%.%%field%%) from %%table_name%% where %%table_name%%.user_id = %%user_id%% GROUP BY %%table_name%%.user_id having %%arith%%(%%table_name%%.%%field%%) %%relationship%% %%goal%%;";
			
			if ($queryBuilder ['arithmetic'] == 'difference')
				$singleQuery = str_replace ( "%%arith%%(%%table_name%%.%%field%%)", "max(%%table_name%%.%%field%%) - min(%%table_name%%.%%field%%)", $singleQuery );
			if ($queryBuilder ['field'] == 'weight')
				$singleQuery = $singleQuery = str_replace ( "%%field%%", $queryBuilder ['field'], $singleQuery );
			$singleQuery = str_replace ( "%%table_name%%", $table [$queryBuilder ['field']], $singleQuery );
			$singleQuery = str_replace ( "%%user_id%%", $queryBuilder ['user_id'], $singleQuery );
			$singleQuery = str_replace ( "%%arith%%", $queryBuilder ['arithmetic'], $singleQuery );
			$singleQuery = str_replace ( "%%relationship%%", $queryBuilder ['relationship'], $singleQuery );
			$singleQuery = str_replace ( "%%goal%%", $queryBuilder ['goal'], $singleQuery );
			
			echo $singleQuery;
			exit ();
			break;
		default :
			
			break;
	}
}

/**
 * Screens in Android and iOS : Sync fitbit
 * Method : POST
 * Sync user fitbit data
 *
 * @param
 *        	$user_id
 * @return $status
 */
function syncfitbit() {
	/*
	 * Sent : user_id
	 */
	$headers = getallheaders ();
	// echo "<pre>";print_r($headers);exit;
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$user_id = $data ['user_id'];
	$data ['user_session_id'] = $this->user->getSessionIDFromAccessToken ( $user_access_token );
	$user_session_id = $data ['user_session_id'];
	
	// checking for empty values START
	if (check_empty_values ( $data )) {
		$this->response ( array (
				'status' => 0,
				'error' => 'Some Fields are missing' 
		), 400 );
	}
	// checking for empty values END
	
	$get_user_data = $this->user->getUsers ( array (
			'u.id' => $user_id 
	) );
	// $this->response($get_user_data);
	if (count ( $get_user_data ) > 0) {
		$user_data = $get_user_data [0];
		$user_data ['user_session_id'] = $user_session_id;
		// echo "<pre>";print_r($user_data);exit;
		$result = $this->sync->syncUserFitbit ( $user_data );
	}
	
	if ($result == 1) {
		$status = 1;
		$msg = "Synced Successfully";
	} else {
		$status = 0;
		$msg = "Operation Failed";
	}
	
	$this->response ( array (
			'type' => 'syncfitbit',
			'id' => 'id',
			'data' => array (
					'message' => $msg 
			) 
	), SUCCESS_OK );
}

/**
 * Screens in Android and iOS : get user added card
 * Method : POST
 * Get User Credit Card Data
 *
 * @param
 *        	$access_token
 * @return $cards_data
 */
function usercards() {
	$headers = getallheaders ();
	// echo "<pre>";print_r($headers);exit;
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$user_id = $data ['user_id'];
	
	if (! $data ['user_id']) {
		$this->response ( array (
				'status' => 0,
				'error' => 'Some Fields are missing' 
		), 400 );
	}
	
	$user_data = $this->user->getUser ( array (
			'u.id' => $user_id 
	) );
	// echo "<pre>";print_r($user_data);exit;
	$user_email = $user_data->email;
	$user_stripe_customer_id = $user_data->stripe_customer_id;
	
	if ($user_stripe_customer_id != "") {
		$stripeapi_response = $this->stripeapi->getCustomerCards ( $user_stripe_customer_id );
		$return_status = $stripeapi_response;
		$card_data = $return_status ['response'];
	} else {
		$card_data = array ();
	}
	
	$this->response ( array (
			'type' => 'cards',
			'id' => 'id',
			'data' => $card_data 
	), SUCCESS_OK );
	
	exit ();
}

/**
 * Screens in Android and iOS : get user added card
 * Method : POST
 * save User Credit Card Data
 *
 * @param
 *        	$access_token,$card_number,$expiry_month,$expiry_year,$security_code
 * @return $card_data
 */
function savecard() {
	/*
	 * Sent : user_id
	 */
	$headers = getallheaders ();
	// echo "<pre>";print_r($headers);exit;
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$user_id = $data ['user_id'];
	
	$card_data ['card_number'] = trim ( $this->input->post ( 'card_number' ) );
	$card_data ['expiry_month'] = trim ( $this->input->post ( 'expiry_month' ) );
	$card_data ['expiry_year'] = trim ( $this->input->post ( 'expiry_year' ) );
	$card_data ['security_code'] = trim ( $this->input->post ( 'security_code' ) );
	
	// checking for empty values START
	if (! $data ['user_id'] && check_empty_values ( $card_data )) {
		$this->response ( array (
				'status' => 0,
				'error' => 'Some Fields are missing' 
		), 400 );
	}
	
	$user_data = $this->user->getUser ( array (
			'u.id' => $user_id 
	) );
	// echo "<pre>";print_r($user_data);exit;
	$user_email = $user_data->email;
	$user_stripe_customer_id = $user_data->stripe_customer_id;
	
	$update_stripe_customer_id = "";
	if ($user_stripe_customer_id == "" || strlen ( $user_stripe_customer_id ) < 3) {
		$return_status = $this->stripeapi->createCardAndCustomer ( $card_data, $user_email );
		// echo "<pre>";print_r($stripeapi_response);exit;
		$user_stripe_customer_id = $return_status ['customer_id'];
		
		$update_data = array (
				"stripe_customer_id" => $user_stripe_customer_id 
		);
		$update_where = array (
				"id" => $user_id 
		);
		$update_user_data = $this->user->updateUserData ( $update_data, $update_where );
		
		if ($update_user_data == 0) {
			$return_status = array (
					'status' => 0,
					'response' => "",
					"msg" => "Card Adding Failed" 
			);
		}
	} else {
		$return_status = $this->stripeapi->addCustomerCard ( $card_data, $user_stripe_customer_id );
	}
	
	$this->response ( array (
			'type' => 'cards',
			'id' => 'id',
			'data' => $return_status ['response'] 
	), SUCCESS_OK );
	
	exit ();
}

/**
 * Screens in Android and iOS : payment api
 * Method : POST
 * Pay using credit card
 *
 * @param
 *        	$access_token,$pay_amount
 * @return $card_data
 */
function payment() {
	/*
	 * Sent : user_id
	 */
	$headers = getallheaders ();
	// echo "<pre>";print_r($headers);exit;
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	$user_id = $data ['user_id'];
	$premiumDetails = $this->premium->getUserInitialPremium ( $user_id );
	$currentPremium = $this->premium->getUserCurrentBillPremium ( $user_id );
	$data ['auto_payment'] = $this->input->post ( 'auto_payment' );
	$data ['payment_date'] = $this->input->post ( 'payment_date' );
	$data ['pay_amount'] = ceil ( trim ( $currentPremium ['premium'] ) );
	if (! $data ['user_id'] && check_empty_values ( $data )) {
		$this->response ( array (
				'status' => 0,
				'error' => 'Some Fields are missing' 
		), 400 );
	}
	
	$user_data = $this->user->getUser ( array (
			'u.id' => $user_id 
	) );
	
	$user_email = $user_data->email;
	$user_stripe_customer_id = $user_data->stripe_customer_id;
	
	$return_status = array (
			'status' => 0,
			'response' => "",
			"msg" => "Payment Failed!" 
	);
	
	if (isset ( $data ['auto_payment'] ) && $data ['auto_payment'] == "true") {
		// echo 1;exit;
		$payment_array = array ();
		$payment_array ['user_id'] = $user_id;
		if ($data ['payment_date'] == null)
			$this->generateErrorMessage ( BAD_REQUEST, "Invalid Date to Schedule" );
		$payment_array ['auto_payment_day'] = $data ['payment_date'];
		
		$payment_array ['created_time'] = date ( "Y-m-d H:i:s" );
		
		$insertStatus = $this->auto_payments->insertAutoPayment ( $payment_array );
		if ($insertStatus) {
			$this->response ( array (
					'type' => 'payment',
					'id' => 'id',
					'data' => "Auto payment scheduled successfully." 
			), SUCCESS_OK );
			exit ();
		} else {
			$this->generateErrorMessage ( BAD_REQUEST, "Error Scheduling Auto Payments." );
			exit ();
		}
	} elseif ($user_stripe_customer_id != "" && $data ['pay_amount'] > 0) {
		// echo $pay_amount;
		$update_array = array ();
		$return_status = $this->stripeapi->makePayment ( $user_stripe_customer_id, $data ['pay_amount'] );
		// echo "<pre>"; print_r( $return_status );exit;
		if (isset ( $return_status ) && isset ( $return_status ['status'] )) {
			$update_array ['status'] = $return_status ['status'];
			$update_array ['transaction_id'] = isset ( $return_status ['response'] ['id'] ) ? $return_status ['response'] ['id'] : '';
			$update_array ['payment_date'] = date ( "Y-m-d H:i:s" );
			// print_r($update_array);exit;
			// echo $currentPremium ['id'];exit;
			$this->premium->updateTransaction ( $update_array, $currentPremium ['id'] );
		}
	} elseif ($data ['pay_amount'] <= 0) {
		$this->generateErrorMessage ( BAD_REQUEST, "Payments up-to-date." );
		exit ();
	}
	
	$this->response ( array (
			'type' => 'payment',
			'id' => 'id',
			'data' => $return_status ['response'] 
	), SUCCESS_OK );
	exit ();
}
public function photo_post() {
	// echo 1;
	try {
		
		// Get image string posted from Android App
		$base = $_REQUEST ['image'];
		// echo $base;exit();
		// Get file name posted from Android App
		$format = $_REQUEST ['format'];
		
		$headers = getallheaders ();
		// echo "<pre>";print_r($headers);exit;
		$user_access_token = $headers ['Accesstoken'];
		$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
		$where_array ['ui.user_id'] = $data ['user_id'];
		$filename = $this->user->getUserFirstName ( $where_array );
		if (count ( $format ) == 0 || $format == null || $format == '')
			$format = 'png';
		$filename = $filename . date ( '_Y:m:d_H:i:s' ) . '.' . $format;
		// echo $filename;exit;
		$user_id = $data ['user_id'];
		$user_session_id = $this->user->getUserSessionFromAccessToken ( $user_access_token );
		
		// Decode Image
		$binary = base64_decode ( $base );
		// echo "hiiiii";exit();
		// echo($binary." ".$user_id);exit();
		
		header ( 'Content-Type: bitmap; charset=utf-8' );
		// Images will be saved under 'www/imgupload/uplodedimages' folder
		$filename = preg_replace ( '/\s+/', '', $filename );
		chmod ( $filename, 0777 );
		// echo $filename;
		$file = fopen ( './assets/uploadedfiles/profile_pictures/' . $filename, 'wb' );
		// Create File
		chmod ( $file, 0777 );
		$file_status = fwrite ( $file, $binary );
		chmod ( $file, 0777 );
		fclose ( $file );
		// echo $file_status;exit;
		$status = $this->user->updatePhotoMobile ( $filename, $user_session_id, $user_id );
		// echo $status;exit();
		if ($status == 0) {
			// echo "Unsuccessfull";
			generateErrorMessage ( BAD_REQUEST, "Upload Failed" );
		} else {
			$this->response ( array (
					'type' => 'photo',
					'id' => 'id',
					'data' => array (
							'message' => 'Profile Photo Updated Successfully',
							'profile_pic' => $filename 
					) 
			), SUCCESS_OK );
		}
	} catch ( Exception $ex ) {
		echo $ex;
	}
}

/**
 * Retrives the live data of $user_id
 *
 * @param array $user_id        	
 * @param array $type_id        	
 * @return live data
 */
function livedata($type_id) {
	$headers = getallheaders ();
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	if (array_key_exists ( 'current-date', $headers )) {
		$user_current_date = $headers ['current-date'];
	} else
		$this->response ( array (
				'status' => 0,
				'error' => 'Invalid date' 
		), 400 );
	
	$current_date = date ( "d" );
	$current_month = date ( "m" );
	$current_year = date ( "Y" );
	
	$data ['month'] = $current_month;
	$data ['year'] = $current_year;
	
	if (! $data ['user_id'] || ! $data ['month'] || ! $data ['year'])
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid user id" );
	
	$today_data = getLiveData ( $data ['user_id'], $user_current_date );
	
	$response_data ['today_steps'] = $today_data ['today_steps'];
	$response_data ['today_weight'] = $today_data ['today_weight'];
	$response_data ['today_savings'] = $today_data ['today_savings'];
	$response_data ['estimated_savings'] = $today_data ['estimated_savings'];
	$response_data ['weight_last_reported_date'] = $today_data ['weight_last_reported_date'];
	
	$this->response ( array (
			'type' => 'live',
			'id' => 'id',
			'data' => $response_data 
	), SUCCESS_OK );
}
/**
 * Retrives the premium discounts of $user_id
 *
 * @param array $user_id        	
 * @return premium discounts
 */
function discounts() {
	$headers = getallheaders ();
	
	if (array_key_exists ( 'current-date', $headers )) {
		$user_current_date = $headers ['current-date'];
	} else
		$this->response ( array (
				'status' => 0,
				'error' => 'Invalid date' 
		), 400 );
	
	$user_access_token = $headers ['Accesstoken'];
	$data ['user_id'] = $this->user->getUserIDFromAccessToken ( $user_access_token );
	
	$current_date = date ( "d", strtotime ( $user_current_date ) );
	$current_month = date ( "m", strtotime ( $user_current_date ) );
	$current_year = date ( "Y", strtotime ( $user_current_date ) );
	
	$data ['date'] = $current_date;
	$data ['month'] = $current_month;
	$data ['year'] = $current_year;
	
	if (! $data ['user_id'] || ! $data ['month'] || ! $data ['year'])
		$this->generateErrorMessage ( BAD_REQUEST, "Enter valid user id" );
	
	$where_array = $data;
	$response_data = $this->user->getDiscounts ( $where_array );
	
	$where_ary ['cm.row_status'] = 1;
	$challenges_response_data = $this->user->challengesData ( $where_ary, $data );
	$response_count = count ( $response_data );
	for($i = 0; $i < $response_count; $i ++)
		$response_data [$i] ['status'] = 0;
	
	$this->response ( array (
			'type' => 'discounts',
			'id' => 'id',
			'data' => array (
					'discounts' => $response_data,
					'challenges' => $challenges_response_data 
			) 
	), SUCCESS_OK );
}
}
?>
