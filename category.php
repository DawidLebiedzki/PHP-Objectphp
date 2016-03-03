<?php
/**
 * Created by PhpStorm.
 * User: Dawid
 * Date: 03.01.2016
 * Time: 11:08
 */
include('login.php');
echo '<br/><a href="add_cat.php">Dodaj kategorię</a><br/><br/>';

echo 'Lista kategorii: <br/>';

    $tbl = $pdo->query('SELECT * FROM `category`');

    echo '<table border="1">';
    echo '<tr>';

        echo '<th>ID</th>';
        echo '<th>Kategoria</th>';
        echo '<th>Edycja</th>';

    echo '</tr>';

    foreach($tbl->fetchAll() as $value){
//        echo '<pre>';
//        print_r($value);

        echo '<tr>';
            echo '<td>'.$value['id'].'</td>';
            echo '<td>'.$value['name'].'</td>';
            echo '<td><a href="cat_del.php?id='.$value['id'].'">Usuń</a> | <a href="add_cat.php?id='.$value['id'].'">Edytuj</a></td>';
        echo '</tr>';
    }

    echo '</table>';