<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/21
 * Time: 15:05
 */

namespace Jasmine\Game\Bullfight\Library;


interface PokerGameInterface
{
    /**
     * @return int
     * itwri 2021/1/25 10:44
     */
    public function getCardsLimitOfEachPlayer();
}