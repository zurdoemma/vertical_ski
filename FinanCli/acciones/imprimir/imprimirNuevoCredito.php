<?php
include ('./config/pls_config_print.php');
require __DIR__ . '/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

$fecha=htmlspecialchars($_POST["fecha"], ENT_QUOTES, 'UTF-8');
$numeroCredito=htmlspecialchars($_POST["numeroCredito"], ENT_QUOTES, 'UTF-8');
$planCredito=htmlspecialchars($_POST["planCredito"], ENT_QUOTES, 'UTF-8');
$cliente=htmlspecialchars($_POST["cliente"], ENT_QUOTES, 'UTF-8');
$sucursal=htmlspecialchars($_POST["sucursal"], ENT_QUOTES, 'UTF-8');
$tipoCliente=htmlspecialchars($_POST["tipoCliente"], ENT_QUOTES, 'UTF-8');
$usuario=htmlspecialchars($_POST["usuario"], ENT_QUOTES, 'UTF-8');
$montoCredito=htmlspecialchars($_POST["montoCredito"], ENT_QUOTES, 'UTF-8');
$cuotas=htmlspecialchars($_POST["cuotas"], ENT_QUOTES, 'UTF-8');
$proximoPago=htmlspecialchars($_POST["proximoPago"], ENT_QUOTES, 'UTF-8');
$tipoDocumento=htmlspecialchars($_POST["tipoDocumento"], ENT_QUOTES, 'UTF-8');
$documento=htmlspecialchars($_POST["documento"], ENT_QUOTES, 'UTF-8');
if(!empty($esCopia)) $esCopia=htmlspecialchars($_POST["esCopia"], ENT_QUOTES, 'UTF-8');
$montoCompra=htmlspecialchars($_POST["montoCompra"], ENT_QUOTES, 'UTF-8');
$montoInteres=htmlspecialchars($_POST["montoInteres"], ENT_QUOTES, 'UTF-8');
$datosCuotas=htmlspecialchars($_POST["datosCuotas"], ENT_QUOTES, 'UTF-8');
$pagaPrimeraCuota=htmlspecialchars($_POST["pagaPrimeraCuota"], ENT_QUOTES, 'UTF-8');


if(empty($fecha) || empty($numeroCredito) || empty($planCredito) || empty($cliente) || empty($tipoCliente) || empty($usuario) || empty($montoCredito) || empty($cuotas) || empty($proximoPago) || empty($tipoDocumento) || empty($documento) || $pagaPrimeraCuota > 1 || $pagaPrimeraCuota < 0) 
{
	echo translate('Msg_Printing_Error_Incorrect_Parameters',$GLOBALS['lang']);
	//echo translate('Msg_Printing_Error_Incorrect_Parameters',$GLOBALS['lang']).$fecha.' - '.$numeroCredito.' - '.$planCredito.' - '.$cliente.' - '.$tipoCliente.' - '.$usuario.' - '.$montoCredito.' - '.$cuotas.' - '.$proximoPago.' - '.$tipoDocumento.' - '.$documento.' - '.$pagaPrimeraCuota;
	return;
}

