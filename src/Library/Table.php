<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 14:15
 */

namespace Jasmine\Game\Bullfight\Library;

use Jasmine\Game\Bullfight\BullfightRule;
use Jasmine\Game\Bullfight\Common\Fun;
use Jasmine\Game\Bullfight\Traits\BaseProperties;
use Jasmine\Game\Bullfight\Constant\TableConstant;
use Jasmine\Game\Bullfight\Library\Seat;
use Jasmine\Poker\Poker;
use \Jasmine\Game\Bullfight\Console\Table as ConsoleTable;

/**
 * 牛牛游戏
 * 玩家：2~7人 （其中一个为庄）
 * 纸牌：1
 *
 * Class Application
 * @package Jasmine\Game\Bullfight
 */
class Table implements TableInterface
{
    use BaseProperties;
    
    /**
     * 这桌子能容纳的座位数
     * @var int 
     */
    protected $capacity = 5;
    /**
     * 桌子有一些座位
     * @var array
     */
    protected $seats = [];

    /**
     * @var Poker|null
     */
    protected $Poker = null;

    /**
     * @var BullfightRule|null 
     */
    protected $BullfightRule = null;

    public function __construct(int $capacity = 5)
    {
        $this->capacity = $capacity;
        /**
         * 初始化扑克牌
         */
        $this->Poker = new Poker(true);
        
        //规则
        $this->setBullfightRule(new BullfightRule());

        /**
         * 初始化座位
         */
        for ($i = 0; $i < $this->capacity; $i++){
            $this->addSeat(str_pad(($i+1),3,'0',STR_PAD_LEFT));
        }
        $this->setBanker(0);
    }

    /**
     * @return Poker|null
     * itwri 2020/12/30 14:29
     */
    public function getPoker(){
        if($this->Poker == null){
            $this->Poker = new Poker(true);
        }
        return $this->Poker;
    }

    /**
     * @return BullfightRule
     * itwri 2021/1/25 10:17
     */
    public function getBullfightRule(){
        if($this->BullfightRule == null){
            $this->BullfightRule = new BullfightRule();
        }
        return $this->BullfightRule;
    }

    /**
     * @param Bullfight $bullfight
     * @return $this
     * itwri 2021/1/25 10:51
     */
    public function setBullfightRule(BullfightRule $bullfightRule){
        $this->BullfightRule = $bullfightRule;
        return $this;
    }

    /**
     * @return array
     * itwri 2021/1/25 10:27
     */
    public function getSeats()
    {
       return $this->seats;
    }
    
    /**
     * @param int $i
     * itwri 2021/1/25 14:58
     */
    public function setBanker($i = 0){
        $this->eachSeats(function(Seat $seat,$j) use($i){
            if($i == $j){
                $seat->setIsBanker(true);
            }else{
                $seat->setIsBanker(false);
            }
        });
    }

    /**
     * @param null $callback
     * itwri 2021/1/25 11:38
     */
    public function eachSeats($callback = null){
        foreach ($this->getSeats() as $i => &$seat) {
            if($callback instanceof \Closure || is_callable($callback)){
                call_user_func_array($callback,[$seat,$i]);
            }
        }
    }
    /**
     * @param $name
     * @return $this
     * itwri 2021/1/25 10:56
     */
    public function addSeat($name){
        $this->seats[] = new Seat($name);
        return $this;
    }

    /**
     * @return $this
     * itwri 2020/12/30 14:23
     */
    public function resetCards(){
        $this->eachSeats(function(Seat $seat,$j){
            $seat->resetCards();
        });
        return $this;
    }

    /**
     * itwri 2020/12/30 14:33
     */
    public function sendCards(){
        $this->getPoker()->reset();
        $this->getPoker()->doWash();

        for ($i = 0; $i < $this->getBullfightRule()->getCardsLimitOfEachPlayer(); $i++){
            $this->eachSeats(function(Seat $seat,$j){
                $seat->receiveACard($this->getPoker()->pop());
            });
        }

        $this->setBanker(rand(0,count($this->seats)-1));
    }
    

    /**
     * @return Seat|null
     * itwri 2021/1/25 12:24
     */
    public function getTheBanker(){
        $banker = null;
        $this->eachSeats(function(Seat $seat,$j) use(&$banker){
            if($seat->isBanker()){
                $banker = $seat;
            }
        });
        return $banker;
    }
}