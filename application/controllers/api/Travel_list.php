<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

//require APPPATH . 'libraries/REST_Controller.php';

class Travel_list extends MY_Controller  {

    public function __construct() { 

        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        
        // Load the user model
        $this->load->model('api/travel_model','travel_model');
        $this->load->model('api/m_main','M_main');
    }
    
    public function createtravellist_put() {


        $jwt = $this->input->post('token');
        $kunci = $this->config->item('thekey');

        $user_id = $this->input->post('user_id');

        $place_name = $this->input->post('place_name');

        $description = $this->input->post('description');


        if(empty($user_id) || empty($place_name) || empty($description)){

               $this->response([
                   'message' => 'Please fill all the fields',
               ], REST_Controller::HTTP_BAD_REQUEST);

            }
    
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
                      'user_id'=>$user_id,
                      'place_name'=>$place_name,
                      'description'=>$description

                    );

                    $res=$this->travel_model->inserttravellist($data);


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



    public function updatetravellist_post() {

        $jwt = $this->input->post('token');
        $kunci = $this->config->item('thekey');

        $user_id = $this->input->post('user_id');

        $place_name = $this->input->post('place_name');

        $description = $this->input->post('description');

        if(empty($user_id) || empty($place_name) || empty($description)){

            $this->response([
                'message' => 'Please fill all the fields',
            ], REST_Controller::HTTP_BAD_REQUEST);

         }
    
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
                      'place_name'=>$place_name,
                      'description'=>$description

                    );

                    $res=$this->travel_model->updatetravellist($data,$user_id);

                    if($res==true)
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

    
            }


       
    }



    public function viewtravellist_get() {

        $jwt = $this->input->post('token');
        $kunci = $this->config->item('thekey');

        // $user_id = $this->input->post('user_id');

        // $place_name = $this->input->post('place_name');

        // $description = $this->input->post('description');
    
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
       
                    $res=$this->travel_model->viewtravellist();

                    if($res==true)
                    {
                       $this->response([
                           'data' => $res
                       ], REST_Controller::HTTP_OK);
                    }
                    else
                    {
                       $this->response([
                           'message' => 'failed',
                       ], REST_Controller::HTTP_BAD_REQUEST);
                    }

    
            }


       
    }


    public function deletetravellist_post() {

        $jwt = $this->input->post('token');
        $kunci = $this->config->item('thekey');

        $user_id = $this->input->post('user_id');

    
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

                    $res=$this->travel_model->deletetravellist($user_id);

                    if($res==true)
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

    
            }


       
    }
    
    

}