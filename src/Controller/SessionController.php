<?php

namespace App\Controller;

class SessionController extends MainController
{

    public function isLogged()
    {
        if (!empty($_SESSION['session_id'])) {
            return true;
        } else {
            return false;
        }
    }

    public function isLegit(){ //Rajouter Utilisateur
        if($_SESSION['rank'] != 'Administrateur' AND $_SESSION['rank'] != 'Utilisateur') $this->redirect('home');
    }

    public function login($data = [], $session_check = false)
    {
        if($session_check == true){
            session_start([
                'cookie_lifetime' => 2,628e+6,
                'read_and_close' => true]); /**Le cookie dure 1 mois **/
        }else {
            session_start([
                'cookie_lifetime' => 7200, /** Le cookie dure 2 heures  */
                'read_and_close' => true]);
        }

        $_SESSION['session_id'] = session_id();
        $_SESSION['pseudo'] = $data['pseudo'];
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['date_register'] = $data['date_register'];
        $_SESSION['rank'] = $data['rank'];
        $this->verifyRank();
        if($_SESSION['rank'] === 'Administrateur') $this->redirect('admin');
        elseif($_SESSION['rank'] === 'Utilisateur') $this->redirect('user');
    }

    public function verifyRank()
    {
        if ($_SESSION['rank'] == 1) {
            $_SESSION['rank'] = 'Administrateur';
        }elseif ($_SESSION['rank'] == 2) {
            $_SESSION['rank'] = 'Utilisateur';
        }
    }

    public function verifySessionRank(){
        if($_SESSION['rank'] === 'Administrateur'){
            $_SESSION['rank'] = 1;
        }elseif ($_SESSION['rank'] === 'Utilisateur'){
            $_SESSION['rank'] = 2;
        }
    }

    public function logout(){
        unset($_SESSION);
        session_destroy();
    }

}
