<?php

namespace App\Controller;

class SessionController extends MainController
{

    public function isLogged()
    {
        if (!empty($this->session->getUserVar('session_id'))) {
            return true;
        } else {
            return false;
        }
    }

    public function isLegit(){ //Rajouter Utilisateur
        if($this->session->getUserVar('rank') != 'Administrateur' AND $this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

    public function login($data = [] /*,$session_check = false*/)
    {
       /** if($session_check == true){
            session_start([
                'cookie_lifetime' => 2,628e+6,
                'read_and_close' => true]);
        }else {
            session_start([
                'cookie_lifetime' => 7200,
                'read_and_close' => true]);
        }**/

        $this->session->createSession($data);
        $this->verifyRank();
        if($this->session->getUserVar('rank') === 'Administrateur') $this->redirect('admin');
        elseif($this->session->getUserVar('rank') === 'Utilisateur') $this->redirect('user');
        exit();
    }

    public function verifyRank()
    {
        if ($this->session->getUserVar('rank') == 1) {
            $this->session->setUserVar('rank', 'Administrateur');
        }elseif ($this->session->getUserVar('rank') == 2) {
            $this->session->setUserVar('rank', 'Utilisateur');
        }
    }

    public function verifySessionRank(){
        if($this->session->getUserVar('rank') === 'Administrateur'){
            $this->session->setUserVar('rank', 1);
        }elseif ($this->session->getUserVar('rank') === 'Utilisateur'){
            $this->session->setUserVar('rank', 2);
        }
    }

    public function logout(){
        unset($_SESSION);
        session_destroy();
    }


}
