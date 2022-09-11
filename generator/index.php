<!-- In dieser Index Datei steht der Code für den Generator, mit dem eine .csv-Datei hochgeladen werden kann um den Inhalt anschließend
in einer Navigationleiste auszugeben. -->

<?php
    session_start();

// Verbindungsaufbau mit mySQL Datenbank, Anlegen aller benötigten Tabellen sofern diese nicht bereits existieren und Hochladen der .csv-Datei.
//    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    
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

    //Hier wird die Datenbank für die einzelnen Studien generiert, sofern diese noch nicht vorhanden sein sollte. 
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
        `Experiment_ID` int(11) NOT NULL AUTO_INCREMENT,
        `Menu_ID` int(11) NOT NULL,
        `User_ID` int(11) NOT NULL,
        `UserSuccessRate` float NOT NULL,
        `TimeOnTask` float NOT NULL,
        `TaskErrorRate` int(11) NOT NULL,
        `ClicksTotal` int(11) NOT NULL,
        `KLM` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`KLM`)),
        `KLM-Time` float NOT NULL,
        PRIMARY KEY (`Experiment_ID`),
        FOREIGN KEY (`Menu_ID`) REFERENCES `Menu-Generator`(`Menu_ID`),
        FOREIGN KEY (`User_ID`) REFERENCES `User`(`Session_ID`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;');
    
    if (!empty($_FILES)) :
        $csv = file_get_contents($_FILES['content']['tmp_name']);
        $array = explode("\n", $csv);
        if (empty($array[count($array)-1])) {
            array_pop($array);
        }
//        print_r($array);
        foreach ($array as &$val) {
            $val = str_getcsv($val, ';');
        }
//        print_r($array);exit;
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
        $csv = file_get_contents($_FILES['words']['tmp_name']);
        $array = explode("\n", $csv);
        if (empty($array[count($array)-1])) {
            array_pop($array);
        }
        foreach($array as &$val) {
            $val = str_replace(array("\r", "\n"), '', $val);
        }
        $words1 = $array;
        shuffle($array);
        $words2 = $array;
        shuffle($array);
        $words3 = $array;
//        print_r($words1);
//        print_r($words2);
//        print_r($words3);exit;
//        print_r($array);exit;
//        echo 'INSERT INTO `Generated_Studies` (`Type`, `Content`, `Track_Usage`, `Calc_KLM`, `Show_Overview`, `Depth`, `study_1_words`, `study_2_words`, `study_3_words`)'
//                . 'VALUES (\'' . $_POST['type'] . '\', \'' . $json . '\', ' . ($_POST['tracking'] ?? 0) . ', ' . ($_POST['klm'] ?? 0) . ', ' . ($_POST['overview'] ?? 0) . ', ' . $depth . ', \'' . json_encode($words1, JSON_UNESCAPED_UNICODE) . '\', \'' . json_encode($words2, JSON_UNESCAPED_UNICODE) . '\', \'' . json_encode($words3, JSON_UNESCAPED_UNICODE) . '\')';exit;
        
        mysqli_query($mysql, 'INSERT INTO `Functions` (`TrackUsage`, `CalcKLM`, `ShowOverview`, `ShowMyList`, `SearchWords`) VALUES (' . ($_POST['tracking'] ?? 0) . ', ' . ($_POST['klm'] ?? 0) . ', ' . ($_POST['overview'] ?? 0) . ', ' . ($_POST['mylist'] ?? 0) . ', ' . ($_POST['searchWords'] ?? 0) . ')');
        
        $functionsID = mysqli_query($mysql, 'SELECT MAX(`Functions_ID`) FROM `Functions`')->fetch_row()[0];
        
        if ($_POST['searchWords']) {
            mysqli_query($mysql, 'INSERT INTO `WordList` (`WordsToSearch`, `Functions_ID`) VALUES (\'' . json_encode($words1, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words2, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . '), (\'' . json_encode($words3, JSON_UNESCAPED_UNICODE) . '\', ' . $functionsID . ')');
            
//            echo $mysql->error;exit;
        }
        
        mysqli_query($mysql, 'INSERT INTO `Menu-Generator` (`Functions_ID`, `Content`, `Depth`) VALUES (' . $functionsID . ', \'' . $json . '\', ' . $depth . ')');
        
//        echo $mysql->error;exit;
        $_SESSION['study'] = mysqli_query($mysql, 'SELECT MAX(`Menu_ID`) FROM `Menu-Generator`')->fetch_row()[0];
//        echo $_SESSION['study'];exit;
        header('Location: menu-types/output.php');
    else :
        require('../header.php');
?>  


<!-- HTML Aufbau für den Generator --> 

        <div class="content">
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Menu Generator</h1>
                <p class="text" style="height:6em">Bacon ipsum dolor amet bacon shankle picanha ball tip. 
                Tri-tip shoulder jowl filet mignon venison flank. Prosciutto pork turducken, 
                kielbasa ground round strip steak short loin chicken fatback. 
                Corned beef t-bone andouille burgdoggen turducken filet mignon landjaeger sausage doner shoulder.
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
                            <span class="btn btn-upload">Upload a file</span>
                           <input type="file" name="words"/><span class="description"></span>
                         </label>
                        <!-- <label class="label"><input type="file" class="btn-upload" name="words"></label> -->
                    </div>

                    <input type="submit" class="btn" value="Absenden">
                </form>
            </div>
            <div class="footer"> 
                <div class="descr1">Fabian Ziegler – Matrikel-Nr. 2082578</div>
                <div class="descr2">Menü-Generator Version 1 - Lokal</div>
            </div>
        </div>
    </body>
</html>

<?php endif; ?>