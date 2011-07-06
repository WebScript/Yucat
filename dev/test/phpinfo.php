<?php


class test {
    
    private $limit = NULL;


    public function lol() {
       // return $this;
    }
    
    public function omg() {
        return $this;
    }
}


$tesst = new test;

var_dump($tesst->lol()
        ->omg());