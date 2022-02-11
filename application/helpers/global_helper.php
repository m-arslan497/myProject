<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//============================================================+
// File name   : global_helper.php
// Begin       : 2017-12
// Last Update : 2018-01
//
// Description : method includes a variety of global "helper" PHP functions.
// Author      : Junaid Ahmed
// -------------------------------------------------------------------

/**
 * Codeigniter Instance
 * Load All Fileds once again
 *
 */
function get_ci_instance() {
    $ci = & get_instance();

    return $ci;
}

/**
 * System Password :: Generate
 *
 */
function saltpasword($string) {
    return hash('sha512', $string . config_item('encryption_key'));
}

function random_number() {
    return rand(1, 1000000);
}

/**
 * Cross-site request forgery (CSRF)
 *
 */
function form_csrf_token($is_return = false) {
    $csrf_token = '<input type="hidden" name="' . csrf_token_field() . '"" value="' . csrf_token() . '">';

    if ($is_return) {
        return $csrf_token;
    }

    echo $csrf_token;
}

// Returns the CSRF token value (the $config['csrf_token_hash'] value).
function csrf_token() {
    return get_ci_instance()->security->get_csrf_hash();
}

// Returns the CSRF token name (the $config['csrf_token_name'] name).
function csrf_token_field() {
    return get_ci_instance()->security->get_csrf_token_name();
}

/**
 * The Carbon class is inherited from the PHP DateTime cla
 *
 */
function carbon($date = NULL) {
    return new DateTime($date);
}

// The today function creates a new current date:
function today($format = NULL) {
    $format = ($format ? $format : 'Y-m-d H:i:s');

    return carbon()->format($format);
}

function dateFormat($date, $format = NULL) {
    $format = ($format ? $format : 'Y-m-d');

    return carbon($date)->format($format);
}

function dateFormator($date, $format = NULL) {
    if($date != NULL) {
        return date($format ? $format : "d/m/Y",strtotime($date));
    }

    return NULL;
}


function isAscii($str) {
    return 0 == preg_match('/[^\x00-\x7F]/', $str);
}

function getSession($key = NULL, $return_by = false) {
    $ci = & get_instance();

    $all = $ci->session->all_userdata();

    if (NULL == $key) {
        return $all;
    } else if ($all[$key]) {
        return $all[$key];
    }

    if ($return_by) {
        return null;
    }

    return [];
}

function getLastQuery($is_bool = TRUE, $title = '',$compiled=false) {
    $ci = & get_instance();

    if (!$compiled){
        dd($ci->db->last_query(), $is_bool, $title);
    }else {
        dd($ci->db->get_compiled_select(), $is_bool, $title);
    }

}
function getLastQuery2($is_bool = TRUE, $title = '',$compiled=false) {
    $ci = & get_instance();

    if (!$compiled){
        dd($ci->db2->last_query(), $is_bool, $title);
    }else {
        dd($ci->db2->get_compiled_select(), $is_bool, $title);
    }

}


/**
 * Data Response
 * ----------------------------------
 */
function sendResponse($status, $message, $data = array(), $collection = 'collection') {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        $collection => $data
    ]);
    exit;
}

function getRestReponse( $object_format = FALSE )
{
    $data = json_decode(file_get_contents("php://input"), $object_format );

    if( count($data) ) {

        foreach ($data as $key => $value)
        {
            $_POST[$key] = $value;
        }

        return $_POST;
    }

    return $data;
}

function include_file($path, $module = NULL) {
    $CI = & get_instance();

    $module = ($module ? $module : getSession('system'));

    $CI->load->view($module . '/' . $path);
}

function currentCallMethod() {
    $CI = & get_instance();
    return $CI->router->fetch_method();
}

function currentCallController() {
    $CI = & get_instance();
    return $CI->router->fetch_class();
}



/**
 * dd()
 * -----------------------------------------
 * The dd function dumps the given variables and ends execution of the script:
 *
 */
function dd($arr, $is_bool = TRUE, $title = '') {
    if ($title) {
        echo '<strong>' . $title . '</strong>';
    }

    echo '<pre>';
    print_r($arr);
    echo '</pre>';

    if ($is_bool)
        exit();
}

