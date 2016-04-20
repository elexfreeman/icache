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
            $this->load->view('head');
            $this->load->view('auth/loginform',$this->data);
            $this->load->view('footer');
        }
    }

    /*Событие входа пользователя*/
    public function login()
    {
        if( count($this->auth_model->GetUserByNameAndPass($_POST['username'],$_POST['password']))>0)
        {
            /*Если есть такой юзер */

            $this->session->set_userdata('username', $_POST['username']);
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
        unset($_SESSION['username']);
        header('Location: '.base_url('auth'));
        exit;
    }
}
