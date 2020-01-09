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


    public function createSession(array $data)
    {
        if ($data['rank'] == 1) $data['rank'] = 'Administrateur';
        elseif ($data['rank'] == 2) $data['rank'] = 'Utilisateur';

        $_SESSION['user'] = [
            'session_id' => session_id(),
            'pseudo' => $data['pseudo'],
            'id_user' => $data['id_user'],
            'email' => $data['email'],
            'date_register' => $data['date_register'],
            'rank' => $data['rank']
        ];
        $this->__construct();
        /** Lance le constructeur pour mettre les données dans $this->session et $this->user */

    }
}