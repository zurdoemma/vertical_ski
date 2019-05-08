<?php 
include "BootstrapMenu.php";

if ($_SESSION["permisos"] == 1)
{
	$str = '[{"text":"'.translate('Home',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/sesionusuario.php", "title": "'.translate('Home',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_User',$GLOBALS['lang']).'", "href": "#", "title": "'.translate('Lbl_User',$GLOBALS['lang']).'", "children": [{"text":"'.translate('Lbl_Edit_User',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/modificardatosusuario.php", "title": "'.translate('Lbl_Edit_User_2',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Admin_User',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/adminusers.php", "title": "'.translate('Lbl_Admin_User',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Exit',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/acciones/salir.php", "title": "'.translate('Lbl_Exit',$GLOBALS['lang']).'", "class": "border-top"}]}    ,{"text":"'.translate('Lbl_Admin_Tool',$GLOBALS['lang']).'", "href": "#", "title": "'.translate('Lbl_Admin_Tool',$GLOBALS['lang']).'", "children": [{"text":"'.translate('Lbl_Chains',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/admincadenas.php", "title": "'.translate('Lbl_Chains',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Tender',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/adminsucursales.php", "title": "'.translate('Lbl_Tender',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Profile_Credit',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/adminperfilcredito.php", "title": "'.translate('Lbl_Profile_Credit',$GLOBALS['lang']).'", "class": "border-top"},{"text":"'.translate('Lbl_Credit_Plan',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/adminplancredito.php", "title": "'.translate('Lbl_Credit_Plan',$GLOBALS['lang']).'"}]}]';
}
else
{
	$str = '[{"text":"'.translate('Home',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/sesionusuario.php", "title": "'.translate('Home',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_User',$GLOBALS['lang']).'", "href": "#", "title": "Usuario", "children": [{"text":"'.translate('Lbl_Edit_User',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/modificardatosusuario.php", "title": "'.translate('Lbl_Edit_User_2',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Exit',$GLOBALS['lang']).'", "href": "https://10.147.26.26/FinanCli/acciones/salir.php", "title": "'.translate('Lbl_Exit',$GLOBALS['lang']).'", "class": "border-top"}]}]';
}
$qMenu = new BootstrapMenu(array('data'=>$str));
//$qMenu->setActiveItem('http://codeignitertutoriales.com');
//$qMenu->insert(array("text"=>'Ooh!', "href"=>'http://codeignitertutoriales.com', "title"=>'Awesome'), 'Another action', 'About');
//$qMenu->insert(array("text"=>'Ultimo item', "href"=>'https://github.com/davicotico', "title"=>'My Github'));
//$qMenu->replace(array('text'=>'About Wow', 'href'=>'about', 'title'=>'Hey'), 'Home');
$menu = $qMenu->html(); 
$menu = substr_replace($menu, '<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Home',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_User',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Exit',$GLOBALS['lang']),0)+1), 0);

$menu = substr_replace($menu, '<i class="fas fa-user-edit"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Edit_User',$GLOBALS['lang']),0)+1), 0);



if ($_SESSION["permisos"] == 1)
{
	$menu = substr_replace($menu, '<i class="fa fa-users"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Admin_User',$GLOBALS['lang']),0)+1), 0);
	$menu = substr_replace($menu, '<i class="fas fa-tools"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Admin_Tool',$GLOBALS['lang']),0)+1), 0);
	$menu = substr_replace($menu, '<i class="fas fa-link"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Chains',$GLOBALS['lang']),0)+1), 0);
	$menu = substr_replace($menu, '<i class="far fa-building"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Tender',$GLOBALS['lang']),0)+1), 0);
	$menu = substr_replace($menu, '<i class="far fa-address-card"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Profile_Credit',$GLOBALS['lang']),0)+1), 0);
	$menu = substr_replace($menu, '<i class="fas fa-landmark"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Credit_Plan',$GLOBALS['lang']),0)+1), 0);
	
}

?>