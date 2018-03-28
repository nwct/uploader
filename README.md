# 文件上传工具

1. nginx 配置注意添加 `client_max_body_size 200M;`
2. 默认在upload.php同级目录必须有一个uploads文件夹，且有写入权限。
3. 测试上传命令：`curl http://domain.com/upload.php -F "file=@filename.zip"`

使用任意的表单key均可，支持多个文件上传。

可以在nginx Index中增加一个 upload.php，即可隐藏upload.php后缀，实现在根目录上传。



