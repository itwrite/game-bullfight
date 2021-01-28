<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 14:16
 */

namespace Jasmine\Game\Bullfight\Library;

use Jasmine\Game\Bullfight\BullfightRule;

interface TableInterface
{
    public function getId();
    public function getName();
    public function addSeat($name);
    public function getSeats();
    public function setBullfightRule(BullfightRule $bullfight);
    public function getBullfightRule();
    public function getPoker();
    public function setBanker($i = 0);
    public function eachSeats($callback = null);
    public function resetCards();
    public function sendCards();
    public function getTheBanker();
}