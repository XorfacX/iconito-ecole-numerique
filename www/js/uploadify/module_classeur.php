<?php
if (!empty($_FILES)) {
  
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

	mkdir($targetPath, 0755);
	move_uploaded_file($tempFile, $targetFile);
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
}
?>