<?php
    require('../config.php');
?>
$(document).ready(function () {
	
	if(window.self === window.top && 'webkitSpeechRecognition' in window) {
		$('body').css('margin',0);
		$('body').empty();
		$('body').append('<iframe id="frame-voice" src="'+document.location.href+'" style="background:#fff; border: 0; width: 100%; height: 100%"></iframe>');
	    
	    $('#frame-voice').css({
	    	position:'absolute'
	    	,top:0
	    	,left:0
	    	,width:window.scrollWidth
	    	,height:window.scrollHeight
	    	
	    });
	    
        $('body').append('<a href="#" id="speech-button"><img src="<?php echo dol_buildpath('/voice/img/voice-icon.png',1); ?>" /></a>');
        
        var btn = $('#speech-button');
        
	    var recognition = new webkitSpeechRecognition();
	    recognition.lang='fr-FR';
	    recognition.continuous=true;
	    recognition.interimResults = false;
	    
	    btn.click(function(e) {
	        e.preventDefault();
	        
	        btn.find('img').attr("src","<?php echo dol_buildpath('/voice/img/voice-icon-run.png',1); ?>");
	        
	        recognition.start();	        
	    });
	    
	    recognition.onresult = function(event) {
	        
	        for(x in event.results) {
	            r = event.results[x];
	            
	            var phrase = r[0].transcript; 
	            
	            if(r.isFinal) {
	                recognition.stop();
	                btn.find('img').attr("src","<?php echo dol_buildpath('/voice/img/voice-icon.png',1); ?>");
	                transcriptionAnalyser(phrase);
	                
	                return 1;
	            }
	            
	        }
	        
/*	        btn.find('img').attr("src","<?php echo dol_buildpath('/voice/img/voice-icon-run.png',1); ?>");
	        recognition.start();*/
	    }
	    
	}
	
});

function transcriptionAnalyser(ph) {

	$frame = $('#frame-voice');

	console.log(ph);

    var matches = ph.match(/^recherche produit (.+)/i);
    if(matches && matches.length>1) {
        search = matches[1];    
            
        $frame.contents().find('#sallsearchleftp').val(search);
        $frame.contents().find('#sallsearchleftp').closest('form').submit();
        
        return 1;     
    }

    var matches = ph.match(/^nouvelle facture/i);
    if(matches) {
        $frame.attr('src',"<?php echo dol_buildpath('/compta/facture.php?action=create',1); ?>");
        
        return 1;     
    }
      
    var matches = ph.match(/^nouvelle propale/i);
    if(matches) {
        $frame.attr('src',"<?php echo dol_buildpath('/comm/propal.php?action=create',1); ?>");
        
        return 1;     
    }
      
    var matches = ph.match(/^nouvel événement/i);
    if(matches) {
        $frame.attr('src',"<?php echo dol_buildpath('/comm/action/card.php?action=create',1); ?>");
        
        return 1;     
    }
      
    var matches = ph.match(/^déconnect(.*) moi/i);
    
    if(matches) {
        $frame.attr('src',"<?php echo dol_buildpath('/user/logout.php',1); ?>");
        
        return 1;     
    }
    
    //alert("Commande non reconnue : "+ph);

}

