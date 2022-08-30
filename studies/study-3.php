<!-- Hier steht der Code und Content für den 3. Studiendurchlauf. Hier soll der Teilnehmer wieder die angegebenen Wörter suchen und zu seiner eigenen Liste hinzufügen. --> 

<?php
    session_start();

    //Verbindung mit bereits vorhandener Datenbank
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
    
    if (!$study || empty($_POST)) {
        header('Location: /fabi');
    }
    
    $timings = json_decode($_POST['timings'], true);
    mysqli_query($mysql, 'UPDATE `User_Interaction` SET `User_Success_Rate_2`=' . (float)$_POST['tsr'] . ', `Time_On_Task_2`=' . (float)$_POST['realtime'] . ', `Task_Error_Rate_2`=' . (int)$_POST['errors'] . ', `Clicks_Total_2`=' . (int)$timings['bb'] . ', `KLM_2`=\'' . $_POST['timings'] . '\', `KLM_Time_2`=' . (float)$_POST['time'] . ' WHERE `User_ID`=' . $id);
    
    require('../header.php');
    
    $menu_obj = json_decode($study[1], true);
    $deepest_elements = list_deepest_elements($menu_obj);
    sort($deepest_elements);
?>
        <script>
            const words = <?= $study[9] ?>;
            const durchlauf = 3;
        </script>
        <div class="content content-preview">
            <div class="study-headline">
                <h1>Durchlauf 3 </h1>
                <p>Finden Sie das Wort: <br> <br>  <span class="word"><?= json_decode($study[9])[0] ?></span></p>
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
                        <div<?php if (!$study[5]) { echo ' class="inactive"'; } ?>>
                            <span>Meine Liste</span>
                            <ul></ul>
                        </div>
                    </nav>
                </header>
            </div>
            <form action="nasa-tlx.php" method="POST" class="hiddenform">
                <input type="hidden" name="time">
                <input type="hidden" name="timings">
                <input type="hidden" name="errors">
                <input type="hidden" name="realtime">
                <input type="hidden" name="tsr">
            </form>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">6</div>
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