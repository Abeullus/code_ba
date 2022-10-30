// Design und Animaion der List-Menu

// Die Dauer der einzelnen Animation wird auf 200ms festgelegt. Dieser Wert wird beim KLM automatisch als Wert W(t) bei jedem auslösen einer Animation addiert. 
var animationDuration = 200;

$(function() {
    $('input[name="searchWords"]').change(function() {
        $(this).parent().next('label').toggle().children('input').val('');
    });
    
    $('input[type="file"]').change(function() {
        if (this.files.length) {
            $(this).siblings('.description').text(this.files[0].name + ' erfolgreich hochgeladen');
        } else {
            $(this).siblings('.description').text('');
        }
    });
    
    $('nav label').each(function() {
        if ($(this).siblings('ul').length) {
            $(this).children('input').remove();
        }
    });

/*  */
    
    $('nav.list span, nav.list label').click(function() {
        let $this = $(this);
        if ($this.next('ul').length && !$this.parents('.inactive').length) {
            if ($this.next('ul').hasClass('open')) {
                $this.next('ul').removeClass('open').stop().fadeOut(animationDuration);
            } else {
                $('nav.list ul.open').removeClass('open').stop().fadeOut(animationDuration);
                $this.parents('ul').addClass('open').stop().fadeIn(animationDuration);
                $this.next('ul').addClass('open').stop().fadeIn(animationDuration);
            }
        }
    });
    
    $('nav').on('change', 'input', function(e) {
        let $this = $(e.target);
        $('nav label').each(function() {
            if ($(this).text() == $this.parent().text()) {
                $(this).children('input')[0].checked = $this[0].checked;
            }
        });
        
        if (this.checked) {
            $('nav > div:last-child ul').append('<li><span>' + $this.parent().text() + '</span></li>');
        } else {
            $('nav > div:last-child ul span').each(function() {
                if ($(this).text() == $this.parent().text()) {
                    $(this).remove();
                }
            });
        }
    });
    

/*  Durch nachfolgene Funktion wird überprüft, ob das eingebundene Google Formular ausgefüllt wurde. Hierbei wird das erneute Laden des 
iFrames beim Absenden des Fragebogens abgefangen. Erst sobald dies erkannt wurde, erscheint ein Button zum fortfahren.  */

    let iframeLoad = 0;
    $('.content-inner > iframe').on('load', function() {
        iframeLoad++;
        if (iframeLoad == 2) {
            $('.btn-continue').show();
        }
    });
});