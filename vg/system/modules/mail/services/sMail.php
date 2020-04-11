<?php
namespace modules\mail\services;

class sMail
{
    protected static $instance;

    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    /**
     * Превратим данные в структурированный текст
     * @param $data
     * @return false|string
     */
    public function getBlockBuffer($data)
    {
        ob_start();                                //  Let's start output buffering.
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        $contents = ob_get_contents();             //  Instead, output above is saved to $contents
        ob_end_clean();
        return $contents;
    }

}