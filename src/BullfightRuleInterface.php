<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/28
 * Time: 9:48
 */

namespace Jasmine\Game\Bullfight;

interface BullfightRuleInterface
{
    /**
     * 计算牛牛的结果值
     *
     * 牌型介绍:
    <1> 无牛: 没有任意三张牌能加起来成为10的整数倍。例如: A, 8, 4, K, 7.
    <2> 牛一~牛九: 有一组三张牌加起来成为10的整数倍,并且另外两张牌之和与10进行取余,所得之数即为牛几.例如: 2, 8, J, 6, 3.即为牛9.
    <3> 牛牛: 有一组三张牌和一组二张牌分别成为10的整数倍. 3, 7, K, 10, J,为牛牛.
    <4> 银牛: 包括10以上的牌,例如: 10, J, Q, K, K,即为银牛.
    <5> 金牛: 包括J以上的牌,例如: J, J, Q, Q, K, 即为金牛.
    <6> 五小牌: 五张牌加起来小于10,例如A, 3, 2, A, 2,即为五小牌.
     * @param array $oneHandCards
     * @return mixed
     * itwri 2020/7/4 12:34
     */
    public function calculate(Array $oneHandCards);

    /**
     * 两手牌比较
     * @param array $firstHandCards
     * @param array $secondHandCards
     * @return int 1为第一手牌大，反之-1则为第二首牌大
     */
    public function compareHandCards(Array $firstHandCards, Array $secondHandCards): int;

    /**
     * @param int $taurusValue
     * @return string
     * itwri 2020/7/6 23:52
     */
    public function valuesToString(int $taurusValue = -1): string;

    /**
     * 每人几张牌
     * @return mixed
     * itwri 2021/1/28 17:05
     */
    public function getCardsLimitOfEachPlayer();
}
