<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2020/7/8
 * Time: 14:11
 */

namespace Jasmine\Game\Bullfight\Library;


use Jasmine\Game\Bullfight\Common\Fun;
use Jasmine\Game\Bullfight\Traits\BaseProperties;
use Jasmine\Poker\Card;

class Seat implements SeatInterface
{
    use BaseProperties;
    protected $Player = null;
    protected $cards = [];
    protected $ready = 0;
    protected $betMoney = 0;
    protected $isBanker = false;

    public function __construct($name = '')
    {
        $this->id = Fun::id();
        $this->name = $name;
    }

    /**
     * @param Person $person
     * @return $this
     * itwri 2021/1/20 13:56
     */
    public function sitDown(Player $player){
        if($this->Player == null){
            $this->Player = $player;
            return true;
        }
        return false; 
    }

    /**
     * @return $this
     * itwri 2021/1/20 13:56
     */
    public function leave(){
        $this->Player = null;
        return $this;
    }

    /**
     * @param CardInterface $card
     * @return mixed|void
     * itwri 2020/7/9 0:25
     */
    public function receiveACard(Card $card)
    {
        $this->cards[] = $card;
    }

    /**
     * @return mixed|void
     * itwri 2020/7/9 0:25
     */
    public function resetCards()
    {
        $this->cards = [];
    }

    /**
     * @return bool
     * itwri 2020/7/9 9:41
     */
    public function isReady()
    {
        return $this->ready == 1;
    }

    /**
     * itwri 2020/7/13 21:16
     */
    public function setReady($ready = true){

        if($this->getPlayer()){
            $this->ready = 1;
        }
        return $this;
    }

    /**
     * @return array|mixed
     * itwri 2021/1/20 13:45
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @return mixed|string
     * itwri 2021/1/20 13:45
     */
    public function cardsToString()
    {
        $cards = [];
        foreach ($this->cards as $card) {
            if($card instanceof Card){
                $cards[] = $card->getName();
            }
        }

        return implode(',',$cards);
    }

    /**
     * @param int $money
     * @return $this|mixed
     * itwri 2021/1/20 13:45
     */
    public function setBetMoney($money = 0)
    {
        $this->betMoney = floatval($money);
        return $this;
    }

    /**
     * @return int|mixed
     * itwri 2020/7/15 10:39
     */
    public function getBetMoney()
    {
        return $this->betMoney;
    }

    /**
     * @return bool
     * itwri 2021/1/25 11:45
     */
    public function isBanker(){
        return $this->isBanker;
    }

    /**
     * @param bool $isBanker
     * @return $this
     * itwri 2021/1/25 11:45
     */
    public function setIsBanker($isBanker = true){
        $this->isBanker = $isBanker && true;
        return $this;
    }
}