<!DOCTYPE html>
<html>
<head>
  <title>个人短网址系统</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/style.css">
  <script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/popper.js/1.15.0/umd/popper.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic.min.css" rel="stylesheet">
  <link href="https://cdn.bootcdn.net/ajax/libs/open-iconic/1.0.0/font/css/open-iconic-bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
header("Content-type:text/html;charset=utf-8");
session_start();
if(isset($_SESSION["dwz.admin"])){

  // 数据库配置
  include '../dbconfig/db_connect_config.php';

  echo '<div class="container mt-3">
  <!-- 导航 -->
  <br/>
  <h2>个人短网址系统 - 创建短网址</h2>
  <p class="cp-tips">你可以在这个面板创建短网址</p>
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link active" href="../creat">创建短网址</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../index">返回首页</a>
    </li>
  </ul>

  <!-- 表单 -->
  <form role="form" action="##" onsubmit="return false" method="post" id="dwz_creat">
    <div class="form-content">
      <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">长链接</span>
          </div>
          <input type="text" name="dwz_long_url" class="form-control" placeholder="请粘贴需要转换的长链接">
      </div>

      <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">标签</span>
          </div>
          <input type="text" name="dwz_biaoqian" class="form-control" placeholder="请输入标签">
      </div>';

      echo '<select class="form-control" name="dwz_yuming" style="margin-bottom: 15px;">';
      echo '<option value="">请选择域名</option>';
      // 获取域名列表
      $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
      $sql_yuming = "SELECT * FROM dwz_yuming";
      $result_yuming = $conn->query($sql_yuming);
      if ($result_yuming->num_rows > 0) {
        while($row_yuming = $result_yuming->fetch_assoc()) {
          // 遍历域名列表
          echo '<option value="'.$row_yuming["dwz_yuming"].'">'.$row_yuming["dwz_yuming"].'</option>';
        }
          // 服务器域名
          echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
      }else{
        // 服务器域名
        echo '<option value="http://'.$_SERVER['HTTP_HOST'].'">http://'.$_SERVER['HTTP_HOST'].'</option>';
      }
      echo '</select>';

      echo '<select class="form-control" name="dwz_status" style="margin-bottom: 15px;">
        <option value="1">正常使用</option>
        <option value="2">暂停使用</option>
      </select>

      <select class="form-control" name="dwz_fh">
        <option value="2">不防红</option>
        <option value="1">防红</option>
      </select>

      <button type="button" class="btn btn-dark" onclick="dwz_creat()" style="width: 120px;margin-top: 15px;height: 40px;">创建短网址</button>
    </div>
  </from>
  
  <!-- 信息提示框 -->
  <div class="Result"></div>

</div>';
}else{
  echo '<div class="container mt-3">
  <br/>
  <h3>登录个人短网址系统</h3>
  <br/>
  <form role="form" action="##" onsubmit="return false" method="post" id="dwz_login">

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">账号</span>
      </div>
      <input type="text" name="admin_user" class="form-control" placeholder="管理后台账号" autocomplete="off">
    </div>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">密码</span>
      </div>
      <input type="password" name="admin_pwd" class="form-control" placeholder="管理后台密码" autocomplete="off">
    </div>
    <button type="button" class="btn btn-dark" onclick="dwz_login()">登录后台</button>
  </form>

  <!-- 信息提示框 -->
  <div class="Result"></div>

</div>';
}
?>

<script>
// 回车事件
$('#dwz_creat').bind('keyup', function(event) {
　　if (event.keyCode == "13") {
        // 按回车的时候执行这个函数
　　　　dwz_creat();
　　}
});

// 信息提示框
function closesctips(){
  $(".container .Result").css('display','none');
}

// AJAX登录
function dwz_login(){
  $.ajax({
      type: "POST",
      url: "../login/",
      data: $('#dwz_login').serialize(),
      success: function (data) {
        $(".container .Result").css('display','block');
        // 登录成功
        if (data.code == 100) {
          $(".container .Result").html("<div class=\"alert alert-success\"><strong>登录成功，正在跳转到后台首页</strong></div>");
          location.href='../index';
        }else{
          $(".container .Result").html("<div class=\"alert alert-danger\"><strong>"+data.msg+"</strong></div>");
        }
      },
      error: function(data) {
        // 登录失败
        $(".container .Result").css('display','block');
        $(".container .Result").html("<div class=\"alert alert-danger\"><strong>登录失败，服务器发生错误</strong></div>");
      }
  });

  // 关闭信息提示框
  setTimeout('closesctips()', 2000);
}

// AJAX创建短网址
function dwz_creat(){
  $.ajax({
      type: "POST",
      url: "../creat/creat.php",
      data: $('#dwz_creat').serialize(),
      success: function (data) {
        $(".container .Result").css('display','block');
        // 创建成功
        if (data.code == 100) {
          $(".container .Result").html("<div class=\"alert alert-success\"><strong>"+data.msg+"，正在跳转到首页</strong></div>");
          // 关闭信息提示框
          setTimeout('closesctips()', 2000);
          location.href='../index';
        } else if (data.code == 109) {
          $(".container .Result").html("<div class=\"alert alert-success\"><strong>"+data.msg+"，短网址是："+data.dwz+"</strong></div>");
        }else{
          $(".container .Result").html("<div class=\"alert alert-danger\"><strong>"+data.msg+"</strong></div>");
          // 关闭信息提示框
          setTimeout('closesctips()', 2000);
        }
      },
      error: function(data) {
        // 创建失败
        $(".container .Result").css('display','block');
        $(".container .Result").html("<div class=\"alert alert-danger\"><strong>创建失败，服务器发生错误</strong></div>");
        // 关闭信息提示框
        setTimeout('closesctips()', 2000);
      }
  });
}
</script>
</body>
</html>