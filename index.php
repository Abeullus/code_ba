<!-- Index Datei für die auszuführende Studie -->


<?php
    session_start();

    /* Generierung der Datenbank für alle 3 Studiendurchläufe, um die bei der Durchführung erhaltenen Ergebnisse in dieser zu speichern.
    Dies ist Nötig, um die Studie am Ende auswerten zu können. Jeder Nutzer erhält eine individuelle ID, um die Google-Forms Fragebögen den richtigen Ergebnis zuordnen zu können */ 


    // Verbindung mit bereits vorhandener Datenbank
    //$mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler'); --> lokaler Server über XAMPP 
    
    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
   


    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        $ids = $ids->fetch_all();
        $ids = array_merge(...$ids);
    }


    if (!$ids || !in_array(session_id(), $ids)) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User` (`Session_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT MAX(`Session_ID`) FROM `User`')->fetch_row()[0];
        session_id($id);
        session_start();
    }
    require('header.php');
    
    //Hier wird die jeweilige Session-ID ausgelesen. 
    if (isset($_GET['study'])) {
        $_SESSION['study'] = (int)$_GET['study'];
    } else {
        unset($_SESSION['study']);
    }
?>

<!-- HTML Content für die 1. Seite der Studie. Hier befindet sich eine kurze Erklärung, sowie die Checkbox für die benötigten Datenschutzbestimmungen --> 
        <div class="content">
            <img class="logo" src="./images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Willkommen zur Onlinestudie</h1>
                <p class="text" style="height: 4em">Bacon ipsum dolor amet bacon shankle picanha ball tip. 
                Tri-tip shoulder jowl filet mignon venison flank. Prosciutto pork turducken, 
                kielbasa ground round strip steak short</p>
                <form action="./studies/questionnaire.php" method="POST">
                    <label style="display: block">
                        <input type="radio" name="datenschutz" value="1" required>
                        Ich habe die <a href="./datenschutz.php" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiert.
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