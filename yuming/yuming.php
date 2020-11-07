<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["dwz.admin"])){

	// 数据库配置
	include '../dbconfig/db_connect_config.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获得表单POST过来的数据
	$dwz_yuming = $_POST["dwz_yuming"];

	if(empty($dwz_yuming)){
		$result = array(
			"code" => "101",
			"msg" => "域名不得为空"
		);
	}else{
		// 设置插入数据库的字符编码
		mysqli_query($conn, "SET NAMES UTF-8");

		// 插入数据库
		$sql_creat = "INSERT INTO dwz_yuming (dwz_yuming) VALUES ('$dwz_yuming')";
		
		if ($conn->query($sql_creat) === TRUE) {
		    $result = array(
				"code" => "100",
				"msg" => "绑定成功"
			);
		} else {
		    $result = array(
				"code" => "103",
				"msg" => "绑定失败，数据库发生错误"
			);
		}
		
		// 断开数据库连接
		$conn->close();
	}
}else{
	$result = array(
		"code" => "104",
		"msg" => "登录失效，请重新登录"
	);
}

// 输出JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>