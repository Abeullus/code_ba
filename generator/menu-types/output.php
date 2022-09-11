<!-- List.php ist zur generierung der Navigationsliste zuständig. Hier werden die im Generator wählbaren Checkboxen ausgewerten um so den richtigen
Inhalt zu erstellen. Der Inhalt dieser Datei wird aus der im Generator erstellten Datenbank ausgelesen. -->

<?php
    session_start();
    
    if (!isset($_SESSION['study'])) {
        header('Location: /fabi');
    }
    
    //Verbindung mit der generierten Datenbank
    $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');
    $study = mysqli_query($mysql, 'SELECT * FROM `Generated_Studies` WHERE `ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study) {
        header('Location: /fabi');
    }
    
    require('../../header.php');
    $menu_obj = json_decode($study[1], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
?>


<!-- HTML Content zur generierung der Navigationsliste --> 
        <div class="content content-preview">
            <img class="logo" src="../../images/ur-logo-bildmarke-grau.png">
            <a class="btn-continue" href="#">Weiter</a>
            <div class="content-inner">
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
                        <div<?php if (!$study[5]) { echo ' class="inactive"'; } ?>>
                            <span>Meine Liste</span>
                            <ul></ul>
                        </div>
                    </nav>
                </header>
            </div>
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