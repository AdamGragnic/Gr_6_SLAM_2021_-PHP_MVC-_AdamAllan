<?php

namespace App\Models;

use CodeIgniter\Model;

class Visiteur extends Model{
    public function Attempt($login, $password)
    {
        $db = db_connect();

        $querry = $db->query("SELECT * FROM visiteur WHERE login = ? AND mdp = ?",[$login,$password]);

        return $querry->getResult();
    }
}