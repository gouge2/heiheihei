<?php
namespace Console\Controller;

use Console\Common\BaseController;

class ProcessController extends BaseController {

    public function run(){
        while (1){
            sleep(1);
        }
    }

    private function keepAlive(){

    }

}