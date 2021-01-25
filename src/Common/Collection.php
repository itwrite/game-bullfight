<?php
/**
 * Created by PhpStorm.
 * User: itwri
 * Date: 2021/1/25
 * Time: 10:57
 */

namespace Jasmine\Game\Bullfight\Common;


class Collection
{
    protected $items = [];
    
    function __construct($items)
    {
        $this->items = $items;
    }
    
    public function filter(){
        
    }

    public function toArray(){
        return (array)$this->items;
    }
}