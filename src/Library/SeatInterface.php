<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2020/7/8
 * Time: 14:13
 */

namespace Jasmine\Game\Bullfight\Library;


use Jasmine\Poker\Card;


interface SeatInterface
{
    public function getId();
    public function getName();

    public function sitDown(Player $player);
    public function leave();

    /**
     * @return mixed
     * itwri 2020/7/14 16:22
     */
    public function getCards();

    /**
     * @return mixed
     * itwri 2020/7/14 16:28
     */
    public function cardsToString();
    
    /**
     * @param CardInterface $card
     * @return mixed
     * itwri 2020/7/9 0:23
     */
    public function receiveACard(Card $card);

    /**
     * @return mixed
     * itwri 2020/7/9 0:24
     */
    public function resetCards();

    /**
     * @return mixed
     * itwri 2020/7/9 9:39
     */
    public function isReady();

    /**
     * @return mixed
     * itwri 2020/7/13 21:16
     */
    public function setReady($ready = true);
    
    

    /**
     * @param float $money
     * @return mixed
     * itwri 2020/7/15 10:37
     */
    public function setBetMoney($money = 0);

    /**
     * @return mixed
     * itwri 2020/7/15 10:38
     */
    public function getBetMoney();
    
    public function isBanker();
    public function setIsBanker($isBanker = true);
    
}