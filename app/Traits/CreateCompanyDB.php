<?php


namespace App\Traits;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDO;
use PDOException;

trait CreateCompanyDB
{
    private $pdo;

    public function __construct()
    {

    }

    public static function create($company){

        $company_db_name = $company->company_db_name;
        $password = bcrypt($company->company_db_user_password);

        //$create_db = "CREATE DATABASE IF NOT EXISTS {$company->company_db_name} /*!40100 DEFAULT CHARACTER SET utf8 */; \n ";
        //$use_db = "USE {$company->company_db_name}; \n \n";
        //$db = DB::statement($create_db);

        $tables = Storage::get('public/db_structure.sql');

        //$sql = $use_db.$tables;//.$insert_new_user.$insert_new_company;
        $sql = $tables;//.$insert_new_user.$insert_new_company;

        return $sql;
        /*
        try {
            $pdo = new PDO("mysql:host=localhost;dbname={$company_db_name}", 'root', '');

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $rs = $pdo->query($content);
            //$rs = $rs->fetchAll(PDO::FETCH_ASSOC);
            $rs->closeCursor();

            if($rs) {
                $u = $pdo->query($insert_user);
                $u->closeCursor();

                $c = $pdo->query($insert_new_company);
                $c->closeCursor();
            }

        }
        catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        */

    }

