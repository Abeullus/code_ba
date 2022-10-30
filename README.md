# code_ba

Sowohl die Studie als auch der Menü-Generator sind noch online aufrufbar.

Studie:     https://studie-bachelor.de/?study=1
Generator:  https://studie-bachelor-ziegler.de/generator/index.php

Damit niemand ungewollterweise auf den Generator zugreifen kann, wurde dieser mit einem Verzeichnisschutz versehen. Dieser besteht auch bei lokaler Verwendung.
Anbei die Zugangsdaten.

*****************************
Benutzername:   FabZie
Passwort:       BA2022! 
****************************


 
Aufbau des Codes: 

\generator:     In diesem Ordner befinden sich die wichtigsten ELemente für den Menü-Generator. 
                Zudem befindet sich hier die .htaccess und .htpasswd Datei, wodurch der Verzeichnisschutz sichergestellt wird. 
                index.php für den Menü-Generator
                Um noch weitere Menü-Typen zu generieren wurde bereits der Ordner 'menu-types' angelegt, indem sich aktuell nur die Output-Datei für das Dropdown-Baum-Menü befindet. 

\images:        Hier befinden sich die verwendeten Bilder für die Studie und den Menü-Generator.

\studies:       Hier befinden sich die allgemeinen Daten für die Studie und für die einzelnen Versuchsdurchläufe und Fragebögen.

\code_ba:       Dies ist der Basis-Ordner, in dem sich alle anderen aufgelisteten Ordner befinden. Zudem sind hier die Dateien enthalten, 
                die sowohl in der Studie als auch beim Generator verwendet werden. 
                Um den Link für die Studie möglichst kurz zu halten, wurde daher die index.php Datei für die Studie dorthin verlegt.
                Des Weiteren befindet sich hier die robots.txt mit welcher die Studie vor Werbecrawlern geschützt werden sollte. 