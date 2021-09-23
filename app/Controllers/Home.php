<?php

namespace App\Controllers;

use App\Models\Frais;
use App\Models\Visiteur;

class Home extends BaseController
{
    public function index()
    {   //Demarre la sessions
        session_start();
        //Si l'utilisateur est connecté
        if ($this->IsLog())
        {
            //Si logout est appelé, deconnecte de la session
            if (isset($_POST['logout'])) {
                $this->Logout();
            }
            //Sinon ff est appelé, ajoute les fraisForfait
            elseif(isset($_POST['ff'])){
                $this->AddFraisForfait();
            } 
            //Sinon fhf est appelé, ajoute les fraisHorsForfait
            elseif (isset($_POST['fhf'])){
                $this->AddFraisHorsForfait();
            } 
            //Sinon select est appelé, choisis un mois
            elseif (isset($_POST['select'])){
                $this->SelectMonth();
            } 
            else {
                //Redirige sur la page principale
                $this->MainPage();
            }
            
          //Sinon 
        } else {
            //Si submit existe, verifie le login
            if (isset($_POST['submit'])) {
                $this->CheckLogin();
            //Sinon, ramene a la page de connexion
            } else {
                $this->LoginPage();
            }
            
        }
    }

    public function LoginPage()
    {   
        //Echo la vue de connexion
        echo view('login');
    }

    public function MainPage($mois = null)
    {   
        //Initialise un nouveau frais
        $frais = new Frais();
        //Initialise un nouveau visiteur
        $visiteur = new Visiteur();

        //Recupere le mois et la date
        if (!isset($mois)){
            $mois = $this->GetMois(date('m'));
        }

        $fraisActuel = $frais->GetFrais($_SESSION['uid'],$mois);
        $user = $visiteur->GetUserData($_SESSION['uid']);
        $fraisForfait = $frais->GetLignesFraisForfait($_SESSION['uid'], $mois);
        $fraisHorsForfait = $frais->GetLignesFraisHorsForfait($_SESSION['uid'], $mois);

        //Echo la vue menu
        echo view('menu', [
            'fraisActuel' => $fraisActuel,
            'user' => $user,
            'fraisForfait' => $fraisForfait,
            'fraisHorsForfait' => $fraisHorsForfait
        ]);
    }

    public function CheckLogin()
    {   
        //Cree une nouvelle instance de visiteur 
        $visiteur = new Visiteur();
        
        //Appelle la fonction Attempt pour verifier le login et le password, si cela correspond $result prends la valeur ID correspondant a l'utilisateur
        $result = $visiteur->Attempt($_POST['login'],$_POST['password']);
        
        //SI l'ID a une valeur, stocke l'ID dans $_SESSION et ramene a la main mage
        if (isset($result)){
            $_SESSION['uid'] = $result->id;
            $this->MainPage();
        }
        //Sinon, ramene a la page de connexion
        else{
            $this->LoginPage();
        }        
    }

    public function Logout()
    {
        //Deconnecte de la session
        session_unset();
        
        //Set l'ID de la session a null
        $_SESSION['uid'] = null;
        
        //Echo la vue de connexion
        echo view('login');
    }

    public function IsLog()
    {
        //Si il y a un id d'utilisateur
        if (isset($_SESSION['uid'])){
            //Retourne vrai
            return true;
        }
        else{
            //Retourne faux
            return false;
        }
    }

    public function AddFraisForfait()
    {
        //Cree un nouveau frais
        $frais = new Frais();
        //Get le mois
        $mois = $this->GetMois(date('m'));
        //Ajoute a LigneFraisForfait les données suivantes
        $frais->AddLigneFraisForfait($_SESSION['uid'],$mois,$_POST['typefrais'],$_POST['quantite']);

        $this->MainPage();
    }

    public function AddFraisHorsForfait()
    {   
        //Cree un nouveau frais
        $frais = new Frais();
        //Get le mois
        $mois = $this->GetMois(date('m'));
        //Ajoute a LigneFraisHorsForfait les données suivantes
        $frais->AddLigneFraisHorsForfait($_SESSION['uid'],$mois,$_POST['nom'],$_POST['prix'],$_POST['date']);
        
        $this->MainPage();
    }

    
    public function SelectMonth()
    {
        //Si month a une valeur
        if(isset($_POST['month'])){
            //$mois prends la valeur de month
            $mois = $_POST['month'];
            //Recupere dans $mois l'ID du mois
            $mois = $this->GetMois(substr($mois, 5,2));
            //MaisPage recupere $mois
            $this->MainPage($mois);
        }
        //Sinon MainPage ne recupere pas $mois
        else{
            $this->MainPage();
        }
    }

    //Initialise la liste des mois
    const LISTEMOIS = [ 1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 =>'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 =>'Novembre', 12 => 'Décembre'];

    private function GetMois($id)
    {
        //retourne la liste des mois
        return $this::LISTEMOIS[(int) $id];
    }
}
