<?php
namespace App\Controller\Globals;

class CookieController{

    private $cookie;

    public function __construct()
    {
        $this->cookie = filter_input_array(INPUT_COOKIE);
    }

    public function createCookie(string $name, string $value = '', int $expire = 0)
    {
        if ($expire === 0) {
            $expire = time() + 3600;
        }
        setcookie($name, $value, $expire, '/');
    }


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