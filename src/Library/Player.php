<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/20
 * Time: 13:48
 */

namespace Jasmine\Game\Bullfight\Library;


use Jasmine\Game\Bullfight\Traits\BaseProperties;

class Player implements PersonInterface
{
    use BaseProperties;

    protected $Wallet = null;
    
    public function __construct($name = '',$money = 0)
    {
        $this->id = Fun::id();
        $this->name = $name;
        
        $this->Wallet = new Wallet($money);
    }

    /**
     * @return Wallet|null
     * itwri 2021/1/20 14:13
     */
    public function getWallet(){
        return $this->Wallet;
    }
}