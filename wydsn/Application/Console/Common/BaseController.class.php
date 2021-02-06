<?php
namespace Console\Common;

use Think\Controller;

class BaseController extends Controller {

    public function println($message){
        echo '【'.date('Y-m-d H:i:s').'】'.$message.PHP_EOL;
    }

}