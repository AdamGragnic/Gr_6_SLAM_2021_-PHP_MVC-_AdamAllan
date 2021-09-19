<?php

namespace App\Models;

use CodeIgniter\Model;

class Frais extends Model{
    public function GetFrais($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM fichefrais WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult()[0];
    }

    public function GetLignesFraisForfait($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraisforfait WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult();
    }

    public function GetLigneFraisForfait($uid, $mois, $id)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraisforfait WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?";

        $query = $db->query($sql,[$uid,$mois, $id]);

        return $query->getResult();
    }
    
    public function GetLignesFraisHorsForfait($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraishorsforfait WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult();
    }
}