function DEPARTMENT_LIST($title = true) {
    $CI = & get_instance();
    $selection_msg = ['' => 'شعبہ منتخب فرمائیں'];
    if($title) {
        return ($selection_msg + array_column($CI->Mdl_login->db_select('department_name,id', DP_LIST), 'department_name', 'id'));
    }
    else {
        return (array_column($CI->Mdl_login->db_select('department_name,id', DP_LIST), 'department_name', 'id'));
    }

}

function checkDuplicationByArrayKeysValue($array) {
    $CI = & get_instance();
    $CI->db->select($array['column']);
    $CI->db->from($array['table']);
    $CI->db->where($array['where']);
    $return = $CI->db->get()->result();

    $column = $array['column'];

    if (!empty($column)) {
        if ($column == '*') {
            return $return;
        } else {
            return $return[0]->$column;
        }
    } else {

        return 0;
    }



}

function encrypt($pure_string) {
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_", "_SLASH_", "_EQUALS_");
    $encrypted_string = base64_encode(json_encode($pure_string));
    return str_replace($dirty, $clean, $encrypted_string);
}

function decrypt($encrypted_string) { 
    $dirty = array("+", "/", "=");
    $clean = array("_PLUS_", "_SLASH_", "_EQUALS_");
    $decrypted_string = json_decode( base64_decode(str_replace($clean, $dirty, $encrypted_string)) , true );
    return $decrypted_string;
}

function getMonthReturn($code) {
    switch ($code) {
        case '1': return 'جنوری';
        case '2': return ' فروری';
        case '3': return ' مارچ';
        case '4': return  ' اپریل';
        case '5': return ' مئی ';
        case '6': return ' جون';
        case '7': return  ' جولائی';
        case '8': return  ' اگست';
        case '9': return ' ستمبر';
        case '10': return' اکتوبر';
        case '11': return' نومبر';
        case '12': return ' دسمبر';

    }
    return false;
}

function getDayTitleByPrefix($code) {
    switch ($code) {
        case 'Sun': return 'اتوار';
        case 'Mon': return ' پیر شریف ';
        case 'Tue': return ' منگل';
        case 'Wed': return  ' بدھ';
        case 'Thu': return ' جمعرات ';
        case 'Fri': return ' جمعۃ المبارک';
        case 'Sat': return  ' ہفتہ';
    }
    return false;
}

function getTitleById($code,$tableName) {
    $CI = & get_instance();
    $CI->db->select('title');
    $CI->db->from($tableName);
    $CI->db->where('id',$code);
    $return = $CI->db->get()->result();
    return $return[0]->title;
}

function getBasicDetail($where, $group_by, $where_in = [0,1]){
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from(TABLE_DIVISION_DETAILS_ALL);
    $CI->db->where($where);
    $CI->db->where_in('auto_genrated',$where_in);
    $CI->db->group_by($group_by);
    $result = $CI->db->get()->result();
    $count = count($result);
    return $count;
}


//Helper to Select Color For KPK DASHBOARD

function getColor() {
    $CI = & get_instance();
    $system = strtolower($CI->session->userdata('system'));
    $CI->db->select('*');
    $CI->db->from(MODEL_CUSTOMIZATION);
    if($system == 'kpk'){$CI->db->where('id', 1);}
    elseif ($system == 'kmq'){$CI->db->where('id', 2);}
    else{$CI->db->where('id', 1);}
    $CI->db->order_by('id', 'ASC');
    $result = $CI->db->get()->result();
    if(count($result) > 0) {
        $return_color['First'] = $result[0]->primary_color;
        $return_color['Second'] = $result[0]->secondary_color;
    }
    else {
        $return_color['First'] = '#438eb9';//Royal Blue
        $return_color['Second'] = '#62a8d1';
    }

    return $return_color;
}




/**
 * Check Karkardagi User Type for Auto Selection 
 * ----------------------------------
 */
function Check_User_Type($user_type) {
    $karkrdagi_types = array(
        KARKARDAGI_TYPE_ID_BY_PROVINCE,
        KARKARDAGI_TYPE_ID_BY_KABINAT,
        KARKARDAGI_TYPE_ID_BY_KABINA,
        KARKARDAGI_TYPE_ID_BY_DIVISION,
        KARKARDAGI_TYPE_ID_BY_ILAQA
    );

    if (in_array($user_type, $karkrdagi_types))
    {
        return TURE;
    }
    return FALSE;
}


/**
 * Check hirarchy_permision 
 * ----------------------------------
 */
