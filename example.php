<?php

namespace KISS\JSPHP;

require __DIR__ . '/vendor/autoload.php';

$C1 = new FunctionObject(function ($a, $b) {
    $this->a = $a;
    $this->b = $b;
});

$C1->prototype->print = function() {
    echo sprintf("A is %s\nB is %s\n", $this->a, $this->b);
};

$c1i = $C1->newInstance(5, 10);
$c1i->print();

$C2 = new FunctionObject(function ($a, $b, $c) use ($C1) {
    $C1->apply($this, [$a, $b]);
    $this->c = $c;
});
$C2->prototype = clone $C1->prototype;
$C2->prototype->printC = function() {
    echo "And finally C is {$this->c}\n";
};

$c2i = $C2->newInstance(1, 2, 3);
$c2i->print();
$c2i->printC();