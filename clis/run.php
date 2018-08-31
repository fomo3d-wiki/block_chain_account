<?php
//namespace clis\cases;
/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 14:39
 */


require_once './../vendor/autoload.php';

$cli = new Run();
try {
    $cli->run();
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

/**
 * Class run
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 14:58
 */
class Run
{
    public $caseNamespace = 'clis\cases';

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
        /*if(!class_exists($class)) {
            $class .= 'Case';
            if(!class_exists($class)) {
                exit('类['.$class.']不存在'.PHP_EOL);
            }
        }*/
	
        spl_autoload_register(function ($class_name) {
	  	 /* 限定类名路径映射 */
        	 $_class_map=array(
       		 // 限定类名 => 文件路径
       		 "Account"=>'clis/cases/account',
            	 );
		$file = '/data/samba/2018-open-source/block_chain_account/'.$class_name.'.php';
		include($file);
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

