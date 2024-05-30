<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

{include file='menu.tpl'}
<div style="clear:both"></div>

<div id="material_order" style="margin-top:60px;">
{if $margin_access}
{$materialPrice = $order_margin.material}
{$laborPrice = $order_margin.labor}
{$retailPrice = $order_margin.netamount}
<input type="hidden" id="order_id" value="{$orderId}"/>
<input type="hidden" id="net_amt" value="{$retailPrice}"/>

{if $target_value==0 || $target_data.retail=="" || (number_format($order_margin.retail,2)==number_format($target_data.retail,2))}
    <table class="orders_wrapper" cellspacing="0" style="margin-bottom:0; border:solid 1px #b4b4b4;">
       <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
            <td width="17%" align="center"><b>Order Id</b></td>
            <td width="17%" align="center"><b>Retail($)</b></td>
            <td width="17%" align="center"><b>Material($)</b></td>
            <td width="17%" align="center"><b>Labor($)</b></td>
            <td width="17%" align="center"><b>Margin($)</b></td>
            <td width="17%" align="center"><b>Margin(%)</b></td>
        </tr>
        <tr style="border:dashed 1px #ccc;">
            <td align="center">{$ord_prefix}{$orderId}</td>
            <td align="center">$ {number_format($retailPrice,2)}</td>
            <td align="center">$ {number_format($materialPrice,2)}</td>
            <td align="center">$ {number_format($laborPrice,2)}</td>

            <!-- Margin Amount = netamount-material_sum-install_sum -->
            {if $target_value gt 0}
                <td align="center">
                    {$margin_price={math equation="x-y-z" x=$retailPrice y=$materialPrice z=$laborPrice}}
                    $ {number_format($margin_price,2)}
                </td>
                <td align="center">{number_format($margin_price*100/$retailPrice,2)}</td>
            {else}
                <td align="center">-</td>
                <td align="center">-</td>
            {/if}
        </tr>
    </table>

    {literal}
        <script type="text/javascript">
          google.charts.load("current", {packages:["corechart"]});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Order Type', 'Amount'],
              ['Margin($)', {/literal}{$margin_price}{literal}],
              ['Material($)',{/literal}{$materialPrice}{literal}],
              ['Labor($)',{/literal}{$laborPrice}{literal}]
            ]);
    
            var options = {
    //          title: 'Order Margin',
                pieHole: 0.4,
                is3D: true,
                legend: {
                    alignment: 'center'
                }
            };
    
            var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
            chart.draw(data, options);
          }
        </script>
    {/literal}
	<div id="donutchart" style="width: 500px; height: 300px; margin:auto;"></div>
{else}
    <table class="orders_wrapper" cellspacing="0" style="margin-bottom:0; border:solid 1px #b4b4b4;">
       <tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
            <td width="10%" align="center" rowspan="2"><b>Order Id</b></td>
            <td align="center" colspan="4"><b>Contract Price</b> ($)</td>
            <td width="9%" align="center" rowspan="2"><b>Margin (%)</b></td>
            <td align="center" colspan="4"><b>Revised Price</b> ($)</td>
            <td width="9%" align="center" rowspan="2"><b>Revised Margin(%)</b></td>
        </tr>
        <tr bgcolor="#F0F0F0">
            <td width="9%" align="center">Material</td>
            <td width="9%" align="center">Labor</td>
            <td width="9%" align="center">Retail <b style="color:#F00;">*</b></td>
            <td width="9%" align="center">Margin</td>
    
            <td width="9%" align="center">Material</td>
            <td width="9%" align="center">Labor</td>
            <td width="9%" align="center">Retail <b style="color:#F00;">**</b></td>
            <td width="9%" align="center">Margin</td>
        </tr>
    
        <tr style="border:dashed 1px #ccc;">
            <td align="center">{$ord_prefix}{$orderId}</td>
    
            <td align="center">{number_format($target_data.material,2)}</td>
            <td align="center">{number_format($target_data.labor,2)}</td>
            <td align="center">{number_format((float)$target_data.net_amt,2)}</td>
			{$target_margin = $target_data.net_amt-$target_data.labor-$target_data.material}
            <td align="center">{number_format($target_margin,2)}</td>
            <td align="center">{number_format((100*$target_margin/$target_data.net_amt),2)}</td>
    
            <td align="center">{number_format($materialPrice,2)}</td>
            <td align="center">{number_format($laborPrice,2)}</td>
            <td align="center">{number_format($retailPrice,2)}</td>
            {$new_margin={math equation="x-y-z" x=$retailPrice y=$materialPrice z=$laborPrice}}
            <td align="center">{number_format($new_margin,2)}</td>
            <td align="center">{number_format(($new_margin*100/$retailPrice),2)}</td>
        </tr>
		<tr>
			<td colspan="2">Adjustment Amount</td>
			<td colspan='4'>
            	<input type="number" name="disc_sur_amt" id="disc_sur_amt" value="{$discount_amt}" /> &nbsp;&nbsp;&nbsp; 
            	<input type="button" id="disc_click" name="disc_click" value="Apply"/>
			</td>
		{if $discount_amt != "0.00"}
			<td colspan="2" align="center">After Adjustment</td>
			<td>
				{$retail_new={math equation="x-y" x=$retailPrice y=$discount_amt}}
                {number_format($retail_new,2)}
			</td>
			<td align="center">
				{$disc_margin_price={math equation="x-y-z-a" x=$retailPrice y=$materialPrice z=$laborPrice a=$discount_amt}}
                {number_format($disc_margin_price,2)}
			</td>
			<td align="center">{number_format(($disc_margin_price)*100/$retail_new,2)}</td>
		{/if}
		</tr>
    </table>

    {literal}
		<script type="text/javascript">
        	google.charts.load("current", {packages:["corechart"]});
			var options = {
	//          title: 'Order Margin',
				pieHole: 0.4,
				is3D: true,
				legend: {
					alignment: 'center'
				}
			};

		  google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data1 = google.visualization.arrayToDataTable([
              ['Order Type', 'Cost'],
              ['Margin Cost', {/literal}{$target_margin}{literal}],
              ['Material Cost',{/literal}{$target_data.material}{literal}],
              ['Labor Cost',{/literal}{$target_data.labor}{literal}]
            ]);
        
            var chart1 = new google.visualization.PieChart(document.getElementById('donutchart1'));
            chart1.draw(data1, options);

            var data2 = google.visualization.arrayToDataTable([
              ['Order Type', 'Cost'],
              ['Margin Cost', {/literal}{$new_margin}{literal}],
              ['Material Cost',{/literal}{$materialPrice}{literal}],
              ['Labor Cost',{/literal}{$laborPrice}{literal}]
            ]);
        
            var chart2 = new google.visualization.PieChart(document.getElementById('donutchart2'));
            chart2.draw(data2, options);
          }
        </script>
    {/literal}
    <div style="width:1000px; margin:auto;">
        <div id="donutchart1" style="width: 450px; height: 300px; margin:auto; float:left;"></div>
        <div id="donutchart2" style="width: 450px; height: 300px; margin:auto; float:right"></div>
    </div>


	<ul style="width:70%; margin:30px auto; clear:both;">
    	<li style="padding:8px 0"><b style="color:#F00; font-size:16px;">&nbsp; *</b>  &nbsp;<b>Retail:</b> Equals the original Contract total (including promotional discounts)</li>
    	<li style="padding:8px 0"><b style="color:#F00; font-size:16px;">**</b>  &nbsp;<b>Target Price:</b> New total after MS and revision occurs. (same as “Measure Specification” in Accuterm)</li>
    </ul>
{/if}

<table class="orders_wrapper" cellspacing="0" style="margin-bottom:20px; border:0; padding: 0;">
   <tr style="border:0">
		<td style="border:0">
			<div style="padding: 20px; float: right; box-shadow: 0 0 5px #ccc; font-size: 10px;">
				<b style="color: red;">*</b> <b>Margin($)</b> = Retail - Material - Labor<br />
				<b style="color: red;">*</b> <b>Margin(%)</b> = Margin($) / Retail
			</div>
		</td>
	</tr>
</table>

{else}
	<center style="font-weight:bold; margin-top:150px; color:#666;">Ooops, you are not allowed to access this page!</center>
{/if}
</div>

