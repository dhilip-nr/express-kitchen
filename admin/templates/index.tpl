<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="no-js" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
	<meta http-equiv="Cache-Control" content="{$smarty.const.CACHE_CONTROL}"/>
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
	<title>{$title} | Admin - {$site_name}</title>
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	{$fav_logo = $smarty.const.APPLOGO}
	<link rel="icon" href="{$root}images/company/{substr($fav_logo, 0, strrpos( $fav_logo, '.'))}.ico" type="image/x-icon"/>
	<link href="{$root}styles/base.css?v{$app_version}" rel="stylesheet" type="text/css" />
	<script type="text/javascript">var root_path="{$root}";</script>
	<script src="{$root}scripts/jquery.min.js?v{$app_version}" type="text/javascript"></script>

	{$pageassets}

   	<script src="{$root}scripts/fancybox/jquery.fancybox.pack.js?v{$app_version}" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="{$root}scripts/fancybox/jquery.fancybox.css?v{$app_version}" media="screen" />

	<script src="{$root}scripts/common.js?v{$app_version}" type="text/javascript"></script>
</head>
<body>
	{* include file="header.tpl" title="Header" *}
	<section id="container">
        {if $userHasAnAccess}
            {$content}
        {else}
            <div align="center" style="padding:50px;">You don't have access to this order</div>
        {/if}
    </section>
	{* include file="footer.tpl" title="Footer" *}
	<section id="loader">
        <div id="circleG">
            <div id="circleG_1" class="circleG"></div>
            <div id="circleG_2" class="circleG"></div>
            <div id="circleG_3" class="circleG"></div>
        </div>
    </section>
</body>
</html>
