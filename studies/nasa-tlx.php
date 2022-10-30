<?php

/* Hier steht der Content für den NASA-TLX Fragebogen, der am Ende der Studie ausgeführt werden soll. 
Dieser wird wie die allgemeinen Fragebögen zu Beginn der Studie mit einem "iFrame" in den HTML-Quellcode eingepflegt. 

Nach Ausfüllen des Fragebogens erscheint wieder ein Button, um die Studie zu beenden. Dieser führt zur Feedback-Seite, auf der 
die Teilnehmer benötigte VP-Stunden erhalten können */ 


    session_start();

    /* Verbindungsaufbau mit der Online Datenbank. Für eine lokale Verwendung müssen die hier angegebenen Daten geändert werden. */
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
    
    if (!$study || empty($_POST)) {
        header('Location: /');
    }

    $timings = json_decode($_POST['timings'], true);

    /* Hier werden die Daten des 5. Versuchsdurchlaufs in die Datenbank geschrieben. Dies erfolgt zeitlich versetzt, da so nicht während eines Versuchsdurchlaufs das Fenster neu geladen werden kann um bessere Ergebnisse zu erzielen. */
    mysqli_query($mysql, 'INSERT INTO `Experiment` (`Experiment_ID`, `Durchgang_ID`, `Menu_ID`, `User_ID`, `UserSuccessRate`, `TimeOnTask`, `TaskErrorRate`, `ClicksTotal`, `KLM`, `KLM-Time`, `Clicks`, `SystemInfo`) VALUES (' . $_SESSION['exp'] . ', 5, ' . $_SESSION['study'] . ', ' . session_id() . ', ' . (float)$_POST['tsr'] . ', ' . (float)$_POST['realtime'] . ', ' . (int)$_POST['errors'] . ', ' . (int)$timings['bb'] . ', \'' . $_POST['timings'] . '\', ' . (float)$_POST['time'] . ', \'' . $_POST['clicks'] . '\', \'' . $_POST['platform'] . '\')');
    
    require('../header.php');

?>

<!-- HTML Content --> 

        <div class="content">
            <div class="study-headline">
                <h1>Allgemeine Fragen Teil 3</h1>
            </div>
            <a class="btn-continue" href="../feedback.php">Studie beenden</a>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSexPMleVHR3aUOsaSordE5ClFl5Rv6kK0XAMIIS3QpoJWuNeQ/viewform?embedded=true" width="640" height="1092" frameborder="0" marginheight="0" marginwidth="0">Wird geladen�</iframe>
            </div>
            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">9</div>
            </div>
        </div>
    </body>
</html>