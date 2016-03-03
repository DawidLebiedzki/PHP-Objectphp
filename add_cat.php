<?php
    include ('login.php');
    

    if(isset($_POST['name'])){

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if($id > 0){

            $sth = $pdo->prepare('UPDATE `category` SET `name`=:name WHERE id = :id');

            $sth->bindParam(':id', $id);

        }else{

            $sth = $pdo->prepare('INSERT INTO `category`(`name`) VALUES (:name)');
        }

        
        $sth->bindParam(':name', $_POST['name']);
        $sth->execute();

        header('Location: category.php');
    }

    $idGet = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if($idGet > 0){
        $sth = $pdo->prepare('SELECT * FROM `category` WHERE id= :id');
        $sth->bindParam(':id', $idGet);
        $sth->execute();

        $result = $sth->fetch();
    }
?>

<form method="post" action="add_cat.php">
    <?php
        if($idGet > 0){
            echo '<input type="hidden" name="id" value="'. $idGet .'" >';
        }

    ?>
    Nazwa kategorii: <input type="text" name="name" <?php if(isset($result['name'])){echo 'value="'. $result['name'] . '"';}?>></br></br>
 
    <input type="submit" value="Zapisz">
</form>


