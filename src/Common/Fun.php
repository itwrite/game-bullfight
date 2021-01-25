<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 13:40
 */

namespace Jasmine\Game\Bullfight\Common;


class Fun
{
    public static function id(){
        return str_replace('.','',microtime(true).'').str_pad(rand(1,1000),4,'0');
    }
}