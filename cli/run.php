<?php
namespace cli;
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 14:39
 */

use cli\cases\Account;

define('APP', dirname(dirname(__FILE__)));

define('CLI', APP.'/cli');

define('CASES', CLI.'/cases');

require_once APP.'/vendor/autoload.php';

/**
 * Class run
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 14:58
 */
class Run
{
    public $caseNamespace = 'cli\cases';

    public $clientParams = [];

    public $case;

    public $function;

    /**
     * @return bool
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/28
     * @Time: 14:42
     */
    public static function isCli()
    {
        return PHP_SAPI === 'cli';
    }

    /**
     * cli constructor.
     */
    public function __construct()
    {
        $this->_init();
    }

    /**
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/28
     * @Time: 14:42
     */
    protected function _init()
    {
        $this->clientParams = self::isCli() ? getopt('c:f:') : $_GET;
        if(isset($this->clientParams['c'])) {
            $this->case = $this->clientParams['c'];
        }
        if(isset($this->clientParams['f'])) {
            $this->function = $this->clientParams['f'];
        }
    }
    /**
     * @return mixed
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/28
     * @Time: 14:42
     */
    public function run()
    {
        if(!isset($this->case)) {
            exit('case必须被指定：例如 -c ***'.PHP_EOL);
        }
        if(!isset($this->function)) {
            exit('function必须被指定：例如 -f ***'.PHP_EOL);
        }

        /**
         * todo 自动加载失败，待优化①
         */
        $file =  CASES .'/'.strtolower($this->case).'.php';
        @include $file;


        $class = $this->caseNamespace.'\\'.ucfirst($this->case);
        $object = new $class();
        return call_user_func([$object,$this->function]);
    }

    /**
     * @param array $properties
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/28
     * @Time: 15:04
     */
    public function block($properties = [])
    {
        foreach ($properties as $property => $value) {
            if(property_exists($this,$property)) {
                $this->$property = $value;
            }
        }
    }
}


try {
    include APP.'/libs/EtherClient.php';
    include APP.'/libs/JsonRpc.php';
    /**
     * todo 自动加载失败，待优化②
     */
    /*spl_autoload_register(function ($class_name) {
        $file =  APP .'/'. str_replace('\\', '/', strtolower($class_name)) . '.php';
        var_dump([$class_name,$file]);die;
        @include $file;
    });*/

    $cli = new Run();

    $cli->run();

} catch (\Exception $e) {
    print_r($e->getMessage());
}

