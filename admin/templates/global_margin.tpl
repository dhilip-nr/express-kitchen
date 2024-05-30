<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Designer | Admin</title>

    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">

    <link rel="stylesheet" href="assets/css/demo_1/style.css" />

	<script>
		function toggleEdit(mode) {
			if(mode=="save"){
				$("#margin_form [name=percent]").val($("#set-margin .save [name=margin]").val());
				
				$("#margin_form").submit();
			} else if(mode=="edit"){
				$("#set-margin .view").addClass("hidden");
				$("#set-margin .save").removeClass("hidden");
			} else if(mode=="cancel"){
				$("#set-margin .save").addClass("hidden");
				$("#set-margin .view").removeClass("hidden");
			}
		}
	</script>

  </head>
  <body>
    <div class="container-scroller">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile border-bottom">
            <a href="#" class="nav-link flex-column">
              <div class="nav-profile-image">
                <img src="../images/logo.png" alt="profile" />
              </div>
              <div class="nav-profile-text d-flex ml-0 mb-3 flex-column">
                <span class="font-weight-semibold mb-1 mt-2 text-center">3D Designer | Admin</span>
				<span style="text-align: center;">( {$smarty.session[$APPSESVAR|cat:"_user"].co_name} )</span>
              </div>
            </a>
			
            <a class="nav-link change-workspace" href="../workspace">
              <span class="menu-title">Back to Workspace</span>
            </a>

          </li>
<!--
          <li class="nav-item pt-3">
            <a class="nav-link d-block" href="index.html">
              <img class="sidebar-brand-logo" src="assets/images/logo.svg" alt="" />
              <img class="sidebar-brand-logomini" src="assets/images/logo-mini.svg" alt="" />
              <div class="small font-weight-light pt-1">Responsive Dashboard</div>
            </a>
          </li>
-->
          <li class="nav-item">
            <a class="nav-link" href="orders">
              <i class="mdi mdi-format-list-bulleted menu-icon"></i>
              <span class="menu-title">Orders</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="catalog">
              <i class="mdi mdi-table-large menu-icon"></i>
              <span class="menu-title">Catalog</span>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#">
              <i class="mdi mdi-currency-usd menu-icon"></i>
              <span class="menu-title">Global Margin</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="users">
              <i class="mdi mdi-playlist-edit menu-icon"></i>
              <span class="menu-title">USERS</span>
            </a>
          </li>

        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
  
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
          <div class="navbar-menu-wrapper d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav">
				<li class="nav-item d-none d-lg-block">
					Global Margin
				</li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-logout d-none d-lg-block">
                <a class="nav-link" href="../logout">
				  <i class="mdi mdi-logout"></i>
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper pb-0">
            <!-- first row starts here -->
            <div class="row">
				
				
				
				
				
				
				
				
				
				
				
				
				<link href="../css/admin.css" rel="stylesheet">

				<div class="container" style="width: 94%; max-width: 94%; margin-bottom: 40px;">
					<ul class="tabs">
						<li class="tab-link current"><a href="#">Manage Global Margin</a></li>
					</ul>
					<div class="tab_links" id="content">
							<div id="set-margin">
								<span class="view">
									<label>Margin (%)</label><input name="margin" value="{$company_info.margin}" readonly />
									<button class="editmargin" onclick="toggleEdit('edit')">Edit</button>
								</span>
								<span class="save hidden">
									<label>Set Margin (%)</label><input name="margin" value="{$company_info.margin}" />
									<button class="setmargin" onclick="toggleEdit('save')">Set</button>
									<button class="setmargin cancel" onclick="toggleEdit('cancel')">Cancel</button>
								</span>
							</div>
							
						<form id="margin_form" method="post" style="display: none">
							<input name="action" value="update_margin" />
							<input name="percent" value="{$company_info.margin}" />
						</form>
					</div>
				</div>
				
				
				
	
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				

            </div>

          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
<!--
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span>
            </div>

            <div>
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block"> Distributed By: <a href="https://themewagon.com/" target="_blank">Themewagon</a></span>
            </div>
          </footer>
-->
        </div>
      </div>
    </div>
  </body>
</html>





