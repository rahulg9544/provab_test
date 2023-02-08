<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Travel_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

        // Load the database library
        $this->load->database();

        $this->userTbl = 'travel_list';
    }

    function inserttravellist($data)
    {
        $query = $this->db->insert('travel_list', $data);
        return $query;
    }


    public function updatetravellist($data, $id)
    {

        //update user data in users table
        $update = $this->db->update($this->userTbl, $data, array('id' => $id));

        //return the status
        return $update ? true : false;
    }

    public function viewtravellist()
    {

        $this->db->select('user.first_name,travel_list.place_name,travel_list.description');
        $this->db->from('travel_list');
        $this->db->join('user', 'travel_list.user_id = user.id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }

    /*
     * Delete user data
     */
    public function deletetravellist($id)
    {
        //update user from users table
        $delete = $this->db->delete('travel_list', array('id' => $id));
        //return the status
        return $delete ? true : false;
    }
}
