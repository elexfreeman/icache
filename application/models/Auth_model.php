<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*Модель логинов*/
/*
 Описание глобалов
^Test.VACUsers("admin","group")="administrator"
^Test.VACUsers("admin","password")="!1qazxsw2"


 * */

class Auth_model extends CI_Model
{
    public $cacheDB;


    public $UsersCache = 'Test.VACUsers';

    public function __construct()
    {

        $this->cacheDB = $this->load->database('default', TRUE);

        $this->load->helper('url');
    }

    public function GetAllUSers()
    {
        $query = $this->db->get('lpu_users');
        return $query->result_array();
    }

    public function IsLogin()
    {
        if($this->session->has_userdata('username'))
        {
            return true;
        }
        else return false;
    }

    /*Проверка на существование юзера*/
    public function  GetUserByNameAndPass($username,$password)
    {
       $username = $this->security->xss_clean($username);
        $password = $this->security->xss_clean($password);
        $sql="SELECT * FROM [LOGINS].[dbo].[lpu_users] where

(username='".$username."')
and(password='".$password."')
";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /*выдает имя зареганого пользователя*/
    function GetloginUser()
    {
        if($this->IsLogin())
        {
            return $this->session->username;
        }
        else return false;
    }

    function GetLogoutUrl()
    {
        return base_url('auth/logout');
    }




}