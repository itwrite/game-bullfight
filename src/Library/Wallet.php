<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 13:57
 */

namespace Jasmine\Game\Bullfight\Library;


class Wallet
{
    protected $money = 0;

    function __construct($money)
    {
        $this->increase($money);
    }

    /**
     * @param $money
     * @return $this
     * itwri 2021/1/20 14:00
     */
    function increase($money){
        $this->money += $money;
        return $this;
    }

    /**
     * @param $money
     * @return $this
     * itwri 2021/1/20 14:00
     */
    function decrease($money){
        $this->money = bcsub($this->money,$money);
        return $this;
    }

    /**
     * @return int
     * itwri 2021/1/20 14:00
     */
    function getMoney(){
        return $this->money;
    }
}