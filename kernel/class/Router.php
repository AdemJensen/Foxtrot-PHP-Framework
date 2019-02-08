<?php

namespace kernel;

class Router {
    private $data = array();
    public const version = "0.1.3.0001";
    private const VARIABLE_MATCH = '/\[\{(.*?)\}\]/';

    /**
     * Register a routing method.
     * @param string $type Routing type, can be one of 'Any', 'Post' or 'Get'.
     * @param string $route The route that you want.
     *     Attention: The original 'GET' method cannot get variables from the URL by using '?...=...'
     *     Instead, there is a more elegant way to get variables: hide them in the route.
     *     For example:
     *         register: 'user/space/[{username}]/'
     *         access: 'user/space/kernel/'
     *         Then you will get _GET['username'] with value 'kernel'.
     *     As you can see, by using '[{' and '}]', you are able to get 'username' key in array _GET.
     * @param string $controllerMethod This string should contain 2 parts: Controller name and method, linked by '@'.
     *     For example: UserController@exec, that means using function 'exec()' in the class UserController.
     *     The kernel will instantiate an instance for you automatically.
     *     Attention: The controller you make must:
     *         - Extend the parent class 'kernel\ControllerBase'.
     *         - Put in the namespace 'controllers', or its sub namespace.
     *     You can view the 'SampleController' for example support.
     */
    public function register(string $type, string $route, string $controllerMethod) {
        $getVarNameList = array();
        preg_match_all(Router::VARIABLE_MATCH, $route, $getVarNameList);
        while (strlen($route) > 1 && $route[0] == '/') $route = substr($route, 1);
        if ($route[strlen($route) - 1] !== '/') $route = $route.'/';
        $partNumber = substr_count($route, '/');
        $pattern = preg_replace('/\//', '\/', $route);
        $pattern = preg_replace(Router::VARIABLE_MATCH, '(.*?)', $pattern);
        $this->data[] = array(
            'type' => $type,
            'pattern' => $pattern,
            'controller' => $controllerMethod,
            'partNumber' => $partNumber,
            'varNameList' => $getVarNameList[1]
        );
    }

    /**
     * Start the routing procedure.
     * @return int If the routing is invalid, then it will return 404.
     * Other return codes should be defined in the controllers.
     */
    public function route() {
        $url = $_GET['__ACCESS_URL__'];
        unset($_GET['__ACCESS_URL__']); //Get and destroy the true access url from _GET array.
        if (strlen($url) === 0 || $url[strlen($url) - 1] !== '/') $url = $url.'/';

        /*
         * The following part will deal with a kind of situation: if a route meets collision, then we will choose the
         * one with the most amount of parts, A.K.A the one with more '/'.
         * This amount was already calculated in the method 'register', stored as 'partNumber'.
         */
        $maxPartNumber = 0;
        $maxRouteInfo = array();
        $maxVarList = array();
        foreach ($this->data as $value) {
            if (preg_match('/^'.$value['pattern'].'$/', $url)) {
                if ($maxPartNumber < $value['partNumber']) {
                    $maxPartNumber = $value['partNumber'];
                    $maxRouteInfo = $value;
                    preg_match_all('/^'.$value['pattern'].'$/', $url, $maxVarList);
                }
            }
        }
        if ($maxPartNumber === 0) return 404;   //Route not found.
        for ($i = 0; $i < count($maxRouteInfo['varNameList']); $i++) {
            $_GET[$maxRouteInfo['varNameList'][$i]] = $maxVarList[$i + 1][0];
        }
        $response = explode('@', $maxRouteInfo['controller']);
        $controllerName = '\\controllers\\'.$response[0];

        /*
         * The following part contains self-check.
         * To provide best execution efficiency, this checking can only be triggered when a user access this route.
         * This may cause some security issues.
         * However, you can check them manually in the ECSM panel. There is a checker in the 'Routing' panel.
         */
        if (!class_exists($controllerName)) $this->reportExecutionError("Controller \'$response[0]\' not found!");
        $controller = new $controllerName();    //Make an instance to support the controller.
        if (!method_exists($controller, $response[1])) $this->reportExecutionError("Method \'$response[1]\' not found!");
        $viewFile = call_user_func(array($controller, $response[1]));    //Make the routing procedure.
        if ($viewFile) require_once $viewFile;
    }

    /**
     * Check if all the routes have the correct controller settings.
     * Normally, this function will only be used in the ECSM. Normal user access will not execute this function.
     * @return array An array with all the faulty route registrations, each element contains two keys:
     *     0 => The stored data array of the router. Contains following information:
     *         'type' => 'Any', 'Post', or 'Get'.
     *         'pattern' => The matching PREG pattern.
     *         'controller' => The controller you have set for this routing, it is faulty.
     *         'partNumber' => Mentioned above, see method 'route' for more information.
     *         'varNameList' => All the variable you have set to fetch from the URL.
     *              see method 'register' for more information.
     *     1 => Error code, -1 means 'Controller doesn't exist', -2 means 'Method doesn't exist'.
     */
    public function validateControllers() {
        $invalid = array();
        foreach ($this->data as $value) {
            $response = explode('@', $value['controller']);
            $controllerName = '\\controllers\\'.$response[0];
            if (!class_exists($controllerName)) {
                $invalid[] = array($value, -1);
                continue;
            }
            $controller = new $controllerName();    //Make an instance to support the controller.
            if (!method_exists($controller, $response[1])) {
                $invalid[] = array($value, -2);
                continue;
            }
        }
        return $invalid;
    }

    /**
     * To throw a RuntimeError with router execution error and $message you have given.
     * @param string $message
     */
    public function reportExecutionError(string $message) {
        throw new \RuntimeException(
            "Router execution error: $message"
        );
    }
}