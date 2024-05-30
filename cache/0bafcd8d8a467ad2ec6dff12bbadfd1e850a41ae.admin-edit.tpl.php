<?php
/* Smarty version 4.3.2, created on 2023-10-27 01:36:36
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\templates\admin-edit.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_653af80485fae5_27704360',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ccbf5665ac4950b6b230bc469d7cc46baa32f177' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\templates\\admin-edit.tpl',
      1 => 1698337273,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 120,
),true)) {
function content_653af80485fae5_27704360 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
  <head>
    <title>Designer 3D - Admin | ReMap Inc.</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <link href="css/admin.css" rel="stylesheet">

    <!-- See README.md for details -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>

    <script>
		
		var margin = 60;
		function loadJson() {
		window.getData("catalog-items.json").then(builtin_data => {
			console.log("builtin_data", builtin_data);
			let html = "";
			let tabs = "";
			builtin_data.items.forEach((groups, gidx) => {
				tabs += "<li class=\"tab-link " + (gidx == 0 ? "current" : "") + "\" data-tab=\"tab-" + (gidx + 1) + "\">" + groups.name + "</li>";
				html += "<div id=\"tab-" + (gidx + 1) + "\" class=\"tab-content " + (gidx == 0 ? "current" : "") + "\"><table>";
				html += "<tr class=\"title\">"
				 + "<td>SI#</td>"
				 + "<td style=\"width: 250px;\">Name</td>"
				 + "<td>SKU</td>"
				 + "<td>Material Cost ($)</td>"
				 + "<td>Labor Cost ($)</td>"
				 + "<td>Margin Percent (%)</td>"
				 + "<td>Retail ($)</td>"
				 + "</tr>";
				groups.items.forEach((item, idx) => {
					let retail = ((Number(item.material_cost) + Number(item.labor_cost)) / (1 - (Number(margin) / 100))).toFixed(2);
					html += "<tr>"
					 + "<td>" + (idx + 1) + "</td>"
					 + "<td>" + item.name + "</td>"
					 + "<td>104525969-2511-" + (27100 + idx) + "</td>"
					 + "<td class=\"right\">" + item.material_cost + "</td>"
					 + "<td class=\"right\">" + item.labor_cost + "</td>"
					 + "<td class=\"right\">" + margin + "</td>"
					 + "<td class=\"right\">" + retail + "</td>"
					 + "</tr>";
				});
				html += "</table></div>";
			});
			$(".tabs").html(tabs);
			$("#content").html(html);
			$('ul.tabs li').click(function () {
				var tab_id = $(this).attr('data-tab');
				$('ul.tabs li').removeClass('current');
				$('.tab-content').removeClass('current');
				$(this).addClass('current');
				$("#" + tab_id).addClass('current');
			})
		});
		}
		$(document).ready(function () {
		$(".setmargin").click(function () {
			margin = Number($("#set-margin input").val());
			loadJson();
		})
		loadJson();
		});




		async function getData(file_path) {
		return new Promise(resolve => {
			$.ajax({
				url: "ajax_process.php",
				type: "POST",
				dataType: "json",
				data: {"action": "get_json_data", "file": file_path},
				async: false,
				success: function(data){
					resolve(data);
				},
				error: handleAjaxError
			});
		})
		}

		function handleAjaxError(xhr, textStatus, errorThrown){
			switch(xhr.status){
				case 401: alert("It seems your session was expired, so we are redirecting you to login page.");
						  window.location.href=root_path+"user/login.html";
						break;
				case 404: alert("Requested service resource was not found. Please contact the administrator ...!");
						break;
				default: alert("Something went wrong, your request could not be completed right now.\n\nPlease try again later...!");
						break;
			}
		}
		
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

	<ul class="tabs">

	</ul>
	<div id="content">

	</div>
</div>

  </body>
</html><?php }
}
