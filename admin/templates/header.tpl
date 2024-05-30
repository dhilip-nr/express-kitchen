<header>
    <a href="{$root}" title="Logo" style="float:left;"><img id="thd_logo" src="{$root}../images/company/{$fav_logo}" alt="" /></a>

    <ol class="print_order">
        <li class="help_link">
        	<a id="help-fancybox" href="{$root}../helpdesk/index.html&portal=admin">HELP</a>
        </li>
	{if $isUser}
        <li>Welcome <b>{$smarty.session[$APPSESVAR|cat:"_adminuser"].un}</b> [ <a href="{$root}logout.html">logout</a> ]</li>
	{/if}
    </ol>
    
	{if $isUser}
	    {$adminMainNavMenu}
	{/if}    
</header>