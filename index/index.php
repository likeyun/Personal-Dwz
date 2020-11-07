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
  <h2>个人短网址系统</h2>
  <p class="cp-tips">你可以在这个面板创建短网址、编辑、删除短网址</p>
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link active" href="../index">短网址</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../creat">创建短网址</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../yuming">域名绑定</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="../login/exit.php">退出登录</a>
    </li>
  </ul>';

  // 数据库配置
  include '../dbconfig/db_connect_config.php';

  // 创建连接
  $conn = new mysqli($db_url, $db_user, $db_pwd, $db_name);

  //计算短网址总数量
  $sql_dwz_all = "SELECT * FROM dwz_list";
  $result_dwz_all = $conn->query($sql_dwz_all);
  $alldwz_num = $result_dwz_all->num_rows;

  //每页显示的短网址数量
  $lenght = 10;

  //当前页码
  @$page = $_GET['p']?$_GET['p']:1;

  //每页第一行
  $offset = ($page-1)*$lenght;

  //总数页
  $allpage = ceil($alldwz_num/$lenght);

  //上一页     
  $prepage = $page-1;
  if($page==1){
    $prepage=1;
  }

  //下一页
  $nextpage = $page+1;
  if($page==$allpage){
    $nextpage=$allpage;
  }

  // 获取短网址列表
  $sql_dwzlist = "SELECT * FROM dwz_list ORDER BY ID DESC limit {$offset},{$lenght}";
  $result_dwzlist = $conn->query($sql_dwzlist);

  if ($result_dwzlist->num_rows > 0) {
    // 遍历短网址列表
    echo '<!-- 列表 -->
    <div class="dwz-list">';
    while($row_dwzlist = $result_dwzlist->fetch_assoc()) {
      $id  = $row_dwzlist["id"];
      $dwz_id  = $row_dwzlist["dwz_id"];
      $dwz_key  = $row_dwzlist["dwz_key"];
      $dwz_status  = $row_dwzlist["dwz_status"];
      $dwz_yuming  = $row_dwzlist["dwz_yuming"];
      $dwz_biaoqian  = $row_dwzlist["dwz_biaoqian"];
      $dwz_pageview  = $row_dwzlist["dwz_pageview"];

      echo '<div class="card">
          <div class="card-body">'.$dwz_yuming.'/'.$dwz_key.'</div> 
          <div class="card-footer">
            <span class="admin-link"><a href="../edi/?dwz_id='.$dwz_id.'">编辑</a></span>
            <span class="admin-link"><a href="javascript:;" data-toggle="modal" id="'.$dwz_id.'" onclick="get_dwzid(this);" data-target="#dwz_del_modal" style="outline:none;">删除</a></span>
            <span class="biaoqian">'.$dwz_biaoqian.'</span>';

            // 判断是否正常
            if ($dwz_status == 1) {
              echo '<span class="biaoqian status"><span class="oi oi-circle-check"></span> 正常</span>';
            }else{
              echo '<span class="biaoqian status"><span class="oi oi-circle-x"></span> 暂停</span>';
            }
            echo '<span class="fwl"><span class="oi oi-eye"></span> '.$dwz_pageview.'</span>
          </div>
        </div>';
    }

    echo '<!-- 分页 -->';
    echo '<ul class="pagination">';
    if ($alldwz_num <= $lenght) {
      // 如果总数小于或等于每个页面展示的数量，则不显示分页
    } else if ($page == 1) {
    echo '<li class="page-item"><a class="page-link" href="?p='.$nextpage.'">下一页</a></li>
    <li class="page-item"><a class="page-link" href="?p='.$allpage.'">尾页</a></li>';
    } else if ($page > 1 && $page < $allpage) {
    echo '<li class="page-item"><a class="page-link" href="?p=1">首页</a></li>
    <li class="page-item"><a class="page-link" href="?p='.$prepage.'">上一页</a></li>
    <li class="page-item"><a class="page-link" href="?p='.$nextpage.'">下一页</a></li>
    <li class="page-item"><a class="page-link" href="?p='.$allpage.'">尾页</a></li>';
    } else if ($page == $allpage) {
    echo '<li class="page-item"><a class="page-link" href="?p=1">首页</a></li>
    <li class="page-item"><a class="page-link" href="?p='.$prepage.'">上一页</a></li>';
    }
    echo '</ul>';
    echo '</div>';
  }else{
    echo "<br/>";
    echo "<p>暂无短网址，<a href='../creat'>点击这里创建</a></p>";
  }
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
<div class="modal fade" id="dwz_del_modal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
 
      <!-- 模态框头部 -->
      <div class="modal-header">
        <h4 class="modal-title">删除短网址</h4>
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

// 获取dwz_id，用于删除短网址
function get_dwzid(event){
  var deldwzid = event.id;
    $("#dwz_del_modal .modal-dialog .modal-footer").html("<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\" id="+deldwzid+" onclick=\"dwz_del(this);\">确定删除</button>");
}

// AJAX删除短网址
function dwz_del(event){
  var dwz_del_id = event.id;
  $.ajax({
      type: "POST",
      url: "../del/?dwz_id="+dwz_del_id,
      success: function (data) {
        // 删除成功
        location.reload();
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