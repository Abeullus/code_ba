<!-- In dieser Index Datei steht der Code für den Generator, mit dem eine .csv-Datei hochgeladen werden kann um den Inhalt anschließend
in einer Navigationleiste auszugeben. -->

<?php
    session_start();

// Verbindungsaufbau mit mySQL Datenbank und Hochladen der .csv-Datei.
    if (!empty($_FILES)) :
        $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');
        $csv = file_get_contents($_FILES['content']['tmp_name']);
        $array = explode("\n", $csv);
        if (empty($array[count($array)-1])) {
            array_pop($array);
        }
        $array = array_map("str_getcsv", $array);
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
        mysqli_query($mysql, 'INSERT INTO `Generated_Studies` (`Content`, `Track_Usage`, `Calc_KLM`, `Show_Overview`, `Show_MyList`, `Depth`, `study_1_words`, `study_2_words`, `study_3_words`)'
                . 'VALUES (\'' . $json . '\', ' . ($_POST['tracking'] ?? 0) . ', ' . ($_POST['klm'] ?? 0) . ', ' . ($_POST['overview'] ?? 0) . ', ' . ($_POST['mylist'] ?? 0) . ', ' . $depth . ', \'' . json_encode($words1, JSON_UNESCAPED_UNICODE) . '\', \'' . json_encode($words2, JSON_UNESCAPED_UNICODE) . '\', \'' . json_encode($words3, JSON_UNESCAPED_UNICODE) . '\')');
        
//        echo $mysql->error;exit;
        $_SESSION['study'] = mysqli_query($mysql, 'SELECT MAX(`ID`) FROM `Generated_Studies`')->fetch_row()[0];
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
                    <div><label class="label"><input type="file" class="btn-upload" name="content"></label></div>
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

                    <div>
                        <label>
                            <input class="label" type="checkbox" name="overview" value="1">
                            Wörter suchen?
                        </label>

                         <div class="upload-btn-wrapper">
                             <button class="btn-upload">Upload a file</button>
                            <input type="file" name="myfile" words/>
                        </div> 
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