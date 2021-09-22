<?php

namespace App\Models;

use CodeIgniter\Model;

class Visiteur extends Model{
    public function Attempt($login, $password)
    {
        $db = db_connect();

        $querry = $db->query("SELECT `id` FROM visiteur WHERE login = ? AND mdp = ?",[$login,$password]);

        return $querry->getResult()[0];
    }

    public function GetUserData($uid)
    {
        
        $db = db_connect();

        $querry = $db->query("SELECT `nom`,`prenom` FROM visiteur WHERE id = ?",[$uid]);

        return $querry->getResult()[0];
    }
}