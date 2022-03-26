<?php


namespace App\Helper;


use App\Models\Company;
use I18N_Arabic;

class Helper
{

    public static function nFormat($number = 0){
        $number = ($number)? str_replace(' ', '', $number) * 1 : 0;
        if($number == 0){
            $number = str_replace('-', '', $number) * 1;
        }
        $number = str_replace(',', '', $number);
        return number_format($number, 2, '.', ' ');
    }
    public static function double($number = 0){
        $number = ($number)? str_replace(' ', '', $number) * 1 : 0;
        return $number;
    }
    public static function qteFormat($number = 0){
        $number = ($number)? str_replace(' ', '', $number) * 1 : 0;
        $qte = explode('.', $number);
        if(isset($qte[1]) && strval($qte[1]) > 0){
            return number_format($number,2, '.', ' ');
        }
        return number_format($number,0, '.', ' ');
    }
    public static function dtFr($dt){
        return date_format(date_create($dt), 'd/m/Y');
        //return date_format(date_create($dt), 'Y-m-d');
    }
    public static function dt($dt){
        return date_format(date_create($dt), 'Y-m-d');
    }
    public static function year($dt){
        return date_format(date_create($dt), 'Y');
    }
    public static function month($dt){
        return date_format(date_create($dt), 'm');
    }
    public static function time($dt){
        return date_format(date_create($dt), 'H:i');
    }
    public static function fullTime($dt){
        return date_format(date_create($dt), 'H:i:s');
    }
    public static function dtTime($dt){
        return date_format(date_create($dt), 'd-m-Y H:i');
    }

    public static function inputDateFormat($dt){
        return date_format(date_create($dt), 'Y-m-d');
    }
    public static function monthNameEn($month_id){
        /*$monthNames_en = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];*/
        $monthNames_en = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];
        return $monthNames_en[$month_id * 1 - 1];
    }
    public static function monthNameAr($month_id){
        $monthNames_ar = ["يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو",
            "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"
        ];
        return $monthNames_ar[$month_id * 1 - 1];

    }
    public static function months(){
        return [
           ['id' => 1, 'name' => "يناير"],
           ['id' => 2, 'name' => "فبراير"],
           ['id' => 3, 'name' => "مارس"],
           ['id' => 4, 'name' => "ابريل"],
           ['id' => 5, 'name' => "مايو"],
           ['id' => 6, 'name' => "يونيو"],
           ['id' => 7, 'name' => "يوليو"],
           ['id' => 8, 'name' => "أغسطس"],
           ['id' => 9, 'name' => "سبتمبر"],
           ['id' => 10, 'name' => "أكتوبر"],
           ['id' => 11, 'name' => "نوفمبر"],
           ['id' => 12, 'name' => "ديسمبر"]
        ];
    }
    public static function nAlphaAr($nbr){
        require_once base_path().'/lib/I18N/Arabic.php';
        $obj = new I18N_Arabic('Numbers');
        $nbr = str_replace(' ', '', $nbr);
        return $obj->money2str($nbr, 'SAR', 'ar');
    }
    public static function addDays($dt, $days){
        return date('Y-m-d H:i:s', strtotime($dt. " + ".$days." days"));
    }
    public static function minusDays($dt, $days){
        return date('Y-m-d H:i:s', strtotime($dt. " - ".$days." days"));
    }

    public static function factor(){
        $company = Company::find(1);
        return $company->factor;
    }
}
