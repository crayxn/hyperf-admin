## hf_admin
> #### hf_admin是基于hyperf框架开发的后台管理
````
前端部分感谢 贤心大大的 LayUI(https://github.com/sentsin/layui)
后台部分参考 ThinkAdmin (https://github.com/zoujingli/ThinkAdmin 对新手非常友好的tp后台框架,墙裂推荐)
````
## 运行说明
* 创建.env文件
```
cp .env.example .env //并配置好数据库等参数
```
* 初始化数据库
``` 
//数据库结构初始化
php bin/hyperf.php migrate --path=migrations/init
//数据初始化
php bin/hyperf.php db:seed --path=seeders/init
```

* 启动应用
```
php bin/hyperf.php start
```
* 登陆应用
```
默认账户:admin 密码:123456
```


