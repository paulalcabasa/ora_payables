<?php

class MY_Controller extends CI_Controller {

    private $system_id;
    private $user_id;
    private $user_type;
    private $user_type_id;
    private $user_level;
    private $user_full_name;

    public function __construct() {
        parent::__construct();
        //$route = $this->router->directory . $this->router->fetch_class();

        // echo $route;

        // Ignore any controllers not to be effected 
       /* $ignore = array(
            'dirname/controllername',
        );
        */
        // If the user has not logged in will redirect back to login
        //&& !in_array($route, $ignore)
        if (!$this->session->userdata('fnbi_user_name') ) {
            $this->session->unset_userdata('fnbi_user_name');
            redirect('http://ecommerce5/finance_bi/' . 'login');
        }

        $this->system_id = 1;
        $user_access = $this->session->userdata('fnbi_system_access');
        $this->user_id = $this->session->userdata('fnbi_user_id');
        $this->user_full_name = $this->session->userdata('fnbi_full_name');
        foreach($user_access as $access){
            if($access->SYSTEM_ID == $this->system_id){
                $this->user_type = $access->USER_TYPE_NAME;
                $this->user_type_id = $access->USER_TYPE_ID;
                $this->user_level = $access->USER_LEVEL;
            }
        }

      
    }

    public function get_user_details(){
        $user_details = array (
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'user_type_id' => $this->user_type_id,
            'user_level' => $this->user_level,
            'user_full_name' => $this->user_full_name
        );
        return (object) $user_details;
    }
}