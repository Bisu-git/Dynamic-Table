<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Home_model');
    }

    public function index()
    {
        $this->load->view('login');
    }

    public function login()
    {
        $this->form_validation->set_rules('emailid', 'Email id', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run()) {
            $emailid = $this->input->post('emailid');
            $password = $this->input->post('password');
            $status = 1;

            $validate = $this->Home_model->validatelogin($emailid, $password, $status);
            if ($validate) {
                $this->session->set_userdata('uid', $validate);
                $this->session->set_flashdata('success', 'Login successful!');
                return redirect('Home');
            } else {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                return redirect('Login');
            }
        } else {
            $this->session->set_flashdata('error', validation_errors());
            return redirect('Login');
        }
    }


    public function logout()
    {
        $this->session->unset_userdata('uid');
        $this->session->sess_destroy();
        return redirect('Login');
    }

}
