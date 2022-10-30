<?php

/* In dieser Index Datei steht der Code für den Generator, mit dem eine .csv-Datei hochgeladen werden kann um den Inhalt anschließend
in einer Navigationleiste auszugeben. */

    session_start();

    /* Verbindungsaufbau mit der Online Datenbank. Für eine lokale Verwendung müssen die hier angegebenen Daten geändert werden. Falls die Datenbank noch nicht existieren sollte, 
    da der Generator zum ersten Mal ausgeführt wird, wird automatisch eine neue Datenbank generiert. */
    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `Functions` (
        `Functions_ID` int(11) NOT NULL AUTO_INCREMENT,
        `TrackUsage` tinyint(1) NOT NULL,
        `CalcKLM` tinyint(1) NOT NULL,
        `ShowOverview` tinyint(1) NOT NULL,
        `ShowMyList` tinyint(1) NOT NULL,
        `SearchWords` tinyint(1) NOT NULL,
        PRIMARY KEY (`Functions_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `WordList` (
        `WordList_ID` int(11) NOT NULL AUTO_INCREMENT,
        `WordsToSearch` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`WordsToSearch`)),
        `Functions_ID` int(11) NOT NULL,
        PRIMARY KEY (`WordList_ID`),
        FOREIGN KEY (`Functions_ID`) REFERENCES `Functions`(`Functions_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');

    /* Hier wird die Datenbank für die einzelnen Studien generiert, sofern diese noch nicht vorhanden sein sollte. */
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `Menu-Generator` (
        `Menu_ID` int(11) NOT NULL AUTO_INCREMENT,
        `Functions_ID` int(11) NOT NULL,
        `Content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Content`)),
        `Depth` int(11) NOT NULL,
        PRIMARY KEY (`Menu_ID`),
        FOREIGN KEY (`Functions_ID`) REFERENCES `Functions`(`Functions_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `User` (
        `Session_ID` int(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`Session_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    
    mysqli_query($mysql, 'CREATE TABLE IF NOT EXISTS `Experiment` (
        `Experiment_ID` int(11) NOT NULL,
        `Durchgang_ID` int(11) NOT NULL,
        `Menu_ID` int(11) NOT NULL,
        `User_ID` int(11) NOT NULL,
        `UserSuccessRate` float NOT NULL,
        `TimeOnTask` float NOT NULL,
        `TaskErrorRate` int(11) NOT NULL,
        `ClicksTotal` int(11) NOT NULL,
        `KLM` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`KLM`)),
        `KLM-Time` float NOT NULL,
        `Clicks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Clicks`)),
        `SystemInfo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`SystemInfo`)),
        PRIMARY KEY (`Experiment_ID`, `Durchgang_ID`),
        FOREIGN KEY (`Menu_ID`) REFERENCES `Menu-Generator`(`Menu_ID`),
        FOREIGN KEY (`User_ID`) REFERENCES `User`(`Session_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    
    if (!empty($_FILES)) :
        $csv = file_get_contents($_FILES['content']['tmp_name']);
        
        $array = explode("\n", $csv);
        if (empty($array[count($array)-1])) {
            array_pop($array);
        }

        foreach ($array as &$val) {
            $val = str_getcsv($val, ';');
        }

        $n = count($array[0]);
        $new_array = [];
        $ev = '';
        $d = 0;
        $depth = 0;
        
