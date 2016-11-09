<?php

class PostData {

    public $CI;

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->helper('array');
        //$this->CI->load->helper('htmlpurifIer');
    }

    /**
     * getSetupPlanData
     * @return array Post Data
     */
    public function getSetupPlanData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $startdateFormat = explode("/", $this->CI->input->post('plan_start_date'));
        if (!empty($startdateFormat) && isset($startdateFormat[2]) && $startdateFormat[2] != "") {
            $start_date = $startdateFormat[2] . '-' . $startdateFormat[0] . '-' . $startdateFormat[1];
        } else {
            $start_date = "";
        }
        $enddateFormat = explode("/", $this->CI->input->post('plan_end_date'));
        if (!empty($enddateFormat) && isset($enddateFormat[2]) && $enddateFormat[2] != "") {
            $end_date = $enddateFormat[2] . '-' . $enddateFormat[0] . '-' . $enddateFormat[1];
        } else {
            $end_date = "";
        }
        
        $postdata = array('plan_name' => $this->CI->input->post('plan_name'),
            'base_premium' => $this->CI->input->post('base_premium'),
            'plan_start_date' => $start_date,
            'plan_end_date' => $end_date,
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
    /**
     * getSetupDiseaseData
     * @return array Post Data
     */
    public function getSetupDiseaseData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $postdata = array('disease_name' => $this->CI->input->post('disease_name'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
    
    /**
     * getSetupDevicesData
     * @return array Post Data
     */
    public function getSetupDeviceData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
      
        
        $postdata = array('device_name' => $this->CI->input->post('device_name'),
            'cost' => $this->CI->input->post('cost'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
    /**
     * getSetupChallengesData
     * @return array Post Data
     */
    public function getSetupChallengeData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $startdateFormat = explode("/", $this->CI->input->post('start_date'));
        if (!empty($startdateFormat) && isset($startdateFormat[2]) && $startdateFormat[2] != "") {
            $start_date = $startdateFormat[2] . '-' . $startdateFormat[0] . '-' . $startdateFormat[1];
        } else {
            $start_date = "";
        }
        $enddateFormat = explode("/", $this->CI->input->post('end_date'));
        if (!empty($enddateFormat) && isset($enddateFormat[2]) && $enddateFormat[2] != "") {
            $end_date = $enddateFormat[2] . '-' . $enddateFormat[0] . '-' . $enddateFormat[1];
        } else {
            $end_date = "";
        }
        
        $postdata = array('plan_id' => $this->CI->input->post('plan_id'),
            'name' => $this->CI->input->post('name'),
            'goal' => $this->CI->input->post('goal'),
            'type' => $this->CI->input->post('type'),
            'discount' => $this->CI->input->post('discount'),
            'frequency' => $this->CI->input->post('frequency'),
            'cheat_days' => $this->CI->input->post('cheat_days'),
            'start_date' => $start_date,
            'end_date' => $end_date,
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
    /**
     * getSetupDiscountData
     * @return array Post Data
     */
    public function getSetupDiscountData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $postdata = array('discount_name' => $this->CI->input->post('discount_name'),
            'discount_type' => $this->CI->input->post('discount_type'),
            'discount_percentage' => $this->CI->input->post('discount_percentage'),
            'cheat_days' => $this->CI->input->post('cheat_days'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
     /**
     * getSetupExpenseData
     * @return array Post Data
     */
    public function getSetupExpenseData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $postdata = array('expense_name' => $this->CI->input->post('expense_name'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
      /**
     * getSetupEmailTemplateData
     * @return array Post Data
     */
    public function getSetupEmailTemplatesData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $postdata = array('title' => $this->CI->input->post('title'),
            'email_subject' => $this->CI->input->post('email_subject'),
            'email_body' => $this->CI->input->post('email_body'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
    
      /**
     * getSetupRemainderData
     * @return array Post Data
     */
    public function getSetupRemainderData() {
        $sessionDetails = $this->CI->session->userdata("admin_session");
        $userSessionId = $sessionDetails["user_session_id"];
        $postdata = array('remainder_name' => $this->CI->input->post('remainder_name'),
            'description' => $this->CI->input->post('description'),
            'template_id' => $this->CI->input->post('template_id'),
            'is_hours' => $this->CI->input->post('is_hours'),
            'remainder_hours' => $this->CI->input->post('remainder_hours'),
            'is_days' => $this->CI->input->post('is_days'),
            'remainder_days' => $this->CI->input->post('remainder_days'),
            'row_status' => 1,
            'user_session_id' => $userSessionId,
            'created_time' => date("Y-m-d H:i:s")
        );

        return $postdata;
    }
   
}
