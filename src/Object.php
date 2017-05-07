<?php

namespace KISS\JSPHP;

use \KISS\JSPHP\Exceptions\{
    NotAFunction
};

/**
 *
 * @author Milko Kosturkov<mkosturkov@gmail.com>
 */
class Object
{
    private $prototype;
    
    public function __construct(Object $prototype = null)
    {
        $this->prototype = $prototype;
    }

    public function __call($name, $arguments)
    {
        $closure = $this->$name;
        if (!($this->$name instanceof \Closure)) {
            throw new NotAFunction('Call to a non-function property: ' . $name);
        }
        $callable = $this->$name->bindTo($this);
        return call_user_func_array($callable, $arguments);
    }
    
    public function __get($name)
    {
        if (!isset ($this->prototype)) {
            return new Undefined();
        }
        return $this->prototype->$name;
    }
}
