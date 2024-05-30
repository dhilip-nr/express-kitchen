<?php

class AdminMainMenu extends Functions {
	function adminMainNavMenu($path=""){
		if(isset($_SESSION[APPSESVAR.'_adminuser']['un'])){
		$page_url_arr = $this->page("arr_url");
		$page_url_arr[count($page_url_arr)] = explode("?", end($page_url_arr))[0];

		if(isset($page_url_arr[count($page_url_arr)-1]))
			$curr_page = array($page_url_arr[count($page_url_arr)-1], end($page_url_arr));
		else
			$curr_page = array(end($page_url_arr), "index.html");

		$menu = '';
		$menu_links = array(
//			array("n"=>"Task / Issues", "r"=>"superadmin", "a"=>"admin/task_issue.php", "l"=>'task_issue.php', "c"=>"", "ac"=>"current"),
//			array("n"=>"Catalog", "r"=>"superadmin", "a"=>"catalog/,catalog/index.html", "l"=>'catalog/index.html', "c"=>"", "ac"=>"current", "s"=>"mc"),
//			array("n"=>"SPA", "r"=>"superadmin", "a"=>"spa/,spa/index.php", "l"=>'spa/index.php', "c"=>"", "ac"=>"current"),
			array("n"=>"Digital Contracts", "r"=>"all", "a"=>"admin/digital_contracts.html", "l"=>'digital_contracts.html', "c"=>"", "ac"=>"current"),
			array("n"=>"Look Up", "r"=>"superadmin", "a"=>"lookup/,lookup/index.php", "l"=>'lookup/index.php', "c"=>"", "ac"=>"current"),
			array("n"=>"Orders", "r"=>"all", "a"=>"admin/view_order.html", "l"=>'view_order.html', "c"=>"", "ac"=>"current"),
			array("n"=>"Dashboard", "r"=>"superadmin,admin", "a"=>"dashboard/,dashboard/index.php", "l"=>'dashboard/index.php', "c"=>"", "ac"=>"current"),
			array("n"=>"&nbsp;", "r"=>"all", "a"=>"admin/,admin/index.html", "l"=>'index.html', "c"=>"home", "ac"=>"ha"),
		);
		$menu_sub_links = array('mc' => array(
				array("n"=>"Buyside Catalog", "a"=>"catalog/buyside_catalog.php", "l"=>'catalog/buyside_catalog.php', "c"=>"", "ac"=>"current"),
//				array("n"=>"Saleside Catalog", "a"=>"catalog/saleside_catalog.php", "l"=>'catalog/saleside_catalog.php', "c"=>"", "ac"=>"current"),
				array("n"=>"Categories", "a"=>"catalog/categories.html", "l"=>'catalog/categories.html', "c"=>"", "ac"=>"current"),
				array("n"=>"Products", "a"=>"catalog/products.html", "l"=>'catalog/products.html', "c"=>"", "ac"=>"current")
			)
		);

		foreach($menu_links as $link){
			$access_roles = explode(",", $link['r']);
			$has_access = (in_array($_SESSION[APPSESVAR.'_adminuser']['role'], $access_roles)?true:false);
			if($access_roles[0]=="all" || ($has_access && $link['n']!="Manage Catalog") || ($has_access && $link['n']=="Manage Catalog" && MNG_CATALOG)){
				$add_class = "";

				$sub_menu = "";
				if (isset($link['s'])){
					$add_class .= " has_child";
					$sub_menu = '<ul class="catalog_submenu">';

					foreach($menu_sub_links['mc'] as $slink){
						$sub_class = "";
						if($curr_page[0]."/".$curr_page[1]==$slink['l']){
							$sub_class = " ".$slink['ac'];
						}

						$sub_menu .= '<li class="'.$slink['c'].$sub_class.'"><a href="'.ROOT.$path.$slink['l'].'">'.$slink['n'].'</a></li>';
						$link['a'] .= ','.$slink['a'];
					}
					$sub_menu .= '</ul>';
				}

				$page_alias = explode(",", $link['a']);
				if(in_array($curr_page[0]."/".$curr_page[1], $page_alias)){
					$add_class .= " ".$link['ac'];
				}

				$menu .= '<li class="'.$link['c'].$add_class.'"><a href="'.ROOT.$path.$link['l'].'">'.$link['n'].'</a>'.$sub_menu.'</li>';
			}
		}

		return '<ul class="print_order">'.$menu.'</ul>';
	}
	}
}

?>