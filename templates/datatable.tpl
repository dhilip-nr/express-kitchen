<!DOCTYPE html>
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



