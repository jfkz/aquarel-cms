<?php
$error = '';
$msg   = '';

foreach ($_FILES as $filename => $file)
    $fileElementName = $filename;

if (!empty($_FILES[$fileElementName]['error']))
{
    switch($_FILES[$fileElementName]['error'])
    {
	case '1':
	    $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
	    break;
	case '2':
	    $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
	    break;
	case '3':
	    $error = 'The uploaded file was only partially uploaded';
	    break;
	case '4':
	    $error = 'No file was uploaded.';
	    break;
	case '6':
	    $error = 'Missing a temporary folder';
	    break;
	case '7':
	    $error = 'Failed to write file to disk';
	    break;
	case '8':
	    $error = 'File upload stopped by extension';
	    break;
	case '999':
	default:
	    $error = 'No error code avaiable';
    }
    
    echo $error;
}
elseif (empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
{
    $error = 'No file was uploaded..';
    echo $error;
}
else
{
    // Подключение классов
    require_once('system/modules/Control/Config.php');
    require_once('system/modules/Control/Core.php');
    require_once('system/modules/Control/Engine.php');
    require_once('system/modules/Control/Live.php');
    
    \Control\Core::init();
    
    $conf = \Control\Core::conf();
    $live = \Control\Live::getInstance();
    
    $em = \Control\Core::em();
    $con = $em->getConnection();
    
    $code = $live->user->id;
    
    $id   = $fileElementName;
    $name = $_FILES[$fileElementName]['name'];
    
    $ext = explode('.', $name);
    $ext = $ext[count($ext)-1];
    
    $path = '/temp/files/'. $code .'_'. $id .'.'. $ext;
    $size = $_FILES[$fileElementName]['size'];
    $time = time();
    
    if (rename($_FILES[$fileElementName]['tmp_name'], $root_dir .''. $path))
    {
	$file_id = $con->executeQuery("SELECT `id` FROM {$db_prefix}files_temp WHERE `user`='{$code}' AND `path`='{$path}'")->fields['id'];
	
	// пользователь перезагрузил файл - обновляем запись в базе
	if ($file_id > 0)
	{
	    $con->executeQuery("UPDATE {$db_prefix}files_temp SET `created`='{$time}', `name`='{$name}' WHERE `user`='{$code}' AND `path`='{$path}';");
	}
	// пользователь загрузил новый файл - добавляем запись в базу
	else
	{
	    $file_id = $con->executeQuery("SELECT `id` FROM {$db_prefix}files_temp ORDER BY `id` DESC LIMIT 1")->fields['id'] + 1;
	    $con->executeQuery("INSERT INTO {$db_prefix}files_temp (`id`,`user`,`name`,`path`,`size`,`created`) VALUES ('{$file_id}','{$code}','{$name}','{$path}','{$size}','{$time}') ");
	}
    }
    
    //for security reason, we force to remove all uploaded file
    @unlink($_FILES[$fileElementName]);
    
    echo $file_id .':'. $path .':'. $size;
}