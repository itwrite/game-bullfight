<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2020/7/6
 * Time: 22:44
 */

namespace Jasmine\Game\Bullfight;


use Jasmine\Component\Poker\Poker;

class Bullfight implements BullfightInterface
{

    /**
     * @var Poker|null
     */
    protected $Poker = null;

    /**
     * @var bool
     */
    protected $hasExtraRules = false;

    function __construct(Poker $poker)
    {
        /**
         * 初始化扑克牌
         * 有大小王
         */
        $this->setPoker($poker);
    }

    /**
     * Desc: 取得扑克牌对象
     * User: itwri
     * Date: 2019/1/25
     * Time: 13:31
     *
     * @return Poker|null
     */
    function getPoker()
    {
        return $this->Poker;
    }

    /**
     * 设置扑克牌对象
     * @param Poker $poker
     * @return $this|mixed
     * itwri 2020/7/4 12:32
     */
    public function setPoker(Poker $poker){
        $this->Poker = $poker;
        return $this;
    }

    /**
     * 计算牛牛的结果值
     *
     * 牌型介绍:
     * <1> 无牛: 没有任意三张牌能加起来成为10的整数倍。例如: A, 8, 4, K, 7.
     * <2> 牛一~牛九: 有一组三张牌加起来成为10的整数倍,并且另外两张牌之和与10进行取余,所得之数即为牛几.例如: 2, 8, J, 6, 3.即为牛9.
     * <3> 牛牛: 有一组三张牌和一组二张牌分别成为10的整数倍. 3, 7, K, 10, J,为牛牛.
     * <4> 银牛: 包括10以上的牌,例如: 10, J, Q, K, K,即为银牛.
     * <5> 金牛: 包括J以上的牌,例如: J, J, Q, Q, K, 即为金牛.
     * <6> 五小牌: 五张牌加起来小于10,例如A, 3, 2, A, 2,即为五小牌.
     * @param array $oneHandCards
     * @return mixed
     * itwri 2020/7/4 12:34
     */
    public function calculate(Array $oneHandCards)
    {
        $minCard = $this->getPoker()->getTheMinCard($oneHandCards);
        $points = self::toPoints($oneHandCards);

        /**
         * 常规牛值计算
         */
        $taurusValue = self::checkoutTaurusValue($points);

        /**
         * 额外补充规则
         * 1、五小牌
         * 2、金牛
         * 3、银牛
         */
        $extraTaurusValue = 0;

        /**
         * 是否计算额外规则
         */
        if($this->hasExtraRules){
            //五小牌: 五张牌加起来小于10,例如A, 3, 2, A, 2,即为五小牌.
            if (array_sum($points) <= 10) {
                $taurusValue = 10;
                $extraTaurusValue = 3;
            } //金牛: 所有牌都在J以上，包括J以上的牌，即最小的牌值大于、等于J的牌值11,例如: J, J, Q, Q, K, 即为金牛. 大小王也算
            elseif ($taurusValue == 10 && $minCard->getValue() >= 11) {
                $extraTaurusValue = 2;
            } //银牛: 所有牌都在10以上，包括J以上的牌，即最小的牌值大于、等于10,例如: 10, J, Q, K, K,即为银牛.
            elseif ($taurusValue == 10 && $minCard->getValue() == 10) {
                $extraTaurusValue = 1;
            }
        }


        return $taurusValue + $extraTaurusValue;
    }


    /**
     * 两手牌比较
     * @param array $firstHandCards
     * @param array $secondHandCards
     * @return int 1为第一手牌大，反之-1则为第二首牌大
     */
    public function compareHandCards(Array $firstHandCards, Array $secondHandCards)
    {

        $firstTaurusValue = self::checkoutTaurusValue($this->toPoints($firstHandCards));

        $secondTaurusValue = self::checkoutTaurusValue($this->toPoints($secondHandCards));

        /**
         * 牛值一样的情况下，比最大的牌,牌型大小为:黑桃>红心>梅花>方块
         */
        if ($firstTaurusValue == $secondTaurusValue) {
            $firstMaxCard = $this->getPoker()->getTheMaxCard($firstHandCards);
            return $firstMaxCard->compareWith($this->getPoker()->getTheMaxCard($secondHandCards));
        }
        return $firstTaurusValue > $secondTaurusValue ? 1 : -1;
    }

    /**
     * 转牛值转换为标签文字
     * @param int $taurusValue
     * @return string
     * itwri 2020/7/4 12:26
     */
    public function valueToString($taurusValue = -1)
    {
        switch ($taurusValue) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
                $label = '牛' . $taurusValue;
                break;
            case 10:
                $label = '牛牛';
                break;
            case 11:
                $label = '银牛';
                break;
            case 12:
                $label = '金牛';
                break;
            case 13:
                $label = '五小牌';
                break;
            default:
                $label = '无牛';
        }
        return $label;
    }


    /**
     * 转换点数
     * @param array $cards
     * @return array
     */
    protected function toPoints(Array $cards = array())
    {
        $points = array();
        foreach ($cards as $card) {
            if ($card instanceof Card) {
                $points[] = $card->getValue() > 10 ? 10 : $card->getValue();
            }
        }

        /**
         * 牛牛的规则是5张版，不是5张牌的不能转换
         */
        return count($points) == 5 ? $points : array();
    }

    /**
     * Desc: 常规牛值计算
     * User: itwri
     * Date: 2019/1/25
     * Time: 11:48
     *
     * @param array $points
     * @return int
     */
    protected function checkoutTaurusValue(Array $points)
    {
        //0表示没牛
        $taurus = 0;
        $len = count($points);
        for ($i = 0; $i < $len; $i++) {
            $point1 = $points[$i];
            if ($taurus == 0 && $i < $len - 2) {
                for ($j = $i + 1; $j < $len; $j++) {
                    if ($taurus == 0) {
                        $point2 = $points[$j];
                        for ($k = $j + 1; $k < $len; $k++) {
                            $point3 = $points[$k];
                            if (($point1 + $point2 + $point3) % 10 == 0) {
                                $points[$i] = 0;
                                $points[$j] = 0;
                                $points[$k] = 0;
                                $sum = array_sum($points) % 10;
                                //这里的sum=10时，需要转换为10点,后续计算
                                $taurus = $sum == 0 ? 10 : $sum;
                            }
                        }
                    }
                }
            }
        }
        return $taurus;
    }
}