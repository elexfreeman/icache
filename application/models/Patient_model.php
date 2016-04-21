<?php
/**
 * Created by PhpStorm.
 * User: cod_llo
 * Date: 11.03.16
 * Time: 17:11
 */
/*Модель для работой с Пациентом*/


/*

Структура глобала
VACPatientTree

Верхний уровень MasterIndexPatient
VACPatientTree[$mip]
Персональные данные
VACPatientTree[$mip]['personal_data']
VACPatientTree[$mip]['personal_data'][$id]['familyName']
VACPatientTree[$mip]['personal_data'][$id]['givenName']
VACPatientTree[$mip]['personal_data'][$id]['middleName']
VACPatientTree[$mip]['personal_data'][$id]['dob']
VACPatientTree[$mip]['personal_data'][$id]['Sex']
VACPatientTree[$mip]['personal_data'][$id]['Snils']
VACPatientTree[$mip]['personal_data'][$id]['Ein']

Участок обслуживания
VACPatientTree[$mip]['ServiceArea']

Адрес проживания
VACPatientTree[$mip]['LivingAddress']
VACPatientTree[$mip]['LivingAddress'][$DateModified]
VACPatientTree[$mip]['LivingAddress'][$DateModified]['Building']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['BuildingLtr']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['Apartment']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['ApartmentLtr']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['BuildingCrp']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['BuildingCrp']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['Street']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['RegionName']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['DistrictName']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['CityName']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['TownName']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['Description']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['OkatoCodeTerInt']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['OkatoCode1Int']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['OkatoCode2Int']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['OkatoCode3Int']
VACPatientTree[$mip]['LivingAddress'][$DateModified]['OkatoCodeRazdel']

Адрес регистрации
VACPatientTree[$mip]['RegistrationAddress']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['Building']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['BuildingLtr']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['Apartment']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['ApartmentLtr']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['BuildingCrp']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['BuildingCrp']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['Street']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['RegionName']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['DistrictName']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['CityName']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['TownName']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['Description']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['OkatoCodeTerInt']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['OkatoCode1Int']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['OkatoCode2Int']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['OkatoCode3Int']
VACPatientTree[$mip]['RegistrationAddress'][$DateModified]['OkatoCodeRazdel']

Документ
VACPatientTree[$mip]['document'][$id]
VACPatientTree[$mip]['document'][$id]['type']
VACPatientTree[$mip]['document'][$id]['Serie']
VACPatientTree[$mip]['document'][$id]['SerieNumber']
VACPatientTree[$mip]['document'][$id]['IssuedBy']
VACPatientTree[$mip]['document'][$id]['IssueDate']

Полюсы
VACPatientTree[$mip]['policy'][$id]['number']
VACPatientTree[$mip]['policy'][$id]['Serie']
VACPatientTree[$mip]['policy'][$id]['InsuranceCompanyCode']
VACPatientTree[$mip]['policy'][$id]['DateAdded']

Карта
VACPatientTree[$mip]['card']



VACPatientTree[$mip]['goal_group']
VACPatientTree[$mip]['med_exemption']

VACPatientTree[$mip]['med_exemption']

[AKTPAK].[AKPC_DOCTORS]
VACAKTPAKDoctors[$DRCODE]
VACAKTPAKDoctors[$DRCODE]['SHORT_NAME']
VACAKTPAKDoctors[$DRCODE]['NAME']
VACAKTPAKDoctors[$DRCODE]['SURNAME']
VACAKTPAKDoctors[$DRCODE]['SECNAME']
VACAKTPAKDoctors[$DRCODE]['DBSOURCE']
VACAKTPAKDoctors[$DRCODE]['PENSION']
VACAKTPAKDoctors[$DRCODE]['LPUWORK']
VACAKTPAKDoctors[$DRCODE]['CODEFRMP']

[AKTPAK].[AKPC_LPU]
VACAKTPAKLPU[$LPUCODE]
VACAKTPAKLPU[$LPUCODE]['NAME']







 * */
