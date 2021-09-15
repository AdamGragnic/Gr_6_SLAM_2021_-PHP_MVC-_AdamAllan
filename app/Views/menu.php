<?php 
  include 'templates/header.php'; 
  $nomFraisForfait = "";
  $montantFraisForfait = "";
?>

<div class="content">
  <table id="fiche">
    <tbody>
      <tr>
        <form action="/" method="post">
          <th><input type="month" name="month"></th>
          <td><button type="submit" name="select">Sélectionner</button></td>
        </form>
      </tr>
      <tr>
        <th>Nom</th>
        <td><?php echo $user->nom." ".$user->prenom?></td>
      </tr>
      <tr>
        <th>Mois</th>
        <td><?php echo $fraisActuel->mois ?></td>
      </tr>
      <tr>
        <th>Montant</th>
        <td><?php echo $fraisActuel->montantValide ?>€</td>
      </tr>
      <tr>
        <th>Date</th>
        <td><?php echo $fraisActuel->dateModif ?></td>
      </tr>
    </tbody>
  </table>
  <div id="frais">
    <table id="fforfait">
      <thead>
        <tr>
          <th>Frais</th>
          <th>Quantite</th>
          <th>Montant</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($fraisForfait as $key => $value){ 
          switch ($value->idFraisForfait){
            case "ETP":
              $nomFraisForfait = "Forfait Étape";
              $montantFraisForfait = $value->quantite * 110;
              break;
            case "KM":
              $nomFraisForfait = "Frais Kilométrique";
              $montantFraisForfait = $value->quantite * 1;
              break;
            case "NUI":
              $nomFraisForfait = "Nuitée Hôtel";
              $montantFraisForfait = $value->quantite * 80;
              break;
            case "REP":
              $nomFraisForfait = "Repas Restaurant";
              $montantFraisForfait = $value->quantite * 25;
              break;
          }
          ?>
          <tr>
            <td><?php echo $nomFraisForfait ?></td>
            <td><?php echo $value->quantite ?></td>
            <td><?php echo $montantFraisForfait ?> €</td></tr>
        <?php } ?>
      </tbody>

      <tfoot>
        <form action="/" method="POST">
          <td>
            <select name='typefrais'>
              <optgroup label="Choisissez un type de frais">
                  <option value="ETP">Forfait Etape</option>
                  <option value="KM">Frais kilomètre</option>
                  <option value="NUI">Nuit Hôtel</option>
                  <option value="REP">Repas Restaurant</option>
              </optgroup>
            </select>
          </td>
          <td><input type="number" value="1" name="quantite"></td>
          <td><button type='submit' name="ff">Valider</button></td>
        </form>
      </tfoot>
    </table>
    <table id="fhorsforfait">
      <thead>
        <tr>
          <th>Frais</th>
          <th>Montant</th>
          <th>Date</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($fraisHorsForfait as $key => $value){ ?>
          <tr>
            <td><?php echo $value->libelle ?></td>
            <td><?php echo $value->montant ?></td>
            <td><?php echo $value->date ?></td>
          </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <form action="/" method="POST">
          <td><input type="text" name="nom"></td>
          <td><input type="number" name="prix"></td>
          <td><input type="date" name="date"></td>
          <td><button type='submit' name="fhf">Valider</button></td>
        </form>
      </tfoot>
    </table>
  </div>
</div>
<?php include 'templates/footer.php'; ?>