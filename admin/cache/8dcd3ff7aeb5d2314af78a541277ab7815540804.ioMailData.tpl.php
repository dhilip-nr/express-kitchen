<?php /*%%SmartyHeaderCode:3178155eaecae012448-56726609%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8dcd3ff7aeb5d2314af78a541277ab7815540804' => 
    array (
      0 => '.\\templates\\ioMailData.tpl',
      1 => 1435861046,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3178155eaecae012448-56726609',
  'variables' => 
  array (
    'order_result' => 0,
    'products_result' => 0,
    'value' => 0,
    'pricing_model' => 0,
    'order_subitems_count' => 0,
    'manfacturer_dup' => 0,
    'lineitem_rows' => 0,
    'i' => 0,
    'product_sku' => 0,
    'catoptions' => 0,
    'catopt' => 0,
    'matches' => 0,
    'uom_except_arr' => 0,
    'mat_qty' => 0,
    'material_lineitems' => 0,
    'value3' => 0,
    'head' => 0,
    'field_sf' => 0,
    'accent_lf' => 0,
    'bulnose_lf' => 0,
    'wastage' => 0,
    'msi_slf' => 0,
    'submat_qty' => 0,
    'icp_num' => 0,
    'ocp_num' => 0,
    'seam_num' => 0,
    'linealfeet' => 0,
    'subitem_qty' => 0,
    'submat_uom' => 0,
    'mat_table' => 0,
    'qty_of_uom' => 0,
    'install_total' => 0,
    'installer_grandtotal' => 0,
    'order_subquery' => 0,
    'value2' => 0,
    'labqty_of_uom' => 0,
    'j' => 0,
    'lt_lfwp_amount' => 0,
    'misc_count' => 0,
    'misc_result' => 0,
    'key' => 0,
    'damage_installer_result' => 0,
    'damage_count' => 0,
    'damage_ct' => 0,
    'valued' => 0,
    'promo_amount' => 0,
    'permit_amount' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.13',
  'unifunc' => 'content_55eaecae586ee5_94831133',
  'cache_lifetime' => 0,
),true); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55eaecae586ee5_94831133')) {function content_55eaecae586ee5_94831133($_smarty_tpl) {?>			                			
			<table cellpadding="5" cellspacing="0"  style="width:100%; border-collapse:collapse; border:solid 1px #ccc; min-width:650px;">
		<tr style="border:solid 1px #ccc;">
			<td colspan="7" style="padding:10px;">
				ORDER ID : <b>BRO1001</b>
                <span style="float:right;" id="jobid">JOB ID : <b>test123 </b></span>
			</td>
		</tr>
		        
		<tr style="border:solid 1px #ccc;">
		<td colspan="7" style="padding:10px;">
					<table cellpadding="5" style="width:45%; float:left; border-collapse:collapse; border:solid 1px #ccc;">
						<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
                                    <td colspan="2">
                                        <b>Customer Details</b>
                                    </td>
                                </tr>
								<tr style="border:dashed 1px #ccc;">
									<td width="25%">
										<b>Name</b>

									</td>
									<td width="75%">
										test test
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td>
										<b>Email</b>
									</td>
									<td>
										test@test.com
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td>
										<b>Phone</b>
									</td>
									<td>
										(111) 111-1111
									</td>
								</tr>
								<tr style="border:dashed 1px #ccc;">
									<td valign="top">
										<b>Address</b>
									</td>
									<td>
										test, test, test test
									</td>
								</tr>
					</table>
					<table cellpadding="5" style="width:45%; float:right; border-collapse:collapse; border:solid 1px #ccc;">
						<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
							<td colspan="2">
								<b>Installer Details</b>
							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td width="25%">
								<b>Company</b>
							</td>
							<td width="75%">Greencraft Interiors LV</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Name</b>
							</td>
							<td>
                            	Greg Mohl
                                                            </td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Email</b>
							</td>
							<td>
								HDI@greencraftinteriors.com																							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Phone</b>
							</td>
							<td>
								480-222-2958								<b style="color:#F68423">/</b> 520-241-1834							</td>
						</tr>
						<tr style="border:dashed 1px #ccc;">
							<td>
								<b>Address</b>
							</td>
							<td>
								8125 E. Indian Bend Road, Suite 105,
   								Scottsdale,
								AZ								85250							</td>
						</tr>
					</table>
		</td>
		</tr>
		<tr bgcolor="#F0F0F0" style="border:solid 1px #ccc;">
			<td width="3%" align="center"><b>#</b></td>
			<td width="17%"><b>Model #</b></td>
			<td width="55%" colspan="2"><b>Description</b></td>
			<td width="8%" align="center"><b>UOM</b></td>
			<td width="7%" align="center"><b>Qty</b></td>
			<td width="10%" align="center" class="rm_price"><b>Labor Cost</b></td>
		</tr>
		                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>American Standard</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="3" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="3" style="border:solid 1px #ddd;">
				3052.609.CLL 			</td>
                        
		


			<td  colspan="5" >
				
                                    Combo Gelcoat Walk-In Tub (Left Drain) 52" W x 30" D - Linen w/Chrome Fixtures (w/ quick drain, extension kit, faucet, neck rest and inline heater)
                                					                        
                                                    <br/> WIDTH: 52
                                
                                
                                                                                                    
                                                    <br/> DEPTH: 30
                                
                                
                                                                                                    
                                                    <br/> DRAIN: Left
                                
                                
                                                                                                    
                                                    <br/> COLOR: Linen
                                
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">3052.609.CLL</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Demo - D3 - Demo shower pan, tub or one piece tub/shower unit (cultured marble, figerglass, steel, cast iron) including shower door, shower rod  and tub/shower trim.  Includes tub/shower trim removal and disconnecting existing plumbing. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 230.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Plumbing - P11 - Install WALK IN TUB ONLY.  Includes installation of provided faucet and handshower, sub floor repair and connection to existing electrical outlet. A dedicated electrical line for the in line heater .All material included except fixtures. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 1,000.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            			
					                                                            	                        
							<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="3" style="border:solid 1px #ddd;">
				2
			</td>
			<td rowspan="3" style="border:solid 1px #ddd;">
				3052.609.CLL 			</td>
                        
		


			<td  colspan="5" >
				
                                    Combo Gelcoat Walk-In Tub (Left Drain) 52" W x 30" D - Linen w/Chrome Fixtures (w/ quick drain, extension kit, faucet, neck rest and inline heater)
                                					                        
                                                    <br/> WIDTH: 52
                                
                                
                                                                                                    
                                                    <br/> DEPTH: 30
                                
                                
                                                                                                    
                                                    <br/> DRAIN: Left
                                
                                
                                                                                                    
                                                    <br/> COLOR: Linen
                                
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">3052.609.CLL</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Demo - D3 - Demo shower pan, tub or one piece tub/shower unit (cultured marble, figerglass, steel, cast iron) including shower door, shower rod  and tub/shower trim.  Includes tub/shower trim removal and disconnecting existing plumbing. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 230.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Plumbing - P11 - Install WALK IN TUB ONLY.  Includes installation of provided faucet and handshower, sub floor repair and connection to existing electrical outlet. A dedicated electrical line for the in line heater .All material included except fixtures. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 1,000.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>BCI</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				BL-5.5-FSCL-A-HD 			</td>
                        
		


			<td  colspan="5" >
				
                                    Acrylic Tub Liner Classic Front 5.5 Foot - Almond
                                					                        
                                                    <br/> LENGTH: 5.5 Foot
                                
                                
                                                                                                    
                                                    <br/> DRAIN & OVERFLOW FINISH: Almond
                                
                                
                                                                                                    
                                                    <br/> COLOR: Almond
                                
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">BL-5.5-FSCL-A-HD</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">OF-A</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">DR-305-A</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Acrylic Products - A3 - Install acrylic liner: Overlay the existing tub or shower pan. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 350.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>Bertch</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="3" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="3" style="border:solid 1px #ddd;">
				6018-66-DB2-Don-P4 			</td>
                        
		


			<td  colspan="5" >
				
                                    Vanity Cabinet Donovan 60"-66" W x 18" D x 31" H - 4 Doors w/3 Middle Drawers (Double Bowl)
                                					                        
                                                    <br/> CONFIGURATION: 4 Doors w/3 Middle Drawers (Double Bowl)
                                
                                
                                                                                                    
                                                    <br/> WIDTH: 60-66
                                
                                
                                                                                                    
                                                    <br/> DEPTH: 18
                                
                                
                                                                                                    
                                                    <br/> HEIGHT: 31
                                
                                
                                                                                                    
                                                    <br/> WOOD SPECIES: Cherry
                                
                                
                                                                                                    
                                                    <br/> FINISH: Rose
                                
                                
                                                                                                    
                                                    <br/> HARDWARE: SN-K11
                                
                                
                                                                                                    
                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">6018-66-DB2</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Demo - D1 - Demo vanity cabinet and top.  Includes sink/faucet removal and disconnecting existing plumbing.  COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 250.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Vanity - VC1 - Install cabinet.  Includes all trim, fillers and pulls.  COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 48.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>HDI Collection</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				CTG-DV-Amber Gardens 			</td>
                        
					
	


			<td  colspan="5" >
				
                                    Granite Counter Top - Amber Gardens
                                					                        
                                                    <br/> EDGE: Bevel
                                
                                
                                                                                                    
                                                    <br/> DOUBLE VANITY: Yes
                                
                                
                                                                                                    
                                                    <br/> EDGE LINEAL FEET: 10
                                
                                
                                                                                                    
                                                    <br/> COLOR: Amber Gardens
                                
                                
                                                                                                    
                                                                                                <span style="float:right; font-weight:normal;">[ UOM:SF, QTY: 18 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Vanity Tops - VT1 - Install one pre-fabricated vanity top of any product 48" or less (integral backsplash) with one or two undermount lavatory bowl(s).  Includes sink hook-up with new supply lines and stops.  All material included except fixtures.  COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 240.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                    			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>Hermitage Lighting</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="3" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="3" style="border:solid 1px #ddd;">
				300019AP 			</td>
                        
		


			<td  colspan="5" >
				
                                    Replacement-Bellamy 5 Blade 60" Fan (2-50W T3 lights) - Antique Pewter
                                					                        
                                                    <br/> STYLE: Replacement
                                
                                
                                                                                                    
                                                                    
                                                    <br/> COLOR: Antique Pewter
                                
                                
                                                                                                    
                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">300019AP</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Demo - D4 - Demo electrical fixtures (lights, ceiling fan, exhaust fans, etc.).  Repair drywall to paint ready condition (no paint).  COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 40.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Electrical - E8 - Electrical-Existing Location:  Install ceiling fan.  All material included except fan. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 80.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>Installer</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				ADNL-C-5 			</td>
                        
		


			<td  colspan="5" >
				
                                    Install Recessed Medicine Cabinet
(Add for Tri-View Mirror in Existing Location when recessed is requested)
                                                                    <span style="float:right; font-weight:normal;">[ UOM:, QTY: 1 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Bath Accessories - BA3 - Install recessed medicine cabinet. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 44.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        			
					                                                            	                        
							<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				2
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				ADNL-C-7 			</td>
                        
					
	


			<td  colspan="5" >
				
                                    Sheetrock
(Hang, finish and sand to paint ready condition; includes material)
                                                                    <span style="float:right; font-weight:normal;">[ UOM:, QTY: 10 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                            
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Carpentry - C1 - Hang, finish and sand sheetrock to paint ready condition (no paint).  All material included. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  SF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 10.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 35.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>MSI</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="4" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="4" style="border:solid 1px #ddd;">
				FCS D1: TTCLASLT66T 			</td>
                        
					
	


			<td  colspan="5" >
				
                                    Flooring (Concrete Subfloor): Design 1 - Tuscany Tumbled Travertine 6x6 - Classic
                                					                        
                                                    <br/> DESIGN: 6x6
                                
                                
                                                                                                    
                                                    <br/> COLOR: Classic
                                
                                
                                                                                                    
                                                                                                <span style="float:right; font-weight:normal;">[ UOM:SF, QTY: 10 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	        
	    	
            
        <td style="width:45%">TTCLASLT66T</td>
        <td align="center" style="width:25%">UOM: SF</td>
        <td align="center" style="width:30%">Quantity: 12</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	        
	    	
            
        <td style="width:45%">#382 Bone Sanded Grout</td>
        <td align="center" style="width:25%">UOM: Bag</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Tile - T4 - Upcharge for natural stone tile.  Tile and grout sealing is included. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  SF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 10.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 33.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Tile - T3 - Install ceramic/porcelain tile on concrete subfloor surface (including subfloor prep).  All material included except tile and grout.  Grout sealing is included. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  SF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 10.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 80.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Demo - D6 - Demo any existing wall or floor surface including sheetrock, tile, lathe, wood, carpet, vinyl, cultured marble, slab product, etc. (includes wood trim such as chair rail, quarter round and/or wall base) </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  SF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 10.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 40.00 &nbsp;</td>
                            </tr> 
                                                                                                                			
					                                                            	                        
							<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="3" style="border:solid 1px #ddd;">
				2
			</td>
			<td rowspan="3" style="border:solid 1px #ddd;">
				D10: 2x2-MX (2x2 CM) 			</td>
                        
				


			<td  colspan="5" >
				
                                    Wall Surround: Design 10 - Venice Glazed Porcelain Mosaic 2x2 - Mixed (Accent: Venice Glazed Porcelain Mosaic 2x2 - Crema)
                                					                        
                                                    <br/> FIELD TILE: 2x2
                                
                                
                                                                                                    
                                                    <br/> FIELD SQ.FT: 20
                                                                                                    
                                
                                                                                                    
                                                    <br/> ACCENTS: Glazed Porcelain Mosaic 2x2 - Crema
                                
                                
                                                                                                    
                                                    <br/> ACCENT LN.FT: 15
                                                                                                    
                                
                                                                                                    
                                                    <br/> COLOR: Mixed
                                
                                
                                                                                                    
                                                    <br/> BULLNOSE LN. FEET: 8
                                                                                                    
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:SF, QTY: 35 ]</span>
                                                                									                					                					                					                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	                    
                            	
            
        <td style="width:45%">NVENCREMA2X2</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 105</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	                    
                            	
            
        <td style="width:45%">NVENMIXED2X2</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 21</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	                      
                            	
            
        <td style="width:45%">NVENCREMA3X13BN</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 3</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

	        
	    	
            
        <td style="width:45%">#382 Bone Sanded Grout</td>
        <td align="center" style="width:25%">UOM: Bag</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Tile - T1 - Install ceramic/porcelain tile on wall surface.  All material included except tile and grout.  Grout sealing is included. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  SF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 35.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 525.00 &nbsp;</td>
                            </tr> 
                                                                                                        
                                                <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Tile - T5 - Install ceramic/porcelain/natural stone factory bullnose, listello, rope, half round, cove, trim or deco strip.  Grout sealing is included. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  LF  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 8.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 32.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                                                            			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>YOW (Danze)</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				D225521 			</td>
                        
		


			<td  colspan="5" >
				
                                    Antioch Single Handle Centerset 50/50TD - Chrome
                                					                        
                                                    <br/> COLOR: CP
                                
                                
                                                                                                    
                                                    <br/> # HANDLES: 1
                                
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">D225521</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Plumbing - P12 - Install LAVATORY FAUCETS.  Includes new supply lines and stops.  All material included except fixtures.   COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 150.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                                                                			
					                                                            	                        
							<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				2
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				D306957T 			</td>
                        
		


			<td  colspan="5" >
				
                                    Opulence Roman Tub (incl. RI Valve) - Chrome
                                					                        
                                                                    
                                                    <br/> COLOR: CP
                                
                                
                                                                                                    
                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">D215000BT</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">D306957T</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Plumbing - P15 - Install a ROMAN TUB VALVE and anti-scald valve.  All material (including anti-scald valve) included except fixtures. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 75.00 &nbsp;</td>
                            </tr> 
                                                                                                                                                                                                                                                    			
					                                                            	                        
								<tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
			<td colspan="7">
				<b>YOW (Gerber)</b>
			</td>
		</tr>
						<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="2" style="border:solid 1px #ddd;">
				1
			</td>
			<td rowspan="2" style="border:solid 1px #ddd;">
				HE-20-004-09 			</td>
                        
		


			<td  colspan="5" >
				
                                    Allerton Elongated Standard Height Toilet (1.28 GPF) - Biscuit
                                					                        
                                                    <br/> HEIGHT: Standard
                                
                                
                                                                                                    
                                                    <br/> COLOR: Biscuit
                                
                                
                                                                                                                                <span style="float:right; font-weight:normal;">[ UOM:EA, QTY: 1 ]</span>
                                                                									                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">HE-20-004-09</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					
                    	                                    
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">G0099213-09</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                 
                                    </table>
                
                                
                        </td>
            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
                            <tr style="border:solid 1px #ddd;">	
                <td style="font-weight:bold;" colspan="2">Plumbing - P16 - Remove and Install Toilet, Bidet or Pedestal sink.  Includes wax ring, supply lines and stops.  All material included except fixtures and faucets.   COUNT EACH ONE. </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center">  EA  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00  </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 143.00 &nbsp;</td>
                            </tr> 
                                                                    			
					                                                            
							<tr style="border:dashed 1px #ddd;">
			<td align="center" rowspan="1" style="border:solid 1px #ddd;">
				2
			</td>
			<td rowspan="1" style="border:solid 1px #ddd;">
				12-760-09 			</td>
                        
		


			<td  colspan="2" >
				
                					<b>Logan Square Rectangle Undercounter Lavatory (20 3/4" W x 17 3/8" D) - Biscuit</b>                          
                                					                        
                                                    <br/> WIDTH: 20 3/4"
                                
                                
                                                                                                    
                                                    <br/> DEPTH: 17 3/8"
                                
                                
                                                                                                    
                                                    <br/> COLOR: Biscuit
                                
                                
                                                                                                                                                            									
                    	            
							<table width="100%" cellpadding="5" style="margin-top:5px; float:left; border-collapse:collapse; border:dashed 1px #ccc;">
                            <tr bgcolor="#F0F0F0" style="border:dashed 1px #ccc;">
                                <td colspan="3">Material Details</td>
                            </tr>
			                                             
                                                            
                        <tr style="border:dashed 1px #ccc;">	

<!--
For Material Material Fix for Qty, UOM - Starts
-->
	

    
            
        <td style="width:45%">12-760-09</td>
        <td align="center" style="width:25%">UOM: EA</td>
        <td align="center" style="width:30%">Quantity: 1</td>
    
<!--
For Material Material Fix for Qty, UOM - Ends
-->                        </tr>                      
                	                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                					                 
                                    </table>
                
                                
                        </td>
                            <td align="center" style="border:solid 1px #ddd;"><b>EA</b></td>
                <td align="center" style="border:solid 1px #ddd;"><b>1.00</b></td>
                <td align="right"><b>$ 0.00</b> &nbsp;</td>
				            

                </tr>

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            			
					

		<!-- LeadTest and LFWP fee - starts -->
		            <tr bgcolor="#f9f9f9" style="border:solid 1px #ddd;">
                <td colspan="7"><b>Lead Fee</b></td>
            </tr>
						            <tr style="border:solid 1px #ddd;">	
                <td align="center" style="border:solid 1px #ddd;">2</td>
                <td style="font-weight:bold;" align="left" colspan="3">Lead Free Work Practice</td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> EA </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="center"> 1.00 </td>
                <td style="font-weight:bold; border-left:solid 1px #ddd;" align="right">$ 500.00 &nbsp;</td>
            </tr>   
			                    		<!-- LeadTest and LFWP fee - ends -->

		<!-- Misc item display starts -->
					<tr bgcolor="#F0F0F0">
				<td colspan="7"><b>Miscellaneous items</b></td>
			</tr>
                        <tr>
                <td align="center" style="border:solid 1px #ddd;">1</td>
                <td style="border:solid 1px #ddd;">test</td>
                <td colspan="2" style="border:solid 1px #ddd;"><b>MSI</b> - test2</td>
                <td align="center" style="border:solid 1px #ddd;">SF</td>
                <td align="center" style="border:solid 1px #ddd;">10</td>
                <td align="right" style="border:solid 1px #ddd;">$20.00 &nbsp;</td>
            </tr>
			                        <tr>
                <td align="center" style="border:solid 1px #ddd;">2</td>
                <td style="border:solid 1px #ddd;">test</td>
                <td colspan="2" style="border:solid 1px #ddd;"><b>AS</b> - test misc header</td>
                <td align="center" style="border:solid 1px #ddd;">EA</td>
                <td align="center" style="border:solid 1px #ddd;">1</td>
                <td align="right" style="border:solid 1px #ddd;">$10.00 &nbsp;</td>
            </tr>
			            	
				<!-- Misc item display ends -->

	<!-- Installer Damaged item display - starts -->	
        
        
                
	<!-- Installer Damaged item display - ends -->

        <tr>
            <td colspan="6" align="right" style="border: solid 1px #ccc; border-right:0;"><b>Permit &nbsp;:</b></td>
            <td style="border: solid 1px #ccc; border-left:0; border-right:0;" align="right">
				                                  $ 600.00 &nbsp;
			</td>
        </tr>

        <tr bgcolor="#f0f0f0" style="border:solid 1px #ccc; height:45px;">
            <td colspan="6" align="right"><b>TOTAL COST &nbsp;: </b></td>
            <td align="right"><b>$ 5,755.00</b> &nbsp;</td>
        </tr>    
	</table>

	        <div style="border:#ccc solid 1px; text-align:justify;">
            <b align="center" style="display:block; text-align:center; margin-bottom:10px">Instructions / Comments</b>
            test
        </div>
    <?php }} ?>