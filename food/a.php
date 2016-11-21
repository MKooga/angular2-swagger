<?php

/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 15.11.2016
 * Time: 17:43
 */
class a
{
    private $o;

    function geto()
    {
        if(empty($this->o)){
            $this->o = ['ckycky'];
        }
        return $this->o;
    }
    function getv()
    {
        return $this->o->v;
    }
}

class b
{
    public $v = 10;

    function getv()
    {
        return $this->v;
    }
}

$a = new a;
$o = $a->geto();
$o['iydcjgxj'] = 1;
print_r( $a->geto());