# Personal-Dwz
在我们的项目当中，如果需要更好传播我们的活动链接，但是链接太长1来是不美观，2来是太过于“笨重”，例如拼多多，淘宝联盟，他们的推广链接都是有短链接的，还有新浪微博。

但是，这些始终都是别人的，我们调用别人的API进行生成，不稳定，所以可以自己做一个，注册一个稍微短一些的域名就行，这就是我们本次开源项目的初衷，我们就是为了让大家能够有一个稳定的平台，所以我开发了个人短网址生成系统。

# 版本
v1.0.0

# 功能概览
`1、创建短网址，可以选择短网址域名、可以选择防红`<br/>
`2、绑定域名，方便创建不同域名下的短网址`<br/>
`3、可以设置防红，在微信端点击短网址，引导用户再浏览器打开`<br/>
`4、可以设置短网址的开关，必要时可以关闭短网址的访问权限`<br/>
`5、可以统计短网址的点击次数，即访问量`<br/>

# 安装环境
`php5.6-7.0`<br/>
`mysql 5.7左右均可`<br/>
`apache服务器`<br/>

apache伪静态

```
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php?id=$1 [L]
</IfModule>
```

apache伪静态请在短网址系统index.php的同一目录建一个文件名为`.htaccess`的伪静态文件

Nginx伪静态
```
location / {
  if (!-e $request_filename) {
    rewrite ^/(.*)$ /index.php?id=$1 last;
  }
}
```

# 安装步骤
直接访问install即可<br/>
例如你的域名是：<br/>
http://www.xxx.com<br/>
你的程序放在服务器根目录下的dwz目录<br/>
那么启动安装的Url是<br/>
http://www.xxx.com/dwz/install/<br/>

# 伪静态设置
（1）如果你是安装在服务器根目录，则无需设置伪静态<br/>
（2）如果安装的时候，直接把域名指向指定的子目录作为根目录，则也无需配置伪静态<br/>
（3）如果你是安装在服务器根目录下的子目录，但你的服务器没法把域名指向指定的目录作为根目录，例如你的代码目录是<br/>
http://www.xxx.com/dwz/<br/>
那么你需要设置伪静态规则<br/>

设置方法：<br/>
（1）在服务器根目录，记住，是根目录不是短网址系统的根目录，是整个服务器的根目录，创建一个问静态文件名称为` .htaccess ` <br/>
（2）然后拷贝下面代码，保存即可<br/>

例如你的代码放在dwz子目录，需要修改下面伪静态规则代码

```
# 解析xxx.cn到dwz子目录
RewriteEngine on 
RewriteCond %{HTTP_HOST} ^xxx.cn$ 
RewriteCond %{REQUEST_URI} !^/dwz/ 
RewriteCond %{REQUEST_FILENAME} !-f 
RewriteCond %{REQUEST_FILENAME} !-d 
RewriteRule ^(.*)$ /dwz/$1 
RewriteCond %{HTTP_HOST} ^xxx.cn$ 
RewriteRule ^(/)?$ dwz/index.php [L]
```

这个操作的目的就是当访问你的域名xxx.cn的时候，就是默认把dwz这个目录作为服务器的目录，当然，如果你在解析域名的时候，可以直接在服务器进行绑定到子目录，就更合适，宝塔面板可以这样做。

# 后台
后台地址是：http://www.xxx.cn/dwz/index/

# 截图展示
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20201107145319.png?raw=true"/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20201107145328.png?raw=true"/>
<img src="https://github.com/likeyun/TANKING/blob/master/%E5%BE%AE%E4%BF%A1%E6%88%AA%E5%9B%BE_20201107145335.png?raw=true"/>
