<?php
/* Smarty version 4.3.2, created on 2023-10-27 16:26:20
  from 'D:\Program Files\xampp\htdocs\designer-global\templates\admin.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_653bc88ce31ee1_98779891',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3c409ae60e4dfcd987b5c06a16a667bd2163cd41' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\designer-global\\templates\\admin.tpl',
      1 => 1698292874,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 120,
),true)) {
function content_653bc88ce31ee1_98779891 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
  <head>
    <title>Designer 3D - Admin | ReMap Inc.</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <link href="css/admin.css" rel="stylesheet">

    <!-- See README.md for details -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/admin.js?v2"></script>
	
	<style>
	h3 {
		border-bottom: #ccc solid 1px;
		padding-bottom: 12px;
	}
	h3 button{
		float: right;
		padding: 5px 15px;
		border-radius: 3px;
		border: #aaa solid 1px;
		cursor: pointer;
	}
	</style>
  </head>

  <body>

<div class="container" style="margin-bottom: 40px;">
	<h3>
		Manage Products	
		<a href="workspace"><button class="backtohome">Back</button></a>
	</h3>
	<div id="set-margin"><label>Set Margin</label><input value="60" /><button class="setmargin">Set</button></div>

	<ul class="tabs">

	</ul>
	<div id="content">
<!--
	<div id="tab-1" class="tab-content current">
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
	</div>
	<div id="tab-2" class="tab-content">
		 Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
	</div>
	<div id="tab-3" class="tab-content">
		Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
	</div>
	<div id="tab-4" class="tab-content">
		Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
	</div>
-->
	</div>
</div>

<!--
  <table id="items">
	<thead>
		<tr>
			<td>SI#</td>
			<td>Name</td>
			<td>SKU</td>
			<td>Material Cost</td>
			<td>Labor Cost</td>
			<td>Margin Percent</td>
			<td>Retail</td>
		</tr>
	</thead>
	<tbody>
	</tbody>
  </table>
-->
  </body>
</html><?php }
}
