<?php

namespace App\Models;

use CodeIgniter\Model;

class Visiteur extends Model{
    public function Attempt($login, $password)
    {
        //Connecte a la base de donnee
        $db = db_connect();
        //La querry recupere et stock l'ID du visiteur connecté
        $querry = $db->query("SELECT `id` FROM visiteur WHERE login = ? AND mdp = ?",[$login,$password]);
        //Retourne le resultat de la querry
        return $querry->getResult()[0];
    }

    public function GetUserData($uid)
    {
        //Connecte a la base de donnee
        $db = db_connect();
        //La querry recupere et stock le nom et prenom du visiteur connecté
        $querry = $db->query("SELECT `nom`,`prenom` FROM visiteur WHERE id = ?",[$uid]);
        //Retourne le resultat de la querry
        return $querry->getResult()[0];
    }
}
