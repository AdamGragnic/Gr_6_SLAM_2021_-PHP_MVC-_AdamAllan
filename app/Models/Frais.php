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

        return $query->getResult()[0];
    }
    
    public function GetLignesFraisHorsForfait($uid, $mois)
    {
        $db = db_connect();

        $sql = "SELECT * FROM lignefraishorsforfait WHERE idVisiteur = ? AND mois = ?";

        $query = $db->query($sql,[$uid,$mois]);

        return $query->getResult();
    }
    public function CreateFicheFrais($uid, $mois)
    {
        $db = db_connect();

        $date = date('Y-m-d');

        $sql = "INSERT INTO `fichefrais` (`idVisiteur`, `mois`, `nbJustificatifs`, `montantValide`, `dateModif`, `idEtat`) VALUES (?, ?, '0', '0', ?, 'CR')";

        $db->query($sql,[$uid,$mois, $date]);        
    }

    public function GetMontantFrais($idFrais)
    {
        $db = db_connect();

        return $db->query("SELECT * FROM `fraisforfait` WHERE id = ?",[$idFrais])->getResult()[0]->montant;
    }

    public function AddLigneFraisForfait($uid, $mois,$idFrais,$quantite)
    {
        $db = db_connect();

        if($this->GetFrais($uid,$mois) == null)
        {
            $this->CreateFicheFrais($uid,$mois);
        }
        $frais = $this->GetLigneFraisForfait($uid, $mois, $idFrais);
        if(isset($frais)){
            $sql = "UPDATE `lignefraisforfait` SET `quantite` = ? WHERE `lignefraisforfait`.`idVisiteur` = ? AND `lignefraisforfait`.`mois` = ? AND `lignefraisforfait`.`idFraisForfait` = ?";
            $db->query($sql,[$quantite + $frais->quantite,$uid,$mois,$idFrais]);
        }
        else{
            $sql = "INSERT INTO `lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (?, ?, ?, ?)";
            $db->query($sql,[$uid,$mois,$idFrais,$quantite]);
        }
        $this->RecalculateMontant($uid,$mois);
    }

    public function AddLigneFraisHorsForfait($uid, $mois,$libelle,$montant,$date)
    {
        $db = db_connect();
        if($this->GetFrais($uid,$mois) == null)
        {
            $this->CreateFicheFrais($uid,$mois);
        }

        $sql = "INSERT INTO `lignefraishorsforfait` (`id`, `idVisiteur`, `mois`, `libelle`, `date`, `montant`) VALUES (NULL, ?, ?, ?, ?, ?)";

        $db->query($sql,[$uid,$mois,$libelle,$date,$montant]);
        $this->RecalculateMontant($uid,$mois);
    }

    public function UpdateFicheFrais($uid, $mois, $montant)
    {
        $db = db_connect();

        $sql = "UPDATE `fichefrais` SET `montantValide` = ? WHERE `fichefrais`.`idVisiteur` = ? AND `fichefrais`.`mois` = ?";

        $db->query($sql,[$montant, $uid, $mois]);
    }

    public function UpdateFraisForfait($uid, $mois, $id, $quantite)
    {
        $db = db_connect();

        $frais = $this->GetLigneFraisForfait($uid,$mois, $id);

        $sql = "UPDATE `lignefraisforfait` SET `quantite` = ? WHERE `lignefraisforfait`.`idVisiteur` = ? AND `lignefraisforfait`.`mois` = ? AND `lignefraisforfait`.`idFraisForfait` = ?";

        $db->query($sql,[$frais->quantite + $quantite, $uid, $mois, $id]);
    }

    public function RecalculateMontant($uid, $mois)
    {
        $db = db_connect();

        $montant = 0;

        $ff = $this->GetLignesFraisForfait($uid,$mois);
        $fhf = $this->GetLignesFraisHorsForfait($uid,$mois);

        foreach ($ff as $frais)
        {
            $montant += $frais->quantite * $this->GetMontantFrais($frais->idFraisForfait);
        }
        foreach ($fhf as $frais)
        {
            $montant += $frais->montant;
        }
        
        $this->UpdateFicheFrais($uid,$mois,$montant);
    }
}