<!-- Index Datei für die auszuführende Studie -->

<?php
    session_start();

    /* Generierung der Datenbank für alle 3 Studiendurchläufe, um die bei der Durchführung erhaltenen Ergebnisse in dieser zu speichern.
    Dies ist Nötig, um die Studie am Ende auswerten zu können. Jeder Nutzer erhält eine individuelle ID, um die Google-Forms Fragebögen den richtigen Ergebnis zuordnen zu können */ 


    // Verbindung mit bereits vorhandener Datenbank
    $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');

    //Hier wird die Datenbank für die einzelnen Studien generiert, sofern diese noch nicht vorhanden sein sollte. 
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `generated_studies` (
        `ID` int(11) NOT NULL AUTO_INCREMENT,
        `Content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Content`)),
        `Track_Usage` tinyint(1) DEFAULT NULL,
        `Calc_KLM` tinyint(1) DEFAULT NULL,
        `Show_Overview` tinyint(1) DEFAULT NULL,
        `Show_MyList` tinyint(1) DEFAULT NULL,
        `Depth` int(11) DEFAULT NULL,
        `study_1_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`study_1_words`)),
        `study_2_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`study_2_words`)),
        `study_3_words` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`study_3_words`)),
        PRIMARY KEY (`ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

      //Hier wird die Datenbank für die jeweiligen Studiendurchläufe generiert
      mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `User_Interaction` (

        /* Durchlauf 1 */
        `User_ID` int(11) NOT NULL AUTO_INCREMENT,
        `User_Success_Rate_1` float DEFAULT NULL,
        `Time_On_Task_1` float DEFAULT NULL,
        `Task_Error_Rate_1` int(11) DEFAULT NULL,
        `Clicks_Total_1` int(11) DEFAULT NULL,
        `KLM_1` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KLM_1`)),
        `KLM_Time_1` float DEFAULT NULL,

        /* Durchlauf 2 */
        `User_Success_Rate_2` float DEFAULT NULL,
        `Time_On_Task_2` float DEFAULT NULL,
        `Task_Error_Rate_2` int(11) DEFAULT NULL,
        `Clicks_Total_2` int(11) DEFAULT NULL,
        `KLM_2` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KLM_2`)),
        `KLM_Time_2` float DEFAULT NULL,

        /* Durchlauf 3 */
        `User_Success_Rate_3` float DEFAULT NULL,
        `Time_On_Task_3` float DEFAULT NULL,
        `Task_Error_Rate_3` int(11) DEFAULT NULL,
        `Clicks_Total_3` int(11) DEFAULT NULL,
        `KLM_3` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`KLM_3`)),
        `KLM_Time_3` float DEFAULT NULL,
        PRIMARY KEY (`User_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1');
    
    if ($id && $id->num_rows) {
        $id = $id->fetch_row()[0];
    }
    if (session_id() != $id) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User_Interaction` (`User_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1')->fetch_row()[0];
        session_id($id);
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
        <div class="content">
            <img class="logo" src="./images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Willkommen</h1>
                <p class="text" style="height: 4em">Bacon ipsum dolor amet bacon shankle picanha ball tip. 
                Tri-tip shoulder jowl filet mignon venison flank. Prosciutto pork turducken, 
                kielbasa ground round strip steak short</p>
                <form action="./studies/questionnaire.php" method="POST">
                    <label style="display: block">
                        <input type="radio" name="datenschutz" value="1" required>
                        Ich habe die <a href="./datenschutz.php" target="_blank">Datenschutzbestimmungen</a> gelesen und akzeptiert
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