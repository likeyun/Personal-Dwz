<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="color-scheme" content="light dark">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<style type="text/css">
		*{
			margin:0;
			padding:0;
		}
		#topyd{
			width: 100%;
			margin:0 auto;
			position: fixed;
			top: 0;
		}
		#topyd img{
			max-width: 100%;
		}

		#centeryd{
			width: 320px;
			margin:180px auto 0;
		}

		#centeryd img{
			max-width: 320px;
		}

		#bottomyd{
			width: 320px;
			margin:30px auto 0;
		}

		#bottomyd p{
			text-align: center;
			font-size: 18px;
			color: #174ded;
			font-weight: bold;
		}
	</style>
</head>
<?php
header("Content-Type:text/html;charset=utf-8");
//获得当前传过来的dwzkey
$dwzkey = $_GET["id"];
//过滤数据
if (trim(empty($dwzkey))) {
	echo "链接不存在";
}else{

	// 数据库配置
	include './dbconfig/db_connect_config.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 检测连接
	if ($conn->connect_error) {
		echo "数据库连接失败，原因：".$conn->connect_error;
	} else {
		//检查数据库是否存在该dwzkey
		$checkKey = "SELECT * FROM dwz_list WHERE dwz_key = '$dwzkey'";
		$result_checkKey = $conn->query($checkKey);
		if ($result_checkKey->num_rows > 0) {
			//如果存在，则解析出长链接并跳转
			while($row_checkKey = $result_checkKey->fetch_assoc()) {

				// 遍历相关字段
				$dwz_long_url = $row_checkKey["dwz_long_url"];
				$dwz_status = $row_checkKey["dwz_status"];
				$dwz_fh = $row_checkKey["dwz_fh"];
				$dwz_pageview = $row_checkKey["dwz_pageview"];
				
				// 验证短网址状态
				if ($dwz_status == 1) {
					// 验证短网址防红状态
					if ($dwz_fh == 1) {
						// 开启防红
						echo '<title>在浏览器打开</title>';
						echo '<!-- 顶部引导 -->
						<div id="topyd"></div>

						<!-- 中部引导 -->
						<div id="centeryd">
							<img src="iosydt.jpg">
						</div>

						<!-- 底部引导 -->
						<div id="bottomyd">
							<p>本站不支持在微信打开</p>
							<p>请在浏览器打开访问</p>
						</div>';
					}else{
						// 不开启防红
						header("Location:$dwz_long_url");
					}
				}else{
					echo "<div style='width:150px;margin:50px auto 10px;'><img src='./images/pause.png' width='150'/></div>";
		    		echo "<p style='text-align:center;'><b>该链接已被管理员暂停使用</b></p>";
				}
			}
		}else{
			// 链接不存在
			echo "<div style='width:150px;margin:50px auto 10px;'><img src='./images/notfound.png' width='150'/></div>";
		    echo "<p style='text-align:center;'><b>该链接不存在或已被删除</b></p>";
		}
	}
}
?>
<!-- 判断浏览器 -->
<script>
	var ua = navigator.userAgent.toLowerCase();
    var isWeixin = ua.indexOf('micromessenger') != -1;
    var isAndroid = ua.indexOf('android') != -1;
    var isIos = (ua.indexOf('iphone') != -1) || (ua.indexOf('ipad') != -1);

    // 判断是不是在微信客户端打开
	if(isWeixin) {  
	    // 判断是在Android的微信客户端还是Ios的微信客户端
	    if (isAndroid) {
	    	// 是在Android的微信客户端
	    	$("#topyd").html("<img src='./browser/android.jpg'/>");
	    	$("#centeryd").html("<img src='./browser/androidydt.jpg'/>");
	    }else if (isIos) {
	    	// 是在Ios的微信客户端
	    	$("#topyd").html("<img src='./browser/ios.jpg'/>");
	    	$("#centeryd").html("<img src='./browser/iosydt.jpg'/>");
	    }else{
	    	// 未知设备系统，默认使用安卓的引导方式
	    	$("#topyd").html("<img src='./browser/android.jpg'/>");
	    	$("#centeryd").html("<img src='./browser/androidydt.jpg'/>");
	    }
	} else { 
		var dwz_status = '<?php echo $dwz_status; ?>';
		if (dwz_status == 1) {
			// 不是微信客户端，直接可以访问链接
	    	location.href="<?php echo $dwz_long_url; ?>";
		}else{
			// 暂停使用
		}
	    
	}
</script>