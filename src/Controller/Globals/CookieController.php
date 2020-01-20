<?php
namespace App\Controller\Globals;

/**
 * Class CookieController
 * @package App\Controller\Globals
 */
class CookieController{

    /**
     * @var mixed
     */
    private $cookie;

    /**
     * CookieController constructor.
     */
    public function __construct()
    {
        $this->cookie = filter_input_array(INPUT_COOKIE);
    }

    /**
     * @param string $name
     * @param string $value
     * @param int $expire
     */
    public function createCookie(string $name, string $value = '', int $expire = 0)
    {
        if ($expire === 0) {
            $expire = time() + 3600;
        }
        setcookie($name, $value, $expire, '/');
    }


    /**
     * @param string $name
     */
    public function destroyCookie(string $name)
    {
        if ($this->cookie[$name] !== null) {

            $this->createCookie($name, '', time() - 3600);
        }
    }

    /**
     * @param string $var
     * @return mixed
     */
    public function getCookieVar(string $var)
    {
        if(!empty($this->cookie[$var])){
            return $this->cookie[$var];
        }
        return false;
    }


}