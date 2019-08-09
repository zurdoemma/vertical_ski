<?php 
	define("SECURE", FALSE); 
	setlocale(LC_ALL, 'es_ES');
	$GLOBALS['lang'] = 'es';
	$GLOBALS['time_expire_sesion'] = 18000;
	$GLOBALS['token_envio_sms'] = '22bf063e3cee0344b294b9cd53257bc0892467b0552141d2669e921e8833b4ea';
	$GLOBALS['url_envio_sms'] = 'https://apps.netelip.com/sms/api.php';	
	$GLOBALS['path_certificado_envio_sms'] = 'C:\wamp\cacert.pem';
	$GLOBALS['url_consulta_estado_financiero'] = 'https://www.pypdatos.com.ar/PypAPI/rest/serviciospyp/persona/';	
	$GLOBALS['usuario_servicio_consulta_estado_financiero'] = 'wslibert';
	$GLOBALS['clave_servicio_consulta_estado_financiero'] = 'edf444';	
	$GLOBALS['imprimir_nuevo_credito'] = 'https://localhost/FinanCli/acciones/imprimir/imprimirNuevoCredito.php';
	$GLOBALS['imprimir_pago_cuota_credito'] = 'https://localhost/FinanCli/acciones/imprimir/imprimirPagoCuotaCredito.php';
	$GLOBALS['imprimir_seleccion_pago_cuotas_credito'] = 'https://localhost/FinanCli/acciones/imprimir/imprimirPagoCuotasCredito.php';
	$GLOBALS['imprimir_pago_total_deuda_credito'] = 'https://localhost/FinanCli/acciones/imprimir/imprimirPagoTotalDeuda.php';
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