<?php
$contracts_array = [
    "consumer_rights" => "../forms/ConsumerRights.php",
    "spec_sheet" => "../forms/SpecSheet.php",
    "change_sheet" => "../forms/ChangeOrder.php",
    "custom_agrement" => "../forms/CustomRemodelingAgrement.php",
    "comp_cer" => "../forms/CompletionCertificate.php"
];
if(isset($_GET['paper']) && isset($contracts_array[$_GET['paper']]))
	$frame_src = $contracts_array[$_GET['paper']];
else
	$frame_src = end($contracts_array);
?>
<title>Digital Contracts | Remodel Direct</title>
<iframe src="<?=$frame_src?>" style="width:90%; margin:auto 5%; border:none; height:100%;"></iframe>