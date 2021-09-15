<?php

namespace App\Models;

use CodeIgniter\Model;

class Frais extends Model{
    public function GetFrais($mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM fichefrais WHERE mois = ?";

        $query = $db->query($sql,[$mois]);

        return $query->getResult()[0];
    }

    public function GetLigneFraisForfait($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraisforfait WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult();
    }
    
    public function GetLigneFraisHorsForfait($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraishorsforfait WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult();
    }
}