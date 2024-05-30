<style>
#order_lookup h4{
	margin: 20px auto;
    text-transform: uppercase;
    text-align: center;
    color: #F88421;
}
#order_lookup table{
	width: 400px; margin: auto;
	border-collapse:collapse;
	font-size:12px;
}
#order_lookup table td{
	border-bottom:dotted 1px #ccc;
}
#order_lookup table a:hover{
	color:#F88421;
	text-decoration:underline;
}

</style>
<div id="order_lookup" style="margin:10% auto; width:700px;">
<h4 style="margin:20px auto;">Digital Contracts</h4>

<table cellpadding="10">
{foreach $contracts_array as $key=>$val}
	<tr>
    	<td>{$key+1}. </td>
        <td><a href="{$root}digital_contracts.php?paper={$val.page}" target="_blank">{$val.name}</a></td>
    </tr>
{/foreach}
</table>
</div>