<?php


include ('baza.php');

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if($id > 0){
        $sthCover = $pdo->prepare('SELECT cover FROM `regal` WHERE id= :id');    // Informacja o rekordzie
        $sthCover->bindParam(':id', $id);
        $sthCover->execute();

        $resultCover = $sthCover->fetch()['cover'];
        if($resultCover){
            unlink(__DIR__ . '/img/' . $resultCover);
        }

        $sth = $pdo->prepare('DELETE FROM `regal` WHERE id= :id');
        $sth->bindParam(':id', $id);
        $sth->execute();

        header('Location: loop.php');
    }else{
        header('Location: loop.php');
    }

