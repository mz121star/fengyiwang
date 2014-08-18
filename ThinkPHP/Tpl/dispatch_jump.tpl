<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 36px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}

.msgdiv{
-moz-border-radius: 40px;
-webkit-border-radius: 40px;
border-radius: 40px;
/*IE 7 AND 8 DO NOT SUPPORT BORDER RADIUS*/
-moz-box-shadow: 0px 0px 20px #6aff66;
-webkit-box-shadow: 0px 0px 20px #6aff66;
box-shadow: 0px 0px 20px #6aff66;
/*IE 7 AND 8 DO NOT SUPPORT BLUR PROPERTY OF SHADOWS*/
filter: progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = '#ff784f', endColorstr = '#c0ff82');
/*INNER ELEMENTS MUST NOT BREAK THIS ELEMENTS BOUNDARIES*/
/*Element must have a height (not auto)*/
/*All filters must be placed together*/
-ms-filter: "progid:DXImageTransform.Microsoft.gradient(GradientType = 1, startColorstr = '#ff784f', endColorstr = '#c0ff82')";
/*Element must have a height (not auto)*/
/*All filters must be placed together*/
background-image: -moz-linear-gradient(left, #ff784f, #c0ff82);
background-image: -ms-linear-gradient(left, #ff784f, #c0ff82);
background-image: -o-linear-gradient(left, #ff784f, #c0ff82);
background-image: -webkit-gradient(linear, left top, right top, from(#ff784f), to(#c0ff82));
background-image: -webkit-linear-gradient(left, #ff784f, #c0ff82);
background-image: linear-gradient(left, #ff784f, #c0ff82);
-moz-background-clip: padding;
-webkit-background-clip: padding-box;
background-clip: padding-box;
/*Use "background-clip: padding-box" when using rounded corners to avoid the gradient bleeding through the corners*/
/*--IE9 WILL PLACE THE FILTER ON TOP OF THE ROUNDED CORNERS--*/
opacity: 0.47;
-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity = 47);
/*-ms-filter must come before filter*/
filter: alpha(opacity = 47);
/*INNER ELEMENTS MUST NOT BREAK THIS ELEMENTS BOUNDARIES*/
/*All filters must be placed together*/


	width:400px;
	margin:0px auto;
	margin-top:180px;
	text-align:center;
}
</style>
</head>
<body>
<div class="system-message">
<present name="message">
<!-- <h1>:)</h1> -->
	<div class="msgdiv">
		<img src="__PUBLIC__/Imgs/right.png"/>
		<p class="success"><?php echo($message); ?></p>
		<p class="jump">
		页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
		</p>
	</div>

<else/>
	<div class="msgdiv">
		<img src="__PUBLIC__/Imgs/error.png"/>
		<p class="error"><?php echo($error); ?></p>
		<p class="jump">
		页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
		</p>
	</div>
</present>
<p class="detail"></p>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time == 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>