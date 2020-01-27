<?php

namespace App\Controller\Globals;


/**
 * Class SessionController
 * @package App\Controller\Globals
 */
class SessionController
{
    const ADMIN = 'Administrateur';

    const USER = 'Utilisateur';

    /**
     * @var mixed
     */
    private $session;

    /**
     * @var
     */
    private $user;

    /**
     * SessionController constructor.
     */
    public function __construct()
    {
        $this->session = filter_var_array($_SESSION);

        if (isset($this->session['user'])) {
            $this->user = $this->session['user'];
        }
    }

    /**
     * @param array $data
     */
    public function createSession(array $data)
    {
        if ($data['rank'] == 1) $data['rank'] = self::ADMIN;
        elseif ($data['rank'] == 2) $data['rank'] = self::USER;

        $this->session['user'] = [
            'session_id' => session_id() . microtime() . rand(0, 9999999999),
            'pseudo' => $data['pseudo'],
            'id_user' => $data['id_user'],
            'email' => $data['email'],
            'date_register' => $data['date_register'],
            'rank' => $data['rank']
        ];
        $this->user = $this->session['user'];
        $_SESSION['user'] = $this->session['user'];
        $this->verifyRank();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        if (!empty($this->getUserVar('session_id'))) {
            return true;
        }
        return false;
    }


    /**
     *
     */
    private function verifyRank()
    {
        if ($this->getUserVar('rank') == 1) {
            $this->setUserVar('rank', self::ADMIN);
        } elseif ($this->getUserVar('rank') == 2) {
            $this->setUserVar('rank', self::USER);
        }
    }

    /**
     * @return mixed
     */
    public function getUserArray()
    {
        return $this->user;
    }

    /**
     * @param $var
     * @return mixed
     */
    public function getUserVar($var)
    {
        return $this->user[$var];
    }


    /**
     * @param string $var
     * @param $data
     */
    public function setUserVar(string $var, $data)
    {
        $this->user[$var] = $data;
    }


}