<?php
function setOutputJson($output = "") {
    $CI = & get_instance();
    $CI->output->set_header('Content-Type: application/json', TRUE);
    if (!empty($output)) {
        $output = (is_array($output)) ? json_encode($output) : (string) $output;
    }
    $CI->output->set_output($output);
}

// validations makes easy, presntly works only for required.
function requiredValidation($inputs = array()) {
    $instance = & get_instance();
    foreach ($inputs as $name) {
        $instance->form_validation->set_rules($name, humanize($name), 'required');
    }
}

/**
 * Genereates random password string
 *
 * @param int $length length of the password
 *
 * @return string $result
 */
function generatePassword($length = 7)
{
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$count = strlen($chars);

	for ($i = 0, $result = ''; $i < $length; $i++) {
		$index = rand(0, $count - 1);
		$result .= substr($chars, $index, 1);
	}

	return $result;
}

// function humanize($str) 
// {
//  $str = trim(strtolower($str));
//  $str = preg_replace('/[^a-z0-9\s+]/', ' ', $str);
//  $str = preg_replace('/\s+/', ' ', $str);
//  $str = explode(' ', $str);
//  $str = array_map('ucwords', $str);
//  return implode(' ', $str);
// }


function renderWithLayout($contentArray, $layout = 'app') {
    if (!$layout) {
        die('$layout argument missing!');
    }
    $instance = & get_instance();
    $instance->load->view('layouts/' . $layout, $contentArray);
}

/**
 * gets multiple partials
 * @param  array $files
 * @return array
 */
function getPartials($files) {
    $instance = &get_instance();
    $partials = array();
    foreach ($files as $file => $path) {
        if (is_array($path)) {
            $info = $path;
            $partials[$file] = $instance->load->view($info['path'], $info['data'], true);
        } else {
            $partials[$file] = $instance->load->view($path, array(), true);
        }
    }
    return $partials;
}

function renderPartial($file, $data = array()) {
    $fParts = explode('/', $file);
    $lastPart = $fParts[count($fParts) - 1];
    $instance = &get_instance();
    $realFile = str_replace($lastPart, '_' . $lastPart, $file);
    $instance->load->view($realFile, $data);
}

function getVersion() {
    $allowedVersions = array('1', '2', '3', '2a', '2b');
    $instance = &get_instance();
    // setting the version of the app
    $version = $instance->input->get('v');
    $sessionVersion = $instance->session->userdata('version');

    // setting version as v1 if both are empty
    if (empty($version) && empty($sessionVersion)) {
        $instance->session->set_userdata('version', '2b');
    } else if (!empty($version)) {
        // setting version requested by user
        if (in_array($version, $allowedVersions)) {
            $v = $version;
        } else {
            $v = '1';
        }
        $instance->session->set_userdata('version', $version);
    }
    return $instance->session->userdata('version');
}

function versionedView($view, $mobile = false) {
    $v = getVersion();
    if ($mobile && isMobile()) {
        $parts = explode('/', $view);
        $view = str_replace($parts[count($parts) - 1], 'mobile/' . $parts[count($parts) - 1], $view);
    }
    if ($v == '1') {
        return $view;
    }
    return $view . '_v' . $v;
}

function mobileCompatibleView($view) {
    if (isMobile()) {
        $parts = explode('/', $view);
        $view = str_replace($parts[count($parts) - 1], 'mobile/' . $parts[count($parts) - 1], $view);
    }
    return $view;
}

function sendEmail($to, $from, $subject, $message) {
    // Always set content-type when sending HTML email
    /*$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: <{$from}>" . "\r\n";
    // $headers .= 'Cc: myboss@example.com' . "\r\n";
    mail($to, $subject, $message, $headers);*/
    $ci =& get_instance();
        $ci->load->library('email');
        $ci->email->set_newline("\r\n");
        $ci->email->from($from);
        $to_emails = array("" , $to);
        $ci->email->to($to_emails);
        $ci->email->set_mailtype('html');
        $ci->email->subject($subject);
        $userDetails = array(
            'email' => $email,
        );
        $data["userDetails"] = $userDetails;
        //echo $msg;exit;
        $ci->email->message($message);
        if ($ci->email->send()) {
            $ci->email->clear(true);
            return true;
        } else {
            return false;
        }
}

function isMobile() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    if (stripos($userAgent, 'mobile') !== false) {
        return true;
    }
    return false;
}

