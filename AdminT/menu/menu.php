<?php 
include "BootstrapMenu.php";

$str = '[{"text":"'.translate('Lbl_Home',$GLOBALS['lang']).'", "href": "https://10.147.26.26/AdminT/home.php", "title": "'.translate('Lbl_Home',$GLOBALS['lang']).'"},{"text":"'.translate('Lbl_Bines',$GLOBALS['lang']).'", "href": "#", "title": "'.translate('Lbl_Bines',$GLOBALS['lang']).'", "children": [{"text":"'.translate('Lbl_Admin_Bines',$GLOBALS['lang']).'", "href": "https://10.147.26.26/AdminT/administrarbines.php", "title": "'.translate('Lbl_Admin_Bines',$GLOBALS['lang']).'"}]},{"text":"'.translate('Lbl_Plans',$GLOBALS['lang']).'", "href": "#", "title": "'.translate('Lbl_Plans',$GLOBALS['lang']).'", "children": [{"text":"'.translate('Lbl_Admin_Plans',$GLOBALS['lang']).'", "href": "https://10.147.26.26/AdminT/administrarplanes.php", "title": "'.translate('Lbl_Admin_Plans',$GLOBALS['lang']).'"}]}]';

$qMenu = new BootstrapMenu(array('data'=>$str));

$menu = $qMenu->html(); 
$menu = substr_replace($menu, '<i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Home',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="fas fa-sort-numeric-up"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Bines',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="fas fa-list-ol"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Plans',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="fas fa-tools"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Admin_Bines',$GLOBALS['lang']),0)+1), 0);
$menu = substr_replace($menu, '<i class="fas fa-cogs"></i>&nbsp;&nbsp;', (strpos($menu,'>'.translate('Lbl_Admin_Plans',$GLOBALS['lang']),0)+1), 0);
?>