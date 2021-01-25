<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/11
 * Time: 18:00
 */

namespace Jasmine\Game\Bullfight;


class Application
{

    public function start(){
        $this->e('start ...');
     
    }

    /**
     * itwri 2020/7/15 16:31
     */
    public function e($message = ''){
        echo implode(',',func_get_args()).PHP_EOL;
    }
}