<?php

namespace KISS\JSPHP\Test;

use \KISS\JSPHP\{
    Object,
    FunctionObject
};

/**
 *
 * @author Milko Kosturkov<mkosturkov@gmail.com>
 */
class FunctionObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInvoking()
    {
        $isCalled = false;
        $object = new FunctionObject(function() use (&$isCalled) {
            $isCalled = true;
        });
        $object();
        $this->assertTrue($isCalled, 'Simple invocation failed!');
    }
    
    /**
     * @depends testInvoking
     */
    public function testInvokingWithParameters()
    {
        $p1 = 5;
        $p2 = 6;
        $correctParams = false;
        $object = new FunctionObject(function($p1, $p2) use (&$correctParams) {
            $correctParams = $p1 == 5 && $p2 == 6;
        });
        $object($p1, $p2);
        $this->assertTrue($correctParams, 'Invocation with params failed!');
    }
    
    /**
     * @depends testInvoking
     */
    public function testInvokingAndReturning()
    {
        $object = new FunctionObject(function() {
            return 5;
        });
        $this->assertEquals(5, $object(), 'Returning failed!');
    }
    
    public function testInstanciatingConstructor()
    {
        $constructor = function($p1, $p2) {
            $this->propOne = $p1;
            $this->propTwo = $p2;
        };
        $object = new FunctionObject($constructor);
        $instance = $object->newInstance(5, 6);
        $this->assertInstanceOf(Object::class, $instance);
        $this->assertEquals(5, $instance->propOne);
        $this->assertEquals(6, $instance->propTwo);
        $this->assertSame($object, $instance->constructor);
    }
    
    public function testSettingPrototype()
    {
        $object = new FunctionObject(function() {});
        $this->assertInstanceOf(Object::class, $object->prototype, 'No prottype object found');
    }
    
    public function testPassingPrototypeOnConstruct()
    {
        $f = new FunctionObject(function() {});
        $f->prototype->a = 5;
        $object = $f->newInstance();
        $this->assertEquals(5, $object->a);
    }
}
