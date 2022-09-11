<?php 

    session_start();

    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        $ids = $ids->fetch_all();
        $ids = array_merge(...$ids);
//        print_r($ids);exit;
    }
    if (!$ids || !in_array(session_id(), $ids)) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User` (`Session_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT MAX(`Session_ID`) FROM `User`')->fetch_row()[0];
        session_id($id);
        session_start();
    }
    
    if (!isset($_SESSION['study'])) {
        header('Location: /');
    }
    
    $study = mysqli_query($mysql, 'SELECT * FROM `Menu-Generator` WHERE `Menu_ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study || empty($_POST)) {
        header('Location: /');
    }

require('header.php');

?>

<!--
Hier kann optinal noch Inhalt für Versuchspersonenstunden eingefügt werden
-->
<div class="content">
            <img class="logo" src="./images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Vielen Dank für Ihre Teilnahme!</h1>
                <p class="text" style="height: 4em">Bacon ipsum dolor amet bacon shankle picanha ball tip. 
                Tri-tip shoulder jowl filet mignon venison flank. Prosciutto pork turducken, 
                kielbasa ground round strip steak short</p>
            </div>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">8</div>
            </div>
        </div>
    </body>
</html>