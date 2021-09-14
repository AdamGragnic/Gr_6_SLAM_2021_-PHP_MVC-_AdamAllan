<?php

namespace App\Controllers;

use App\Models\Frais;
use App\Models\Visiteur;

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
                $this->MainPage();
            } 
            elseif (isset($_POST['fhf'])){
                $this->MainPage();
            } 
            elseif (isset($_POST['select'])){
                $this->MainPage();
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

    public function MainPage()
    {
        echo view('menu');
    }

    public function CheckLogin()
    {
        $visiteur = new Visiteur();
        
        $result = $visiteur->Attempt($_POST['login'],$_POST['password']);

        if (isset($result[0])){
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
        echo view('menu');
    }

    public function AddFraisHorsForfait()
    {
        echo view('menu');
    }

    public function SelectMonth()
    {
        echo view('menu');
    }
}
