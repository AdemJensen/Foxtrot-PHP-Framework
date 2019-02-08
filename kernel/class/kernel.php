<?php

namespace kernel;

class Kernel {
    public const version = "0.1.2.0001";
    private $const = array();

    /**
     * kernel constructor.
     * @param array $constList Const list you need to pass in.
     * Attention: The following Const value must be set in the exact type below:
     *     _ROOT_ : string, The root path of the whole project. Default = __DIR__
     *     _FOUNDATION_DIRS_ : array, dirs name that needed to be loaded. Default value contains all the relative routes
     *         of the 'kernel' directory.
     */
    public function __construct(array $constList = array()) {
        if (!is_array($constList)) $this->reportInitializationError(
            'Invalid CONST LIST TYPE(arrays expected, '.gettype($constList).' found)!'
        );
        $this->const = $constList;
        $this->setConst('_VERSION_', Kernel::version);

        $this->checkKernelFoundationVar('_ROOT_');
        $this->checkKernelFoundationVar('_FOUNDATION_DIRS_');

        //Load file operational auxiliary module before loading sequence.
        require_once $this->getFoundationModuleRoute('auxiliary', 'FileOperations.php');

        //Loading sequence.
        $this->loadModule('auxiliary');
        $this->loadModule('class');
        $this->loadModule('controllers');

    }

    /**
     * Transfer a relative location into absolute location.
     * @param string $relativeLocation The relative location (relative to the root path, the one with index.php).
     * @return string The full(absolute) address of the given $relativeLocation.
     */
    public function getAbsoluteLocation(string $relativeLocation) {
        return $this->const['_ROOT_'].($relativeLocation[0] === '/' ? '' : '/').$relativeLocation;
    }

    /**
     * Set a const value into kernel storage.
     * @param string $key The key that you want to put into kernel.
     * @param mixed $value The value that you want to put into kernel.
     */
    public function setConst(string $key, $value) {
        $this->const[$key] = $value;
    }

    /**
     * Check if the specific $key const value have been set. If unset, the function will throw an RuntimeException.
     * @param string $key The name of the const value.
     */
    public function checkKernelFoundationVar(string $key) {
        if (!isset($this->const[$key])) {
            throw new \RuntimeException(
                "Kernel initialization error: CONST '$key' is unset!"
            );
        }
    }

    /**
     * Get a const value from kernel storage.
     * @param string $key The key that you want to pull out from kernel.
     * @return mixed The specific const value stored in kernel.
     */
    public function getConst(string $key) {
        return $this->const[$key];
    }

    /**
     * Generate the full path of the specific file.
     * @param string $dirName The dir name you have put into the _FOUNDATION_DIRS_ array.
     * @param string $fileName The filename in the given directory.
     * @return string The full path of the specific file.
     */
    public function getFoundationModuleRoute(string $dirName, string $fileName = '') {
        if (!isset($this->getConst('_FOUNDATION_DIRS_')[$dirName])) return NULL;
        $result = $this->getAbsoluteLocation($this->getConst('_FOUNDATION_DIRS_')[$dirName]."/$fileName");
        if (!is_dir($result) && !is_file($result)) {
            return NULL;
        }
        return $result;
    }

    /**
     * Load files in the specific directory.
     * @param string $dirName The dir name you have put into the _FOUNDATION_DIRS_ array or the full route of dir.
     *     If you input the full route, then the directory can be out of the _FOUNDATION_DIRS_ array.
     * @param string $loadSequence A function, tees the kernel how to load a directory.
     *     This function should receive following two parameters in the exact order.
     *     - $dirRoute The full route of the given dir name in the _FOUNDATION_DIRS_ array.
     *     - $fileName The file information that the kernel have scanned in the directory. (array, with following key:
     *         'name' - The name of the current file.
     *         'type' - 'dir' or 'file', shows whether it's a file or directory.
     *     )
     *     This function will be executed for every file name found in that directory.
     *     the default settings is to load all the files in current directory and sub directories.
     */
    private function loadModule(string $dirName, $loadSequence = '') {
        $dirRoute = $this->getFoundationModuleRoute($dirName);
        if ($dirRoute == NULL) $dirRoute = $dirName;
        if (!is_dir($dirRoute)) $this->reportInitializationError("Directory '$dirRoute' not found.");
        $dirContentList = getFileList($dirRoute, NULL, true);
        foreach ($dirContentList as $file) {
            if ($loadSequence === '') {
                if ($file['type'] === 'file') require_once $dirRoute.'/'.$file['name'];
                else $this->loadModule($dirRoute.$file['name']);
            } else {
                $loadSequence($dirRoute, $file);
            }
        }
    }

    /**
     * To throw a RuntimeError with kernel initialization error and $message you have given.
     * @param string $message
     */
    public function reportInitializationError(string $message) {
        throw new \RuntimeException(
            "Kernel initialization error: $message"
        );
    }
}