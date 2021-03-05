<?php
namespace App\Components;

use Illuminate\Support\Facades\DB;

/**
 *
 */
class Report
{

    function __construct()
    {
        // code...
    }

    /*
    # Report query date sequence
    parameter :
        - from (date) : Y-m-d
        - until (date) : Y-m-d
    */
    public static function _stringQueryDateSequence($from, $until){
        // date
        $query = "
            WITH RECURSIVE Date_Ranges AS (
                SELECT '". date('Y-m-d', strtotime($from)) ."' AS DATE

                UNION ALL

                SELECT DATE + INTERVAL 1 DAY
                FROM Date_Ranges
                WHERE DATE < '". date('Y-m-d', strtotime($until)) ."'
            )
            SELECT * FROM Date_Ranges
        ";

        return $query;
    }

}


?>
