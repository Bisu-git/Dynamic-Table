<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Edit_schema extends CI_Controller
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
        $data['all_table'] = $this->Schema_model->get_all_tables();
        // echo '<pre>';print_r($data);die('hii');
        $this->load->view('edit_update_schema' , $data);
    }


    public function get_columns() 
    {
        $table_name = $this->input->post('table_name');
        $columns = $this->Schema_model->get_columns($table_name);
        // echo '<pre>';print_r($columns);die('hii');
        echo json_encode($columns);
    }

public function processAction() {
    $data = $this->input->post();
    // echo '<pre>';print_r($data);die('hii');

    $result = $this->Schema_model->alter_table($data);

    if ($result) {
        $this->session->set_flashdata('message', 'Schema updated successfully!');
    } else {
        $this->session->set_flashdata('message', 'Failed to update schema!');
    }

    redirect('Edit_schema');
}

}
