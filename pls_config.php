<?php 
	define("SECURE", FALSE); 
	setlocale(LC_ALL, 'es_ES');
	$GLOBALS['lang'] = 'es';
	$GLOBALS['time_expire_sesion'] = 1800;
	date_default_timezone_set("America/Argentina/Buenos_Aires");
	
	function translate($phrase, $lang) {     
    $translation = null;     
    switch ($lang) {         
        case 'es':             
            if(strpos(getcwd(), 'acciones') !== false) require('../lang/es.php');
			else require('./lang/es.php');
            $translation = $dicc[$phrase];             
            break;                  
        case 'en':             
            $translation = $phrase;             
            break;        
    }
    
    return $translation;     
}
?>