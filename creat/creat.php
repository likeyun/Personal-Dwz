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

		// 验证当前长链接是否已经生成过了
		$checkUrl = "SELECT dwz_key FROM dwz_list WHERE dwz_long_url = '$dwz_long_url'";
		$result_checkUrl = $conn->query($checkUrl);

		if ($result_checkUrl->num_rows > 0) {
			// 获得当前url下的dwz_key
			while($row_checkUrl = $result_checkUrl->fetch_assoc()) {
				$dwz_key = $row_checkUrl["dwz_key"];
			}

			// 返回数据库的dwz_key
			$result = array(
				"code" => "109",
				"msg" => "此链接之前已经生成",
				"dwz" => $dwz_yuming."/".$dwz_key
			);
		}else{
			// 设置插入数据库的字符编码
			mysqli_query($conn, "SET NAMES UTF-8");

			// 创建短网址id
			$dwz_id = rand(100000,999999);

			//生成5位数的dwzkey
			$key_str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
			$dwz_key = substr(str_shuffle($key_str),mt_rand(0,strlen($key_str)-11),5);

			// 插入数据库
			$sql_creat = "INSERT INTO dwz_list (dwz_id, dwz_key, dwz_biaoqian, dwz_long_url, dwz_status, dwz_fh, dwz_yuming) VALUES ('$dwz_id', '$dwz_key', '$dwz_biaoqian', '$dwz_long_url', '$dwz_status', '$dwz_fh', '$dwz_yuming')";
			
			if ($conn->query($sql_creat) === TRUE) {
			    $result = array(
					"code" => "100",
					"msg" => "创建成功",
					"dwz" => $dwz_yuming."/".$dwz_key
				);
			} else {
			    $result = array(
					"code" => "107",
					"msg" => "创建失败，数据库发生错误"
				);
			}
			
			// 断开数据库连接
			$conn->close();
		}
		
	}else{
		$result = array(
			"code" => "106",
			"msg" => "你的长链接不标准"
		);
	}
}else{
	$result = array(
		"code" => "108",
		"msg" => "登录失效，请重新登录"
	);
}

// 输出JSON
echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>