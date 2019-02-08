<?php

namespace controllers;

use kernel\ControllerBase;

class SampleController extends ControllerBase {
    public function run() {
        echo 'SUCCESS<br/>$_GET content: ';
        var_dump($_GET);
    }

    public function index() {
        return view('index');
    }
}