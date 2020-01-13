<?php

namespace App\Controller\Globals;

use App\Controller\MainController;

/**
 * Class SessionController
 * @package App\Controller\Globals
 */
class SessionController
{

    /**
     * @var mixed
     */
    private $session;

    private $user;

    public function __construct()
    {
        $this->session = filter_var_array($_SESSION);

        if (isset($this->session['user'])) {
            $this->user = $this->session['user'];
        }
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

        $this->setUserVar('session_id', session_id()); //Au lieu de lancer $this->construct
        $this->setUserVar('pseudo', $data['pseudo']);
        $this->setUserVar('id_user', $data['id_user']);
        $this->setUserVar('email', $data['email']);
        $this->setUserVar('date_register', $data['date_register']);
        $this->setUserVar('rank', $data['rank']);
    }

    public function isLegit()
    { //Rajouter Utilisateur
        if ($this->getUserVar('rank') != 'Administrateur' AND $this->getUserVar('rank') != 'Utilisateur'){
            $main = new MainController();
            $main->redirect('home');
        } //$this->redirect('home');
    }

    public function isLogged()
    {
        if (!empty($this->getUserVar('session_id'))) {
            return true;
        }
        return false;
    }

    /**
     * @param array $data
     */
    public function login($data = [] /*,$session_check = false*/)
    {
        /* TODO
          if($session_check == true){
          session_start([
          'cookie_lifetime' => 2,628e+6,
          'read_and_close' => true]);
          }else {
          session_start([
          'cookie_lifetime' => 7200,
          'read_and_close' => true]);
          }*/
        $main = new MainController();
        $this->createSession($data);
        $this->verifyRank();
        if ($this->getUserVar('rank') === 'Administrateur') $main->redirect('admin');
        elseif ($this->getUserVar('rank') === 'Utilisateur') $main->redirect('user');
    }


    public function verifyRank()
    {
        if ($this->getUserVar('rank') == 1) {
            $this->setUserVar('rank', 'Administrateur');
        } elseif ($this->getUserVar('rank') == 2) {
            $this->setUserVar('rank', 'Utilisateur');
        }
    }

    public function logout()
    {
        unset($_SESSION);
        session_destroy();
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