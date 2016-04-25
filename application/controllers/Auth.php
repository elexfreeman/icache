<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {


    public $data;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');
        $this->load->model('auth_model');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index()
    {
        if($this->auth_model->IsLogin())
        {
            header('Location: '.base_url());
            exit;
        }
        else
        {
            $this->data['error']='1';
            $this->load->view('nf_head');
            $this->load->view('auth/loginform',$this->data);
            $this->load->view('nf_footer');
       }
    }

    /*Событие входа пользователя*/
    public function login()
    {


        $auth=$this->auth_model->GetUserByNameAndPass($_POST['username'],$_POST['password']);
       if( $auth->login==$_POST['username'])
        {

            $this->session->set_userdata('auth', $auth);
            header('Location: '.base_url());
            exit;
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }


    }

    public function logout()
    {
        unset($_SESSION['auth']);
        header('Location: '.base_url('auth'));
        exit;
    }
}
