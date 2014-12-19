<?php

session_start();

$dossierTmp = getTmpFolder();

if (!empty($_FILES)) {

    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_REQUEST['folder'] . '/';
    $targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

    file_put_contents("/tmp/iconito-uploadify.log",$dossierTmp.' '.$targetPath."\n",FILE_APPEND);

    if(!preg_match('/^'.str_replace("/", '\/', $dossierTmp).'/', $targetFile)) {
//        file_put_contents("/tmp/iconito-uploadify.log","dossier TEMP not in TARGETFILE\n",FILE_APPEND);
        die("hack attempt 1\n");
    }

    if(preg_match('/\.\./', $targetFile)) {
//        file_put_contents("/tmp/iconito-uploadify.log",".. \n",FILE_APPEND);
        die("hack attempt 2\n");
    }

    if(preg_match('/\.php$/', $targetFile)) {
//        file_put_contents("/tmp/iconito-uploadify.log","php FILE \n",FILE_APPEND);
        die("hack attempt 3\n");
    }

    if(preg_match('/\.php.?$/', $targetFile)) {
//        file_put_contents("/tmp/iconito-uploadify.log","php FILE \n",FILE_APPEND);
        die("hack attempt 4\n");
    }

    if(preg_match('/\.php.?\..*$/', $targetFile)) {
//        file_put_contents("/tmp/iconito-uploadify.log","php FILE \n",FILE_APPEND);
        die("hack attempt 5\n");
    }

    if (!file_exists($targetPath)) {
        mkdir($targetPath, 0755);
    }

    move_uploaded_file($tempFile, $targetFile);
    echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
}

function getTmpFolder()
{
    if (isset($_ENV['DYLD_LIBRARY_PATH']) && $_ENV['DYLD_LIBRARY_PATH']== '/Applications/MAMP/Library/lib:') // Patch MAMP
        $dossierTmp = '/tmp';
    elseif (ini_get('upload_tmp_dir')) {
        $dossierTmp = ini_get('upload_tmp_dir');
    } else {
        $dossierTmp = sys_get_temp_dir();
    }
    if (substr($dossierTmp, -1) != '/') {
        $dossierTmp = $dossierTmp.'/';
    }
    return $dossierTmp;
}