// Parsen der hochgeladenen .csv-Datei und Erstellen eines mehrdimensionalen Arrays
        foreach ($array as $j => $element) {
            if ($element[0]) {
                if ($j > 0) {
                    $ev .= ' => null';
                    for ($i = 1; $i < $d; $i++) {
                        $ev .= ']';
                    }
                    $ev .= '];';
                }
                $d = 0;
                $ev .= '$new_array[\'' . $element[0] . '\'] = ';
            } else {
                for ($i = 1; $i < $n; $i++) {
                    if ($element[$i]) {
                        if ($i > $d) {
                            if ($i > 1) {
                                $ev .= ' => ';
                            }
                            $ev .= '[';
                        } elseif ($i < $d) {
                            $ev .= ' => null';
                            for ($j = $i; $j < $d; $j++) {
                                $ev .= ']';
                            }
                            $ev .= ', ';
                        } else {
                            $ev .= ' => null, ';
                        }
                        $d = $i;
                        if ($depth < $d) {
                            $depth = $d;
                        }
                        $ev .= '\'' . $element[$i] . '\'';
                        break;
                    }
                }
            }
        }
        $ev .= ' => null';
        for ($i = 1; $i < $d; $i++) {
            $ev .= ']';
        }
        $ev .= '];';
        eval($ev);
        $json = json_encode($new_array, JSON_UNESCAPED_UNICODE);

        /* Hier wird der Inhalt des Menüs in die Datenbank geschrieben. */
        mysqli_query($mysql, 'INSERT INTO `Functions` (`TrackUsage`, `CalcKLM`, `ShowOverview`, `ShowMyList`, `SearchWords`) VALUES (' . ($_POST['tracking'] ?? 0) . ', ' . ($_POST['klm'] ?? 0) . ', ' . ($_POST['overview'] ?? 0) . ', ' . ($_POST['mylist'] ?? 0) . ', ' . ($_POST['searchWords'] ?? 0) . ')');
        
        $functionsID = mysqli_query($mysql, 'SELECT MAX(`Functions_ID`) FROM `Functions`')->fetch_row()[0];
        
        if ($_POST['searchWords']) {
            $csv = file_get_contents($_FILES['words']['tmp_name']);
            $array = explode("\n", $csv);
            if (empty($array[count($array)-1])) {
                array_pop($array);
            }
            foreach($array as &$val) {
                $val = str_replace(array("\r", "\n"), '', $val);
            }

            /* Hier werden die zu suchenden Wörter durchgewürfelt und in die Datenbank geschrieben. Somit hat jeder Versuchsdurchlauf eine unterschiedliche Reihenfolge der Wörter, die wiederum für alle Probanden identisch ist. */
            $words1 = $array;
            shuffle($array);
            $words2 = $array;
            shuffle($array);
            $words3 = $array;
            shuffle($array);
            $words4 = $array;
            shuffle($array);
            $words5 = $array;
            
            mysqli_query($mysql, 'INSERT INTO `WordList` (`WordsToSearch`, `Functions_ID`) VALUES (\'' . json_encode($words1, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words2, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words3, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words4, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words5, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . ')');
            
        }
        
        mysqli_query($mysql, 'INSERT INTO `Menu-Generator` (`Functions_ID`, `Content`, `Depth`) VALUES (' . $functionsID . ', \'' . $json . '\', ' . $depth . ')');
        
        $_SESSION['study'] = mysqli_query($mysql, 'SELECT MAX(`Menu_ID`) FROM `Menu-Generator`')->fetch_row()[0];
        header('Location: menu-types/output.php');
    else :
        require('../header.php');
?>  


<!-- HTML Aufbau für den Generator --> 

        <div class="content">
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Menü Generator</h1>
                <p class="text" style="height:6em">Hier haben Sie die Möglichkeit eine CSV-Datei hochzuladen um daraus automatisch ein horizontales Baum-Menü zu generieren. 
                Zudem haben Sie die Möglichkeit mehrere Funktionen zu wählen. Das Ergebnis wird in einer automtisch generierten Datenbank hinterlegt.
                </p>
                <form class="form" method="post" enctype="multipart/form-data">
                    <div><label class="label"><span class="btn btn-upload">Upload a file</span><input type="file" class="btn-upload" name="content"><span class="description"></span></label></div>
                    <div class="two-cols">
                        <div>
                            <label>
                                <input class="label" type="checkbox" name="tracking" value="1">
                                Zeiten messen
                            </label>
                        </div>
                        <div>
                            <label>
                                <input class="label" type="checkbox" name="klm" value="1">
                                Key Stroke Level Model berechnen
                            </label>
                        </div>
                        <div>
                            <label>
                                <input class="label" type="checkbox" name="overview" value="1">
                                "Übersicht" anzeigen
                            </label>
                        </div>
                        <div>
                            <label>
                                <input class="label" type="checkbox" name="mylist" value="1">
                                "Meine Liste" anzeigen
                            </label>
                        </div>
                    </div>

                    <div class="uploadWordList">
                        <label>
                            <input class="label" type="checkbox" name="searchWords" value="1">
                            Wörter suchen?
                        </label>
                        <label>
                            <span class="btn btn-upload" title="Am besten eine CSV-Datei mit nur einer Spalte :)">Upload a file</span>
                           <input type="file" name="words"/><span class="description"></span>
                         </label>
                    </div>

                    <input type="submit" class="btn" value="Absenden">
                </form>
            </div>
            <div class="footer"> 
                <div class="descr1">Fabian Ziegler – Matrikel-Nr. 2082578</div>
                <div class="descr2">Menü-Generator Version 1 - Online</div>
            </div>
        </div>
    </body>
</html>

<?php endif; ?>