try 
{
	if($GLOBALS['tipoimpresora'] == translate('Lbl_Type_China_Print_Credit',$GLOBALS['lang']))
	{
		$justification = array(
			Printer::JUSTIFY_LEFT,
			Printer::JUSTIFY_CENTER,
			Printer::JUSTIFY_RIGHT);
		
		$connector = new WindowsPrintConnector($GLOBALS['nameprint']);
		$printer = new Printer($connector);

		$printer -> initialize();

		$printer -> setJustification($justification[1]);	
		$printer -> setTextSize(2, 3);
		$printer -> text(translate('Lbl_Title_Text_Print_Credit',$GLOBALS['lang']));	
		$printer -> feed();
		$printer -> setTextSize(1, 1);
		//$printer -> setJustification($justification[2]);
		$printer -> text(translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).': '.str_replace("-","/",$fecha));
		if($esCopia == 1)
		{
			$printer -> feed();
			$printer -> text('-------------------------------');
			$printer -> feed();
			$printer -> setTextSize(1, 2);
			$printer -> text(translate('Lbl_Print_Type_Voucher_Generate_Credit',$GLOBALS['lang']));
			$printer -> feed();
			$printer -> setTextSize(1, 1);			
			$printer -> text(translate('Lbl_Print_Voucher_Copy',$GLOBALS['lang']));
			$printer -> feed();
			$fechaRIC = date("d-m-Y H:i:s");
			$printer -> text(str_replace("-","/",$fechaRIC));
			$printer -> feed();
			$printer -> text('-------------------------------');			
			$printer -> feed();			
		}
		else 
		{
			$printer -> feed();
			$printer -> text('-------------------------------');
			$printer -> feed();
			$printer -> setTextSize(1, 2);
			$printer -> text(translate('Lbl_Print_Type_Voucher_Generate_Credit',$GLOBALS['lang']));
			$printer -> feed();
			$printer -> text('-------------------------------');			
			$printer -> feed();
			$printer -> setTextSize(1, 1);
		}
		$printer -> setJustification($justification[0]);
		$printer -> text(translate('Lbl_Credit_Number',$GLOBALS['lang']).': '.$numeroCredito);
		$printer -> feed();	
		$printer -> text(translate('Lbl_Name_Print_Credit_Plan',$GLOBALS['lang']).': '.$planCredito);
		$printer -> feed();	
		$printer -> text(translate('Lbl_Type_Client_Print',$GLOBALS['lang']).': '.$tipoCliente);
		$printer -> feed();	
		$printer -> text(translate('Lbl_Name_Print_Client',$GLOBALS['lang']).': '.$cliente);
		$printer -> feed();	
		$printer -> text(translate('Lbl_Tender_Print',$GLOBALS['lang']).': '.$sucursal);
		$printer -> feed();	
		$printer -> text(translate('Lbl_User_Print',$GLOBALS['lang']).': '.$usuario);
		$printer -> feed();	
		$printer -> text(translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).': '.$cuotas);
		$printer -> feed();	
		if(!empty($proximoPago))
		{
			$proximoPago = str_replace("-","",$proximoPago);
			$printer -> text(translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang']).': '.substr($proximoPago,6,2).'/'.substr($proximoPago,4,2).'/'.substr($proximoPago,0,4));
		}
		else $printer -> text(translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang'])).': ---';
		$printer -> feed();
		$printer -> text('-------------------------------');
		$printer -> feed();		
		$printer -> setJustification($justification[1]);
		$printer -> setTextSize(1, 2);
		$printer -> text(translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']));
		$printer -> feed();
		$printer -> setTextSize(1, 1);		
		$printer -> text(translate('Lbl_Fee_Print_Credit',$GLOBALS['lang']).'    '.translate('Lbl_Date_Expire_Print_Credit',$GLOBALS['lang']).'      '.translate('Lbl_Amount_Fee_Print_Credit',$GLOBALS['lang']));
		$cuotasArr = explode(":",$datosCuotas);
		for ($i = 0; $i < count($cuotasArr); $i++) 
		{
			$printer -> feed();
			$datosCuotX = explode("!",$cuotasArr[$i]);
			$printer -> text('   '.$datosCuotX[0].'    '.substr($datosCuotX[1],6,2).'/'.substr($datosCuotX[1],4,2).'/'.substr($datosCuotX[1],0,4).'    $'.number_format(($datosCuotX[2]/100.00), 2, ',', '.'));
		}
		$printer -> feed();
		if($pagaPrimeraCuota == 1)
		{			
			$printer -> text(translate('Msg_First_Fee_Paid',$GLOBALS['lang']).'.');
			$printer -> feed();		
		}		
		$printer -> setJustification($justification[0]);
		$printer -> setTextSize(1, 1);
		$printer -> text('-------------------------------');
		$printer -> feed();
		//$printer -> text(translate('Lbl_Amount_Purchase_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoCompra/100.00), 2, ',', '.'));
		//$printer -> feed();
		//$printer -> text(translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoInteres/100.00), 2, ',', '.'));			
		//$printer -> feed();			
		$printer -> setJustification($justification[1]);
		$printer -> text('********************************');
		$printer -> feed();	
		$printer -> setTextSize(1, 2);
		$printer -> text(translate('Lbl_Amount_Purchase_Print_Credit',$GLOBALS['lang']).': $'.number_format($montoCompra, 2, ',', '.'));
		//$printer -> text(translate('Lbl_Amount_Print_Credit',$GLOBALS['lang']).': $'.number_format($montoCredito, 2, ',', '.'));
		$printer -> feed();
		$printer -> setTextSize(1, 1);	
		$printer -> text('********************************');	
		$printer -> feed(1);
		$printer -> setJustification($justification[0]);	
		$printer -> text(translate('Msg_Accordance_Print_Credit_57',$GLOBALS['lang']).'.');	
		$printer -> feed(2);
		$printer -> setJustification($justification[1]);
		$printer -> text('-----------------------------');
		$printer -> feed();	
		$printer -> text(translate('Lbl_Sign_Print_Credit',$GLOBALS['lang']));
		$printer -> feed(2);
		$printer -> text('-----------------------------');
		$printer -> feed();	
		$printer -> text(translate('Lbl_Clarification_Print_Credit',$GLOBALS['lang']));	
		$printer -> feed();
		$printer -> text($tipoDocumento.': '.$documento);	
		$printer -> feed(3);	

		if(translate('Lbl_Print_Duplicate_New_Credit',$GLOBALS['lang']) == 'SI')
		{
			$printer -> setJustification($justification[1]);
			$printer -> text('-------------------------------');
			$printer -> feed();			
			$printer -> text('-------------------------------');			
			$printer -> setTextSize(2, 3);
			$printer -> text(translate('Lbl_Title_Text_Print_Credit',$GLOBALS['lang']));	
			$printer -> feed();
			$printer -> setTextSize(1, 1);
			//$printer -> setJustification($justification[2]);
			$printer -> text(translate('Lbl_Date_Print_Credit',$GLOBALS['lang']).': '.str_replace("-","/",$fecha));
			if($esCopia == 1)
			{
				$printer -> feed();
				$printer -> text('-------------------------------');
				$printer -> feed();
				$printer -> setTextSize(1, 2);
				$printer -> text(translate('Lbl_Print_Type_Voucher_Generate_Credit',$GLOBALS['lang']));
				$printer -> feed();
				$printer -> setTextSize(1, 1);
				$printer -> text(translate('Lbl_Print_Voucher_Copy',$GLOBALS['lang']));
				$printer -> feed();
				$fechaRIC = date("d-m-Y H:i:s");
				$printer -> text(str_replace("-","/",$fechaRIC));
				$printer -> feed();				
				$printer -> text('-------------------------------');			
				$printer -> feed();			
			}
			else 
			{
				$printer -> feed();
				$printer -> text('-------------------------------');
				$printer -> feed();
				$printer -> setTextSize(1, 2);
				$printer -> text(translate('Lbl_Print_Type_Voucher_Generate_Credit',$GLOBALS['lang']));
				$printer -> feed();
				$printer -> text('-------------------------------');			
				$printer -> feed();
				$printer -> setTextSize(1, 1);
			}
			$printer -> setJustification($justification[0]);
			$printer -> text(translate('Lbl_Credit_Number',$GLOBALS['lang']).': '.$numeroCredito);
			$printer -> feed();	
			$printer -> text(translate('Lbl_Name_Print_Credit_Plan',$GLOBALS['lang']).': '.$planCredito);
			$printer -> feed();	
			$printer -> text(translate('Lbl_Type_Client_Print',$GLOBALS['lang']).': '.$tipoCliente);
			$printer -> feed();	
			$printer -> text(translate('Lbl_Name_Print_Client',$GLOBALS['lang']).': '.$cliente);
			$printer -> feed();
			$printer -> text($tipoDocumento.': '.$documento);		
			$printer -> feed();	
			$printer -> text(translate('Lbl_Tender_Print',$GLOBALS['lang']).': '.$sucursal);
			$printer -> feed();	
			$printer -> text(translate('Lbl_User_Print',$GLOBALS['lang']).': '.$usuario);
			$printer -> feed();	
			$printer -> text(translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']).': '.$cuotas);
			$printer -> feed();	
			if(!empty($proximoPago))
			{
				$proximoPago = str_replace("-","",$proximoPago);
				$printer -> text(translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang']).': '.substr($proximoPago,6,2).'/'.substr($proximoPago,4,2).'/'.substr($proximoPago,0,4));
			}
			else $printer -> text(translate('Lbl_Next_Paid_Print_Credit',$GLOBALS['lang'])).': ---';
			$printer -> feed();
			$printer -> text('-------------------------------');
			$printer -> feed();		
			$printer -> setJustification($justification[1]);
			$printer -> setTextSize(1, 2);
			$printer -> text(translate('Lbl_Fees_Print_Credit',$GLOBALS['lang']));
			$printer -> feed();
			$printer -> setTextSize(1, 1);
			$printer -> text(translate('Lbl_Fee_Print_Credit',$GLOBALS['lang']).'    '.translate('Lbl_Date_Expire_Print_Credit',$GLOBALS['lang']).'      '.translate('Lbl_Amount_Fee_Print_Credit',$GLOBALS['lang']));
			$cuotasArr = explode(":",$datosCuotas);
			for ($i = 0; $i < count($cuotasArr); $i++) 
			{
				$printer -> feed();
				$datosCuotX = explode("!",$cuotasArr[$i]);
				$printer -> text('   '.$datosCuotX[0].'    '.substr($datosCuotX[1],6,2).'/'.substr($datosCuotX[1],4,2).'/'.substr($datosCuotX[1],0,4).'    $'.number_format(($datosCuotX[2]/100.00), 2, ',', '.'));
			}
			$printer -> feed();
			$printer -> setJustification($justification[0]);
			$printer -> setTextSize(1, 1);		
			$printer -> text('-------------------------------');
			$printer -> feed();
			//$printer -> text(translate('Lbl_Amount_Purchase_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoCompra/100.00), 2, ',', '.'));
			//$printer -> feed();
			//$printer -> text(translate('Lbl_Amount_Interest_Print_Credit',$GLOBALS['lang']).': $'.number_format(($montoInteres/100.00), 2, ',', '.'));			
			//$printer -> feed();			
			$printer -> setJustification($justification[1]);
			$printer -> text('********************************');
			$printer -> feed();	
			$printer -> setTextSize(1, 2);
			$printer -> text(translate('Lbl_Amount_Purchase_Print_Credit',$GLOBALS['lang']).': $'.number_format($montoCompra, 2, ',', '.'));
			//$printer -> text(translate('Lbl_Amount_Print_Credit',$GLOBALS['lang']).': $'.number_format($montoCredito, 2, ',', '.'));
			$printer -> feed();	
			$printer -> setTextSize(1, 1);
			$printer -> text('********************************');	
			$printer -> feed(1);
			$printer -> setJustification($justification[0]);	
			$printer -> text(translate('Msg_Accordance_Print_Credit_57',$GLOBALS['lang']).'.');		
			$printer -> feed(3);		
		}		
		$printer -> pulse();
		$printer -> close();
		
		echo translate('Msg_The_New_Credit_Was_Printed_Correctly',$GLOBALS['lang']);
		return;	
	}
	else
	{
		
	}
} catch (Exception $e) {
    echo translate('Msg_Print_Error_Driver',$GLOBALS['lang']).' :'.$e->getMessage();
}
