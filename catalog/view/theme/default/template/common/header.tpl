<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo WEBSITE_TITLE;?></title>
<base href="<?php echo LOCAL_PROD; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0;">
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<link rel="icon" href="image/edsiicon2.png" type="image/x-icon">
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.11.2.min.js"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/jquery-ui.css">
<script type="text/javascript" src="vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="vendor/metisMenu.min.js"></script>
<script type="text/javascript" src="vendor/raphael.min.js"></script>
<script type="text/javascript" src="vendor/morris.min.js"></script>
<script type="text/javascript" src="vendor/sb-admin-2.min.js"></script>
<link rel="stylesheet" type="text/css" href="vendor/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<link href="vendor/sb-admin-2.css" rel="stylesheet">
<link href="vendor/morris.css" rel="stylesheet">
<script type="text/javascript" src="catalog/view/ajax/ajax.js"></script>
<link rel="stylesheet" type="text/css" href="css/font-awesome/css/font-awesome.css" />
<script src="vendor/jquery.dataTables.min.js"></script>
<script src="vendor/dataTables.bootstrap.min.js"></script>
<script src="vendor/dataTables.responsive.js"></script>
<link rel="stylesheet" type="text/css" href="css/summernote/summernote.css" />
<script type="text/javascript" src="css/summernote/summernote.js"></script>
<script type="text/javascript" src="css/summernote/opencart.js"></script>
<script type="text/javascript" src="catalog/view/ajax/ajax.js"></script>
</head>
<body>


<div id="container-fluid">