    public static function syncUserPermissions($user_id){
        return "INSERT INTO `permission_user` (`permission_id`, `user_id`, `user_type`) VALUES
        (1, {$user_id}, 'App\\\Models\\\User'),
        (2, {$user_id}, 'App\\\Models\\\User'),
        (3, {$user_id}, 'App\\\Models\\\User'),
        (4, {$user_id}, 'App\\\Models\\\User'),
        (5, {$user_id}, 'App\\\Models\\\User'),
        (6, {$user_id}, 'App\\\Models\\\User'),
        (7, {$user_id}, 'App\\\Models\\\User'),
        (8, {$user_id}, 'App\\\Models\\\User'),
        (9, {$user_id}, 'App\\\Models\\\User'),
        (10, {$user_id}, 'App\\\Models\\\User'),
        (11, {$user_id}, 'App\\\Models\\\User'),
        (12, {$user_id}, 'App\\\Models\\\User'),
        (13, {$user_id}, 'App\\\Models\\\User'),
        (14, {$user_id}, 'App\\\Models\\\User'),
        (15, {$user_id}, 'App\\\Models\\\User'),
        (16, {$user_id}, 'App\\\Models\\\User'),
        (17, {$user_id}, 'App\\\Models\\\User'),
        (18, {$user_id}, 'App\\\Models\\\User'),
        (19, {$user_id}, 'App\\\Models\\\User'),
        (20, {$user_id}, 'App\\\Models\\\User'),
        (21, {$user_id}, 'App\\\Models\\\User'),
        (22, {$user_id}, 'App\\\Models\\\User'),
        (23, {$user_id}, 'App\\\Models\\\User'),
        (24, {$user_id}, 'App\\\Models\\\User'),
        (25, {$user_id}, 'App\\\Models\\\User'),
        (26, {$user_id}, 'App\\\Models\\\User'),
        (27, {$user_id}, 'App\\\Models\\\User'),
        (28, {$user_id}, 'App\\\Models\\\User'),
        (29, {$user_id}, 'App\\\Models\\\User'),
        (30, {$user_id}, 'App\\\Models\\\User'),
        (31, {$user_id}, 'App\\\Models\\\User'),
        (32, {$user_id}, 'App\\\Models\\\User'),
        (33, {$user_id}, 'App\\\Models\\\User'),
        (34, {$user_id}, 'App\\\Models\\\User'),
        (35, {$user_id}, 'App\\\Models\\\User'),
        (36, {$user_id}, 'App\\\Models\\\User'),
        (37, {$user_id}, 'App\\\Models\\\User'),
        (38, {$user_id}, 'App\\\Models\\\User'),
        (39, {$user_id}, 'App\\\Models\\\User'),
        (40, {$user_id}, 'App\\\Models\\\User'),
        (41, {$user_id}, 'App\\\Models\\\User'),
        (42, {$user_id}, 'App\\\Models\\\User'),
        (43, {$user_id}, 'App\\\Models\\\User'),
        (44, {$user_id}, 'App\\\Models\\\User'),
        (45, {$user_id}, 'App\\\Models\\\User'),
        (46, {$user_id}, 'App\\\Models\\\User'),
        (47, {$user_id}, 'App\\\Models\\\User'),
        (48, {$user_id}, 'App\\\Models\\\User'),
        (49, {$user_id}, 'App\\\Models\\\User'),
        (50, {$user_id}, 'App\\\Models\\\User'),
        (51, {$user_id}, 'App\\\Models\\\User'),
        (52, {$user_id}, 'App\\\Models\\\User'),
        (53, {$user_id}, 'App\\\Models\\\User'),
        (54, {$user_id}, 'App\\\Models\\\User'),
        (55, {$user_id}, 'App\\\Models\\\User'),
        (56, {$user_id}, 'App\\\Models\\\User'),
        (57, {$user_id}, 'App\\\Models\\\User'),
        (58, {$user_id}, 'App\\\Models\\\User'),
        (59, {$user_id}, 'App\\\Models\\\User'),
        (60, {$user_id}, 'App\\\Models\\\User'),
        (61, {$user_id}, 'App\\\Models\\\User'),
        (62, {$user_id}, 'App\\\Models\\\User'),
        (63, {$user_id}, 'App\\\Models\\\User'),
        (64, {$user_id}, 'App\\\Models\\\User'),
        (65, {$user_id}, 'App\\\Models\\\User'),
        (66, {$user_id}, 'App\\\Models\\\User'),
        (67, {$user_id}, 'App\\\Models\\\User'),
        (68, {$user_id}, 'App\\\Models\\\User'),
        (69, {$user_id}, 'App\\\Models\\\User'),
        (70, {$user_id}, 'App\\\Models\\\User'),
        (71, {$user_id}, 'App\\\Models\\\User'),
        (72, {$user_id}, 'App\\\Models\\\User'),
        (73, {$user_id}, 'App\\\Models\\\User'),
        (74, {$user_id}, 'App\\\Models\\\User'),
        (75, {$user_id}, 'App\\\Models\\\User'),
        (76, {$user_id}, 'App\\\Models\\\User'),
        (77, {$user_id}, 'App\\\Models\\\User'),
        (78, {$user_id}, 'App\\\Models\\\User'),
        (79, {$user_id}, 'App\\\Models\\\User'),
        (80, {$user_id}, 'App\\\Models\\\User'),
        (81, {$user_id}, 'App\\\Models\\\User'),
        (82, {$user_id}, 'App\\\Models\\\User'),
        (83, {$user_id}, 'App\\\Models\\\User'),
        (84, {$user_id}, 'App\\\Models\\\User'),
        (85, {$user_id}, 'App\\\Models\\\User'),
        (86, {$user_id}, 'App\\\Models\\\User'),
        (87, {$user_id}, 'App\\\Models\\\User'),
        (88, {$user_id}, 'App\\\Models\\\User'),
        (89, {$user_id}, 'App\\\Models\\\User'),
        (90, {$user_id}, 'App\\\Models\\\User'),
        (91, {$user_id}, 'App\\\Models\\\User'),
        (92, {$user_id}, 'App\\\Models\\\User'),
        (93, {$user_id}, 'App\\\Models\\\User'),
        (94, {$user_id}, 'App\\\Models\\\User'),
        (95, {$user_id}, 'App\\\Models\\\User'),
        (96, {$user_id}, 'App\\\Models\\\User'),
        (97, {$user_id}, 'App\\\Models\\\User'),
        (98, {$user_id}, 'App\\\Models\\\User'),
        (99, {$user_id}, 'App\\\Models\\\User'),
        (100, {$user_id}, 'App\\\Models\\\User');
";
    }

}
