<?php


include ('baza.php');

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if($id > 0){

        $sth = $pdo->prepare('DELETE FROM `category` WHERE id= :id');
        $sth->bindParam(':id', $id);
        $sth->execute();

        header('Location: category.php');
    }else{
        header('Location: category.php');
    }

