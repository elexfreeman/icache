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

    public function GetAllUsers()
    {

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
        $username=mb_convert_encoding($username,"Windows-1251","UTF-8");
        $password=mb_convert_encoding($password,"Windows-1251","UTF-8");
        $sql="select ".$this->UsersCache."_GetUser('$username','$password') a";

        $query = $this->cacheDB->query($sql);
        $row=$query->result_array();
        $row = $row[0]['a'];
        $row=mb_convert_encoding($row,"UTF-8","Windows-1251");
        $row=json_decode($row);
        return $row;
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