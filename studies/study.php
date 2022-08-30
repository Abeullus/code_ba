<!-- Hier steht der Code und Content für den 1. Studiendurchlauf. In dieser hat der Teilnehmer die Aufgabe, die vorgegebenen Wörter im Menü zu wählen und in die eigene Liste hinzuzufügen. 
Für den 1. Durchlauf hat der Nutzer die Möglichkeit sowohl die alphabetisch sortierte Übersicht als auch kategorische Suche zu verwenden. 

Die zu suchenden Wörter sind bei jedem Durchlauf gleich, allerdings wird die Reihenfolge durch Zufall abgeändert. 

Bei jedem Durchlauf wird ein KLM-Modell generiert und in der Datenbank gespeichert. Zudem wird die reale Zeit, die Error Rate und die Task-Success Rate berechnet. --> 


<?php
    session_start();

    //Verbinden mit bereits erstellter Datenbank
    $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');
    $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1')->fetch_row()[0];
    if (session_id() != $id) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User_Interaction` (`User_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1')->fetch_row()[0];
        session_id($id);
        session_start();
    }
    
    if (!isset($_SESSION['study'])) {
        header('Location: /fabi');
    }
    
    $study = mysqli_query($mysql, 'SELECT * FROM `Generated_Studies` WHERE `ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study) {
        header('Location: /fabi');
    }
    
    require('../header.php');
    
    $menu_obj = json_decode($study[1], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
?>
        <script>
            const words = <?= $study[7] ?>;
            const durchlauf = 1;
        </script>
        <div class="content content-preview">
            <div class="study-headline">
                <h1>Durchlauf 1</h1>
                <p>Finden Sie das Wort: <br> <br> <span class="word"><?= json_decode($study[7])[0] ?></span></p>
            </div>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner content-bg">
                <header>
                    <nav class="list">
                        <div<?php if (!$study[4]) { echo ' class="inactive"'; } ?>>
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
                        <div<?php if (!$study[5]) { echo ' class="inactive"'; } ?>>
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
            </form>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">4</div>
            </div>
        </div>
        <script src="klm.js"></script>
    </body>
</html>

<?php
    function recurse_menu($a) {
        if (empty($a)) {
            return;
        }
        $r = '<ul>';
        foreach ($a as $key => $val) {
            $r .= '<li><label>' . $key . '<input type="checkbox"></label>' . recurse_menu($val) . '</li>';
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