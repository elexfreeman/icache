<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*контроллер главной страницы*/
class Mainpage extends CI_Controller {

    /*тут хранятся настройки, закгружаются в конструкторе*/
    public $settings;
    public $data;

    public function __construct()
    {
        parent::__construct();

        parent::__construct();
        /*Загружаем  библиотеку сессий*/
        $this->load->library('session');

        /*Загружаем библиотеку вставки в каше*/
        $this->load->library('icache');
        $this->icache->init('default');

        /*Загружаем модели*/


        /*Закгружаем хелперы*/
        $this->load->helper('form');
        $this->load->helper('url');

        $this->load->model('auth_model');
    }

	public function index()
	{
        /*переменные для языков описанны тут: \application\language\*/
        $this->data['text_h1']=$this->lang->line('text_h1');;
        $this->data['text_description']=$this->lang->line('text_description');


        if( $this->auth_model->IsLogin())
        {

            $this->load->view('nf_head',$this->data);
            $this->load->view('navbar/nf_admin_topnav',$this->data);
            /*шаблон страницы*/
            $this->load->view('main');

            $this->load->view('navbar/nf_admin',$this->data);

            $this->load->view('nf_footer',$this->data);
        }
        else
        {
            header('Location: '.base_url('auth'));
            exit;
        }


	}
}
