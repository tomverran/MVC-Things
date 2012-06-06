<?php
namespace Library;
/**
 * Http.php
 * Makes accessing GET/POSTDATA less tedious.
 * Most frameworks do this, so why not.
 * @author Tom
 * @since 06/06/12
 */
class Http {

    private function __construct()
    {
        //static class
    }

    /**
     * @static
     * @param $index
     * @param $default
     * @return mixed
     */
    public static function get($index, $default)
    {
        return isset($_GET[$index]) ? $_GET[$index] : $default;
    }

    /**
     * @static
     * @param $index
     * @param $default
     * @return mixed
     */
    public static function post($index, $default)
    {
        return isset($_POST[$index]) ? $_POST[$index] : $default;
    }

}
