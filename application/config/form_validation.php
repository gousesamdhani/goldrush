<?php

/**
 * FormValidation.php
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category    FormValidation.php
 * @package     Config
 * @author      Vijay.Ch <vijay.ch@vendus.com>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT License
 * @link        http://localhost/medicare/index.php/users
 * @dateCreated 11/05/2015  MM/DD/YYYY
 * @dateUpdated 11/05/2015  MM/DD/YYYY 
 * @functions   0
 */

$config = array(
    'setupPlan' => array
        (
        array(
            'field' => 'plan_name',
            'label' => 'Plan Name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkplannameexist'
        ),
        array(
            'field' => 'base_premium',
            'label' => 'Base Premium',
            'rules' => 'trim|required|min_length[3]|max_length[100]|numeric'
        ),
        array(
            'field' => 'plan_start_date',
            'label' => 'Plan Start Date',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'plan_end_date',
            'label' => 'Plan End Date',
            'rules' => 'trim|required'
        )
    ),
    'setupDisease' => array
        (
        array(
            'field' => 'disease_name',
            'label' => 'Disease Name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkdiseasenameexist'
        )
    ),
    'setupDevice' => array
        (
        array(
            'field' => 'device_name',
            'label' => 'Device name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkdevicenameexist'
        ),
        array(
            'field' => 'cost',
            'label' => 'Base Premium',
            'rules' => 'trim|required|min_length[3]|max_length[100]|numeric'
        )
    ),
    'setupChallenge' => array
        (
        array(
            'field' => 'plan_id',
            'label' => 'Plan Name',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'name',
            'label' => 'Challenge Name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkchallengenameexist'
        ),
        array(
            'field' => 'type',
            'label' => 'type',
            'rules' => 'trim|required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'goal',
            'label' => 'Goal',
            'rules' => 'trim|required|min_length[1]|numeric'
        ),
        array(
            'field' => 'discount',
            'label' => 'Discount',
            'rules' => 'trim|required|min_length[1]|numeric'
        ),
        array(
            'field' => 'start_date',
            'label' => 'Start Date',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'end_date',
            'label' => 'End Date',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'frequency',
            'label' => 'Frequency',
            'rules' => 'trim|required|min_length[2]|max_length[100]|numeric'
        )
    ),
    'setupDiscount' => array
        (
        array(
            'field' => 'discount_name',
            'label' => 'discount_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkdiscountnameexist'
        ),
        array(
            'field' => 'discount_type',
            'label' => 'discount_type',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'discount_percentage',
            'label' => 'Discount Percentage',
            'rules' => 'trim|required|min_length[1]|numeric'
        ),
        array(
            'field' => 'cheat_days',
            'label' => 'Cheat Days',
            'rules' => 'trim|required|min_length[2]|max_length[100]|numeric'
        )    
    ),
    'setupExpense' => array
        (
        array(
            'field' => 'expense_name',
            'label' => 'Expense Name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkexpensenameexist'
        )
    ),
    'setupEmailTemplate' => array
        (
        array(
            'field' => 'title',
            'label' => 'title',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checktemplatetitleexist'
        ),
       
        array(
            'field' => 'email_subject',
            'label' => 'email_subject',
            'rules' => 'trim|required|min_length[3]|max_length[255]'
        ),
        array(
            'field' => 'email_body',
            'label' => 'email_body',
            'rules' => 'trim|required|min_length[3]'
        )
    ),
    
    'setupRemainder' => array
        (
        array(
            'field' => 'remainder_name',
            'label' => 'remainder_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|callback_checkremaindernameexist'
        ),
        array(
            'field' => 'description',
            'label' => 'description',
            'rules' => 'trim|required|min_length[3]'
        ),
        array(
            'field' => 'template_id',
            'label' => 'template_id',
            'rules' => 'trim|required',
            'message' => 'This field is required'
        )
    ),
    'editAdminProfile' => array
        (
        array(
            'field' => 'first_name',
            'label' => 'first_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'last_name',
            'label' => 'last_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'trim|required|email|'
            
        ),
        array(
            'field' => 'phone_number',
            'label' => 'phone_number',
            'rules' => 'trim|required|min_length[10]'
            
        ),
        array(
            'field' => 'address',
            'label' => 'address',
            'rules' => 'trim|required|max_length[255]'
            
        )
        
    ),
     'changeAdminPassword' => array
        (
        array(
            'field' => 'current_passowrd',
            'label' => 'current_passowrd',
            'rules' => 'trim|required|min_length[8]|max_length[16]|callback_checkCurrentPassword'
        ),
        array(
            'field' => 'new_password',
            'label' => 'new_password',
            'rules' => 'trim|required|min_length[8]|max_length[16]'
        ),
        array(
            'field' => 'confirm_password',
            'label' => 'confirm_password',
            'rules' => 'trim|required|min_length[8]|max_length[16]|callback_checkPasswordMatch'
            
        )
    ),
    'editUserProfile' => array
        (
        array(
            'field' => 'first_name',
            'label' => 'first_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'last_name',
            'label' => 'last_name',
            'rules' => 'trim|required|min_length[3]|max_length[100]'
        ),
        array(
            'field' => 'email',
            'label' => 'email',
            'rules' => 'trim|required|email|'
            
        ),
        array(
            'field' => 'phone_number',
            'label' => 'phone_number',
            'rules' => 'trim|required|min_length[10]'
            
        ),
        array(
            'field' => 'address',
            'label' => 'address',
            'rules' => 'trim|required|max_length[255]'
            
        )
        
    ),
    'addSurvey' => array
        (
        array(
            'field' => 'survey_name',
            'label' => 'Survey Name',
            'rules' => 'trim|required|min_length[3]|max_length[100]|alpha'
        ),
        array(
            'field' => 'no_questions',
            'label' => 'No.of Questions',
            'rules' => 'trim|required|max_length[100]|numeric'
        )
        
    )

);
