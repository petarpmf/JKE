<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>
<body bgcolor="#EEEEEE" style="padding: 20px; background: #EEEEEE;">
	<table width="100%" class="header" bgcolor="#FFFFFF" style="background: #FFFFFF; max-width: 600px; padding: 0; margin: auto; padding-bottom: 0; border-spacing: 0; border: none; border-collapse: collapse;">
		<tr>
			<td class="centered" style="font-family: sans-serif; line-height: 26px; color: #666666; font-size: 14px; text-align: center;">
				<img width="100%" height="auto" src="{{env('FRONT_URL')}}/assets/images/mail-header.jpg" alt="">
			</td>
		</tr>
	</table>
	<table width="100%" bgcolor="#FFFFFF" style="background: #FFFFFF; max-width: 600px; padding: 20px; margin: auto;">
		<tr>
			<td style="font-family: sans-serif; line-height: 26px; color: #666666; font-size: 14px;">
				<h1 class="centered" style="margin: 10px 0 30px; color: #666666; font-family: sans-serif; line-height: 26px; text-align: center;">Dear {{$firstName}} {{$lastName}},</h1>
			</td>
		</tr>
		<tr>
			<td style="font-family: sans-serif; line-height: 26px; color: #666666; font-size: 14px;">
				<p style="font-family: sans-serif; line-height: 26px; color: #666666; font-size: 14px;">Please follow the <a href="{{env('FRONT_URL').env('CONFIRM_PASSWORD_PATH').'/'.$forgotToken}}" style="font-family: sans-serif; line-height: 26px; color: #26c;">link</a> to reset your password. If you did not request to change your password please ignore this message.</p>
			</td>
		</tr>
		<tr>
			<td style="font-family: sans-serif; line-height: 26px; color: #555555; font-size: 14px;">
				<p style="margin: 10px 0 30px; font-size: 16px; color: #555555;">
					<i>Thank You!</i>
				</p>
				<p style="margin: 10px 0 30px; font-size: 16px; color: #555555;">
					<i>The Joe Knows Energy Team</i>
				</p>
			</td>
		</tr>
	</table>
</body>
</html>