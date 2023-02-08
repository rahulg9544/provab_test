<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

//require APPPATH . 'libraries/REST_Controller.php';



class User extends MY_Controller  {

    public function __construct() { 

        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        
        // Load the user model
        $this->load->model('api/user_model','user_model');
        $this->load->model('api/m_main','M_main');
    }
    
    public function create_post() {

        // echo "test"; exit;

        $u = $this->post('email'); //Username Posted
        $p = $this->post('password'); //Pasword Posted
        $q = array('email' => $u); //For where query condition
        $kunci = $this->config->item('thekey');
        $invalidLogin = ['status' => 'Invalid Login']; //Respon if login invalid
        $val = $this->M_main->get_user($q)->row(); //Model to get single data row from database base on username

        // var_dump($val);
        // exit;

        if($this->M_main->get_user($q)->num_rows() == 0)
        {
            $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }
        $match = $val->password;   //Get password for user from database
        

        if($p == $match){ 

            // echo "hello";
            // exit;
            
            //Condition if password matched
        	$token['id'] = $val->id;  //From here
            $token['username'] = $u;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
            $output['token'] = JWT::encode($token,$kunci ); //This is the output token


         //   $this->set_response($output, REST_Controller::HTTP_OK); //This is the respon if success

            $this->response(['message' => 'User login successful.','token' => $output['token']], REST_Controller::HTTP_OK);

        }
        else {
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND); //This is the respon if failed
        }


       
    }


    public function adduser_post() {

        $jwt = $this->input->post('token');
        $kunci = $this->config->item('thekey');

        $first_name = $this->input->post('first_name');

        $last_name = $this->input->post('last_name');

        $password = $this->input->post('password');

        $email = $this->input->post('email');
    
        try {
          $decode = JWT::decode($jwt, $kunci, array('HS256'));
          
       } catch (Exception $e) {
           $invalid = ['status' => $e->getMessage()]; //Respon if credential invalid
           $this->response($invalid, 401);//401
           exit;
       }
    
      //  print_r($decode);
      //  exit;
    
        $q = array('id' => $decode->id);
    
        $invalidLogin = ['status' => 'Not a user'];
        
    
        if($this->M_main->get_user_id($q)->num_rows() == 0)
            {
                $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
            }
    
        else{
    
          $val = $this->M_main->get_user($q)->row();
    
          // print_r($val);
          // exit;
    
                  $data=array(
                      'first_name'=>$first_name,
                      'last_name'=>$last_name,
                      'password'=>$password,
                      'email'=>$email,
                    );

                    $res=$this->user_model->insertuser($data);


                    if($res==1)
                    {
                       $this->response([
                           'message' => 'success'
                       ], REST_Controller::HTTP_OK);
                    }
                    else
                    {
                       $this->response([
                           'message' => 'failed',
                       ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                  
                //   $this->response([
                //     'data' => $result
                // ], REST_Controller::HTTP_OK);
    
            }

       
    }
    
    

}