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

    public function register_user()
    {
        $this->load->view('resister_user');
    }


    public function registerUser() {
        $data = $this->input->post();

        // Simple validation (you can expand it later)
        if ($data['userPassword'] !== $data['confirmPassword']) {
            $this->session->set_flashdata('messages', 'Passwords do not match');
            redirect('AuthController/showRegister');
            return;
        }
        $userData = [
            'firstName'     => $data['firstName'],
            'lastName'      => $data['lastName'],
            'emailId'          => $data['emailId'],
            'mobileNumber'         => $data['mobileNumber'],
            // 'userPassword'       => password_hash($data['userPassword'], PASSWORD_DEFAULT),
            'userPassword'       => $data['userPassword'],
            'isActive'      => 1,
            'lastUpdationDate'     => date('Y-m-d H:i:s')
        ];

        $this->Home_model->insert_user($userData);
        $this->session->set_flashdata('messages', 'Registration successful!');
        redirect('Login/register_user');
    }

}
