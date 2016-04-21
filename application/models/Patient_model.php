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
    $id = id пациента в базе вакцинации
    VACPatientTree[$mip]['vaccination'][$id]
    VACPatientTree[$mip]['vaccination'][$id]['VaccinationSchemePeriod']
    VACPatientTree[$mip]['vaccination'][$id]['DateAppointment']
    VACPatientTree[$mip]['vaccination'][$id]['DateVaccReal']
    VACPatientTree[$mip]['vaccination'][$id]['DateVaccRealComment']
    VACPatientTree[$mip]['vaccination'][$id]['PreparationId']
    VACPatientTree[$mip]['vaccination'][$id]['Doza']
    VACPatientTree[$mip]['vaccination'][$id]['VacReactionId']
        Id	Code	Value	Description
    8	0	Не указано	Не указано
    1	1	отсутствует	отсутствует
    2	2	слабая	слабая
    3	3	умеренная	умеренная
    4	4	сильная	сильная
    VACPatientTree[$mip]['vaccination'][$id]['DoctorId']
    VACPatientTree[$mip]['vaccination'][$id]['VaccinationView']
    Id	Code	Value	Description
    1	1	V1	Первая вакцинация
    2	2	V2	Вторая вакцинация
    3	3	V3	Третья вакцинация
    4	4	V4	Четвертая вакцинация
    5	5	V5	Пятая вакцинация
    6	6	RV1	Первая ревакцинация
    7	7	RV2	Вторая ревакцинация
    8	8	RV3	Третья ревакцинация
    9	9	RV4	Четвертая ревакцинация
    10	10	RV5	Пятая ревакцинация
    11	16	IM	Иммунизация
    VACPatientTree[$mip]['vaccination'][$id]['VaccinationResultId']


     * */
    /**
     *
     */
    public function load_vaccination()
    {
        /*перебираем всех пациентов из cache*/
        /*Устанавливаем стартовый mpi*/
        $mip=1;
        $i=1;
        while($mip!='')
        {
            if(($i % 1000)==0)
            echo $i.' '.$mip."\n";

            /*Получаем пациента по mip*/
            $patient=$this->get_next_by_mip($mip);
            /*выбираем из базы вакцины поциента по фио и држд*/
            $VACPatientTree = array();



            $this->icache->update($this->PatientsCache,$VACPatientTree);
            $patient_vac=$this->get_by_fiod_from_sql($patient->familyName,$patient->givenName,$patient->middleName,$patient->dob);
            foreach ($patient_vac as $patient_vac_row)
            {
                $sql="SELECT  [vac].[Id]
      ,[vac].[PatientId]
      ,[vac].[VaccinationSchemePeriodId]
      ,[vac].[DateAppointment]
      ,[vac].[DateVaccReal]
      ,[vac].[Comment]
      ,[vac].[PreparationId]
      ,[vac].[Doza]
      ,[vac].[VacReactionId]
      ,[vac].[UserId]
      ,[vac].[DoctorId]
      ,[doctor].[Code] Doctor_code
      ,[vac].[VaccinationViewId]
      ,[vac].[DateModified]
      ,[vac].[Serie]
      ,[vac].[VaccinationResultId]
      ,[vac].[IsDMark]
      ,[vac].[TourVaccinationId]
      ,[vac].[PolCounter]
      ,[vac].[PolLpuin]
      ,[vac].[PolExCounter]
      ,[vac].[PolExLpuin]
      ,[vac].[Papule]
      ,[vac].[PreparationCKey]
      ,[vac].[LpuId]
  FROM [vaccination2].[dbo].[VACD_PatientVaccination] vac

  left join [vaccination2].[dbo].[VACM_Doctor] doctor
  on vac.DoctorId=doctor.id
  where PatientId=".$patient_vac_row['id'];

                $query = $this->srvEreg->query($sql);
                foreach($query->result_array() as $row)
                {
                    $row['DateAppointment'] = strtotime( $row['DateAppointment'] );
                    $row['DateAppointment'] = date( 'Y-m-d', $row['DateAppointment'] );

                    $row['DateVaccReal'] = strtotime( $row['DateVaccReal'] );
                    $row['DateVaccReal'] = date( 'Y-m-d', $row['DateVaccReal'] );

                    $row['DateModified'] = strtotime( $row['DateModified'] );
                    $row['DateModified'] = date( 'Y-m-d', $row['DateModified'] );

                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['VaccinationSchemePeriod']=$row['VaccinationSchemePeriodId'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['DateAppointment']=$row['DateAppointment'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['DateVaccReal']=$row['DateVaccReal'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['Comment']=$row['Comment'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PreparationId']=$row['PreparationId'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['Doza']=$row['Doza'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['Doctor_code']=$row['Doctor_code'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['VaccinationViewId']=$row['VaccinationViewId'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['DateModified']=$row['DateModified'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['Serie']=$row['Serie'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['VaccinationResultId']=$row['VaccinationResultId'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['TourVaccinationId']=$row['TourVaccinationId'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PolCounter']=$row['PolCounter'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PolLpuin']=$row['PolLpuin'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PolExCounter']=$row['PolExCounter'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PolExLpuin']=$row['PolExLpuin'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['Papule']=$row['Papule'];
                    $VACPatientTree[$mip]['vaccination'][$row['Id']]['PreparationCKey']=$row['PreparationCKey'];
                    $this->icache->update($this->PatientsCache,$VACPatientTree);
                    print_r($VACPatientTree);
                }
            }


            $mip=$patient->mpiid;
            $i++;
            unset($patient);
            unset($patient_save);
            if($i==10) break;
        }
    }

    /*выбираем из базы вакцины поциента по фио и држд*/
    /*выдает id*/
    public function get_by_fiod_from_sql($LastName,$FirstName,$MiddleName,$BirthDate)
    {
        /*конвертируем дату*/

        $BirthDate = strtotime( $BirthDate );
        $BirthDate = date( 'd.m.Y', $BirthDate );
        $FirstName=mb_convert_encoding($FirstName,"Windows-1251","UTF-8");
        $LastName=mb_convert_encoding($LastName,"Windows-1251","UTF-8");
        $MiddleName=mb_convert_encoding($MiddleName,"Windows-1251","UTF-8");
        $sql="select id from ".$this->PatientsSQL."
          where
             FirstName='$FirstName'
             and MiddleName='$MiddleName'
             and LastName='$LastName'
             and BirthDate = '$BirthDate'
          ";

        $query = $this->srvEreg->query($sql);
        return $query->result_array();
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