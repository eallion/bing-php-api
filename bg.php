<?php
	$str = file_get_contents('https://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1'); //读取必应 api
	$str = json_decode($str,true);
	$imgurl = 'https://www.bing.com'.$str['images'][0]['url'];    //获取图片 url
	header("Location: $imgurl");    //302 跳转
?>