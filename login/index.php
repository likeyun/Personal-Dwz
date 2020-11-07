<?php
header("Content-type:application/json");

// 数据库配置
include '../dbconfig/db_connect_config.php';

$adminuser = trim($_POST["admin_user"]);
$adminpsw = trim($_POST["admin_pwd"]);

if($adminuser == "" && $adminpsw == ""){
	$result = array(
		"code" => "101",
		"msg" => "账号和密码不得为空"
	);
}else if($adminuser == ""){
	$result = array(
		"code" => "102",
		"msg" => "账号不得为空"
	);
}else if ($adminpsw == "") {
	$result = array(
		"code" => "103",
		"msg" => "密码不得为空"
	);
}else{
	
	// 创建数据库连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
	
	// 验证账号和密码
	$sql_checkuser = "SELECT * FROM dwz_admin WHERE admin_user = '$adminuser' AND admin_pwd = '$adminpsw'";
	$result_checkuser = $conn->query($sql_checkuser);
	
	if ($result_checkuser->num_rows > 0) {
		// 注册session
		session_start();
		$_SESSION['dwz.admin'] = $adminuser;
	    $result = array(
			"code" => "100",
			"msg" => "登录成功"
		);
	} else {
	    $result = array(
			"code" => "104",
			"msg" => "用户名或密码错误"
		);
	}

    // 断开数据库连接
	$conn->close();
}
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>