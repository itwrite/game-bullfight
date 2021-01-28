<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 13:51
 */

namespace Jasmine\Game\Bullfight\Traits;


use Jasmine\Game\Bullfight\Common\Fun;

trait BaseProperties
{
    protected $id = null;
    protected $name = '';

    /**
     * @return mixed|string
     * itwri 2020/7/9 9:51
     */
    public function getId(){
        if($this->id == null){
            $this->id = self::id();
        }
        return $this->id;
    }

    /**
     * @return string
     * itwri 2020/7/9 9:51
     */
    public function getName(){
        return $this->name;
    }

    protected function id(){
        return str_replace('.','',microtime(true).'').str_pad(rand(1,1000),4,'0');
    }
}