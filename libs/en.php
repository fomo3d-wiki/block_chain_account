<?php
namespace libs;

/**
 * Created by PhpStorm.
 * @Author: Luck Li Di
 * @Email : lucklidi@126.com
 * @Date: 2018/8/28
 * @Time: 20:06
 */
class en
{
    public $fileName = './geten,json';

    public function getEN()
    {
        $EN = $this->_list();
        if ($EN) {
            $en = array_column($EN['lucklidi'], 'en');
            @file_put_contents('./en.txt',$en);
        }
    }

    /**
     * @return array|bool|mixed|string
     * @Author: Luck Li Di
     * @Email : lucklidi@126.com
     * @Date: 2018/8/28
     * @Time: 14:16
     */
    protected function _list()
    {
        $content = '';
        if(file_exists($this->fileName)) {
            $content = file_get_contents($this->fileName);
        }

        if(empty($content)) {
            $content = [];
        } else {
            $content = json_decode($content,true);
        }

        return $content;
    }
}