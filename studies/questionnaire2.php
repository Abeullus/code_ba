<!-- Hier steht der Content für den 2. Fragebogen. Dieser wird per "iFrame" von Google Forms geladen, da dies die Auswertung des Ergebnisses am Ende der Studie erleichtert. 
Um den Fragebogen den richtigen Teilnehmer zuordnen zu können, wird dieser aufgefordert seine individuelle Session-ID einzutragen. 

Zur Sicherstellung, dass der Fragebogen ausgefüllt wird erscheint der Button um mit der Studie fortzufahren erst, nachdem der Teilnehmer auf den "Senden" Button in dem Formular gedrückt hat. 
Dies hat zur Folge, dass das iFrame erneut geladen wird. Dieser Ladevorgang wird abgefangen um anschließend einen Button einzublenden. 

-->

<?php
    session_start();
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
?>
        <div class="content">
            <div class="study-headline">
                <h1>Allgemeine Fragen Teil 2</h1>
            </div>
            <img class="logo" src="../images/ur-logo-bildmarke-grau.png">
            <a class="btn-continue" href="./study.php">Weiter</a>
            <div class="content-inner">
                <iframe src="https://docs.google.com/forms/d/e/1FAIpQLSfokT4F4PbyNGexxsuVWGYrM_I8tAdtVtFlCQERnNIhSP2kuQ/viewform?embedded=true" width="640" height="1092" frameborder="0" marginheight="0" marginwidth="0">Wird geladen…</iframe>
            </div>
            
            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">3</div>
            </div>
        </div>
    </body>
</html>
