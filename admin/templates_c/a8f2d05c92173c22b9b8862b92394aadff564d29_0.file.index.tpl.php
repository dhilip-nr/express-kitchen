<?php
/* Smarty version 4.3.2, created on 2024-04-09 20:16:05
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\admin\templates\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_661585e597fd35_36953952',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a8f2d05c92173c22b9b8862b92394aadff564d29' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\admin\\templates\\index.tpl',
      1 => 1712686558,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_661585e597fd35_36953952 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta http-equiv="Cache-Control" content="<?php echo (defined('CACHE_CONTROL') ? constant('CACHE_CONTROL') : null);?>
"/>
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
	<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
 | Admin - <?php echo $_smarty_tpl->tpl_vars['site_name']->value;?>
</title>
	<!--[if lt IE 9]>
		<?php echo '<script'; ?>
 src="http://html5shiv.googlecode.com/svn/trunk/html5.js"><?php echo '</script'; ?>
>
	<![endif]-->
	<?php $_smarty_tpl->_assignInScope('fav_logo', (defined('APPLOGO') ? constant('APPLOGO') : null));?>
	<link rel="icon" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
images/company/<?php echo substr($_smarty_tpl->tpl_vars['fav_logo']->value,0,strrpos($_smarty_tpl->tpl_vars['fav_logo']->value,'.'));?>
.ico" type="image/x-icon"/>
	<link href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
styles/base.css?v<?php echo $_smarty_tpl->tpl_vars['app_version']->value;?>
" rel="stylesheet" type="text/css" />
	<?php echo '<script'; ?>
 type="text/javascript">var root_path="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
";<?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
scripts/jquery.min.js?v<?php echo $_smarty_tpl->tpl_vars['app_version']->value;?>
" type="text/javascript"><?php echo '</script'; ?>
>

	<?php echo $_smarty_tpl->tpl_vars['pageassets']->value;?>


   	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
scripts/fancybox/jquery.fancybox.pack.js?v<?php echo $_smarty_tpl->tpl_vars['app_version']->value;?>
" type="text/javascript"><?php echo '</script'; ?>
>
	<link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
scripts/fancybox/jquery.fancybox.css?v<?php echo $_smarty_tpl->tpl_vars['app_version']->value;?>
" media="screen" />

	<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['root']->value;?>
scripts/common.js?v<?php echo $_smarty_tpl->tpl_vars['app_version']->value;?>
" type="text/javascript"><?php echo '</script'; ?>
>
</head>
<body>
		<section id="container">
        <?php if ($_smarty_tpl->tpl_vars['userHasAnAccess']->value) {?>
            <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

        <?php } else { ?>
            <div align="center" style="padding:50px;">You don't have access to this order</div>
        <?php }?>
    </section>
		<section id="loader">
        <div id="circleG">
            <div id="circleG_1" class="circleG"></div>
            <div id="circleG_2" class="circleG"></div>
            <div id="circleG_3" class="circleG"></div>
        </div>
    </section>
</body>
</html>
<?php }
}
