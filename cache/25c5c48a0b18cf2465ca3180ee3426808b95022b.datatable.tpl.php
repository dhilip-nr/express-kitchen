<?php
/* Smarty version 4.3.2, created on 2023-10-26 17:55:42
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\templates\datatable.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_653a8bfe8afe10_58205268',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd74972b7ac0f475fa6b97ad2e136300b4e3cf7a6' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\templates\\datatable.tpl',
      1 => 1698335741,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 120,
),true)) {
function content_653a8bfe8afe10_58205268 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
  <head>
    <title>Designer 3D - Admin | ReMap Inc.</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <link href="css/datatables.css" rel="stylesheet">

	<script src="js/jquery.js"></script>
    <script src="js/datatables.js"></script>
	<script>
		$(document).ready(function() {
			$('#example').DataTable({
				ajax: 'ajax_process.php?action=catalog_data',
				columns: [
					{ items: 'name' },
					{ items: 'name' },
					{ items: 'name' },
					{ items: 'name' },
					{ items: 'name' },
					{ items: 'name' }
/*
					{ data: 'name' },
					{ data: 'sku' },
					{ data: 'material_cost' },
					{ data: 'labor_cost' },
					{ data: 'margin_per' },
					{ data: 'price' }
*/
				],
				processing: true
			});
		});
	</script>
	
	
	
	
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


		<table id="example" class="display" style="width:100%">
			<thead>
				<tr>					
					<th>SI#</th>
					<th>Name</th>
					<th>SKU</th>
					<th>Material Cost</th>
					<th>Labor Cost</th>
					<th>Margin Percent</th>
					<th>Retail</th>
				</tr>
			</thead>
		</table>
	</div>

  </body>
</html>



<?php }
}
