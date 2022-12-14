<?php 

/* Hier steht der Quellcode und Content für den 1. Versuchsdurchlauf der Studie. Hierbei hat der Teilnehmer die Aufgabe, die vorgegebenen Wörter im Menü zu wählen, um sie so in den Reiter "meine Liste" hinzuzufügen. 
Für den 1. Durchlauf hat der Nutzer die Möglichkeit sowohl die alphabetisch sortierte Übersicht als auch kategorische Suche zu verwenden. 

Die zu suchenden Wörter sind bei jedem Durchlauf gleich, allerdings wechselt bei jedem Durchlauf die Reihenfolge. 

Bei jedem Durchlauf wird ein KLM generiert und die Werte werden in der Datenbank gespeichert. Zudem wird die reale Zeit, die Task-Error Rate, die Anzahl der Clicks und die Task-Success Rate ermittelt. */ 


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
    
    if (!$study) {
        header('Location: /');
    }
    
    require('../header.php');

    /* Hier werden die Informationen aus dem Menü-Genrator geladen. */
    $menu_obj = json_decode($study[2], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
    
    $functions = mysqli_query($mysql, 'SELECT * FROM `Functions` WHERE `Functions_ID`=' . $study[1])->fetch_assoc();
    
    $wordList = mysqli_query($mysql, 'SELECT `WordList_ID`, `WordsToSearch` FROM `WordList` WHERE `Functions_ID`=' . $study[1] . ' ORDER BY `WordList_ID` ASC LIMIT 1')->fetch_row();

?>

<!-- HTML Content --> 

        <script>
            const words = <?= $wordList[1] ?>;
            const durchlauf = 1;
        </script>
        <div class="content content-preview">
            <div class="study-headline">
                <h1>Versuchsdurchlauf 1</h1>
                <p>Finden Sie das Wort:<b> <br> <br> <span class="word"><?= json_decode($wordList[1])[0] ?></span> </b> </p>
            </div>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner content-bg">
                <header>
                    <nav class="list">
                        <div<?php if (!$functions['ShowOverview']) { echo ' class="inactive"'; } ?>>

                            <!-- Da im 1. Versuchsdurchlauf neben der kategorischen Suche, noch die alphabetische Übersicht aktiviert ist. muss diese hier implementiert werden. -->
                            <span>Übersicht</span>
                            <ul>
                                <?php if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['A','B','C'])) : ?>
                                <li>
                                    <span>ABC</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['A','B','C'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['D','E','F'])) :
                                ?>
                                <li>
                                    <span>DEF</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['D','E','F'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['G','H','I'])) :
                                ?>
                                <li>
                                    <span>GHI</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['G','H','I'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['J','K','L'])) :
                                ?>
                                <li>
                                    <span>JKL</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['J','K','L'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['M','N','O'])) :
                                ?>
                                <li>
                                    <span>MNO</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['M','N','O'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['P','Q','R'])) :
                                ?>
                                <li>
                                    <span>PQR</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['P','Q','R'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['S','T','U'])) :
                                ?>
                                <li>
                                    <span>STU</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['S','T','U'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php
                                    endif;
                                    if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['V','W','X','Y','Z'])) :
                                ?>
                                <li>
                                    <span>VWXYZ</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['V','W','X','Y','Z'])) {
                                            echo '<li class="list-color"><label>' . array_shift($deepest_elements) . '<input type="checkbox"></span></li>';
                                        }
                                    ?>
                                    </ul>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <?php foreach ($menu_obj as $name => $inner) : ?>
                        <div>
                            <span><?= $name ?></span>
                            <?= recurse_menu($inner) ?>
                        </div>
                        <?php endforeach; ?>
                        <div<?php if (!$functions['ShowMyList']) { echo ' class="inactive"'; } ?>>
                            <span>Meine Liste</span>
                            <ul></ul>
                        </div>
                    </nav>
                </header>
            </div>
            <form action="study-2.php" method="POST" class="hiddenform">
                <input type="hidden" name="time">
                <input type="hidden" name="timings">
                <input type="hidden" name="errors">
                <input type="hidden" name="realtime">
                <input type="hidden" name="tsr">
                <input type="hidden" name="clicks">
                <input type="hidden" name="platform">
            </form>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">4</div>
            </div>
        </div>
        <script src="platform.js"></script>
        <script src="settings.js"></script>
    </body>
</html>

<?php
    function recurse_menu($a) {
        if (empty($a)) {
            return;
        }
        $r = '<ul>';
        foreach ($a as $key => $val) {
					if (is_array($val)) {
            $r .= '<li><span>' . $key . '</span>' . recurse_menu($val) . '</li>';
					} else {
            $r .= '<li><label>' . $key . '<input type="checkbox"></label>' . recurse_menu($val) . '</li>';
					}
        }
        $r .= '</ul>';

        return $r;
    }
    
    function list_deepest_elements($array) {
        $elements = [];
        foreach ($array as $key => $value) {
            if ($value !== null) {
                array_push($elements, ...list_deepest_elements($value));
            } else {
                $elements[] = $key;
            }
        }
        
        return $elements;
    }
?>