class Patient_model extends CI_Model
{
    public $cacheDB;
    public $srv224DB;

    public $PatientsCache = 'Test.VACPatients';
    public $PatientsSQL = '[vaccination2].[dbo].[VACD_Patient]';

    public function __construct()
    {
        $this->cacheDB = $this->load->database('default', TRUE);
        //$this->srv224DB = $this->load->database('srv224', TRUE);
        $this->srvEreg = $this->load->database('srvEreg', TRUE);
        date_default_timezone_set('Europe/London');
        $this->load->helper('url');
        /*Загружаем библиотеку вставки в каше*/
        $this->load->library('icache');
        $this->icache->init('default');
        /*загружаем библиотеку поиска mip*/
        $this->load->model('mpi_model');
    }

    /*Загружаем всех пациентов из базы вакцины*/
    /*вставляем в cache и проставляем мастер индекс*/
    public function load_from_vac()
    {
        $sql="SELECT top 100 [Id]
      ,[FirstName]
      ,[MiddleName]
      ,[LastName]
      ,[FullName]
      ,[BirthDate]
      ,[Sex]
      ,[Enp]
      ,[Snils]
      ,[LpuId]
      ,[ServiceAreaId]
      ,[DateModified]
      ,[LivingAddressId]
      ,[RegistrationAddressId]
      ,[WorkPhone]
      ,[MobilePhone]
      ,[OrganizationId]
      ,[Comment]
      ,[Ein]
      ,[Mkey]
      ,[SocialStatusId]
      ,[PolCounter]
      ,[PolLpuin]
      ,[PolExCounter]
      ,[PolExLpuin]
      ,[AddressText]
  FROM [vaccination2].[dbo].[VACD_Patient]";

    }

    /*Поиск пациентов*/
    public function Search()
    {

    }

    /*Выдает первые 100 пациентов*/
    function GetaTop100()
    {
        $sql = 'select top 100 FirstName,MiddleName,LastName,BirthDate, Enp, Sex, Snils from ' . $this->PatientsCache;

        $query = $this->cacheDB->query($sql);
        return $query->result_array();
    }


    /*загружает мастер-индексы пациентов и выстраивает базу их же*/
    public function LoadPatients()
    {

        /*составляем запро на кажды 10-к*/
        $year = 1900;

        while ($year < 2100) {
            $yeark = $year + 2;
            $sql = "
  Select
  patient.lastName,patient.FirstName,patient.middleName,patient.BirthDate

  , Count(*) p_count
  from [vaccination2].[dbo].[VACD_Patient]  patient
  where
   BirthDate   BETWEEN '" . $year . "0101 00:00:00' AND '" . $yeark . "0101 00:00:00'
   and enp is not null

  Group by patient.FirstName,patient.lastName,patient.middleName,BirthDate

  having
    --Count(*) > 1 and
     FirstName is not null
    and lastName is not null
    and middleName is not null
    and BirthDate is not null

    and FirstName <>''
    and lastName <>''
    and middleName <>''

order by Lastname
  ";

            echo "BETWEEN " . $year . " AND " . $yeark . "\n";
            /*Получаем пациентов из базы*/
            echo "SEARCH FROM SQL \n";
            /*освобождаем память*/
            unset($query);
            unset($patients);
            $query = $this->srvEreg->query($sql);
            $patients = $query->result_array();
            echo "INSERT IN TO CACHE \n";
            /*id персональных данных*/
            $id=1;
            foreach ($patients as $patient) {
                /*Получаем mip пациента*/
                $patient['BirthDate'] = strtotime( $patient['BirthDate'] );
                $patient['BirthDate'] = date( 'Y-m-d', $patient['BirthDate'] );
                print_r($patient);
                $mip = $this->mpi_model->get($patient['lastName'],$patient['FirstName'],$patient['middleName'],$patient['BirthDate']);

                echo $mip."\n";
                if($mip!='')
                {
                    /*Персональные данные*/
                    unset($VACPatientTree);
                    $VACPatientTree[$mip]['personal_data'][$id]['familyName'] = $patient['lastName'];
                    $VACPatientTree[$mip]['personal_data'][$id]['givenName'] = $patient['FirstName'];
                    $VACPatientTree[$mip]['personal_data'][$id]['middleName'] = $patient['middleName'];
                    $VACPatientTree[$mip]['personal_data'][$id]['dob'] = $patient['BirthDate'];

                    $this->icache->update($this->PatientsCache,$VACPatientTree);
                }
            }
            $year = $year + 3;
        }
    }

