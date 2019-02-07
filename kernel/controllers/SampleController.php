<?php

namespace controllers;

use kernel\ControllerBase;

class SampleController extends ControllerBase {
    public function run() {
        echo "SUCCESS";
    }

    public function index() {
        global $kernel;
        echo '<h1>It worked!</h1><br/>Foxtrot Framework 0.1<br/>Kernel version '.$kernel->getConst('_VERSION_');
    }
}