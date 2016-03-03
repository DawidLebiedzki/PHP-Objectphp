<?php
/**
 * Created by PhpStorm.
 * User: Dawid
 * Date: 03.01.2016
 * Time: 11:08
 */
include('login.php');

    function minMax($val, $min, $max){          // Funkcja zwraca wartosc w jakims przedziale
        return ($val >= $min && $val <=$max);
    }

    $count = $pdo->query('SELECT COUNT(id) as cnt FROM regal')->fetch()['cnt'];    // Liczymy ile mamy rekordow w bazie

    $page = isset($_GET['page']) ? intval($_GET['page' ] - 1) : 1;  // Przekazujemy numer strony w zmienne $_GET ,jeśli nie jest ustawiona to otrzymuje wartosc 1

    $limit = 10;                     // Limit rekordów na stronę

    $from = $page * $limit;         // Od jakiego rekordu sa wyswietlane dane na stronie

    $allPage = ceil($count / $limit);
    // Zapytanie do bazy
    $tbl = $pdo->query('SELECT r.*, c.name FROM `regal` r LEFT JOIN category c ON r.cat_id = c.id ORDER BY r.id LIMIT ' . $from . ',' .$limit);

    echo 'PAGE :' . $page . '<br>';
    echo 'COUNT :' . $count . '<br>';
    echo 'LIMIT :' . $limit . '<br>';
    echo 'FROM :' . $from . '<br>';
    echo 'ALL PAGES :' . $allPage . '<br>';



echo '<br/><a href="add.php">Dodaj ksiazke</a><br/><br/>';

echo 'Lista książek: <br/>';

echo '<table border="1">';
    echo '<tr>';

        echo '<th>ID</th>';
        echo '<th>Tytuł</th>';
        echo '<th>Kategoria</th>';
        echo '<th>Autor</th>';
        echo '<th>Recenzja</th>';
        echo '<th>Edycja</th>';


    echo '</tr>';

    foreach($tbl->fetchAll() as $value){
//        echo '<pre>';
//        print_r($value);

        echo '<tr>';
            echo '<td>'.$value['ID'].'</td>';
            echo '<td>'.$value['Tytul'].'</td>';
            echo '<td>'.$value['name'].'</td>';
            echo '<td>'.$value['Autor'].'</td>';
            echo '<td>'.$value['Recenzja'].'</td>';
            echo '<td><a href="usun.php?id='.$value['ID'].'">Usuń</a> | <a href="add.php?id='.$value['ID'].'">Edytuj</a></td>';
        echo '</tr>';
    }

    echo '</table>';



    if($page > 4){
        echo '<a href="loop.php?page=1"> < pierwsza strona </a>|';
    }

    for($i = 1; $i<=$allPage; $i++){

        if ( minMax( $i, ($page - 3), ($page+5))){

        $bold = ($i == ($page + 1)) ? 'style="font-size: 20px;"': '';

        echo '<a '. $bold . 'href="loop.php?page=' .$i. '">'. $i .'</a>|';
        }
    }

    if($page < ($allPage - 1)){
        echo '<a href="loop.php?page='.$allPage.'"> ostatnia strona ></a>';
    }

    echo $page;