<?php

namespace App\Controllers;

use App\Models\Frais;
use App\Models\Visiteur;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    public function index()
    {
        session_start();

        if ($this->IsLog())
        {
            if (isset($_POST['logout'])) {
                $this->Logout();
            }
            elseif(isset($_POST['ff'])){
                $this->AddFraisForfait();
            } 
            elseif (isset($_POST['fhf'])){
                $this->AddFraisHorsForfait();
            } 
            elseif (isset($_POST['select'])){
                $this->SelectMonth();
            } 
            else {
                $this->MainPage();
            }
            
            
        } else {
            if (isset($_POST['submit'])) {
                $this->CheckLogin();
            } else {
                $this->LoginPage();
            }
            
        }
    }

    public function LoginPage()
    {
        echo view('login');
    }

    public function MainPage($mois = null)
    {
        $frais = new Frais();
        $visiteur = new Visiteur();

        if (!isset($mois)){
            $mois = $this->GetMois(date('m'));
        }

        $fraisActuel = $frais->GetFrais($_SESSION['uid'],$mois);
        $user = $visiteur->GetUserData($_SESSION['uid']);
        $fraisForfait = $frais->GetLignesFraisForfait($_SESSION['uid'], $mois);
        $fraisHorsForfait = $frais->GetLignesFraisHorsForfait($_SESSION['uid'], $mois);

        echo view('menu', [
            'fraisActuel' => $fraisActuel,
            'user' => $user,
            'fraisForfait' => $fraisForfait,
            'fraisHorsForfait' => $fraisHorsForfait
        ]);
    }

    public function CheckLogin()
    {
        $visiteur = new Visiteur();
        
        $result = $visiteur->Attempt($_POST['login'],$_POST['password']);

        if (isset($result[0])){
            $_SESSION['uid'] = $result[0]->id;
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

    public function IsLog()
    {
        if (isset($_SESSION['uid'])){
            return true;
        }
        else{
            return false;
        }
    }

    public function AddFraisForfait()
    {
        // Ajout du frais si non présent pour ce frais et ce mois. Addition des frais si déjà présent
        // pa de - stp
        $this->MainPage();
    }

    public function AddFraisHorsForfait()
    {
        // Insert en fonction du mois

        $this->MainPage();
    }

    public function SelectMonth()
    {
        if(isset($_POST['month'])){
            $mois = $_POST['month'];

            $mois = $this->GetMois(substr($mois, 5,2));

            $this->MainPage($mois);
        }
        else{
            $this->MainPage();
        }
    }

    const LISTEMOIS = [ 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 =>'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 =>'Novembre', 12 => 'Décembre'];

    private function GetMois($id)
    {
        return $this::LISTEMOIS[(int) $id];
    }
}
