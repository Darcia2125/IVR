#! /usr/bin/php -q
<?php

    require('phpagi.php');

    $agi = new AGI();

    $numero = $agi -> request['agi_callerid'];

    $bdd = new PDO('mysql:host=localhost;db_name=VOIP','darcia','darcia');
    if (!isset($DB_HOST)) {
        $DB_HOST = 'localhost';
        $DB_USER = 'darcia';
        $DB_PASS = 'darcia';
        $DB_NAME = 'VOIP';
    } 
    try{
        $pdo = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASS);
    }catch(Exception $error){
        die("[ERREUR] Connexion à la BDD a échouée :" . $error->getMessage());
    }
    
    $requete = $pdo -> query("SELECT username,solde FROM client WHERE phone_num='$numero'");
    $result = $requete -> fetchall();

    $username = $result[0][0];
    $solde = $result[0][1];

    $agi -> set_variable('numero',$numero);
    $agi -> set_variable('username',$username);
    $agi -> set_variable('solde',$solde);
    $agi -> answer();
?>
