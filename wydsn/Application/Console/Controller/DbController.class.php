<?php
namespace Console\Controller;

use Console\Common\BaseController;
use PDO;

class DbController extends BaseController {

    public function init(){
        $database = './databases';
        if(is_dir($database)){
            include './Public/inc/db.config.php';
            $dsn="mysql:dbname=".DB_NAME.";host=".DB_HOST;
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $files = scandir($database);
            foreach ($files as $file){
                if($file == '.' || $file == '..'){
                    continue;
                }
                $sql = file_get_contents($database.'/'.$file);
                try {
                    $pdb = $pdo->prepare($sql);
                    $pdb->execute();
                }catch (\Exception $e){
                    echo $e->getTraceAsString().PHP_EOL;
                }
            }
        }
    }

}