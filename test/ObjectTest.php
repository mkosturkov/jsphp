<?php

namespace KISS\JSPHP\Test;

use \KISS\JSPHP\{
    Object,
    Undefined,
    Exceptions\NotAFunction
};

/**
 * @author Milko Kosturkov<mkosturkov@gmail.com>
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testCallingPropertiesAsFunctions()
    {
        $object = new Object();
        $isCalled = false;
        $object->f = function() use (&$isCalled) {
            $isCalled = true;
        };
        $object->f();
        $this->assertTrue($isCalled, 'Calling property as function failed!');
    }
    
    /**
     * @depends testCallingPropertiesAsFunctions
     */
    public function testCallingPropertiesAsFunctionsWithParams()
    {
        $object = new Object();
        $p1 = 5;
        $p2 = 6;
        $correctParams = false;
        $object->f = function($p1, $p2) use (&$correctParams) {
            $correctParams = $p1 == 5 && $p2 == 6;
        };
        $object->f($p1, $p2);
        $this->assertTrue($correctParams, 'Calling property as function with params failed!');
    }
    
    /**
     * @depends testCallingPropertiesAsFunctions
     */
    public function testCallingPropertiesAsFunctionsAndReturn()
    {
        $object = new Object();
        $boundTo = null;
        $object->f = function() use (&$boundTo) {
            $boundTo = $this;
            return 5;
        };
        $this->assertEquals(5, $object->f(), 'Calling property as function and returning value failed!');
        $this->assertEquals($object, $boundTo);
    }
    
    /**
     * @depends testCallingPropertiesAsFunctions
     */
    public function testExceptionOnCallingNonFunction()
    {
        $this->expectException(NotAFunction::class);
        $object = new Object();
        $object->f = 5;
        $object->f();
    }
    
    public function testGettingPropertyFromPrototype()
    {
        $prototype = new Object();
        $prototype->testVar = 5;
        $object = new Object($prototype);
        $this->assertEquals(5, $object->testVar);
    }
    
    public function testReturningUndefinedOnNonSetProperty()
    {
        $object = new Object();
        $this->assertInstanceOf(Undefined::class, $object->a, 'Not undefined returned!');
    }
    
    public function testCallingMethodFromPrototype()
    {
        $boundTo = null;
        $func = function($p1, $p2) use (&$ap1, &$ap2, &$boundTo) {
            $ap1 = $p1;
            $ap2 = $p2;
            $boundTo = $this;
        };
        $prototype = new Object();
        $prototype->func = $func;
        $object = new Object($prototype);
        $object->func(1, 2);
        $this->assertEquals(1, $ap1);
        $this->assertEquals(2, $ap2);
        $this->assertEquals($object, $boundTo);
    }
}
