<?php

namespace App\Controller\Globals;

class SessionController
{

    private $session;

    private $user;

    public function __construct()
    {
        $this->session = filter_var_array($_SESSION);

        if (isset($this->session['user'])) {
            $this->user = $this->session['user'];
        }

    }

    public function getUserArray()
    {
        return $this->user;
    }

    public function getUserVar($var)
    {
        return $this->user[$var];
    }


    public function getSessionArray()
    {
        return $this->session;
    }

    public function getSessionVar($var)
    {
        return $this->session[$var];
    }

    public function setUserVar(string $var, $data)
    {
        $this->user[$var] = $data;
    }


}