function check_User_Permission($user_hierarchy) {
    $ci = & get_instance();

    if( Check_User_Type($ci->session->karkardagi_user_type)) {
        $permission = json_decode($ci->session->data_permission);

        foreach ($user_hierarchy as $key => $val)
        {
           $check_array = (array)$permission->$key;
           if (!in_array($val,  $check_array))
           {
               return false;
           }
        }

        return true;

    }
    else if($ci->session->karkardagi_user_type == KARKARDAGI_TYPE_ID_BY_SUPER_ADMIN || $ci->session->karkardagi_user_type ==
    KARKARDAGI_TYPE_ID_BY_IT_SUPER_ADMIN || $ci->session->karkardagi_user_type == KARKARDAGI_TYPE_ID_BY_SUB_ADMIN)
    {
        return true;
    }

    else{
        return false;
    }

}



function check_menu_perm($uri, $check_type = 'v') {
    $ci = &get_instance();
    $system = $ci->session->userdata('system');
    $function_permission = json_decode($ci->session->userdata('function_permission'), true);
    $permission = $function_permission[$uri][$check_type];
    if ($permission != 1) {
        $ci->session->set_flashdata('توجہ فرمائیں', ' آپ کو یہ مینیو دیکھنے کااختیار نہیں');
        redirect(base_url() . $system . '/Dashboard', 'refresh');
    }
}

function getDays($y, $m, $d)
{
    $data = new DatePeriod(
        new DateTime("first ".$d." of $y-$m"),
        DateInterval::createFromDateString('next '.$d.''),
        new DateTime("last day of $y-$m")
    );
    $counter = 1;
    foreach ($data as $day) {
        $days[$counter++] = $day->format("Y-m-d");
    }
    return $days;
}

