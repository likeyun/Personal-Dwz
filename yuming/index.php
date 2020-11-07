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
  echo '<div class="container mt-3">
  <!-- 导航 -->
  <br/>
  <h2>个人短网址系统 - 域名绑定</h2>
  <p class="cp-tips">你可以在这个面板添加域名</p>
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link active" href="../yuming">域名绑定</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../index">返回首页</a>
    </li>
  </ul>
  <!-- 表单 -->
  <div class="alert alert-secondary">域名需要按照格式进行添加，在绑定域名之前，请提前做好域名解析，以http或https开头，结尾不得带"/"，正确格式 <span class="badge badge-secondary">http://www.baidu.com</span></div>
  <form role="form" action="##" onsubmit="return false" method="post" id="dwz_yuming">
    <div class="form-content">
      <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">域名</span>
          </div>
          <input type="url" name="dwz_yuming" class="form-control" placeholder="请输入你要绑定的域名">
      </div>
      <button type="button" class="btn btn-dark" onclick="dwz_ym()" style="width: 120px;margin-top: 15px;height: 40px;">添加域名</button>
    </div>
  </form>

  <!-- 信息提示框 -->
  <div class="Result"></div>';

  // 数据库配置
  include '../dbconfig/db_connect_config.php';

  echo '<div class="yuming-list">
    <ul class="list-group">';

  // 获取域名列表
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);
  $sql_yuming = "SELECT * FROM dwz_yuming";
  $result_yuming = $conn->query($sql_yuming);
  if ($result_yuming->num_rows > 0) {
    while($row_yuming = $result_yuming->fetch_assoc()) {
      $dwz_yuming = $row_yuming["dwz_yuming"];
      $ym_id = $row_yuming["id"];
      echo '<li class="list-group-item">
        <span>'.$dwz_yuming.'</span>
        <span><a href="javascript:;" data-toggle="modal" id="'.$ym_id.'" onclick="get_ymid(this);" data-target="#dwz_del_yuming_modal">删除</a></span>
      </li>';
    }
  }
    echo '</ul>
  </div>
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

<!-- 模态框 -->
<div class="modal fade" id="dwz_del_yuming_modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除域名</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
 
      <!-- 模态框主体 -->
      <div class="modal-body">
        确定要删除吗？
      </div>
 
      <!-- 模态框底部 -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">确定删除</button>
      </div>
 
    </div>
  </div>
</div>

<script>
// 回车事件
$('#dwz_login').bind('keyup', function(event) {
　　if (event.keyCode == "13") {
        // 按回车的时候执行这个函数
　　　　dwz_login();
　　}
});

$('#dwz_yuming').bind('keyup', function(event) {
　　if (event.keyCode == "13") {
        // 按回车的时候执行这个函数
　　　　dwz_ym();
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

// AJAX添加域名
function dwz_ym(){
  $.ajax({
      type: "POST",
      url: "../yuming/yuming.php",
      data: $('#dwz_yuming').serialize(),
      success: function (data) {
        $(".container .Result").css('display','block');
        // 添加成功
        if (data.code == 100) {
          $(".container .Result").html("<div class=\"alert alert-success\"><strong>添加成功，正在跳转到后台首页</strong></div>");
          location.reload();
        }else{
          $(".container .Result").html("<div class=\"alert alert-danger\"><strong>"+data.msg+"</strong></div>");
        }
      },
      error: function(data) {
        // 添加失败
        $(".container .Result").css('display','block');
        $(".container .Result").html("<div class=\"alert alert-danger\"><strong>添加失败，服务器发生错误</strong></div>");
      }
  });

  // 关闭信息提示框
  setTimeout('closesctips()', 2000);
}


// 获取ym_id，用于删除域名
function get_ymid(event){
  var delymid = event.id;
    $("#dwz_del_yuming_modal .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id="+delymid+" onclick=\"ym_del(this);\">确定删除</button>");
}

// AJAX删除域名
function ym_del(event){
  var ymid = event.id;
  $.ajax({
      type: "POST",
      url: "../yuming/del_yuming.php?ymid="+ymid,
      success: function (data) {
        if (data.code == 100) {
          // 删除成功
          location.reload();
        }else{
          // 删除失败
          alert(data.ymid);
        }
      },
      error: function(data) {
        // 删除失败
        alert("删除失败，服务器发生错误");
      }
  });
}
</script>
</body>
</html>
<br/>
<br/>
<br/>