function getPlanData($plan) {
    if ($plan == '$250,000') {
        return array('plan_type' => 'Basic', 'plan_amount' => '250k', 'plan_amount_full' => '250,000', 'image' => 'bronze_image', 'image_name' => 'Fitbit Flex + Aria Scale', 'box_bg' => 'bronze_box_bg', 'color' => '#d34b5c', 'devices' => array('Fitbit Flex', 'Aria Scale'), 'device_images' => array('plan_fitbit_flex_m.png'));
    } elseif ($plan == '$500,000') {
        return array('plan_type' => 'Pro', 'plan_amount' => '500k', 'plan_amount_full' => '500,000', 'image' => 'silver_image', 'image_name' => 'Fitbit Flex + Aria Scale', 'box_bg' => 'silver_box_bg', 'color' => '#e1a246', 'devices' => array('Fitbit Flex', 'Aria Scale'), 'device_images' => array('plan_fitbit_flex_m.png'));
    } elseif ($plan == '$750,000') {
        return array('plan_type' => 'Premium', 'plan_amount' => '750k', 'plan_amount_full' => '750,000', 'image' => 'gold_image', 'image_name' => 'Fitbit ChargeHR + Aria Scale', 'box_bg' => 'gold_box_bg', 'color' => '#557ac1', 'devices' => array('Fitbit ChargeHR', 'Aria Scale'), 'device_images' => array('plan_fitbit_charge_m.png'));
    } elseif ($plan == '$1,000,000') {
        return array('plan_type' => 'Ultimate', 'plan_amount' => '1m', 'plan_amount_full' => '1,000,000', 'image' => 'platinum_image', 'image_name' => 'Fitbit Surge + Aria Scale', 'box_bg' => 'platinum_box_bg', 'color' => '#bdd467', 'devices' => array('Fitbit Surge', 'Aria Scale'), 'device_images' => array('plan_fitbit_surge_m.png'));
    }
    return array('plan_type' => 'Basic', 'plan_amount' => '250k', 'plan_amount_full' => '250,000', 'image' => 'bronze_image', 'image_name' => 'Fitbit Flex + Aria Scale', 'box_bg' => 'bronze_box_bg', 'color' => '#d34b5c', 'devices' => array('Fitbit Flex', 'Aria Scale'), 'device_images' => array('plan_fitbit_flex_m.png'));
}

function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
         * Return array converted to object
         * Using __FUNCTION__ (Magic constant)
         * for recursive call
         */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

/**
 * add user data
 * @param $array array
 * @param $keySearch string 
 * @return boolean key found or not  
 */
