<?php

namespace App\Models;

use CodeIgniter\Model;

class Frais extends Model{
    public function GetFrais()
    {
        $db = db_connect();

        $sql = "SELECT * FROM fichefrais";

        $query = $db->query($sql);

        return $query->getResult();
    }
}