<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<title><?php echo $status;?></title>
	<style>
		.block {
			border : 1px solid #4c1400;
			margin : 100px auto;
			border-collapse: collapse;
		}
		.header {
			background : #4c1400;
			color : #ffffff;
			font : bold 16px Tahoma;
			text-align : center;
		}
		.title {
			color : #333333;
			font : bold 14px Tahoma;
		}
		.message {
			color : #333333;
			font : 11px Tahoma;
		}
		.footer {
			background : #4c1400;
			color : #ffffff;
			font : bold 11px Tahoma;
			text-align : right;
		}
	</style>
</head>
<body>
<table border="0" cellpadding="10" cellspacing="1" width="300" align="center" class="block asd">
<tr>
	<td class="header"><?php echo $status;?></td>
</tr>
<tr>
	<td class="title"><?php echo $title;?></td>
</tr>
<tr>
	<td class="message"><?php echo $message;?></td>
</tr>
<tr>
	<td class="footer"><?php echo $footer;?></td>
</tr>
</table>
</body>
</html>