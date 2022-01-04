<?php

/* Luodaan tietokantayhteys */

function openDb(){

    try{
        $db = new PDO('mysql:host=localhost;dbname=n0keka00', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $error){
        echo '<br>'.$error->getMessage();
    }

    return $db;
}


/**
 * Tarkistaa onko tietokannassa käyttäjä ja onko salasana validi
 */
function checkUser(PDO $db, $username, $password){

    //Sanitoidaan.
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    try{
        $sql = "SELECT password FROM user_ WHERE username=?";  //komento, arvot parametreina
        $prepare = $db->prepare($sql);   //valmistellaan
        $prepare->execute(array($username));  //kysely tietokantaan

        $rows = $prepare->fetchAll(); //haetaan tulokset (voitaisiin hakea myös eka rivi fetch ja tarkistus)

        //Käydään rivit läpi (max yksi rivi tässä tapauksessa) 
        foreach($rows as $row){
            $pw = $row["password"];  //password sarakkeen tieto (hash salasana tietokannassa)
            if( password_verify($password, $pw) ){  //tarkistetaan salasana tietokannan hashia vasten
                return true;
            }
        }

        //Jos ei löytynyt vastaavuutta tietokannasta, palautetaan false
        return false;

    }catch(PDOException $error){
        echo '<br>'.$error->getMessage();
    }
}

/* Tallennetaan uusi käyttäjä */

function createUser(PDO $db, $username, $password){
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);


    try{
        $hash_pw = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "INSERT IGNORE INTO user_ VALUES (?,?)"; 
        $prepare = $db->prepare($sql); 
        $prepare->execute(array($username, $hash_pw));  
    }catch(PDOException $error){
        echo '<br>'.$error->getMessage();
    }
}

/* Tallennetaan käyttäjälle jotain yksityistä tietoa */

function createUserData(PDO $db, $username, $userinfo){
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $userinfo = filter_var($userinfo, FILTER_SANITIZE_STRING);

    try{
        $sql = "INSERT IGNORE INTO user_info VALUES (?,?)"; 
        $prepare = $db->prepare($sql); 
        $prepare->execute(array($username, $userinfo));  
    }catch(PDOException $error){
        echo '<br>'.$error->getMessage();
    }
}

function getUserData(PDO $db, $username) {
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    try {
        $sql = "SELECT info from user_info WHERE username = $username";
    } catch(PDOException $error){
        echo '<br>'.$error->getMessage();
    }
}