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
            'sessionId' => session_id(),
            'pseudo' => $data['pseudo'],
            'idUser' => $data['idUser'],
            'email' => $data['email'],
            'dateRegister' => $data['dateRegister'],
            'rank' => $data['rank']
        ];
        $this->user = $this->session['user'];
        $_SESSION['user'] = $this->session['user'];
        $this->verifyRank();
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
    }

    /**
     * @return bool
     */
    public function isLogged()
    {
        if (!empty($this->getUserVar('sessionId'))) {
            return true;
        }
        return false;
    }

    public function isAdmin()
    {
        if ($this->getUserVar('rank') !== 'Administrateur') {
            header('Location: index.php?page=home');
        }
        return true;
    }

    public function isUser()
    {
        if ($this->getUserVar('rank') !== 'Utilisateur') {
            header('Location: index.php?page=home');
        }
        return true;
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

}