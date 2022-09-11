<!-- Hier steht der Content für den NASA-TLX Fragebogen, der am Ende der Studie ausgeführt werden soll. 
Dieser wird wie die allgemeinen Fragebögen zu Beginn der Studie mit einem "iFrame" in den HTML-Quellcode eingepflegt. 

Nach erfolgreichen Ausfüllen des Fragebogens erscheint wieder ein Button, um die Studie zu beenden. Dieser führt zur Feedback-Seite, auf der 
die Teilnehmer benötigte VP-Stunden erhalten können --> 

<?php
    session_start();

    //Verbindung mit vorhandener Datenbank
     //$mysql = mysqli_connect('rdbms.strato.de', 'dbu2938481', 'Bachelor2022!', 'dbs8555354');

     $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler'); // --> lokaler Server über XAMPP 
    $ids = mysqli_query($mysql, 'SELECT `Session_ID` FROM `User`');
    
    if ($ids && $ids->num_rows) {
        $ids = $ids->fetch_all();
        $ids = array_merge(...$ids);
//        print_r($ids);exit;
    }
    if (!$ids || !in_array(session_id(), $ids)) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User` (`Session_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT MAX(`Session_ID`) FROM `User`')->fetch_row()[0];
        session_id($id);
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
    mysqli_query($mysql, 'INSERT INTO `Experiment` (`Menu_ID`, `User_ID`, `UserSuccessRate`, `TimeOnTask`, `TaskErrorRate`, `ClicksTotal`, `KLM`, `KLM-Time`) VALUES (' . $_SESSION['study'] . ', ' . session_id() . ', ' . (float)$_POST['tsr'] . ', ' . (float)$_POST['realtime'] . ', ' . (int)$_POST['errors'] . ', ' . (int)$timings['bb'] . ', \'' . $_POST['timings'] . '\', ' . (float)$_POST['time'] . ')');
    
    require('../header.php');
?>

<!-- HTML Content --> 

        <div class="content">
            <div class="study-headline">
                <h1>NASA-TLX</h1>
            </div>
            <a class="btn-continue" href="../feedback.php">Studie beenden</a>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSexPMleVHR3aUOsaSordE5ClFl5Rv6kK0XAMIIS3QpoJWuNeQ/viewform?embedded=true" width="640" height="1092" frameborder="0" marginheight="0" marginwidth="0">Wird geladen�</iframe>
            </div>
            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">7</div>
            </div>
        </div>
    </body>
</html>