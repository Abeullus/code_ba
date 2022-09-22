<?php 

    session_start();

    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        while ($id = $ids->fetch_row()) {
            $id_array[] = $id[0];
        }
    }
    if (!$id_array || !in_array(session_id(), $id_array)) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User` (`Session_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT MAX(`Session_ID`) FROM `User`')->fetch_row()[0];
        session_id(sprintf('%03d', $id));
        session_start();
    }
    
    if (!isset($_SESSION['study'])) {
        header('Location: /');
    }
    
    $study = mysqli_query($mysql, 'SELECT * FROM `Menu-Generator` WHERE `Menu_ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study) {
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
                <p class="text" style="height: 4em">Sofern für die Teilnahme an der Studie <b>VP-Stunden</b> angerechnet werden sollen, bitte ich Sie darum noch das nachfolgende Formular mit dem dafür Benötigten Daten auszufüllen. <br>
                Sofern <b>keine VP-Stunden</b> benötigt werden, können Sie das Browser-Fenster schließen.
                </p>
                <a class="btn" href="https://docs.google.com/forms/d/e/1FAIpQLSepHefNTiObxzZcLvF9Hg3w0YxWZWFv0Y6y1tIhTXSzXSq71g/viewform?usp=sf_link">VP-Stunden</a>
                <a class="btn" href="https://docs.google.com/forms/d/e/1FAIpQLScWewwIWbDoV8DLEdYh4RzTIGlb7nA9xnjtst08oxiSCG0akA/viewform?usp=sf_link">Studie beenden</a>
            </div>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">10</div>
            </div>
        </div>
    </body>
</html>