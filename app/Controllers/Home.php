<?php

namespace App\Controllers;

use App\Models\Frais;
use App\Models\Visiteur;

class Home extends BaseController
{
    public function index()
    {
        session_start();

        var_dump($_SESSION);

        // Vérification si l'utilisateur es connecté
        if ($this->IsLog()){
            // Traitement des formulaires
            if (isset($_POST['submit'])){
                switch ($_POST['submit']){
                    case 'ff':
                        break;
                    case 'fhf':
                        break;
                    case 'logout':
                        $this->Logout();
                        break;
                }
            }

            // Redirection sur les pages
            if (isset($_GET['url']))
            {
                switch($_GET['url'])
                {
                    case 'home':
                        $this->MainPage();
                        break;
                    case 'login':
                        $this->LoginPage();
                        break;
                }
            }
            else {
                $this->MainPage();
            }
        }
        else{
            if (isset($_POST['login'])){
                $this->CheckLogin();
            }
            else{
                $this->LoginPage();
            }
        }
    }

    public function LoginPage()
    {
        echo view('login');
    }

    public function MainPage()
    {
        echo view('menu');
    }

    public function CheckLogin()
    {
        $visiteur = new Visiteur();
        
        $result = $visiteur->Attempt($_POST['login'],$_POST['password']);

        echo "Login";
        die;

        if ($result != ""){
            $_SESSION['uid'] = "516";
            $this->MainPage();
        }
        else{
            $this->LoginPage();
        }        
    }

    public function Logout()
    {
        session_unset();

        $_SESSION['uid'] = null;

        echo view('login');
    }

    private function IsLog()
    {
        if (isset($_SESSION['uid'])){
            return true;
        }
        else{
            return false;
        }
    }
}