function findKey($array, $keySearch) {
    foreach ($array as $key => $item) {
        if ($key == $keySearch) {
            //echo 'yes, it exists';
            return true;
        } else {
            if (is_array($item) && findKey($item, $keySearch)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * returns min and max date from array
 * @param $date1_timestamp string mindate timestamp
 * @param $date2_timestamp string maxdate timestamp
 * @return $months array list of all months between dates
 */
function get_months($date1_timestamp, $date2_timestamp) {
    $time1 = $date1_timestamp;
    $time2 = $date2_timestamp;

    $time1 = strtotime(date('Y-m', $time1) . '-01');
    $time2 = strtotime(date('Y-m', $time2) . '-01');

    $my = date('mY', $time2);

    $months[] = array('month_name_year' => date('M Y', $time1), 'timestamp' => $time1, 'month_num_year' => date('mY', $time1));

    while ($time1 < $time2) {
        $time1 = strtotime(date('Y-m-d', $time1) . ' +1 month');
        if (date('mY', $time1) != $my && ($time1 < $time2))
            $months[] = array('month_name_year' => date('M Y', $time1), 'timestamp' => $time1, 'month_num_year' => date('mY', $time1));
    }

    //$months[] = array(date('M Y', $time2),$time2,date('mY', $time2));
    $months[] = array('month_name_year' => date('M Y', $time2), 'timestamp' => $time2, 'month_num_year' => date('mY', $time2));
    $months = array_unique($months, SORT_REGULAR);
    //echo "<pre>";print_r($months);exit;
    return $months;
}

/**
 * returns users savings in terms of months
 * @param $user_profile_data array
 * @param $user_savings array 
 * @return $result array which consists of all months premiums  
 */
function getUserMonthlyPremium($user_profile_data, $user_savings) {
    //echo "<pre>"; print_r($user_savings);
    $result = array();
    $all_months = array();
    $current_date_timestamp = strtotime(date("Y-m-d"));
    $user_created_date_timestamp = strtotime($user_profile_data->created_time);
    //echo $current_date_timestamp;
   // echo $user_created_date_timestamp;exit;
    if ($user_created_date_timestamp < $current_date_timestamp) {
        $all_months = get_months($user_created_date_timestamp, $current_date_timestamp);
    }
    $user_monthly_savings = array();
    foreach ($user_savings as $key => $value) {
        $user_monthly_savings[mktime(0,0,0,$value['month'],1,$value['year'])][] = $value;
    }
    $i = 0;
    foreach ($all_months as $key => $value) {
    $flag = 0;
        foreach ($user_monthly_savings as $k => $v) {
            if($value['timestamp'] == $k) {
                $flag++;
            }
        }
        if ($flag > 0) {
            $final_array[$i] = $value;
            $res = searchMultiArray($user_savings, 'discount_name', 'Steps Discount');
            if ($res != "" && count($res) > 0) {
                $final_array[$i]['steps_goal_discount'] = $res['discount_percentage'];
            } else {
                $final_array[$i]['steps_goal_discount'] = 0;
            }
            $res = searchMultiArray($user_savings, 'discount_name', 'Weight Goal Discount');
            if ($res != "" && count($res) > 0) {
                $final_array[$i]['weight_goal_discount'] = $res['discount_percentage'];
            } else {
                $final_array[$i]['weight_goal_discount'] = 0;
            }
            $res = searchMultiArray($user_savings, 'discount_name', 'Weight Maintenance Discount');
            if ($res != "" && count($res) > 0) {
                $final_array[$i]['weight_maintenance_discount'] = $res['discount_percentage'];
            } else {
                $final_array[$i]['weight_maintenance_discount'] = 0;
            }

            $final_array[$i]['total_discount'] = $final_array[$i]['weight_goal_discount'] + $final_array[$i]['weight_maintenance_discount'] + $final_array[$i]['steps_goal_discount'];
            $final_array[$i]['premium'] = $user_profile_data->initial_premium - ($user_profile_data->initial_premium * $final_array[$i]['total_discount'] / 100);
        } else {
            $final_array[$i] = $value;
            $final_array[$i]['premium'] = $user_profile_data->initial_premium;
            $final_array[$i]['weight_goal_discount'] = 0;
            $final_array[$i]['weight_maintenance_discount'] = 0;
            $final_array[$i]['steps_goal_discount'] = 0;
            $final_array[$i]['total_discount'] = $final_array[$i]['weight_goal_discount'] + $final_array[$i]['weight_maintenance_discount'] + $final_array[$i]['steps_goal_discount'];
        }
        $i++;
    }
    return $final_array;
}

/**
 * returns users life time savings 
 * @param $user_profile_data array
 * @param $user_savings array 
 * @return $result array which consists of lifetime savings  
 */
function getUserLifetimeSavings($user_profile_data, $user_savings) {
    $return_array = array();
    //echo "<pre>";print_r($user_profile_data);exit;

    if (is_object($user_profile_data)) {
        $initial_premium_rate = $user_profile_data->initial_premium;
        $term_length = $user_profile_data->term_length;
    }
    $term_length = $term_length * 12;
    return $user_savings['lifetime_savings'] * $term_length;
}

function check_empty_values($assoc_array) {
    if (count($assoc_array) > 0) {
        foreach ($assoc_array as $key => $value) {
            if ($value == "")
                return true;
        }
        return false;
    }
}

function changeFitbitDateFormat($date) {
    $return_date_array = array();
    if ($date != "") {
        $date_array = explode("T", $date);
        $date = $date_array[0];
        $time = substr($date_array[1], 0, strlen($date_array[1]) - 4);
        $return_date_array = array('date' => $date, 'time' => $time);
    }
    return $return_date_array;
}
/**
 * getOS
 * @global type $user_agent
 * @return string
 */
function getOS() {
    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];

    
    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );

    foreach ($os_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }

    return $os_platform;
}
/**
 * getBrowser
 * @global type $user_agent
 * @return string
 */
function getBrowser() {
    $user_agent     =   $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown Browser";

    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );

    foreach ($browser_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    return $browser;
}

function arrayToObject($var){
    if (is_array($var)){
        $object = new stdClass();
        foreach ($var as $key => $value)
        {
            $object->$key = $value;
        }
        return $object;
    } else {
        return $var;
    }
}

/**
    * returns users savings in terms of months
    * @param $user_data array
    * @param $normal_height_weight_array array
    * @param $user_savings array
    * @param $user_savings array 
    * @return $amount string savings amount  
*/
function getUserSavingsAmount($user_data, $normal_height_weight_array, $today_steps, $today_weight )
{
     //echo "<pre>";print_r($user_data);exit;
     //echo "<pre>";print_r($normal_height_weight_array);exit;       

     $initial_premium_rate = $user_data->initial_premium;
     $steps_discount = $initial_premium_rate * (3/100);
     $weight_discount = $initial_premium_rate * (5/100);
     //echo $steps_discount;exit;   
    
     $each_step_cost = $steps_discount/270000;

     $steps_savings = $each_step_cost * $today_steps;
     //echo $steps_savings;exit;

     $user_data->height = str_ireplace('"', '\'', $user_data->height);

    
    //replace double quote with single quote for height comparison
    if(count($normal_height_weight_array) > 0)
    {    
        foreach($normal_height_weight_array as $key => $subarray) {
           foreach($subarray as $subkey => $subsubarray) {
              $normal_height_weight_array[$key][$subkey]['height'] = str_ireplace('"', '\'', $subsubarray['height']);
           }
        }
    }    

    //weight savings calculation START
     $height_min_max = searchMultiArray( $normal_height_weight_array , "height",$user_data->height);
     //echo "<pre>";print_r($height_min_max);exit;

     if( $user_data->weight < $height_min_max['normal_start'] && $today_weight > 0 && is_array($height_min_max) && count($height_min_max)>0 )
     {
        $weight_difference= $height_min_max['normal_start'] - $user_data->weight;
        $weight_change = $today_weight - $user_data->weight; 
     }
     elseif( $user_data->weight > $height_min_max['normal_end']  && $today_weight > 0  && is_array($height_min_max) && count($height_min_max)>0 )
     {
        $weight_difference= $user_data->weight - $height_min_max['normal_end'];
        $weight_change = $user_data->weight - $today_weight;    
     }
     else
     {
        $weight_difference = 0;
        $weight_change = 0; 
     }      

     //echo $weight_difference;exit;
     //echo $weight_difference." ".$weight_change;exit;
     $each_pount_cost = ($weight_difference > 0) ? ($weight_discount / $weight_difference) : 0;

     $weight_savings = $weight_change * $each_pount_cost;
     //echo $weight_savings;exit;

     if( $weight_savings > $weight_discount )
     {
        $weight_savings = $weight_discount; 
     }  

     //weight savings calculation END
      
     return array('steps_savings' => $steps_savings , 'weight_savings' => $weight_savings,'steps_discount'=>$steps_discount,'weight_discount'=>$weight_discount);
}


/**
    * returns formatted currency
    * @param $amount string
    * @return $return_amount string savings amount  
*/
function currencyFormat($amount)
{
    $return_amount = "$0";

    if( $amount < 0.10 )
    {
       $return_amount = round($amount*100)."&#65504;";          
    }
    else
    {   
        $return_amount = "$".number_format($amount,2);
    }    
    return $return_amount;
}


/**
    * returns todays_steps, weight and savings
    * @param $user_id int
    * @return $return_array array  
*/
function getLiveData( $user_id, $user_current_date )
{
    //echo $user_current_date;exit;
        $ci = & get_instance();

        //get user data
        $user_data = $ci->user->getUser(array('u.id' => $user_id));
        //echo "<pre>";print_r($user_data);exit;
        
        if($user_current_date == null)
        	$user_current_date = date("Y-m-d");
        //echo date("Y-m-d");
        //echo $user_current_date;exit;
        //get user today steps START
        $steps_data =  $ci->user->getUserSteps(array('us.user_id' => $user_id,'DATE_FORMAT(us.steps_date,"%Y-%m-%d")' => date("Y-m-d", strtotime ( $user_current_date )) ));
        $today_steps = 0;
        if( count($steps_data) > 0 )
        {
            //echo "<pre>";print_r($steps_data);exit;
            $today_steps = $steps_data[0]['steps'];
        }    
        //get user today steps end 

        //get user today weight START
        $weights_data =  $ci->user->getUserWeights(array('uw.user_id' => $user_id,'DATE_FORMAT(uw.weight_date,"%Y-%m-%d")<=' => date("Y-m-d", strtotime ( $user_current_date )) ));
        $today_weight = 0;
        if( count($weights_data) > 0 )
        {
            //echo "<pre>";print_r($steps_data);exit;
            $today_weight = $weights_data[0]['weight'];
            $weight_last_reported_date = $weights_data[0]['weight_date'];
            $last_reported = date('F Y', strtotime($weight_last_reported_date));

        }    
        //get user today weight end

        $today_user_savings="$0"; 
        //get height weight chart data START
        $height_weight_data = $ci->user->getHeightWeightChart();
        //get height weight chart data END

        $savings_array = getUserSavingsAmount($user_data , $height_weight_data, $today_steps, $today_weight ) ; 

        $today_savings =  $savings_array['steps_savings'] + $savings_array['weight_savings']; 

        $estimated_savings = $savings_array['steps_discount']+$savings_array['weight_discount'];

        return array( 'today_steps' => $today_steps , 
                      'today_weight' => $today_weight, 
                      'today_savings' => round($today_savings,2),
                      'estimated_savings' => round($estimated_savings,2),
                      'weight_last_reported_date' => $weight_last_reported_date);       

}

/**
    * returns user information in strucured format
    * @param $user_info array
    * @return $return_array array  
*/
function prepareSessionData($user_data) {
    $user_info = array();
    if (count($user_data) > 0) {
       $user_data = array_shift($user_data);
        $user_info['user_name'] = isset($user_data['first_name'])?$user_data['first_name']:"";
        $dob_array = explode(" ", $user_data['user_date_of_birth']);
        $dob_array = explode("-", $dob_array[0]);
        $user_info['user_month'] = ltrim($dob_array[1],0);
        $user_info['user_day'] = ltrim($dob_array[2],0);
        $user_info['user_year'] = $dob_array[0];
        $user_info['user_address_line1'] = $user_data['user_address_line1'];
        $user_info['user_address_line2'] = $user_data['user_address_line2'];
        $user_info['user_city'] = $user_data['user_address_city'];
        $user_info['user_state'] = $user_data['user_address_state'];
        $user_info['user_zipcode'] = $user_data['user_address_zipcode'];
        $user_info['user_dln'] = $user_data['user_driving_license'];
        $user_info['user_smoke'] = $user_data['user_smoke_habbit'];
        $user_info['user_ssn'] = $user_data['user_ssn'];
        $user_info['us_citizen'] = $user_data['user_us_citizen'];
        $user_info['user_born_city'] = $user_data['user_birth_city'];
        $user_info['user_born_country'] = $user_data['user_birth_state'];
        $user_info['user_cell_no'] = $user_data['user_phone_number'];
        $user_info['user_work_no'] = $user_data['user_work_number'];
        $user_info['user_home_no'] = $user_data['user_home_number'];
        $user_info['user_occupation'] = $user_data['user_occupation'];
        $user_info['user_employer'] = $user_data['user_employer'];
        $user_info['user_duration'] = $user_data['user_experience'];
        $user_info['user_work_city'] = $user_data['user_employer_city'];
        $user_info['user_work_state'] = $user_data['user_employer_state'];
        $user_info['spouse_info'] = $user_data['spouse_info'];
        $user_info['spouse_name'] = $user_data['spouse_name'];
        $dob_array = explode(" ", $user_data['spouse_date_of_birth']);
        $dob_array = explode("-", $dob_array[0]);
        $user_info['spouse_month'] = ltrim($dob_array[1],0);
        $user_info['spouse_day'] = ltrim($dob_array[2],0);
        $user_info['spouse_year'] = $dob_array[0];
        $user_info['spouse_address_line1'] = $user_data['spouse_address_line1'];
        $user_info['spouse_address_line2'] = $user_data['spouse_address_line2'];
        $user_info['spouse_us_citizen'] = $user_data['spouse_us_citizen'];
        $user_info['spouse_city'] = $user_data['spouse_address_city'];
        $user_info['spouse_state'] = $user_data['spouse_address_state'];
        $user_info['spouse_zipcode'] = $user_data['spouse_address_zipcode'];
        $user_info['spouse_dln'] = $user_data['spouse_driver_license_no'];
        $user_info['spouse_ssn'] = $user_data['spouse_ssn'];
        $user_info['spouse_born_city'] = $user_data['spouse_birth_city'];
        $user_info['spouse_born_country'] = $user_data['spouse_birth_state'];
        $user_info['spouse_cell_no'] = $user_data['spouse_phone_number'];
        $user_info['spouse_work_no'] = $user_data['spouse_work_number'];
        $user_info['spouse_home_no'] = $user_data['spouse_home_number'];
        $user_info['spouse_occupation'] = $user_data['spouse_occupation'];
        $user_info['spouse_employer'] = $user_data['spouse_employer'];
        $user_info['spouse_duration'] = $user_data['spouse_experience'];
        $user_info['spouse_work_city'] = $user_data['spouse_employer_city'];
        $user_info['spouse_work_state'] = $user_data['spouse_employer_state'];
        if ($user_data['owner_type'] == 5002) {
            $user_info['policy_manager'] = "trust";
        } else if ($user_data['owner_type'] == 5001) {
             $user_info['policy_manager'] = "person";
        } else {
            $user_info['policy_manager'] = "";
        }

        if ($user_data['person_status'] == 5003) {
            $user_info['policy_manager_person'] = "someone";
        } else if ($user_data['person_status'] == 5004) {
             $user_info['policy_manager_person'] = "me";
        } else {
            $user_info['policy_manager_person'] = "";
        }

        if ($user_data['join_owner_status'] == 5005) {
            $user_info['joint_owner'] = "yes";
        } else if ($user_data['join_owner_status'] == 5006) {
             $user_info['joint_owner'] = "no";
        } else {
            $user_info['joint_owner'] = "";
        }
        $user_info['trust_name'] = $user_data['trust_name'];
        $user_info['trust_address_line1'] = $user_data['trust_address_line1'];
        $user_info['trust_address_line2'] = $user_data['trust_address_line2'];
        $user_info['trust_city'] = $user_data['trust_address_city'];
        $user_info['trust_state'] = $user_data['trust_address_state'];
        $user_info['trust_zipcode'] = $user_data['trust_address_zipcode'];
        $user_info['person_name'] = $user_data['person_name'];
        $user_info['person_relationship'] = $user_data['person_owner_relation'];
        $user_info['person_taxid'] = $user_data['person_owner_ssn'];
        $user_info['person_address_line1'] = $user_data['person_address_line1'];
        $user_info['person_address_line2'] = $user_data['person_address_line2'];
        $user_info['person_city'] = $user_data['personaddress_city'];
        $user_info['person_state'] = $user_data['personaddress_state'];
        $user_info['person_zipcode'] = $user_data['personaddress_zipcode'];
        $user_info['primary_beneficiary'] = $user_data['primary_beneficiary'];
        $user_info['beneficiary_relation'] = $user_data['primary_benef_relation'];
        $user_info['contigent_beneficiary'] = $user_data['contingent_beneficiary'];
        $user_info['beneficiary_relation_to_you'] = $user_data['contingent_benef_relation'];
        $user_info['insured_name'] = $user_data['insured_name'];
        $user_info['insured_company'] = $user_data['compnay'];
        $user_info['insured_amount'] = $user_data['amount'];
        $user_info['insured_policy_number'] = $user_data['policy_number'];
        $user_info['insured_policy_pending'] = $user_data['insurance_pending'];
        $user_info['insured_year'] = $user_data['policy_issued_year'];
        $user_info['your_drug'] = $user_data['user_drug_dependency'];
        $user_info['spouse_drug'] = $user_data['user_spouse_dependency'];
        $user_info['bank_name'] = $user_data['bank_name'];
        $user_info['bank_ac_no'] = $user_data['account_number'];
        $user_info['bank_routing_no'] = $user_data['routing_number'];
        $user_info['bank_intial_settlement'] = $user_data['initial_settlement'];
        $user_info['bank_shortage'] = $user_data['shortage'];
        $user_info['bank_requirement'] = $user_data['requirement']; 
        $user_info['policy_logo'] = $user_data['user_policy_logo']; 
        $user_info['policy_premium'] = $user_data['premium_mode']; 
        $user_info['policy_term'] = $user_data['category']; 
    }
    
    return $user_info;
}