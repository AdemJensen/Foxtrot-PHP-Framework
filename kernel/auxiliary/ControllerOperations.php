<?php
/**
 * This file contains some basic routing support functions for kernel to use.
 * Copyright (C) Chorg 2018-2019
 */

/**
 * @param string $viewName
 * @return string 
 */
function view(string $viewName) {
    global $kernel;
    return $kernel->getFoundationModuleRoute('views').$viewName.'.view.php';
}