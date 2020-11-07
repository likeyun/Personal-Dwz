<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["dwz.admin"])){

	// 数据库配置
	include '../dbconfig/db_connect_config.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获取域名id
	$ymid = $_GET["ymid"];

	if(empty($ymid)){
		$result = array(
			"code" => "101",
			"msg" => "非法请求"
		);
	}else{
		// 删除数据
		mysqli_query($conn,"DELETE FROM dwz_yuming WHERE id=".$ymid);
		$result = array(
			"code" => "100",
			"msg" => "已删除"
		);
	}
}else{
	$result = array(
		"code" => "102",
		"msg" => "未登录"
	);
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>