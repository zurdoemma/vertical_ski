<?php
	set_time_limit(120);	  
	include ('../utiles/funciones.php');

	if(file_exists("./importar/importarBinesNew.txt"))
	{
		if(!unlink('./importar/importarBinesNew.txt')) 
		{
			echo translate('Msg_Verify_Process_Import',$GLOBALS['lang']);
			return;
		}
	}

	if(file_exists("./importar/importarBinesUso.txt"))
	{
		echo translate('Msg_Ongoing_Load_Import_Bins_Wait_Finish',$GLOBALS['lang']);
		return;
	}

	$uploadedfileload="true";

	if (!($_FILES['uploadedfile']['type'] == "text/plain"))
	{
		$msg=translate('Msg_File_Must_Be_Plain_Ttext_Other_Files_Not_Allowed',$GLOBALS['lang']);
		$uploadedfileload="false";
	}

	$file_name=$_FILES['uploadedfile']['name'];

	if(strcmp("importarBines.txt", $file_name) != 0)
	{
		$msg=translate('Msg_Upload_Selected_File_Have_To_Name',$GLOBALS['lang']);
		$uploadedfileload="false";
	}

	$add="./importar/importarBinesNew.txt";
	if($uploadedfileload=="true")
	{
		if(move_uploaded_file ($_FILES['uploadedfile']['tmp_name'], $add))
		{
			echo translate('Msg_File_Has_Been_Uploaded_Successfully_Press_Import_Button',$GLOBALS['lang']);
			return;
		}
		else
		{
			echo translate('Msg_Error_Uploading_File_Try_Again',$GLOBALS['lang']);
			return;
		}
	}
	else
	{
		echo $msg;
	}
?>