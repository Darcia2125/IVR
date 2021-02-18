#! /usr/bin/php -q
<?php

    require('phpagi.php');

    $agi = new AGI();

    $numero = $agi -> request['agi_callerid'];
    $destinataire = $agi-> get_variable('Destinataire', true);
    $trans = $agi-> get_variable('Trans', true);

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
    
    $requete = $pdo -> prepare("UPDATE client SET solde= (solde - ?) WHERE phone_num = ? ");
    $requete->execute(array($trans,$numero));

    $requete = $pdo -> prepare("UPDATE client SET solde= (solde + ?) WHERE phone_num = ? ");
    $requete->execute(array($trans,$destinataire));

    $requete = $pdo -> query("SELECT username,solde FROM client WHERE phone_num='$numero'");
    $result = $requete -> fetchall();

    $requete_dest = $pdo -> query("SELECT username FROM client WHERE phone_num='$destinataire'");
    $result_dest = $requete_dest -> fetchall();

    $username = $result[0][0];
    $solde = $result[0][1];
    $username_dest = $result_dest[0][0];


    $agi -> set_variable('numero',$numero);
    $agi -> set_variable('username',$username);
    $agi -> set_variable('username_dest',$username_dest);
    $agi -> set_variable('solde',$solde);
    $agi -> answer();
?>
