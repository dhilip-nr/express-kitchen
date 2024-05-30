<?php
/* Smarty version 4.3.2, created on 2024-04-16 17:29:40
  from 'D:\Program Files\xampp\htdocs\hdi\designer-global\templates\workspace.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.2',
  'unifunc' => 'content_661e99645872b9_73070922',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e1eddf89eda61762ad885c9b38779ced45e6d0da' => 
    array (
      0 => 'D:\\Program Files\\xampp\\htdocs\\hdi\\designer-global\\templates\\workspace.tpl',
      1 => 1713258380,
      2 => 'file',
    ),
  ),
  'cache_lifetime' => 120,
),true)) {
function content_661e99645872b9_73070922 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html>
  <head>
    <title>Designer 3D | ReMap Inc.</title>
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/workspace.css?v1713281380" rel="stylesheet">
	<link href="css/summary.css?v1713281380" rel="stylesheet">
	<link href="css/loader.css" rel="stylesheet">

    <!-- See README.md for details -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>

    <!-- Make sure you've built blueprint3d.js -->
	<script src="js/three.min.js"></script>
    <script src="js/blueprint3d.js?v1713281380"></script>
    <script  src="js/workspace.js?v1713281380"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"></script> 
	<link rel="manifest" href="manifest.json">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<script type="text/javascript" src="https://cdn.rawgit.com/prashantchaudhary/ddslick/master/jquery.ddslick.min.js" ></script>

  </head>

  <body>
	<div id="door-config-modal">
	  	<div class="modal-content">
		<span class="close">&times;</span>
		<h4 id="group_title">Door Configuration</h4>
	 	<div class="door-conf"><label>Material</label><div id="dr-material"></div></div>
		<div class="door-conf"><label>Style</label><div id="dr-style"></div></div>
		<div class="door-conf"><label>Drawer Front</label><div id="dr-front"></div></div>
		<div class="door-conf"><label>Color</label><div id="dr-colors"></div></div>
		<h4 style="margin: 20px 0 5px; border-bottom: #ccc dashed 1px; font-size: 16px; padding-bottom: 5px;">Pull Hardware</h4>
		<div class="door-conf"><label>Finish</label><div  id="hn-finish"></div></div>
		<div class="door-conf"><label>Type</label><div id="hn-color"></div></div>
		
		<button class="saveConfig" disabled id="saveConfig" onClick=saveConfig() style="margin-left: 300px;">Save Configuration</button>
		<div id='set-all-config' style="display: block; float: left; margin-top: 20px; width: 100%;"> <input type="checkbox" /> <span></span>
		<button class="saveConfig" disabled id="updateConfig" onClick=saveConfig('update')>Update Configuration</button></div></div>
	   </div>
	</div>   
			<div id="loading-container">
				<div class="loading"></div>
				<div id="loading-text">loading</div>
			</div>
    <div id="uom_container">
      <span>
              Measures
      <select>
      <option value="IN">Inches</option>
      <option value="FT">Feet</option>
      </select>
      </span>
      </div>
    <!-- <script src="https://requirejs.org/docs/release/2.3.5/minified/require.js"></script> -->
    <div class="container-fluid">
      
      <div class="row main-row">
	  <div id="checkdesign-modal"></div>
	 <!--  <div id="confirmation-modal">
	  <div  class='modal-content'>
	  <span>Your design was not saved. Do you want to load your design when you continue later? </span>
           <div class="ret-btns">
		   <button class="close-draft">No, Cancel</button>
		   <button onclick="draftBtnActiion("save","")" class="draft-btn-type">Yes, Load</button>
		     </div>
			 </div>-->
	  </div>
        <!-- Left Column -->
        <div id="sidebar" class="col-xs-3">
					<!-- Wall Info Block -->
			<div id="wall-details">
				<div id="wall-select"></div>
				<div id="wall-measure"></div>
			</div>
     
          <!-- Main Navigation -->
          <ul class="nav nav-sidebar">
            <li id="floorplan_tab" style="display: none;"><a href="#">
              Wall Design
              <span class="glyphicon glyphicon-chevron-right pull-right"></span>
            </a></li>
            <li id="design_tab"><a href="#">
              Workspace
              <span class="glyphicon glyphicon-chevron-right pull-right"></span>
            </a></li>
			<li id="standard_items_tab">
				<a href="#">
				  Add Standard Items
				  <span class="glyphicon glyphicon-chevron-right pull-right"></span>
				</a>
	<!-- SCENE ITEM OPTIONS - STARTS -->
			  <!-- Add Build-in Items -->
			  <div id="add-standard-items"></div>
	<!-- SCENE ITEM OPTIONS - ENDS -->
			</li>
            <li id="items_tab" style="display: inline-block; width: 100%;">
				<a href="#">
				  Add Catalog Items
				  <span class="glyphicon glyphicon-chevron-right pull-right"></span>
				</a>
	<!-- SCENE ITEM OPTIONS - STARTS -->
			  <!-- Add Items -->
			  <div id="add-items"></div>
	<!-- SCENE ITEM OPTIONS - ENDS -->
			</li>
          </ul>

          <!-- Context Menu -->

          <div id="context-menu">
            <div>
              <span id="context-menu-name" class="lead"></span>
            <br />
            <div class="panel panel-default">
              <div class="panel-heading">
			  Adjust Size
			<div id="btn-action">
				<button  class="btn btn-block btn-success copy_btn" id="context-menu-copy">
				  <span class="glyphicon glyphicon-copy"></span> 
				 Copy
				</button>
			    <button class="btn btn-block btn-danger delete_btn" id="context-menu-delete">
                <span class="glyphicon glyphicon-trash"></span> 
                Delete
              </button>
			</div>
			  </div>
              <div class="panel-body" style="color: #333333">

                <div class="form form-horizontal" class="lead">
                  <div class="form-group">
                    <label class="col-sm-4 control-label">
                    Width
                    </label>
                   
                      <div style="display: none;" class="display-inch col-sm-8">
                        <div class="quantity">
                          <a href="#"  ><span  onclick='qty("#item-width","","-")' class="quantity__minus">-</span></a>
                          <input readonly name="quantity" type="text" id="item-width" class="quantity__input" >
                          <a href="#" class="quantity__plus"><span onclick='qty("#item-width","","+")'>+</span></a>
                        </div>
                      </div>
             
                   <div style="display: none;" class="display-ft col-sm-8">
                    <div class="quantity">
                      <a href="#" class="quantity__minus"><span onclick='qty("#item-width-in","#item-width-ft","-")'>-</span></a>
					  <div>
						  <input readonly name="quantity" type="text" id="item-width-ft" class="quantity__input ft" value="W-Ft"><span >'</span>
						  <input readonly name="quantity" type="text" id="item-width-in" class="quantity__input ft" value="W-FtI"><span >"</span>
					</div>
                      <a href="#" class="quantity__plus"><span onclick='qty("#item-width-in","#item-width-ft","+")'>+</span></a>
                    </div>
                     <!-- <div  class="col-sm-4">
                      <input type="number" class="form-control" id="item-width-ft">
                    </div>
                    <div  class="col-sm-4">
                      <input type="number" class="form-control" id="item-width-in"> 
                    </div> -->
                  </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">
                     Depth
                    </label>
                    <div style="display: none; " class="display-inch col-sm-8">
                      <div class="quantity">
                        <a href="#" class="quantity__minus"><span onclick='qty("#item-depth","","-")'>-</span></a>
                        <input readonly name="quantity" type="text" id="item-depth" class="quantity__input" value="D-I">
                        <a href="#" class="quantity__plus"><span onclick='qty("#item-depth","","+")'>+</span></a>
                      </div>
                    </div>
                   <div style="display: none;" class="display-ft col-sm-8">
                    <div class="quantity">
                      <a href="#" class="quantity__minus"><span onclick='qty("#item-depth-in","#item-depth-ft","-")'>-</span></a>
					  <div>
						  <input readonly name="quantity" type="text" id="item-depth-ft" class="quantity__input ft" ><span>'</span>
						  <input readonly name="quantity" type="text" id="item-depth-in" class="quantity__input ft" ><span>"</span>
					  </div>
                      <a href="#" class="quantity__plus"><span onclick='qty("#item-depth-in","#item-depth-ft","+")'>+</span></a>
                    </div>
                  </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-4 control-label">
                      Height
                    </label>
                    <div style="display: none; " class="display-inch col-sm-8">
                    <div class="quantity">
                      <a href="#" class="quantity__minus"><span onclick='qty("#item-height","","-")'>-</span></a>
                      <input readonly name="quantity" type="text" id="item-height" class="quantity__input" >
                      <a href="#" class="quantity__plus"><span onclick='qty("#item-height","","+")'>+</span></a>
                    </div>
                    </div>
                   <div style="display: none;" class="display-ft col-sm-8">
                    <div class="quantity">
                      <a href="#" class="quantity__minus"><span onclick='qty("#item-height-in","#item-height-ft","-")'>-</span></a>
					  <div>
						  <input readonly name="quantity" type="text" id="item-height-ft" class="quantity__input ft" ><span>'</span>
						  <input readonly name="quantity" type="text" id="item-height-in" class="quantity__input ft"><span>"</span>
					  </div>
                      <a href="#" class="quantity__plus"><span onclick='qty("#item-height-in","#item-height-ft","+")'>+</span></a>
                    </div>
                  </div>
                  </div>
                </div>
                <small><span class="text-muted">Measurements in inches.</span></small>
             
              </div>
            </div>

            <label><input type="checkbox" id="fixed" /> Lock in place</label>
			<button style='display:none' id='edit-sel-config'>Edit Configuration</button>

            <br />


<!--
<div class="btn-group" style="margin:5px;width:100%">
	<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><img src="images/models/textures/transparent.png"> <div>None<span style="margin-left:85px" class="caret"></span></div></a>
	<ul class="dropdown-menu">
		<li><a href="javascript:void(0);">
		<img src="images/models/textures/transparent.png"><div>None</div></a>
		</li>
		<li><a href="javascript:void(0);">
		<img src="images/models/textures/clear_lacquer.jpg" /><div>Clear Laquer</div></a>
		</li>
		<li><a href="javascript:void(0);">
		<img src="images/models/textures/butterscotch.jpg" /> <div>Butterscotch</div> </a>
		</li>
		<li><a href="javascript:void(0);">
		<img src="images/models/textures/dark_honey.jpg" /><div>Dark Honey</div> </a>
		</li>
	</ul>
</div>
	<div id="colorOptions">
		<div class="panel panel-default">
		  <div class="panel-heading">
		  Available Colors
		  
		  		<button class="btn btn-block btn-danger" id="clear_color">
				 Clear
				</button>
		  </div>
		  <div class="panel-body" style="color: #333333"></div>
		</div>
	</div>
-->	

            </div>
          </div>
    
          <!-- Floor textures -->
          <div id="floorTexturesDiv" style="display:none; padding: 0 20px">
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Floor</div>
              <div class="panel-body" style="color: #333333">

                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="images/rooms/textures/light_fine_wood.jpg" texture-stretch="false" texture-scale="300">
                    <img alt="Thumbnail light fine wood" src="images/rooms/thumbnails/thumbnail_light_fine_wood.jpg" />
                  </a>
                </div>
              </div>
            </div>
          </div>

          <!-- Wall Textures -->

          <div id="wallTextures" style="display:none; padding: 0 20px">
            <div class="panel panel-default">
              <div class="panel-heading">Adjust Wall</div>
              <div class="panel-body" style="color: #333333">
                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="images/rooms/textures/marbletiles.jpg" texture-stretch="false" texture-scale="300">
                    <img alt="Thumbnail marbletiles" src="images/rooms/thumbnails/thumbnail_marbletiles.jpg" />
                  </a>
                </div>
                <div class="col-sm-6" style="padding: 3px">
                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="images/rooms/textures/wallmap_yellow.png" texture-stretch="true" texture-scale="">
                    <img alt="Thumbnail wallmap yellow" src="images/rooms/thumbnails/thumbnail_wallmap_yellow.png" />
                  </a>
                </div>
                <div class="col-sm-6" style="padding: 3px">

                  <a href="#" class="thumbnail texture-select-thumbnail" texture-url="images/rooms/textures/light_brick.jpg" texture-stretch="false" texture-scale="100">
                    <img alt="Thumbnail light brick" src="images/rooms/thumbnails/thumbnail_light_brick.jpg" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-xs-9 main">

          <!-- 3D Viewer -->
          <div id="viewer-control">


            <div id="main-controls">

				<button class="btn btn-sm btn-default menu">
					<span class="glyphicon glyphicon-align-justify"></span>
					<ul>
						<li class="admin">
							<span class="glyphicon glyphicon-briefcase"></span> &nbsp;
							Management Portal
						</li>
						<li class="download_image" id="saveFile">
							<span class="glyphicon glyphicon-save"></span> &nbsp;
							Download Design
						</li>
						<li class="save_design" id="saveJSONFile">
							<span class="glyphicon glyphicon-floppy-disk"></span> &nbsp;
							Save Design
						</li>
						<li class="logout">
							<span class="glyphicon glyphicon-log-out"></span> &nbsp;
							Logout
						</li>
					</ul>
				</button>

				<a href="#" class="btn btn-default btn-sm" id="goto_wall_planner">
				<span class="glyphicon glyphicon-pencil"></span> &nbsp; 
					Edit Walls
				</a>
				        <button  class="btn btn-sm btn-default undo">
<i class="fa fa-undo" style="font-size:18px;"></i>
</button>
<button class="btn btn-sm btn-default redo">

<i  class="fa fa-undo" style="font-size:18px;transform: rotateY(180deg);"></i>
</button>

<!--
				<a href="#" class="btn btn-default btn-sm" id="model_preload_tab">
				  Pre-Loaded Styles
				</a>
-->
<!--
              <a href="#" class="btn btn-default btn-sm" id="new">
                New
              </a>
              <a href="#" class="btn btn-default btn-sm" id="saveFile">
                Save
              </a>
              <a class="btn btn-sm btn-default btn-file">
               <input type="file" class="hidden-input" id="loadFile">
               Load
              </a>
			  
-->
				<a href="#" class="btn btn-default btn-sm" id="prev_tab" style="float: right">
				<span class="glyphicon glyphicon-shopping-cart"></span> &nbsp; 
				Cart (<span id="cart_count">0</span>)
				</a>
            </div>


            <div id="camera-controls">

				<a href="#" class="btn btn-default" id="model_preload_tab" style="float: left;">
					Load Designs
				</a>
				
				<div id="switch-view" style="display: inline-block; margin-right: 10px; border-right: #ccc solid 2px; padding-right: 15px;">
				  <a href="#" class="btn btn-default bottom" id="straight-view">
				   Straight
				  </a>
				  <a href="#" class="btn btn-default bottom" id="top-view">
					Top
				  </a>
				</div>

				<a href="#" class="btn btn-default bottom" id="zoom-out">
					<span class="glyphicon glyphicon-zoom-out"></span>
				</a>
				<a href="#" class="btn btn-default bottom" id="reset-view">
					<span class="glyphicon glyphicon glyphicon-repeat"></span>
				</a>
				<a href="#" class="btn btn-default bottom" id="zoom-in">
					<span class="glyphicon glyphicon-zoom-in"></span>
				</a>
<!--
              <span>&nbsp;</span>

              <a class="btn btn-default bottom" href="#" id="move-left" >
                <span class="glyphicon glyphicon-arrow-left"></span>
              </a>
              <span class="btn-group-vertical">
                <a class="btn btn-default" href="#" id="move-up">
                  <span class="glyphicon glyphicon-arrow-up"></span>
                </a>
                <a class="btn btn-default" href="#" id="move-down">
                  <span class="glyphicon glyphicon-arrow-down"></span>
                </a>
              </span>
              <a class="btn btn-default bottom" href="#" id="move-right" >
                <span class="glyphicon glyphicon-arrow-right"></span>
              </a>
-->
            </div>
<!--
			<div id="loading-container">
				<div class="loading"></div>
				<div id="loading-text">loading</div>
			</div>
-->
<!--
            <div id="loading-modal">
              <h1>loading</h1>  
            </div>
-->
          </div>
          <div id="viewer"></div>
          <!-- 2D Floorplanner -->
          <div id="floorplanner">
            <canvas id="floorplanner-canvas"></canvas>
            <div id="floorplanner-controls">
				<button class="btn btn-sm btn-default menu">
					<span class="glyphicon glyphicon-align-justify"></span>
					<ul>
						<li class="admin">
							<span class="glyphicon glyphicon-briefcase"></span>
							Management Portal
						</li>
						<li class="logout">
							<span class="glyphicon glyphicon-log-out"></span>
							Logout
						</li>
					</ul>
				</button>

<button id="selected_mode" class="btn btn-sm btn-default">
	<div id="selected">
		<span class="glyphicon glyphicon-move"></span>
		Move Walls
	</div>
	<div id="opt-menu">
		<span class="glyphicon glyphicon-chevron-down"></span>
	</div>
</button>

<ul id="wall_options">
	<li id="move">
		<span class="glyphicon glyphicon-move"></span>
		Move Walls
	</li>
	<li id="draw">
		<span class="glyphicon glyphicon-pencil"></span>
		Draw Walls
	</li>
	<li id="delete">
		<span class="glyphicon glyphicon-remove"></span>
		Delete Walls
	</li>
</ul>
<span class="btn btn-sm btn-default" id="wall-ht">
	Wall Size
	<select onchange="setWallSize()">
		<option selected value="244">Full</option>
		<option value="90">Half</option>
		<option value="0">Custom</option>
	</select>
	<input id="cust-wall-inp" style="display: none;" type="text" value="0" onchange="setWallSizeVal()">
</span>

<!--
<button id="move" class="btn btn-sm btn-default">
	<span class="glyphicon glyphicon-move"></span>
	Move Walls
</button>
<button id="draw" class="btn btn-sm btn-default">
	<span class="glyphicon glyphicon-pencil"></span>
	Draw Walls
</button>
<button id="delete" class="btn btn-sm btn-default">
	<span class="glyphicon glyphicon-remove"></span>
	Delete Walls
</button>
-->


				<button class="btn btn-primary btn-sm" id="update-floorplan" style="float: right">
				Done
				<span class="glyphicon glyphicon-chevron-right marg-r5"></span> &nbsp; 
				</button>
            </div>
            <div id="draw-walls-hint">
              Press the "Esc" key to stop drawing walls
            </div>
          </div>

		  <!-- Pre-load Items Container -->
          <div id="preload-viewer">
			<h2>Standard Kitchen Styles</h2>
			<ul id="content-slider" style="text-align: center; padding: 0;">
				<li> 
					<div class="box">
						<a href="#">
							<span class="shape_name">L-Shape</span>
							<img class="shape" alt="" src="images/counter_styles/shape1.png" name="1">
						</a>
					</div>
				</li>
				<li>
					<div class="box">
						<a href="#">
							<span class="shape_name">U-Shape</span>
							<img class="shape" alt="" src="images/counter_styles/shape2.png" name="2">
						</a>
					</div>
				</li>
				<li>
					<div class="box">
						<a href="#">
							<span class="shape_name">Gallay</span>
							<img class="shape" alt="" src="images/counter_styles/shape3.png" name="3">
						</a>
					</div>
				</li>
				<li> 
					<div class="box">
						<a href="#">
							<span class="shape_name">Batwing</span>
							<img class="shape" alt="" src="images/counter_styles/batwing.png" name="4">
						</a>
					</div>
				</li>
			</ul>
			<div style="float: right; padding-right: 50px; margin: 30px 0">
				<button class="btn btn-default btn-sm" id="clear-room">
				<span class="glyphicon glyphicon-trash"></span> &nbsp; 
				Clear Room
				</button>
			</div>

			<h2 id="my-design">
				My Designs
                <span class="glyphicon glyphicon-chevron-right closed active"></span>
                <span class="glyphicon glyphicon-chevron-down opened"></span>
			</h2>
			<div id="view-mydesign"></div>

<!--
<div  align="left" >
  <button data-toggle="tab" data-tabs=".gtabs.demo" data-tab=".view-mydesign"  class="btn btn-tab" >My Design</button>
  <button data-toggle="tab" data-tabs=".gtabs.demo" data-tab=".view-draft" class="btn btn-tab" >Draft Design</button>
</div>
<br />
<div class="gtabs demo" >
  <div class="gtab  view-mydesign">
    <h1>My Design</h1>
  </div>

  <div class="gtab view-draft">
    <h1>Draft</h1>
  </div>
-->
				

          </div>

          <!-- Add Summary Items -->
          <div id="summary-viewer">
		        <div id="controls">
					<a href="#" class="btn btn-default btn-sm" id="back-to-viewer">
					<span class="glyphicon glyphicon-chevron-left"></span> Back
					</a>
				</div>
		        <div id="content">
				</div>
		        <div id="price_summary">
				</div>
          </div>
        </div>



		<div id="prev_summary_handler"></div>
        <div id="prev_summary" class="col-xs-3">
          <div id="back-to-wall" class="prev-sum-header">
            <span class="glyphicon glyphicon-chevron-left pull-left"></span>
            <a >Preview Summary</a>
          </div>
          <div id="content"></div>
          <button id="view_summary">
			<a href="#">Go To Summary</a>
		  </button>
        </div>
      
       
      
        <!-- End Right Column -->
      </div>
    </div>

  </body>
</html><?php }
}
