<?php
error_reporting(E_ALL^E_NOTICE^E_WARNING);
header("Content-type:application/json");

$servername = $_POST["servername"];
$username = $_POST["username"];
$password = $_POST["password"];
$dbname = $_POST["dbname"];
$adminuser = $_POST["adminuser"];
$adminpwd = $_POST["adminpwd"];

if (empty(trim($servername))) {
	$result = array(
		"msg" => "数据库服务器地址未填"
	);
} else if (empty(trim($username))) {
	$result = array(
		"msg" => "数据库账号未填"
	);
} else if (empty(trim($password))) {
	$result = array(
		"msg" => "数据库密码未填"
	);
} else if (empty(trim($dbname))) {
	$result = array(
		"msg" => "数据库名未填"
	);
} else if (empty(trim($adminuser))) {
	$result = array(
		"msg" => "管理员账号未设置"
	);
} else if (empty(trim($adminpwd))) {
	$result = array(
		"msg" => "管理员密码未设置"
	);
}else{
	// 创建连接
	$conn = new mysqli($servername, $username, $password, $dbname);
	// 检测连接
	if ($conn->connect_error) {
	    $error_msg = $conn->connect_error;
		if(strpos($error_msg,'getaddrinfo') !==false){
			$result = array(
				"msg" => "数据库服务器地址不正确"
			);
		}else if(strpos($error_msg,'password') !==false){
			$result = array(
				"msg" => "数据库账号或密码不正确"
			);
		}else if(strpos($error_msg,'database') !==false){
			$result = array(
				"msg" => "数据库名称不正确"
			);
		}
	} else{

		// 创建dwz_list数据表
		$dwz_list = "CREATE TABLE dwz_list (
			id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			dwz_id VARCHAR(32) NULL,
			dwz_key VARCHAR(32) NULL,
			dwz_biaoqian VARCHAR(32) NULL,
			dwz_long_url TEXT(1000) NULL,
			dwz_status VARCHAR(32) NULL,
			dwz_fh VARCHAR(32) NULL,
			dwz_yuming TEXT(100) NULL,
			dwz_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			dwz_pageview VARCHAR(32) DEFAULT '0'
		)";

		// 创建dwz_yuming数据表
		$dwz_yuming = "CREATE TABLE dwz_yuming (
			id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			dwz_yuming TEXT(100) NULL
		)";

		// 创建dwz_admin数据表
		$dwz_admin = "CREATE TABLE dwz_admin (
			id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			admin_uid VARCHAR(32) NULL,
			admin_user VARCHAR(32) NULL,
			admin_pwd VARCHAR(32) NULL,
			admin_creat_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)";

		// 验证$dwz_list是否安装成功
		if ($conn->query($dwz_list) === TRUE) {
			// 验证$dwz_yuming是否安装成功
			if ($conn->query($dwz_yuming) === TRUE) {
				// 验证$dwz_admin是否安装成功
				if ($conn->query($dwz_admin) === TRUE) {
					// 所有数据表安装成功
					// 创建管理员账号
					// 创建管理员账号
					$admin_uid = rand(100000,999999);
					mysqli_query($conn,"INSERT INTO dwz_admin (admin_uid, admin_user, admin_pwd) VALUES ('$admin_uid', '$adminuser', '$adminpwd')");
					// 创建本地文件
					$db_file = "../dbconfig/db_connect_config.php";
					if(file_exists($db_file)){
						$result = array(
							"msg" => "请删除/dbconfig/db_connect_config.php再安装"
						);
					}else{
						$mysql_data = '<?php
						$db_url = "'.$servername.'";
						$db_user = "'.$username.'";
						$db_pwd = "'.$password.'";
						$db_name = "'.$dbname.'";
						?>';
						//生成数据库配置文件
						file_put_contents('../dbconfig/db_connect_config.php', $mysql_data);
						
						// 返回安装结果
						$result = array(
							"msg" => "安装成功",
							"code" => "100"
						);
					}
				}else{
					if(strpos($conn->error,'exists') !==false){
						$result = array(
							"msg" => "安装失败，请到数据库把dwz_前缀的表给删掉再安装"
						);
					}else{
						$result = array(
							"msg" => "安装失败，原因：".$conn->error
						);
					}
				}
			}else{
				if(strpos($conn->error,'exists') !==false){
					$result = array(
						"msg" => "安装失败，请到数据库把dwz_前缀的表给删掉再安装"
					);
				}else{
					$result = array(
						"msg" => "安装失败，原因：".$conn->error
					);
				}
			}
		}else{
			if(strpos($conn->error,'exists') !==false){
				$result = array(
					"msg" => "安装失败，请到数据库把dwz_前缀的表给删掉再安装"
				);
			}else{
				$result = array(
					"msg" => "安装失败，原因：".$conn->error
				);
			}
		}
	}
	// 断开数据库连接
	$conn->close();
}

echo json_encode($result,JSON_UNESCAPED_UNICODE);
?>
