<!-- Hier steht der Content für den NASA-TLX Fragebogen, der am Ende der Studie ausgeführt werden soll. 
Dieser wird wie die allgemeinen Fragebögen zu Beginn der Studie mit einem "iFrame" in den HTML-Quellcode eingepflegt. 

Nach erfolgreichen Ausfüllen des Fragebogens erscheint wieder ein Button, um die Studie zu beenden. Dieser führt zur Feedback-Seite, auf der 
die Teilnehmer benötigte VP-Stunden erhalten können --> 

<?php
    session_start();

    //Verbindung mit vorhandener Datenbank
    $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');
    $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1')->fetch_row()[0];
    if (session_id() != $id) {
        session_destroy();
        mysqli_query($mysql, 'INSERT INTO `User_Interaction` (`User_ID`) VALUES (NULL)');
        $id = mysqli_query($mysql, 'SELECT `User_ID` FROM `User_Interaction` ORDER BY `User_ID` DESC LIMIT 1')->fetch_row()[0];
        session_id($id);
        session_start();
    }
    
    //Verbindung mit Studie 
    if (!isset($_SESSION['study'])) {
        header('Location: /fabi');
    }
    
    $study = mysqli_query($mysql, 'SELECT * FROM `Generated_Studies` WHERE `ID`=' . $_SESSION['study'])->fetch_row();
    
    if (!$study || empty($_POST)) {
        header('Location: /fabi');
    }
    
    $timings = json_decode($_POST['timings'], true);
    mysqli_query($mysql, 'UPDATE `User_Interaction` SET `User_Success_Rate_3`=' . (float)$_POST['tsr'] . ', `Time_On_Task_3`=' . (float)$_POST['realtime'] . ', `Task_Error_Rate_3`=' . (int)$_POST['errors'] . ', `Clicks_Total_3`=' . (int)$timings['bb'] . ', `KLM_3`=\'' . $_POST['timings'] . '\', `KLM_Time_3`=' . (float)$_POST['time'] . ' WHERE `User_ID`=' . $id);
    
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