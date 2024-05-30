<footer>
	<div class="copy">Copyright @ {"Y"|date} | All rights reserved.</div>
	<div class="credit"><a onclick="$('#vupdate_container').show();" style="cursor:pointer;">Version {$app_version}</a> | Powered by <a href="http://www.nathanresearch.com">Nathan Research Inc.</a></div>
</footer>

<table id="vupdate_container">
<tr><td>
	<div style="background:#efefef; width:60%; min-width:600px; height:auto; margin:10% auto; box-shadow:0 0 20px #000; overflow: hidden;">
		<h3 style="border-bottom:dashed 1px #999; padding:10px 10px 10px 30px;">What's new in this version <a  id="guide_link" target="_blank" href="../notes/Guide - Version {$app_version}.pdf">see v{$app_version} user guide</a> <a id="vu_close" onclick="$('#vupdate_container').hide();">X</a></h3>
		<ol id="update_listing">
{*
			<li><span class="relnote_subitem"></span> &nbsp; The dealers have an ability to see the margin percentage</li>
			<li><span class="relnote_subitem"></span> &nbsp; Line items comment should show up in the material order tab</li>
            <li><span class="relnote_subitem"></span> &nbsp; Misc. items has “Others” as one of the category option</li>
*}
			<li><span class="relnote_subitem"></span> &nbsp; Added ability to acknowledge dealers to confirm MO received</li>
			<li><span class="relnote_subitem"></span> &nbsp; Added email tamplate for acknowledgement process which can be able to customize the email content</li>
			<li><span class="relnote_subitem"></span> &nbsp; Create an ability to assign the material order item from one vendor to another</li>
			<li><span class="relnote_subitem"></span> &nbsp; Capture all vendor changes into the revision logs</li>
			<li><span class="relnote_subitem"></span> &nbsp; Modify vendors address fields into address, city, state and zipcode</li>
		</ol>
		&nbsp;<br />&nbsp;
	</div>
</td></tr>
</table>


<style>
#vupdate_container{
	width:100%;
	min-width:950px;
	height:100%;
	background: rgba(0, 0, 0, 0.5);
	top:0;
	position:fixed;
	display: none;
}
a#guide_link{
	font-size:12px;
	color:#F88421;
}
a#guide_link:before{
	content:" [ ";
	font-weight:bold;
}
a#guide_link:after{
	content:" ]";
	font-weight:bold;
}

#vu_close{
	font-weight: bold;
	float:right;
	cursor: pointer;
	margin-right:10px;
}
#update_listing li{
	padding: 10px 30px;
	text-align: justify;
}
.relnote_subitem:before{
	content:"[#]";
	font-weight:bold;
	color:#F88421;
}
</style>