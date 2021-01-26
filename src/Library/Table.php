<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 14:15
 */

namespace Jasmine\Game\Bullfight\Library;

use Jasmine\Game\Bullfight\Common\Fun;
use Jasmine\Game\Bullfight\Console\Output;
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
     * 等待的时间
     * @var int
     */
    protected $waitTime = 0;

    /**
     * 0-等待用户准备
     * 1-用户已准备，等待
     * @var int
     */
    protected $step = 1;
    
    /**
     * 斗牛游戏规则
     * @var Bullfight
     */
    protected $Bullfight;


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
     * @var Output|null
     */
    protected $Output = null;

    public function __construct()
    {
        /**
         * 初始化扑克牌
         */
        $this->Poker = new Poker(true);

        $this->Output = new Output();
        
        //规则
        $this->setBullfight(new Bullfight());

        /**
         * 初始化座位
         */
        for ($i = 0; $i < $this->capacity; $i++){
            $this->addSeat(str_pad(($i+1),3,'0',STR_PAD_LEFT));
        }
        $this->setBanker(0);
    }

    /**
     * @return Output|null
     * itwri 2021/1/26 11:06
     */
    public function getOutput(){
        return $this->Output;
    }

    /**
     * @return Poker|null
     * itwri 2020/12/30 14:29
     */
    protected function getPoker(){
        if($this->Poker == null){
            $this->Poker = new Poker(true);
        }
        return $this->Poker;
    }

    /**
     * @return Bullfight
     * itwri 2021/1/25 10:17
     */
    public function getBullfight(){
        if($this->Bullfight == null){
            $this->Bullfight = new Bullfight();
        }
        return $this->Bullfight;
    }

    /**
     * @param Bullfight $bullfight
     * @return $this
     * itwri 2021/1/25 10:51
     */
    public function setBullfight(Bullfight $bullfight){
        $this->Bullfight = $bullfight;
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

    public function goToStep($step,$time = 0){
        $this->step = $step;
        $this->waitTime = $time;
        return $this;
    }
    public function run()
    {
        $running = true;
        while ($running){

            /**
             * 等待结束
             */
            if($this->waitTime == 0){
                
                switch ($this->step){
                    case 1:
                        $this->e("游戏开始");
                        
                        /**
                         * 游戏开始，用户有5秒时间准备
                         */
                        $this->goToStep(2,4);
                        
                        break;
                    case 2:
                        $this->e('发牌');
                        /**
                         * 重置所有座位的牌
                         */
                        $this->resetCards();
                        
                        $this->sendCards();
                        
                        $this->goToStep(3,2);

                        break;
                    case 3:
                        $this->e('开牌');
                        $this->showPlayersCards();
                        $this->e(PHP_EOL.PHP_EOL);
                        sleep(5);
                        
                        $this->goToStep(1,0);
                        break;

                }
            }else{
                $this->e($this->waitTime."秒");
            }
            sleep(1);

            /**
             * 等待时间减少
             */
            if($this->waitTime>0){
                $this->waitTime --;
            }
            
        }
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
    protected function resetCards(){
        $this->eachSeats(function(Seat $seat,$j){
            $seat->resetCards();
        });
        return $this;
    }

    /**
     * itwri 2020/12/30 14:33
     */
    protected function sendCards(){
        $this->getPoker()->reset();
        $this->getPoker()->doWash();

        for ($i = 0; $i < $this->getBullfight()->getCardsLimitOfEachPlayer(); $i++){
            $this->eachSeats(function(Seat $seat,$j){
                $seat->receiveACard($this->getPoker()->pop());
            });
        }

//        $this->setBanker(rand(0,count($this->seats)-1));
    }

    /**
     * itwri 2021/1/25 12:18
     */
    public function showPlayersCards(){

        $consoleTable = new ConsoleTable();

        $consoleTable->setHeader(['座位','开牌','结果','输/赢'],ConsoleTable::ALIGN_CENTER);

        $rows = [];
        /** @var Seat $banker */
        $banker = $this->findTheBanker();
        $this->eachSeats(function(Seat $seat,$j) use($banker,&$rows){


            $cards = $seat->getCards();

            $taurusValue = $this->getBullfight()->calculate($cards);
            //比较输赢
            $result = $this->getBullfight()->compareHandCards($banker->getCards(),$seat->getCards());

            $rows[] = [
                $seat->getName(),
                $seat->cardsToString(),//
                $this->getBullfight()->valuesToString($taurusValue),
                ($seat->isBanker()?"【庄】":($result>0?"【输】":"【赢】"))
            ];
        });
     
        $this->e($consoleTable->render($rows,ConsoleTable::ALIGN_LEFT),32);
    }

    /**
     * @return Seat|null
     * itwri 2021/1/25 12:24
     */
    public function findTheBanker(){
        $banker = null;
        $this->eachSeats(function(Seat $seat,$j) use(&$banker){
            if($seat->isBanker()){
                $banker = $seat;
            }
        });
        return $banker;
    }

    /**
     * itwri 2020/7/15 16:31
     */
    public function e($message = '',$color = 37){
        $this->getOutput()->writeln([sprintf("\033[%dm{$message}\033[0m",$color)]);
    }

}