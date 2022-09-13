<?php

/*Hier steht der Content für den 1. Fragebogen. Dieser wird per "iFrame" von Google Forms geladen, da dies die Auswertung des Ergebnisses am Ende der Studie erleichtert. 
Um den Fragebogen den richtigen Teilnehmer zuordnen zu können, wird dieser aufgefordert seine individuelle Session-ID einzutragen. 

Zur Sicherstellung, dass der Fragebogen ausgefüllt wird erscheint der Button um mit der Studie fortzufahren erst, nachdem der Teilnehmer auf den "Senden" Button in dem Formular gedrückt hat. 
Dies hat zur Folge, dass das iFrame erneut geladen wird. Dieser Ladevorgang wird abgefangen um anschließend einen Button einzublenden. */

    session_start();
    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        while ($id = $ids->fetch_row()) {
            $id_array[] = $id[0];
        }
//        print_r($ids);exit;
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
    
    require('../header.php');
?>
        <div class="content">
            <div class="study-headline">
                <h1>Allgemeine Fragen Teil 1</h1>
            </div>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <a class="btn-continue" href="./questionnaire2.php">Weiter</a>
            <div class="content-inner">
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSd4k9hQHc8N8jy9eQbGVuC5aRojv7LhTGa8TwhQicwcMOP5ow/viewform?embedded=true" width="640" height="1092" frameborder="0" marginheight="0" marginwidth="0">Wird geladen�</iframe>
            </div>
            
            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">2</div>
            </div>
        </div>
    </body>
</html>