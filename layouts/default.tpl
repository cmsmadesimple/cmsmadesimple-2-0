<html>
<head>
<title>{$title}</title>
{javascript file="lib/silk/jquery/jquery.js"}
{javascript file="lib/silk/jquery/jquery.color.js"}
{javascript file="lib/silk/jquery/jquery.silk.js"}
</head>

<body>

<div id="messages">
	{php}echo SilkFlash::get_instance()->get('std'){/php}
</div>

{$content}

</body>

</html>