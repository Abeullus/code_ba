<?php 

session_start();
    $mysql = mysqli_connect('localhost', 'FabZie', 'BA2022!', 'BA_Ziegler');

require('header.php');

?>

<!--
Hier kann optinal noch Inhalt für Versuchspersonenstunden eingefügt werden
-->
<div class="content">
            <img class="logo" src="./images/ur-logo-bildmarke-grau.png">
            <div class="content-inner">
                <h1 class="h1">Vielen Dank für Ihre Teilnahme!</h1>
                <p class="text" style="height: 4em">Bacon ipsum dolor amet bacon shankle picanha ball tip. 
                Tri-tip shoulder jowl filet mignon venison flank. Prosciutto pork turducken, 
                kielbasa ground round strip steak short</p>
            </div>

            <div class="footer"> 
                <div class="id">Session ID: #<?= session_id() ?></div>
                <div class="page">8</div>
            </div>
        </div>
    </body>
</html>