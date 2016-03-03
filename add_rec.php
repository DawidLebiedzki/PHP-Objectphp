<?php
/**
 * Skrypt dodaje 500 rekordów do bazy danych
 * User: Dawid
 * Date: 21.02.2016
 * Time: 14:39
 */

$autor = "Mickiewicz";
$tytyl = "Pan Tadeusz";
$recenzja = "Super powieść patryiotyczna";
$cat_id = 3;

include ('login.php');
   for($i=1; $i<=500; $i++)
    {
        $sth = $pdo->prepare('INSERT INTO `regal`(`Tytul`, `cat_id`, `Autor`, `Recenzja`) VALUES (:Tytul,:cat_id,:Autor,:Recenzja)');
        $sth->bindParam(':Tytul', $tytyl);
        $sth->bindParam(':cat_id', $cat_id);
        $sth->bindParam(':Autor', $autor);
        $sth->bindParam(':Recenzja', $recenzja);

        $sth->execute();

        echo $i;
        echo '<br>';
    }
//    header('Location: loop.php');