<?php
    // Index-Datei für die auszuführende Studie

    session_start();

    /* Generierung der Datenbank für alle 5 Studiendurchläufe, um die bei der Durchführung erhaltenen Resultate in dieser zu speichern.
    Dies ist Nötig, um die Studie am Ende auswerten zu können. Jeder Nutzer erhält eine individuelle ID, um die Google-Forms Fragebögen den richtigen Ergebnis zuordnen zu können */ 


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
    require('header.php');
    
    //Hier wird die jeweilige Session-ID gesetzt. 
    if (isset($_GET['study'])) {
        $_SESSION['study'] = (int)$_GET['study'];
    } else {
        unset($_SESSION['study']);
    }
?>

<!-- HTML Content für die 1. Seite der Studie. Hier befindet sich eine kurze Erklärung, sowie die Checkbox für die benötigten Datenschutzbestimmungen --> 
        <div class="content content-start">
            <img class="logo" src="./images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Willkommen zur Online-Studie "vom Novizen zum Experten" </h1>
                <p class="text" style="height: 5em">Die Studie besteht aus insgesamt <b> drei Fragebögen </b> und  <b> fünf Versuchsdurchläufen</b>, die Ihnen nacheinander gezeigt werden. 
                Sie werden bei jedem Fragebogen gebeten Ihre <b> Session-ID </b> einzugeben, diese befindet sich links unten in der Leiste. 
                Die Studie dauert ca. 30 Minuten. Sofern benötigt, erhalten nach erfolgreicher Teilnahme am Ende  <b> 0,5 VP </b> . Zudem gibt es eine Verlosung für einen 15€ Gutschein Ihrer Wahl.
                <br>
                <br> 
                Vielen Dank, dass Sie mir bei meiner Bachelorarbeit helfen! </p>
                <br>
                <br>

                <form action="./studies/questionnaire.php" method="POST">
                    <label style="display: block">
                        <input type="radio" name="datenschutz" value="1" required>
                        Ich habe die <a href="../datenschutz.php" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiert
                    </label>
                    <input type="submit" class="btn" value="Jetzt teilnehmen">
                </form>
            </div>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">1</div>
            </div>
        </div>
    </body>
</html>