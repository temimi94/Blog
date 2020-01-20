<?php

namespace App\Controller\Globals;

use App\Controller\MainController;
use App\Model\LoginModel;

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
     * @param bool $remember_me
     */
    public function login($data = [], $remember_me = null)
    {
        if ($remember_me == true) {
            $auth_token = bin2hex(openssl_random_pseudo_bytes(32));
            $token = new LoginModel();
            $token->createAuthToken($auth_token, $data['id_user']);
            $cookie = new CookieController();
            $cookie->createCookie('gtk', $auth_token, time()+604800);//Create cookie that expires in 1 week
            //setcookie('gtk',$auth_token, time()+604800);
        }
        $main = new MainController();
        $this->createSession($data);
        $this->verifyRank();
        if ($this->getUserVar('rank') === 'Administrateur') $main->redirect('admin');
        elseif ($this->getUserVar('rank') === 'Utilisateur') $main->redirect('user');
    }

    public function verifyAuth(){
        $cookie = new CookieController();
        $cookie = $cookie->getCookieVar('gtk');
        if(!empty($cookie) AND !$this->isLogged()){ // AND !isset($_SESSION['remember_me'])
            $token = filter_input(INPUT_COOKIE, 'gtk');
            $req = new LoginModel();
            $req = $req->searchAuthToken($token);
            if($req){
                $this->createSession($req);
                $this->verifyRank();
            }
        }
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
        $cookie = new CookieController();
        $cookie->destroyCookie('gtk');
        //setcookie('gtk', '', time()-3600);
    }

    public function getUserArray()
    {
        return $this->user;
    }

    public function getUserVar($var)
    {
        return $this->user[$var];
    }


    public function setUserVar(string $var, $data)
    {
        $this->user[$var] = $data;
    }


}