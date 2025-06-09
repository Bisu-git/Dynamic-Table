<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Create_schema extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (! $this->session->userdata('uid'))
            redirect('Login');
        $this->load->model('Home_model');
        $this->load->model('Schema_model');
    }

    public function index() {
        $this->load->view('create_schema');
    }

    public function createTable() {
        $table_name = $this->input->post('table_name');
        $columns = $this->input->post('columns');
        // echo "<pre>";
        // print_r($columns);
        // die('hii');
        $result = $this->Schema_model->createDynamicTable($table_name, $columns);

        if ($result) {
            $this->session->set_flashdata('message', "Table '$table_name' created successfully!");
        } else {
            $this->session->set_flashdata('message', "Failed to create table!");
        }

        redirect('Create_schema');
    }

}