function getStartAndEndDate($week, $year) {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['week_start'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['week_end'] = $dto->format('Y-m-d');
    return $ret;
}

function user_check($level, $user_details, $task_details) {
    switch ($level) {
        case NIGRAN_DIVISION_ID:
            if($task_details->type == MEETING && $task_details->level < 6) {
                if($task_details->level == 5) {
                    $where = ['id' => $user_details->division_id];
                }
                elseif($task_details->level == 4) {
                    $where = ['id' => $user_details->kabina_id];
                }
                elseif($task_details->level == 3) {
                    $where = ['id' => $user_details->kabinat_id];
                }
                elseif($task_details->level == 2) {
                    $where = ['id' => $user_details->province_id];
                }
                elseif($task_details->level == 1) {
                    $where = ['id' => $user_details->country_id];
                }
            }
            else {
                $where = ['division_id' => $user_details->division_id];
            }
            break;
        case NIGRAN_KABINA_ID:
             if($task_details->type == MEETING && $task_details->level < 5) {
                if($task_details->level == 4) {
                    $where = ['id' => $user_details->kabina_id];
                }
                if($task_details->level == 3) {
                    $where = ['id' => $user_details->kabinat_id];
                }
                if($task_details->level == 2) {
                    $where = ['id' => $user_details->province_id];
                }
                if($task_details->level == 1) {
                    $where = ['id' => $user_details->country_id];
                }
}
             else {
                 $where = ['kabina_id' => $user_details->kabina_id];
             }
            break;
        case NIGRAN_ZONE_ID:
            if($task_details->type == MEETING && $task_details->level < 4) {
                if($task_details->level == 3) {
                    $where = ['id' => $user_details->kabinat_id];
                }
                if($task_details->level == 2) {
                    $where = ['id' => $user_details->province_id];
                }
                if($task_details->level == 1) {
                    $where = ['id' => $user_details->country_id];
                }
            }
            else {
                $where = ['kabinat_id' => $user_details->kabinat_id];
            }
            break;
        case NIGRAN_REGION_ID:
            if($task_details->type == MEETING && $task_details->level < 5) {
                if($task_details->level == 4) {
                    $where = ['province_id' => $user_details->province_id];
                }
                if($task_details->level == 3) {
                    $where = ['province_id' => $user_details->province_id];
                }
                if($task_details->level == 2) {
                    $where = ['id' => $user_details->province_id];
                }
                if($task_details->level == 1) {
                    $where = ['id' => $user_details->country_id];
                }
            }
            else {
                $where = ['province_id' => $user_details->province_id];
            }
            break;
        default:
            return FALSE;
            break;
    }
    return $where;
}

function setPermissionsForUsers($user) {
    $ci = &get_instance();
    $user_type = $user->karkardagi_user_type;
    switch ($user_type) {
        case COUNTRY_ZIMADAR_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('country_id', 'country_id');
            $kabinat_where = array('country_id', 'country_id');
            $kabina_where = array('country_id', 'country_id');
            $division_where = array('country_id', 'country_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
            break;
        case RUKNE_SHURA_ZIMADAR_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('country_id', 'country_id');
            $kabinat_where = array('country_id', 'country_id');
            $kabina_where = array('country_id', 'country_id');
            $division_where = array('country_id', 'country_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
            break;
        case NIGRAN_REGION_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('id', 'province_id');
            $kabinat_where = array('province_id', 'province_id');
            $kabina_where = array('province_id', 'province_id');
            $division_where = array('province_id', 'province_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
        break;

        case NIGRAN_ZONE_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('id', 'province_id');
            $kabinat_where = array('id', 'kabinat_id');
            $kabina_where = array('kabinat_id', 'kabinat_id');
            $division_where = array('kabinat_id', 'kabinat_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
        break;

        case NIGRAN_KABINA_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('id', 'province_id');
            $kabinat_where = array('id', 'kabinat_id');
            $kabina_where = array('id', 'kabina_id');
            $division_where = array('kabina_id', 'kabina_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
        break;

        case NIGRAN_DIVISION_ID:
            $ci->load->model('Mdl_login');
            $province_where = array('id', 'province_id');
            $kabinat_where = array('id', 'kabinat_id');
            $kabina_where = array('id', 'kabina_id');
            $division_where = array('id', 'division_id');
            return $ci->Mdl_login->set_permissions($user, 'country_id', $province_where, $kabinat_where, $kabina_where, $division_where);
            break;
    }




}

function get_hierarchy($level) { // Modified Level
    switch ($level) {
        case SUPER_ADMIN_LEVEL:
            return 'country_id';
            break;
        case NIGRAN_REGION_ID:
            return 'province_id';
            break;
        case NIGRAN_ZONE_ID:
            return 'kabinat_id';
            break;
        case NIGRAN_KABINA_ID:
            return 'kabina_id';
            break;
        case NIGRAN_DIVISION_ID:
            return 'division_id';
            break;
        case NIGRAN_ILAQA_ID:
            return 'ilaqa_id';
            break;
        default:
            return FALSE;
            break;
    }
}
function getHierarchyByLevel($level) { // Actual Level
    switch ($level) {
        case 1:
            return 'country_id';
            break;
        case 2:
            return 'province_id';
            break;
        case 3:
            return 'kabinat_id';
            break;
        case 4:
            return 'kabina_id';
            break;
        case 5:
            return 'division_id';
            break;
        case 6:
            return 'ilaqa_id';
            break;
        default:
            return FALSE;
            break;
    }
}
function getTableByTaskLevel($level) {
    switch ($level) {
        case 1:
            $table = TABLE_COUNTRY;
            break;
        case 2:
            $table = TABLE_PROVINCE;
            break;
        case 3:
            $table = TABLE_KABINAT;
            break;
        case 4:
            $table = TABLE_KABINA;
            break;
        case 5:
            $table = TABLE_DIVISIONS;
            break;
        case 6:
            $table = TABLE_ILAQA;
            break;
        default:
            return FALSE;
            break;
    }
    return $table;
}

function getGroupByTaskLevel($level) {
    switch ($level) {
        case 1:
            $group_by = 'country_code';
            break;
        case 2:
            $group_by = 'province_code';
            break;
        case 3:
            $group_by = 'kabinat_code';
            break;
        case 4:
            $group_by = 'kabina_code';
            break;
        case 5:
            $group_by = 'division_code';
            break;
        case 6:
            $group_by = 'ilaqa_code';
            break;
    }
    return $group_by;
}

function jd_date_check($jd_date) {
    $ci = &get_instance();
    $system = $ci->session->userdata('system');
    if($jd_date > date('Y-m-d')) {
        $ci->session->set_flashdata('توجہ فرمائیں', ' آپ آنے والے دن کے کاموں کی انٹری نہیں کرسکتے۔');
        redirect(base_url() . $system . '/Dashboard', 'refresh');
    }
}

function getLocation($record)
{
//    if ($record->level > 0 && $record->type == 1) {
//        switch ($record->level) {
//            case 1:
//                $return['table'] = TABLE_COUNTRY;
//                $return['postfix'] = ' ملک';
//                break;
//            case 2:
//                $return['table'] = TABLE_PROVINCE;
//                $return['postfix'] = ' ریجن';
//                break;
//            case 3:
//                $return['table'] = TABLE_KABINAT;
//                $return['postfix'] = ' زون';
//                break;
//            case 4:
//                $return['table'] = TABLE_KABINA;
//                $return['postfix'] = ' کابینہ';
//                break;
//            case 5:
//                $return['table'] = TABLE_DIVISIONS;
//                $return['postfix'] = ' ڈویژن';
//                break;
//            case 6:
//                $return['table'] = TABLE_ILAQA;
//                $return['postfix'] = ' علاقہ';
//                break;
//            default:
//                return FALSE;
//                break;
//        }
//
//        return $return;
//    }
}

function HijriDate($time) {
    $m = dateFormator($time, 'm');
    $d = dateFormator($time, 'd');
    $y = dateFormator( $time, 'Y');
    $jd = cal_to_jd(CAL_GREGORIAN, $m, $d, $y);
    $jd = $jd - 1948440 + 10632;
    $n  = (int)(($jd - 1) / 10631);
    $jd = $jd - 10631 * $n + 354;
    $j  = ((int)((10985 - $jd) / 5316)) *
        ((int)(50 * $jd / 17719)) +
        ((int)($jd / 5670)) *
        ((int)(43 * $jd / 15238));
    $jd = $jd - ((int)((30 - $j) / 15)) *
        ((int)((17719 * $j) / 50)) -
        ((int)($j / 16)) *
        ((int)((15238 * $j) / 43)) + 29;
    $m  = (int)(24 * $jd / 709);
    $d  = $jd - (int)(709 * $m / 24);
    $y  = 30*$n + $j - 30;
    return array($m, $d, $y);
}
// For Paishgi and Amli Jadwal
function grading_1($number) {
    switch ($number) {
        case 0:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
        break;

        case 1:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            break;

        case 2:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            break;

        case 3:
            $return['status'] = 'مناسب';
            $return['color'] = '#C3D69B';
            break;

        case 4:
            $return['status'] = 'بہتر';
            $return['color'] = '#92D050';
            break;

        case 5:
            $return['status'] = 'ممتاز';
            $return['color'] = '#00B050';
            break;
        default:
            return '';
            break;
    }
    return $return;
}
// For Deeni Kaam
function grading_2($number) {
    switch ($number) {
        case 0:
            $return['status'] = '';
            $return['color'] = '#FFFFFF';
            $return['number'] = 0;
            break;

        case 1:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            $return['number'] = 2;
            break;

        case 2:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            $return['number'] = 2;
            break;

        case 3:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            $return['number'] = 2;
            break;

        case 4:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            $return['number'] = 2;
            break;

        case 5:
            $return['status'] = 'کمزور';
            $return['color'] = '#FF0000';
            $return['number'] = 2;
            break;
        case 6:
            $return['status'] = 'مناسب';
            $return['color'] = '#C3D69B';
            $return['number'] = 5;
            break;

        case 7:
            $return['status'] = 'مناسب';
            $return['color'] = '#C3D69B';
            $return['number'] = 5;
            break;

        case 8:
            $return['status'] = 'بہتر';
            $return['color'] = '#92D050';
            $return['number'] = 8;
            break;

        case 9:
            $return['status'] = 'بہتر';
            $return['color'] = '#92D050';
            $return['number'] = 8;
            break;

        case 10:
            $return['status'] = 'ممتاز';
            $return['color'] = '#92D050';
            $return['number'] = 10;
            break;

        case 11:
            $return['status'] = 'ممتاز';
            $return['color'] = '#00B050';
            $return['number'] = 10;
            break;

        case 12:
            $return['status'] = 'ممتاز';
            $return['color'] = '#00B050';
            $return['number'] = 10;
            break;

        default:
            return '';
            break;
    }
    return $return;
}
// ٖ For Attendance
function grading_3($record) {
    if ($record->leaves <= 2 && $record->less_minutes <= 4) {
        $return['status'] = 'ممتاز';
        $return['color'] = '#00B050';
        $return['number'] = 10;
    }
    elseif ($record->leaves >= 3 && $record->less_minutes >= 0) {
        $return['status'] = 'بہتر';
        $return['color'] = '#92D050';
        $return['number'] = 5;
    }
    else {
        $return['status'] = 'کمزور';
        $return['no_entry'] = 1;
        $return['color'] = '#FF0000';
        $return['number'] = 0;
    }
    $return['extra_number'] = $record->extra_number;
    return $return;
}
// ٖ General Grading function
function grading_4($status_type) {
    if ($status_type == 1) {
        $return['status'] = 'ممتاز';
        $return['color'] = '#00B050';
    }
    elseif ($status_type == 2) {
        $return['status'] = 'بہتر';
        $return['color'] = '#92D050';
    }
    elseif ($status_type == 3) {
        $return['status'] = 'مناسب';
        $return['color'] = '#FF0000';
    }
    else {
        $return['status'] = 'کمزور';
        $return['no_entry'] = 1;
        $return['color'] = '#FF0000';
    }

    return $return;
}
// ٖ Overall sum grading
function grading_5($overal_obtained_number) {
    if ($overal_obtained_number >= 80) {
        $return['status'] = 'ممتاز';
        $return['color'] = '#00B050';
    }
    elseif ($overal_obtained_number >= 70 && $overal_obtained_number <= 79) {
        $return['status'] = 'بہتر';
        $return['color'] = '#92D050';
    }
    elseif ($overal_obtained_number >= 60 && $overal_obtained_number <= 69) {
        $return['status'] = 'مناسب';
        $return['color'] = '#C3D69B';
    }
    else {
        $return['status'] = 'کمزور';
        $return['color'] = '#FF0000';
    }

    return $return;
}


function deeni_kaam_category($cat) {
    switch ($cat) {
        case 1:
            $deeni_kaam = [
                1 => ['فجر کے لئے جگانا', 'targat' => 16],
                2 => ['تفسیر سننے سنانے کا حلقہ', 'targat' => 16],
                3 => ['مسجد درس', 'targat' => 16],
                4 => ['چوک درس', 'targat' => 16],
                5 => ['مدرسۃ المدینہ بالغان', 'targat' => 16]
            ];
            break;
        case 2:
            $deeni_kaam = [
                6 => ['علاقائی دورہ', 'targat' => 2],
                7 => ['ہفتہ وار یوم تعطیل اعتکاف', 'targat' => 2],
                8 => ['ہفتہ وار رسالہ', 'targat' => 2],
                9 => ['ہفتہ وار مدنی مذاکرہ', 'targat' => 2],
                10 => ['ہفتہ وار اجتماع', 'targat' => 2]
            ];
            break;
        case 3:
            $deeni_kaam = [
                11 => ['نیک اعمال  کا جائزہ', 'targat' => 1],
                12 => ['مدنی قافلہ', 'targat' => 1],
            ];
            break;
        default:
            redirect(base_url());
    }

    return $deeni_kaam;
}

function line($line){
    $ci = & get_instance();
    return $ci->lang->line($line);
}

function previousMonth($current_month){
    if($current_month == 1){
        $prev_month = 12;
    }else{
        $prev_month = $current_month - 1;
    }
    return $prev_month;
}

function nextMonth($current_month){
    if($current_month == 12){
        $next_month = 1;
    }else{
        $next_month = $current_month+1;
    }
    return $next_month;
}

function findYearByMonth($madani_month) {
    if($madani_month == 1 && date('m') == 12) {
        $year = date('Y') + 1;
    } else {
        $year = date('Y');
    }
    return $year;
}

function get_nigran_level_title($level_id){
    $title = null;
    switch ($level_id) {
        case NIGRAN_DIVISION_ID:
            $title = 'نگرانِ ڈویژن';
            break;
        case NIGRAN_KABINA_ID:
            $title = 'نگرانِ کابینہ';
            break;
        case NIGRAN_ZONE_ID:
            $title = 'نگرانِ زون';
            break;
        case NIGRAN_REGION_ID:
            $title = 'نگرانِ ریجن';
            break;
        default:
            $title = 'نگرانِ ';
    }
    return $title;
}

function get_level_title($level_id){
    $title = null;
    switch ($level_id) {
        case NIGRAN_DIVISION_ID:
            $title = 'ڈویژن';
            break;
        case NIGRAN_KABINA_ID:
            $title = 'کابینہ';
            break;
        case NIGRAN_ZONE_ID:
            $title = 'زون';
            break;
        case NIGRAN_REGION_ID:
            $title = 'ریجن';
            break;
        default:
            $title = '';
    }
    return $title;
}

function mobile_number_convert($phone_number){
    return preg_replace('/^0/','92',$phone_number);
}

function convertLevel($task_details){
    if($task_details->level == 3){
        $task_details->level = NIGRAN_ZONE_ID;
    }elseif ($task_details->level == 4){
        $task_details->level = NIGRAN_KABINA_ID;
    }
    elseif ($task_details->level == 5){
        $task_details->level = NIGRAN_DIVISION_ID;
    }
    return $task_details;
}

function checKPermissionToEdit($value){
    if(is_null($value->nigranEntry)){
        return true;
    }else{
        if($value->nigranLevel == NIGRAN_REGION_ID){
            $allowed_users = array(NULL,COUNTRY_ZIMADAR_ID,NIGRAN_REGION_ID);
        }elseif ($value->nigranLevel == NIGRAN_ZONE_ID){
            $allowed_users = array(NULL,COUNTRY_ZIMADAR_ID,NIGRAN_ZONE_ID);
        }
        if(in_array(getSession('level'),$allowed_users)){
            return true;
        }else{
            return false;
        }
    }
}

function reportFilters(){
    $ci = get_instance();
    $level = $ci->session->userdata['level'];
    switch ($level) {
        case SUPER_ADMIN_LEVEL:
            $permissions = [
                'deeni_kaam_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
            break;

        case COUNTRY_ZIMADAR_ID:
            $permissions = [
                'deeni_kaam_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
            break;

        case RUKNE_SHURA_ZIMADAR_ID:
            $permissions = [
                'deeni_kaam_report' => array( NIGRAN_REGION_ID => 'نگرانِ ریجن'),
                'schedule_report' => array( NIGRAN_REGION_ID => 'نگرانِ ریجن'),
                'entry_report' => array( NIGRAN_REGION_ID => 'نگرانِ ریجن'),
            ];
            break;

        case NIGRAN_REGION_ID:
            $permissions = [
                'deeni_kaam_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array( NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
        break;

        case NIGRAN_ZONE_ID:
            $permissions = [
                'deeni_kaam_report' => array(NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array(NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array(NIGRAN_ZONE_ID => 'نگرانِ زون', NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array( NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
            break;

        case NIGRAN_KABINA_ID:
            $permissions = [
                'deeni_kaam_report' => array(NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array(NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array(NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array(NIGRAN_KABINA_ID => 'نگرانِ کابینہ', NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
            break;

        case NIGRAN_DIVISION_ID:
            $permissions = [
                'deeni_kaam_report' => array(NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'schedule_report' => array(NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'summary_report' => array(NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
                'entry_report' => array(NIGRAN_DIVISION_ID => 'نگرانِ ڈویژن'),
            ];
            break;
    }


    return $permissions;
}



function check($userLevel,$permissionArray,$summary = null){
    if($summary){
        return true; // return true means redirection condition is true i.e redirect it
    }else{
        if(in_array($userLevel,$permissionArray)){
            return false;
        }
        else{
            return true;
        }
    }
}

function getLevelConversion($level_id,$type = 'realtofake'){
    // level converion from real level to fake level and vice versa
    // whereas type is either realtofake or faketoreal:
    // real means actual level and fake means configured software levels

    if($type == 'realtofake'){
        switch ($level_id) {
            case 1:
                $level = NIGRAN_PAKISTAN_ID; // Pakistan Level
                break;
            case 2:
                $level = NIGRAN_REGION_ID; // Region
                break;
            case 3:
                $level = NIGRAN_ZONE_ID; // Zone
                break;
            case 4:
                $level = NIGRAN_KABINA_ID; // Kabina
                break;
            case 5:
                $level = NIGRAN_DIVISION_ID; // Division
                break;
            case 6:
                $level = NIGRAN_ILAQA_ID; // Ilaqa
                break;
            default:
                $level = null;
        }
    }
    elseif ($type == 'faketoreal'){
        switch ($level_id) {
            case NIGRAN_PAKISTAN_ID:
                $level = 1; // Pakistan Level
                break;
            case NIGRAN_REGION_ID:
                $level = 2; // Region
                break;
            case NIGRAN_ZONE_ID:
                $level = 3; // Zone
                break;
            case NIGRAN_KABINA_ID:
                $level = 4; // Kabina
                break;
            case NIGRAN_DIVISION_ID:
                $level = 5; // Division
                break;
            case NIGRAN_ILAQA_ID:
                $level = 6; // Ilaqa
                break;
            default:
                $level = null;
        }
    }

    return $level;
}