<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 14:16
 */

namespace Jasmine\Game\Bullfight\Library;

interface TableInterface
{
    public function getId();
    public function getName();
    public function addSeat($name);
    public function getSeats();
    public function setBullfight(Bullfight $bullfight);
}