<?php 
	$GLOBALS['lang'] = 'es';
	$GLOBALS['nameprint'] = 'PrinterBlueNew';
	$GLOBALS['tipoimpresora'] = 'China';
	
	date_default_timezone_set("America/Argentina/Buenos_Aires");
	
	function translate($phrase, $lang) {     
    $translation = null;     
    switch ($lang) {         
        case 'es':             
			require('./lang/es.php');
            $translation = $dicc[$phrase];             
            break;                  
        case 'en':             
            $translation = $phrase;             
            break;        
    }
    
    return $translation;     
}
?>