<?php
require('./../smarty/libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->setTemplateDir('./../smarty/templates');
$smarty->setCompileDir('./../smarty/templates_c');
$smarty->setCacheDir('./../smarty/cache');
$smarty->setConfigDir('./../smarty/configs');

$name = "Adrian";

$smarty->assign('imie', $name);

$smarty->display('test.tpl');


?>