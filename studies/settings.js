/* Dieses Script dient zur Berechnung des Keystroke Level Models. Die jeweiligen zeitlichen Angaben für die einzelnen zu messenden Elemente 
wurden aus dem Paper "Using the Keystroke-Level Model to Estimate Execution Times"* übernommen.

Zudem wird hier noch die reale Zeit des jeweiligen Studiendurchlaufs, sowie die Error-Rate und die Task-Success Rate berechnet.

* Quelle: Kieras, D. (2001). Using the keystroke-level model to estimate execution times. University of Michigan, 555 */ 

var time = 0;
var pos = [0,0];
var timeOut, thinking;
var timings = {
    // Bewegen des Mauszeiges auf eine Stelle des Displays --> 1.1 sec
    p: 0,

    // Klick auf Mouse-Button --> 0.2 sec
    bb: 0,

    // Zeit, die zum Überlegen benötigt wird --> 1.2 sec
    m: 0,

    // Zeit, die auf das System gewartet werden muss (Dauer der Animation) --> hier 0.2 sec
    w: 0,

    // Tastendruck --> 0.28 sec
    k: 0
};
var errors = 0;
var realtime = 0;
var starttime = Date.now();
var clicks = [];

time += 1.2; //M wird zu Beginn immer automatisch addiert.
timings.m += 1;

var currentWord = 0;
var correctMenu = false;

//Zeit bei Tastendruck --> K
$(document).keypress(function() {
    time += .28;
    timings.k += 1;
});

//Zeit bei Bewegen der Maus --> P. 
// Hier werden alle Bewegungen zu der Zeit addiert, die größer sind als 20px, um versehentliche Bewegungen rauszufiltern. 
// Sobald die Maus länger als 1.2sec nicht mehr bewegt wird, wird automatisch der Wert von M (1.2 sec) addiert. 
$(document).mousemove(function(e) {
    if (e.clientX <= pos[0] - 20 || e.clientX >= pos[0] + 20 || e.clientY <= pos[1] - 20 || e.clientY >= pos[1] + 20) {
        clearTimeout(timeOut);
        clearTimeout(thinking);
        timeOut = setTimeout(function() {
            time += 1.1;
            timings.p += 1;
            pos = [e.clientX, e.clientY];
        }, 100);
        thinking = setTimeout(function() {
            time += 1.2;
            timings.m += 1;
        }, 1200);
    }
});

//Zeit bei Klick auf Mouse-Button --> BB 
$(document).click(function(e) {
    if ($(e.target).is('label, span')) {
        clicks.push($(e.target).text());
    } else if ($(e.target).is('nav')) {
        clicks.push('Nav-Leiste');
    } else if ($(e.target).hasClass('content-bg')) {
        clicks.push('Hintergrund');
    }
    time += .2;
    timings.bb += 1;
});

$('nav').on('change', 'input', function() {
    time += 1.2 + .2;
    timings.m += 1;
    timings.w += 1;
});


$('nav span, nav label').click(function() {
    if ($(this).next('ul').length) {
        time += .2;
        timings.w += 1;
        correctMenu = false;
        $(this).next('ul').find('span, label').each(function() {
            if ($(this).text() == words[currentWord]) {
                correctMenu = true;
            }
        });
        if (!correctMenu) {
            errors++;
        }
    } else if ($(this).text() == words[currentWord]) {
        if ($(this).children('input')[0].checked) {
            currentWord++;
            $('nav ul').removeClass('open').stop().fadeOut(200); //Zeit der Animation

            //Sobald alle Wörter gefunden wurden, startet automatisch der Timer und es geht weiter in den nächsten Durchlauf
            if (currentWord >= words.length) {
                realtime = Date.now() - starttime;
                if (durchlauf < 5) {
                    $('h1').html('Sie haben alle W&ouml;rter gefunden!');
                    $('h1 + p').html('In wenigen Sekunden geht es weiter mit Durchlauf ' + (durchlauf + 1));
                    $('.content-inner').replaceWith('<div class="countdown">30</div>');

                    // Timer für Zwischenseite 
                    let timer = 30;
                    let iv = setInterval(function() {
                        if (timer > 0) {
                            timer--;
                            $('.countdown').text(timer);
                        }

                        else if (timer == 0) {
                            $('input[name="realtime"]').val(realtime / 1000);
                            $('input[name="time"]').val(time);
                            $('input[name="timings"]').val(JSON.stringify(timings));
                            $('input[name="errors"]').val(errors);
                            $('input[name="tsr"]').val(words.length / (words.length + errors));
                            $('input[name="clicks"]').val(JSON.stringify(clicks));
                            $('input[name="platform"]').val(platform.description + ' (' + screen.width + 'x' + screen.height + ')');
                            $('form').submit();
                        }
                    }, 1000);
                } else {
                    $('input[name="realtime"]').val(realtime / 1000);
                    $('input[name="time"]').val(time);
                    $('input[name="timings"]').val(JSON.stringify(timings));
                    $('input[name="errors"]').val(errors);
                    $('input[name="clicks"]').val(JSON.stringify(clicks));
                    $('input[name="platform"]').val(platform.description + ' (' + screen.width + 'x' + screen.height + ')');

                    //Berechnung der Task-Success-Rate nach:
                    // Osinusi, K. (o. D.). Make It Count – A Guide to Measuring the User Experience. Toptal.com. Abgerufen am 29. August 2022, von https://www.toptal.com/designers/ux/measuring-the-user-experience
                    $('input[name="tsr"]').val(words.length / (words.length + errors));
                    $('form').submit();
                }
            } else {
                $('.word').text(words[currentWord]);
            }
        }
    } else {
        errors++;
    }
});