    /*Зазрузка инфо по прививкам*/
    /*
     таблица VACD_PatinetVaccination
    Карта
    VACPatientTree[$mip]['vaccination']
     * */
    public function load_vaccination()
    {
        /*перебираем всех пациентов из cache*/
        /*Устанавливаем стартовый mpi*/
        $mip=1;
        for($i=1;$i<100;$i++)
        {
            $patient=$this->get_next_by_mip($mip);
            print_r($patient);


            $mip=$patient->mpiid;
        }


    }

    /*todo подгрузить данные по вакцинации в глобал*/
    /*составить запрос об вакцинации*/

    /*о пациенте по его мастериндексу*/
    public function get_by_mip($masterindex)
    {
        $masterindex=$this->security->xss_clean($masterindex);

        $sql="select Test.VACPatientClass_GetByMIP('".$masterindex."') a";

        $query = $this->cacheDB->query($sql);
        $row=$query->result_array();
        $row = $row[0]['a'];
        $row=mb_convert_encoding($row,"UTF-8","Windows-1251");
        $row=json_decode($row);
        return $row;
    }

    /*следующий пациент*/
    /*если $mip==1 то начинается с первой записи */
    public function get_next_by_mip($mip)
    {
        $masterindex=$this->security->xss_clean($mip);

        $sql="select Test.VACPatientClass_GetNextByMIP('".$mip."') a";

        $query = $this->cacheDB->query($sql);
        $row=$query->result_array();
        $row = $row[0]['a'];
        $row=mb_convert_encoding($row,"UTF-8","Windows-1251");
        $row=json_decode($row);
        return $row;
    }









    public function GetCache()
    {
        $sql = 'SELECT * FROM [AKTPAK].[dbo].[' . $tablename . ']';

        $query = $this->srv224DB->query($sql);

        return $query->result_array();
    }


    /*Загрузка пола енп снилс еин*/
    public function LoadPatientsParams1()
    {
        $year = 1900;

        while ($year < 2100) {
            $yeark = $year + 2;
            $sql = "select * from  from [vaccination2].[dbo].[VACD_Patient]  patient
where patient.lastName,
patient.FirstName,
patient.middleName,
patient.BirthDate
";


            $year = $year + 3;
        }
    }

    function test1()
    {
        $this->insert_patients_to_cache(1, 1, 1, 1);
    }

    function insert_patients_to_cache($LastName, $FirstName, $MiddleName, $BirthDate,$enp)
    {
        $sql = "select top 1 Test.VACPatientClass_InsertPatient(
        '" . $LastName . "',
        '" . $FirstName . "' , '" . $MiddleName . "' , '" . $BirthDate . "' , '" . $enp . "') from Test.VACPatientClass";


       // echo $sql;
        $query = $this->cacheDB->query($sql);
        return true;
    }

    /*
     ClassMethod GetPatientByFirstName(FirstName As %String) As %String [ SqlProc ]
    {
        Set res=""
        Set key = $Order(^Test.VACPatientClass(""))

        While (key '= "") {
         // Write out contents
         set keyname = $Order(^Test.VACPatientClass(key,""))
         while (keyname '= "") {
             if keyname=FirstName {
                  set res=res_"|"_(@($QUERY(^Test.VACPatientClass(key,keyname))))
                 }
             //write $QUERY(^Test.VACPatientClass(key,keyname))
              Set keyname = $Order(^Test.VACPatientClass(key,keyname))
         }
         // Find next node
         Set key = $Order(^Test.VACPatientClass(key))
        }
        Quit res
    }
    */


}