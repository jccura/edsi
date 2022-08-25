<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<title><?php echo WEBSITE_TITLE;?></title> 
<base href="<?php echo LOCAL_PROD; ?>" /> 
<meta name="viewport" content="width=device-width, initial-scale=1.0;">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
<link rel="stylesheet" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="printstylesheet.css" />
</head>
<body style="font-family: Yu Gothic UI, Geneva, sans-serif;" align="center">
<div id="container">
	<div style="align-content: center;">
		<br>
		<table style="border-top:2px solid black; border-bottom:2px solid black; border-right:2px solid black; border-left:2px solid black;  background: linear-gradient(#89f1f5, #315657); padding:0; " height="324px"width="205px;" >
			<tbody>
				<tr>
					<td align="center">
						<span style="font-family: Segoe UI, Geneva, sans-serif; color:#000000; font-weight:bold; font-size:10px; ">ESTHETIQUE DIRECT SALES INC.</span><br>
							<img src="image/4.png" style="width:40px; height:40px; margin:0; padding:0;"/>
					</td>
				</tr>
				<tr>
					<td align="center">
						<img src="profiles/<?php if($this->user->getExtension() != "") {?><?php echo $this->user->getId();?>.<?php echo $this->user->getExtension();?><?php }else{ ?>default.jpg<?php }?>" style="border:2px solid black; border-radius:50%; height:90px; width:90px;"/>
					</td>
				</tr>
				<tr>
					<td align="center">
						<span style="font-family: Segoe UI, Geneva, sans-serif; color:#fff; font-size:14px; "><?php echo $this->user->getName();?></span><br>
						<span style="font-family: Segoe UI, Geneva, sans-serif; color:#fff; font-size:9px;"><?php echo $this->user->getUserGroup();?></span>
					</td>
				</tr>
				<tr>
					<td align="center" rowspan="1">
						<img src="https://api.qrserver.com/v1/create-qr-code/?data=<?php echo $this->user->getIdNo(); ?>&size=50x50&margin=0"><br>
						<span style="font-weight: bold; font-size: 9px; color:#7CBEBD;">ID NO. <?php echo $this->user->getIdNo();?></span>
						<p style="font-family: Segoe UI, Geneva, sans-serif; color:#7CBEBD ; line-height: normal; font-size:7px; line-heigth:1px;">Head Office Address:
						<br>Door #3	G.A. Esteban Building Lacson Street Bacolod City</p>
						<!--<p style="font-family: Segoe UI, Geneva, sans-serif; color:#fff; font-size:5px;">Mindanao Branch:
						<br>Room 308,Mendoza Building, Pilar Street, Zone IV, Zamboanga City, Zamboanga Del Sur</p>-->
					</td>
				</tr>
				<tr>
					<td align="center">
						<p style=" font-family: Segoe UI, Geneva, sans-serif; color:#7CBEBD; font-size:6px;">esthetiquedirectsales.com</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
</body>
</html>
