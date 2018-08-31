<?php
namespace cli;
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 14:39
 */

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
            exit('case必须被指定'.PHP_EOL);
        }
        $class = $this->caseNamespace.'\\'.ucfirst($this->case);
        if(!class_exists($class)) {
            $class .= 'Case';
            if(!class_exists($class)) {
                exit('类['.$class.']不存在'.PHP_EOL);
            }
        }

        spl_autoload_register(function ($class_name) {
            include APP .'/'. strtolower($class_name) . '.php';
        });

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

$cli = new Run();
try {
    $cli->run();
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

