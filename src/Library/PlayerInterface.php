<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 13:47
 */

namespace Jasmine\Game\Bullfight\Library;


interface PlayerInterface
{
    public function getId();
    public function getName();
    
    public function getWallet();
}