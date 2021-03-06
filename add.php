<?php
//**************************************************************************************************//


include('login.php');
if (isset($_POST['autor'])) {   // Jesli jest ustawiona zmienna autor czyli jesli formularz zostal wyslany

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    $fileName = 0;

    // Jezeli istnieje plik obrazu i nie zawiera bledu
    if (isset($_FILES['cover']['error']) && ($_FILES['cover']['error'] == 0)) {

        require("vendor/autoload.php");

        //********************** Pobranie pliku obrazu **********************************//
        $uid = uniqid();    // Funkcja nadaje unikalne ID

        $ext = pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION);     // Funkcja pobiera rozszerzenie pliku

        $fileName = 'cover_' . $uid . '.' . $ext;   // Nadawaanie nazwy + ID + rozszerzenie
        $imagine = new Imagine\Gd\Imagine();
        $size = new Imagine\Image\Box(120, 120);

        //$mode    = Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;

        $imagine->open($_FILES['cover']['tmp_name'])// Otwarcie pliku
        ->thumbnail($size, $mode)
            ->save(__DIR__ . '/img/' . $fileName);      // Zapisanie pliku
    }


    //*********************** Zapytanie do bazy******************************************//

    // Jesli wystepuje $id czyli id to wykonaj update rekordu ,jesli nie to dodaj nowy
    if ($id > 0) {

        // Gdy plik istnieje w bazie
        if ($fileName) {
            $sth = $pdo->prepare('UPDATE `regal` SET `Tytul`=:Tytul,`cat_id`=:cat_id,`Autor`=:Autor,`Recenzja`=:Recenzja, `cover`=:cover WHERE id = :id');
            $sth->bindParam(':cover', $fileName);

            $sthCover = $pdo->prepare('SELECT cover FROM `regal` WHERE id= :id');    // Informacja o rekordzie
            $sthCover->bindParam(':id', $id);
            $sthCover->execute();

            $resultCover = $sthCover->fetch()['cover'];
                if($resultCover){
                    unlink(__DIR__ . '/img/' . $resultCover);
                }
        } else {
            $sth = $pdo->prepare('UPDATE `regal` SET `Tytul`=:Tytul,`cat_id`=:cat_id,`Autor`=:Autor,`Recenzja`=:Recenzja WHERE id = :id');
        }
        $sth = $pdo->prepare('UPDATE `regal` SET `Tytul`=:Tytul,`cat_id`=:cat_id,`Autor`=:Autor,`Recenzja`=:Recenzja, `cover`=:cover WHERE id = :id');
        $sth->bindParam(':id', $id);
    } else {
        $sth = $pdo->prepare('INSERT INTO `regal`(`Tytul`, `cat_id`, `Autor`, `Recenzja`, `cover`) VALUES (:Tytul,:cat_id,:Autor,:Recenzja,:cover)');
    }
    if ($fileName) {
        $sth->bindParam(':cover', $fileName);
    }

    $sth->bindParam(':Tytul', $_POST['tytul']);
    $sth->bindParam(':cat_id', $_POST['cat_id']);
    $sth->bindParam(':Autor', $_POST['autor']);
    $sth->bindParam(':Recenzja', $_POST['recenzja']);
    $sth->execute();

    header('Location: loop.php');
    //************************** Koniec zapytań*****************************************//
}


//****************************Obsluga fromularza z pliku loop.php***********************//
$idGet = isset($_GET['id']) ? intval($_GET['id']) : 0; // Jesli zostalo przeslana zmienna GET $id z pliku loop.php
if ($idGet > 0) {
    $sth = $pdo->prepare('SELECT * FROM `regal` WHERE id= :id');
    $sth->bindParam(':id', $idGet);
    $sth->execute();

    $result = $sth->fetch();
}

$sth2 = $pdo->prepare('SELECT * FROM `category` ORDER BY name ASC'); //
$sth2->bindParam(':id', $idGet);
$sth2->execute();

$category = $sth2->fetchAll();

?>

<form method="post" action="add.php" enctype="multipart/form-data">
    <?php
    if ($idGet > 0) {
        echo '<input type="hidden" name="id" value="' . $idGet . '" >';
    }

    ?>
    Tytuł: <input type="text" name="tytul" <?php if (isset($result['Tytul'])) {
        echo 'value="' . $result['Tytul'] . '"';
    } ?>></br></br>
    Kategoria :<select name="cat_id">
        <?php

        foreach ($category as $value) {

            $selected = ($value['id'] == $result['cat_id']) ? 'selected="selected"' : '';
            echo '<option ' . $selected . ' value="' . $value['id'] . '">' . $value['name'] . '</option>';
        }

        ?>
    </select></br></br>
    Autor: <input type="text" name="autor" <?php if (isset($result['Autor'])) {
        echo 'value="' . $result['Autor'] . '"';
    } ?>></br></br>
    Recenzja: <textarea name="recenzja"><?php if (isset($result['Recenzja'])) {
            echo $result['Recenzja'];
        } ?></textarea></br></br>
    Okładka: <input type="file" name="cover">
    <?php
    if (isset($result['cover']) && $result['cover']) {
        echo '<img src="img/' . $result['cover'] . '">';
    }
    ?>

    </br></br>
    <input type="submit" value="Zapisz">
</form>


