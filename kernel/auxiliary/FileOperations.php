<?php
/**
 * This file contains some basic file operational functions for kernel to use.
 * Copyright (C) Chorg 2018-2019
 */

/**
 * Used for fetching a directory's file list.
 * @param string $dir The directory you want to scan.
 * @param string $pattern A PREG pattern to sort out the names you need.
 * For example: /^(.*)$/ To match any strings.
 * If you don't want to use the pattern system, you can simply pass in NULL or void string.
 * If your PREG pattern is invalid, then the function will throw an RuntimeException.
 * @param bool $fullMode Decides how the return value is shown.
 * @return array The return value is decided by following rule:
 * - If the variable $fullMode is false, then it will output an array contains file names.
 * - If the variable $fullMode is true, then it will output an two-dimensional array:
 *     [NUM]['name'] => The name of the file.
 *     [NUM]['type'] => It will be 'dir' or 'file' indicating whether it's a file or directory.
 */
function getFileList(string $dir, string $pattern = NULL, bool $fullMode = false) {
    $list = scandir($dir);
    if ($pattern == NULL) $pattern = "/^(.*)$/";
    $result = array();
    clearstatcache();
    foreach ($list as $value) {
        if ($value == '.' || $value == '..') continue;
        $checkValue = @preg_match($pattern, $value);
        if ($checkValue === 0) continue;
        else if ($checkValue === FALSE) {
            throw new RuntimeException(
                "getFileList('$dir', '$pattern', $fullMode): Invalid PREG pattern",
                -1
            );
        }
        if ($fullMode) {
            $result[] = array(
                'name' => $value,
                'type' => is_dir($dir.'/'.$value) ? 'dir' : 'file'
            );
        } else {
            $result[] = $value;
        }
    }
    return $result;
}