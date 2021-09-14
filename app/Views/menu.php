<?php include 'templates/header.php'; ?>

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
        <td>Test</td>
      </tr>
      <tr>
        <th>Mois</th>
        <td>Test</td>
      </tr>
      <tr>
        <th>Montant</th>
        <td>Test</td>
      </tr>
      <tr>
        <th>Date</th>
        <td>Test</td>
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
        <tr><td>Nuit d'hotêl</td><td>3</td><td>35 €</td></tr>
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
        <tr><td>Essence</td><td>45 €</td><td>10/09/2021</td></tr>
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