<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {

	/*переменная для вывода в view*/
	public $data;

	public function __construct()
	{
		parent::__construct();
		/*Загружаем  библиотеку сессий*/
		$this->load->library('session');
		/*Загружаем модели*/

		$this->load->model('patient_model');
		/*Закгружаем хелперы*/
		$this->load->helper('form');
		$this->load->helper('url');

	}

	/*Загрузка из Cache*/
	public function LoadFromCache($tablename)
	{
		echo $tablename;
	}

	/*Загрузка из SQL*/
	public function load()
	{

		$this->load->view('head');
		echo "<pre>";
		$this->patient_model->LoadPatients();
		echo "</pre>";
		$this->load->view('footer');
	}




	/*Выводит данные об пациенте*/
	public function index()
	{
		$this->load->view('head');
		//$this->data['patients']=$this->patient_model->GetaTop100();
		//$this->data['test']=$this->patient_model->test1();
		$this->load->view('patients/search_form',$this->data);
		$this->load->view('patients/index',$this->data);
		$this->load->view('footer');

	}

	public function get_by_mip($mip)
	{
		print_r($this->patient_model->get_next_by_mip($mip));
	}

	/*Загрузка инфы по вакцинации*/
	public function load_vaccination()
	{
		$this->patient_model->load_vaccination();
	}



}
