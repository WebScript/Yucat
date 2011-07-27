<?php

class test {
    public $test;
}

class add extends test{
    public function add() {
        $this->test = 110;
    }
}

$test = new test();

$add = new add();
$add->add();

echo $test->test . 'lol';
echo $add->test;