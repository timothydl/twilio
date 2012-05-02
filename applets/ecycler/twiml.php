<?php
$apikey = AppletInstance::getValue('apikey');
$material_type = AppletInstance::getValue('mid');
$caller = normalize_phone_to_E164($_REQUEST['Caller']);
$ecycler_only = '1';
$count = '0';

switch ($material_type) {
    case 1:
        $material = "Bags of Newspapers";
        break;
    case 2:
        $material = "Bags of Aluminum Cans";
        break;
    case 3:
        $material = "Bags of PET Bottles";
        break;
    case 4:
        $material = "Glass Containers";
        break;
    case 5:
        $material = "5 cent CRV Containers";
        break;
    default:
        $material = "No materials available";
	}

$response = new Response();

if(!empty($apikey)) {


	// Put together POST call to retrieve user ID from caller ID
	$url_user = 'http://api.ecycler.com/1.1/get_phone.php';
	$fields_user = array(
	            'apikey'=>urlencode($apikey),
	            'phone_nbr'=>substr($caller, -10) //send only the 10 digits of the phone number
				);
	
	foreach($fields_user as $key=>$value) { $fields_string_user .= $key.'='.$value.'&'; }
	rtrim($fields_string_user,'&');

	//Perform the POST call
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url_user);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string_user);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$ecycler_xml = curl_exec($ch);
	curl_close($ch);

	$xml_user = new SimpleXMLElement($ecycler_xml);
	$user = intval($xml_user->{'item'}->{'userid'});

	// Put together POST to retrieve material count
	$url_mat = 'http://api.ecycler.com/1.1/material.php';
	$fields_mat = array(
	            'mid'=>$material_type,
	            'apikey'=>urlencode($apikey),
	            'userid'=>$user,
	            'ec_bool'=>$ecycler_only
				);
	
	foreach($fields_mat as $key=>$value) { $fields_string_mat .= $key.'='.$value.'&'; }
	rtrim($fields_string_mat,'&');


	//Perform the POST call
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url_mat);
	curl_setopt($ch,CURLOPT_POST,true);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string_mat);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$ecycler_xml = curl_exec($ch);
	curl_close($ch);

	$xml_mat = new SimpleXMLElement($ecycler_xml);
	$count = strval($xml_mat->{'item'}->{'material'});

	if(AppletInstance::getFlowType() == 'voice') {
		$response->addSay("Your recycling count for");
		$response->addSay($material);
		$response->addSay("is");
		$response->addSay($count);
		$next = AppletInstance::getDropZoneUrl('next');
		if(!empty($next))
			$response->addRedirect($next);
		}
	else
		$response->addSms("ecycler recycling count is ".$count);
	}
else {
	$response->addSay("ee-sy-kler A P I Key is required!");
	$next = AppletInstance::getDropZoneUrl('next');
	if(!empty($next))
		$response->addRedirect($next);
	}

$response->Respond();