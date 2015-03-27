<?php
    require('../config.php');
?>
$(document).ready(function () {
	

	if('webkitSpeechRecognition' in window) {
	    
        $('body').append('<a href="#" id="speech-button"><img src="<?php echo dol_buildpath('/voice/img/voice-icon.png',1); ?>" /></a>');
        
        var btn = $('#speech-button');
        
	    var recognition = new webkitSpeechRecognition();
	    recognition.lang='fr-FR';
	    recognition.continuous=false;
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
	        
	        
	    }
	    
	}
	
});

function transcriptionAnalyser(ph) {

    var pattern = /^recherche produit (\w+)/i;
    var matches = ph.match(pattern);
    
    if(matches && matches.length>1) {
        search = matches[1];    
            
        $('#sallsearchleftp').val(search);
        $('#sallsearchleftp').closest('form').submit();
        
    }
      
}
