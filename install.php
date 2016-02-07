<?php

    // Подключение классов
    require_once('system/modules/Control/Config.php');
    require_once('system/modules/Control/Core.php');
    
    \Control\Core::init();
    
    $conf = \Control\Core::conf();
    $em   = \Control\Core::em();
    
    $classLoader = new \Doctrine\Common\ClassLoader('Control', $conf->root_dir .'/system/modules');
    $classLoader->register();
    $classLoader = new \Doctrine\Common\ClassLoader('Modules', $conf->root_dir .'/system/modules');
    $classLoader->register();
    
    $modules_installed = array();
    try {
        $modules = $em->getRepository('Modules\Entities\Module')->findBy(array('install' => 1));
        
        foreach($modules as $module)
            $modules_installed[] = $module->name;
    } catch (Exception $e) { }
    
    $modules = new \Modules\Controller;
    $output = $modules->install($modules_installed);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Aquarel installing...</title>
    <meta name="description" content="Aquarel Content Management System" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <?php echo $output; ?>
    
    <br/><br/>
    
    <a href="/">Перейти на сайт →</a><br/>
    <a href="/control">Перейти в панель управления →</a><br/><br/>
    
    <?php if (empty($modules_installed)): ?>
    Логин: root<br/>
    Пароль: root
    <?php endif; ?>
</body>
</html>