<?php

namespace App\Models;

use CodeIgniter\Model;

class Frais extends Model{
    
//Recupere le contenu de la table fichefrais pour le visiteur connect�
public function GetFrais($uid, $mois)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Cette requete SQL recupere tout le contenu de la table fichefrais de ce mois-ci pour ce visiteur
        $sql = "SELECT * FROM fichefrais WHERE idVisiteur = ? AND mois = ?";

        //La query recupere et stocke $uid et $mois
        $query = $db->query($sql,[$uid,$mois]);

        //Retourne le resultat de la query
        return $query->getResult()[0];
    }

    //Recupere le contenu de la table lignefraisforfait pour le visiteur
    public function GetLignesFraisForfait($uid, $mois)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Cette requete SQL recupere tout le contenu de la table lignefraisforfait de ce mois-ci pour ce visiteur
        $sql = "SELECT * FROM lignefraisforfait WHERE idVisiteur = ? AND mois = ?";

        //La query recupere et stocke $uid et $mois
        $query = $db->query($sql,[$uid,$mois]);

        //Retourne le resultat de la query
        return $query->getResult();
    }

    //Recupere le contenu de la ligne lignefraisforfait pour le frais choisis pour le visiteur
    public function GetLigneFraisForfait($uid, $mois, $id)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Cette requete SQL recupere tout le contenu de la table lignefraishorsforfait de ce mois-ci pour ce visiteur
        $sql = "SELECT * FROM lignefraisforfait WHERE idVisiteur = ? AND mois = ? AND idFraisForfait = ?";

        //La query recupere et stock $uid et $mois
        $query = $db->query($sql,[$uid,$mois, $id]);

        //Retourne le resultat de la query
        return $query->getResult()[0];
    }
    
    //Recupere le contenu de la tabke lignefraishorsforfait pour le visiteur
    public function GetLignesFraisHorsForfait($uid, $mois)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Cette requete SQL recupere tout le contenu de la table lignefraishorsforfait de ce mois-ci pour ce visiteur
        $sql = "SELECT * FROM lignefraishorsforfait WHERE idVisiteur = ? AND mois = ?";

        //La query recupere et stock $uid et $mois
        $query = $db->query($sql,[$uid,$mois]);
        //Retourne le resultat de la query
        return $query->getResult();
    }
    
    //Crée une ficheFrais
    public function CreateFicheFrais($uid, $mois)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Determine le format de la date
        $date = date('Y-m-d');

        //Insert dans la table fichefrais les donn�es suivantes
        $sql = "INSERT INTO `fichefrais` (`idVisiteur`, `mois`, `nbJustificatifs`, `montantValide`, `dateModif`, `idEtat`) VALUES (?, ?, '0', '0', ?, 'CR')";

        //Execute la requete SQL
        $db->query($sql,[$uid,$mois, $date]);        
    }

    //Recupere le montant des frais
    public function GetMontantFrais($idFrais)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Retourne le montant des frais via une query qui recupere tout le contenu d'un frais selon son id
        return $db->query("SELECT * FROM `fraisforfait` WHERE id = ?",[$idFrais])->getResult()[0]->montant;
    }

    //Ajoute les frais a lignefraisforfait si les frais de ce mois-ci existent deja, sinon, cree la ligne et y insert les frais
    public function AddLigneFraisForfait($uid, $mois,$idFrais,$quantite)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Si il y n'y a pas de ficheFrais pour ce mois-ci
        if($this->GetFrais($uid,$mois) == null)
        {
            //Cree la ficheFrais de ce mois-ci
            $this->CreateFicheFrais($uid,$mois);
        }

        $frais = $this->GetLigneFraisForfait($uid, $mois, $idFrais);
        
        //Verifie si lignefraisforfait existe, si il existe, update lignefraisforfait
        if(isset($frais)){
            $sql = "UPDATE `lignefraisforfait` SET `quantite` = ? WHERE `lignefraisforfait`.`idVisiteur` = ? AND `lignefraisforfait`.`mois` = ? AND `lignefraisforfait`.`idFraisForfait` = ?";
            //Execute la requete SQL
            $db->query($sql,[$quantite + $frais->quantite,$uid,$mois,$idFrais]);
        }
        //Sinon, Insert dans lignefraisforfait les don�es suivantes
        else{
            $sql = "INSERT INTO `lignefraisforfait` (`idVisiteur`, `mois`, `idFraisForfait`, `quantite`) VALUES (?, ?, ?, ?)";
            //Execute la requete SQL
            $db->query($sql,[$uid,$mois,$idFrais,$quantite]);
        }
        $this->RecalculateMontant($uid,$mois);
    }

    //Ajoute les frais a lignefraishorsforfait si les frais existent deja, sinon, cree la ligne et y insert les frais
    public function AddLigneFraisHorsForfait($uid, $mois,$libelle,$montant,$date)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Si il n'y a pas de ficheFrais pour ce mois-ci
        if($this->GetFrais($uid,$mois) == null)
        {
            //Cree la ficheFrais de ce mois-ci
            $this->CreateFicheFrais($uid,$mois);
        }

        //Insert dans la table fraishorsfait les donn�es suivantes
        $sql = "INSERT INTO `lignefraishorsforfait` (`id`, `idVisiteur`, `mois`, `libelle`, `date`, `montant`) VALUES (NULL, ?, ?, ?, ?, ?)";

        //Execute la requete SQL
        $db->query($sql,[$uid,$mois,$libelle,$date,$montant]);
        $this->RecalculateMontant($uid,$mois);
    }

    //Mets a jour la table ficheFrais
    public function UpdateFicheFrais($uid, $mois, $montant)
    {
        //Connecte a la base de donn�e
        $db = db_connect();
        //update la table Fichefrais
        $sql = "UPDATE `fichefrais` SET `montantValide` = ? WHERE `fichefrais`.`idVisiteur` = ? AND `fichefrais`.`mois` = ?";

        //Execute la requete SQL
        $db->query($sql,[$montant, $uid, $mois]);
    }

    //Mets a jour la table lignefraisforfait
    public function UpdateFraisForfait($uid, $mois, $id, $quantite)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        
        $frais = $this->GetLigneFraisForfait($uid,$mois, $id);

        //Update la table lignefraisforfait en y ajoutant les donn�es suivantes
        $sql = "UPDATE `lignefraisforfait` SET `quantite` = ? WHERE `lignefraisforfait`.`idVisiteur` = ? AND `lignefraisforfait`.`mois` = ? AND `lignefraisforfait`.`idFraisForfait` = ?";

        //Execute la requete SQL
        $db->query($sql,[$frais->quantite + $quantite, $uid, $mois, $id]);
    }

    
    //recalcule le montant des frais en additionant le montant saisis avec celui deja present en base
    public function RecalculateMontant($uid, $mois)
    {
        //Connecte a la base de donn�e
        $db = db_connect();

        //Initialise le montant a 0
        $montant = 0;

        //Insert les valeurs de LignesFraitForfait dans un tableau $ff
        $ff = $this->GetLignesFraisForfait($uid,$mois);
        //Insert les valeurs de LigneFraisHorsForfait dans un tableau $fhf
        $fhf = $this->GetLignesFraisHorsForfait($uid,$mois);
        //Traite chaque case du tableau $ff comme un $frais
        foreach ($ff as $frais)
        {
            //Calcule le montant du frais
            $montant += $frais->quantite * $this->GetMontantFrais($frais->idFraisForfait);
        }
        //Traite chaque case du tableau $fhf comme un $frais
        foreach ($fhf as $frais)
        {   
            //Calcule le montant du frais
            $montant += $frais->montant;
        }
        
        //Update la fichefrais avec le nouveau montant de frais
        $this->UpdateFicheFrais($uid,$mois,$montant);
    }
}