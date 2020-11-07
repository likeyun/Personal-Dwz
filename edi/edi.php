<?php
header("Content-type:application/json");
session_start();
if(isset($_SESSION["dwz.admin"])){

	// 数据库配置
	include '../dbconfig/db_connect_config.php';

	// 创建连接
	$conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

	// 获得表单POST过来的数据
	$dwz_long_url = $_POST["dwz_long_url"];
	$dwz_biaoqian = $_POST["dwz_biaoqian"];
	$dwz_yuming = $_POST["dwz_yuming"];
	$dwz_status = $_POST["dwz_status"];
	$dwz_fh = $_POST["dwz_fh"];
	$dwz_id = $_POST["dwz_id"];

	// 用explode进行判断url是否合法
	$url_array = explode('http',$dwz_long_url);

	if(empty($dwz_long_url)){
		$result = array(
			"code" => "101",
			"msg" => "长链接不得为空"
		);
	}else if(empty($dwz_biaoqian)){
		$result = array(
			"code" => "102",
			"msg" => "标签不得为空"
		);
	}else if(empty($dwz_yuming)){
		$result = array(
			"code" => "103",
			"msg" => "请选择域名"
		);
	}else if(empty($dwz_status)){
		$result = array(
			"code" => "104",
			"msg" => "请选择启用状态"
		);
	}else if(empty($dwz_fh)){
		$result = array(
			"code" => "105",
			"msg" => "请选择防红状态"
		);
	}else if(count($url_array)>1){
		// 更新短网址
		mysqli_query($conn,"UPDATE dwz_list SET dwz_long_url='$dwz_long_url',dwz_biaoqian='$dwz_biaoqian',dwz_status='$dwz_status',dwz_fh='$dwz_fh',dwz_yuming='$dwz_yuming' WHERE dwz_id='$dwz_id'");
		$result = array(
			"code" => "100",
			"msg" => "更新成功"
		);

	}else{
		$result = array(
			"code" => "106",
			"msg" => "你的长链接不标准"
		);
	}
}else{
	$result = array(
		"code" => "107",
		"msg" => "登录失效，请重新登录"
	);
}

// 输出JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>