<?php

namespace KISS\JSPHP;

/**
 *
 * @author Milko Kosturkov<mkosturkov@gmail.com>
 */
class FunctionObject extends Object
{
    private $function;
    
    public $prototype;
    
    public function __construct(\Closure $function)
    {
        $this->function = $function;
        $this->prototype = new Object();
    }
    
    public function __invoke(...$arguments)
    {
        return call_user_func_array($this->function, $arguments);
    }
    
    public function newInstance(...$arguments) : Object
    {
        $instance = new Object($this->prototype);
        $instance->constructor = $this;
        $binded = $this->function->bindTo($instance);
        call_user_func_array($binded, $arguments);
        return $instance;
    }
}
