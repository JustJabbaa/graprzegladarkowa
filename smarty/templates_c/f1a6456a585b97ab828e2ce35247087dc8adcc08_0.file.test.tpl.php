<?php
/* Smarty version 3.1.39, created on 2021-04-29 20:59:59
  from 'C:\xampp\htdocs\graprzegladarkowa\smarty\templates\test.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.39',
  'unifunc' => 'content_608b022f6212f3_88704758',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f1a6456a585b97ab828e2ce35247087dc8adcc08' => 
    array (
      0 => 'C:\\xampp\\htdocs\\graprzegladarkowa\\smarty\\templates\\test.tpl',
      1 => 1619722794,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_608b022f6212f3_88704758 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    Cześć <?php echo $_smarty_tpl->tpl_vars['imie']->value;?>
!
</body>
</html><?php }
}
