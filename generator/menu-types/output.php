<?php
    /* List.php ist zur generierung der Navigationsliste zuständig. Hier werden die im Generator wählbaren Checkboxen ausgewerten um so den richtigen
Inhalt zu erstellen. Der Inhalt dieser Datei wird aus der im Generator erstellten Datenbank ausgelesen. */

    session_start();
    
    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    
    if (!isset($_SESSION['study'])) {
        echo 'keine study ohoh';
//        header('Location: /');
    }
    
    $study = mysqli_query($mysql, 'SELECT * FROM `Menu-Generator` WHERE `Menu_ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study) {
        echo 'kein post oho oh';
//        header('Location: /');
    }
    
    require('../../header.php');
    $menu_obj = json_decode($study[2], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
    
    $functions = mysqli_query($mysql, 'SELECT * FROM `Functions` WHERE `Functions_ID`=' . $study[1])->fetch_assoc();
?>


<!-- HTML Content zur generierung der Navigationsliste --> 
        <div class="content content-preview">
            <a href="../index.php"> <img class="logo" src="../../images/ur-logo-bildmarke-grau.png" ></a>
            <a class="btn-continue" href="#">Weiter</a>
            <div class="content-inner">
                <header>
                    <nav class="list">
                        <div<?php if (!$functions['ShowOverview']) { echo ' class="inactive"'; } ?>>
                            <span>Übersicht</span>
                            <ul>
                                <?php if (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['A','B','C'])) : ?>
                                <li>
                                    <span>ABC</span>
                                    <ul>
                                    <?php
                                        while (!empty($deepest_elements) && in_array(strtoupper(substr($deepest_elements[0], 0, 1)), ['A','B','C'])) {
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></label></li>';
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
                                            echo '<li><label>' . array_shift($deepest_elements) . '<input type="checkbox"></span></li>';
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
        </div>

         <div class="footer"> 
                <div class="descr1">Fabian Ziegler – Matrikel-Nr. 2082578</div>
                <div class="descr2">Menü-Generator Version 1 - Online</div>
        </div>
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
    
    // Funktion um die einzelnen Menüelemente aus dem Array zu extrahieren.
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