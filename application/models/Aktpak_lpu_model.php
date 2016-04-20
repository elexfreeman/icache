<?php
/**
 * Created by PhpStorm.
 * User: cod_llo
 * Date: 11.03.16
 * Time: 17:11
 */
/*Модель для работой с докторами
пример как описывать любую модель данных для приложения
*/

/*
[AKTPAK].[AKPC_DOCTORS]
VACAKTPAKLPU[$LPUCODE]
VACAKTPAKLPU[$LPUCODE]['TYPE_S']
VACAKTPAKLPU[$LPUCODE]['NAME']
VACAKTPAKLPU[$LPUCODE]['NAME_L']
VACAKTPAKLPU[$LPUCODE]['NAME_S']
VACAKTPAKLPU[$LPUCODE]['OMS']

*/
class Aktpak_lpu_model extends CI_Model
{
    public $cacheDB;
    public $srv224DB;

    /*Имя базы*/
    public $GlobalDB = "";

    /*Глобал для Докторов*/
    public $LPUCache = "VACAKTPAKLPU";


    public function __construct()
    {
        $this->cacheDB = $this->load->database('default', TRUE);
        $this->srv224DB = $this->load->database('srv224', TRUE);
        date_default_timezone_set('Europe/London');
        $this->load->helper('url');
        /*Имя базы*/
        $this->GlobalDB=$this->config->item('cache_db_name');
        $this->load->library('icache');
        $this->icache->init('default');
        $this->load->model('aktpak_model');
    }

    /*Вставляет/обновляет данные об докторе*/
    /*вставка и обновление идет паралельно*/
    /*особенноость Cache*/
    /*$doctor - массив */
    public function incert_update($lpu)
    {
        $this->icache->update($this->LPUCache,$lpu);  // Object instances will always be lower case
    }

    public function get($LPUCODE)
    {

    }

    /*Загружает справочник докторов из актпака*/
    public function load_from_aktpak()
    {
        $objects=$this->aktpak_model->GetTableSrv224('AKPC_LPU');
        foreach ($objects as $obj_t)
        {
            unset($obj);
            $obj = array();
            $LPUCODE=$obj_t->LPUCODE;
            $obj[$LPUCODE]['TYPE_S'] = $obj_t->TYPE_S;
            $obj[$LPUCODE]['NAME'] = $obj_t->NAME;
            $obj[$LPUCODE]['NAME_L'] = $obj_t->NAME_L;
            $obj[$LPUCODE]['OMS'] = $obj_t->OMS;
            $obj[$LPUCODE]['PHONE'] = $obj_t->PHONE;
            $obj[$LPUCODE]['E_MAIL'] = $obj_t->E_MAIL;


            $this->incert_update($obj);
            echo $LPUCODE."\n";
        }
    }





}