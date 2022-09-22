<?php

/* Hier steht der Code und Content für den 3. Studiendurchlauf. Hier soll der Teilnehmer wieder die angegebenen Wörter suchen und zu seiner eigenen Liste hinzufügen. */ 

    session_start();

    //Verbindung mit bereits vorhandener Datenbank
    $mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');
    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        while ($id = $ids->fetch_row()) {
            $id_array[] = $id[0];
        }
//        print_r($ids);exit;
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
    
    if (!$study || empty($_POST)) {
        header('Location: /');
    }
    
    $timings = json_decode($_POST['timings'], true);
    mysqli_query($mysql, 'INSERT INTO `Experiment` (`Experiment_ID`, `Durchgang_ID`, `Menu_ID`, `User_ID`, `UserSuccessRate`, `TimeOnTask`, `TaskErrorRate`, `ClicksTotal`, `KLM`, `KLM-Time`, `Clicks`, `SystemInfo`) VALUES (' . $_SESSION['exp'] . ', 2, ' . $_SESSION['study'] . ', ' . session_id() . ', ' . (float)$_POST['tsr'] . ', ' . (float)$_POST['realtime'] . ', ' . (int)$_POST['errors'] . ', ' . (int)$timings['bb'] . ', \'' . $_POST['timings'] . '\', ' . (float)$_POST['time'] . ', \'' . $_POST['clicks'] . '\', \'' . $_POST['platform'] . '\')');
    
    require('../header.php');
    
    $menu_obj = json_decode($study[2], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
    
    $functions = mysqli_query($mysql, 'SELECT * FROM `Functions` WHERE `Functions_ID`=' . $study[1])->fetch_assoc();
    
    $wordList = mysqli_query($mysql, 'SELECT `WordList_ID`, `WordsToSearch` FROM `WordList` WHERE `Functions_ID`=' . $study[1] . ' ORDER BY `WordList_ID` ASC LIMIT 2, 1')->fetch_row();
?>
        <script>
            const words = <?= $wordList[1] ?>;
            const durchlauf = 3;
        </script>
        <div class="content content-preview">
            <div class="study-headline">
                <h1>Versuchsdurchlauf 3</h1>
                <p>Finden Sie das Wort:<b> <br> <br>  <span class="word"><?= json_decode($wordList[1])[0] ?></span> </b> </p>
            </div>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner content-bg">
                <header>
                    <nav class="list">
                        <div class="inactive">
                            <span>Übersicht</span>
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
            <form action="study-4.php" method="POST" class="hiddenform">
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
                <div class="page">6</div>
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