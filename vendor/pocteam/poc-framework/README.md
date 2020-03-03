# poc-framework

## 目标

本项目旨在为部门内部提供一个通用的, 好用的PHP框架, 以提高开发效率, 提高代码的可维护性, 同时减少重复代码.

## 特点

1. 基于PHP 7.x版本, 不提供对PHP 5.x版本的支持
1. 基本框架基于Slim, 性能损失较少, 大家更熟悉, 如果需要对老项目进行迁移, 成本较低
1. 数据库操作基于Laravel项目的illuminate/database, API设计合理, 尤其是通过Closure的方式描述查询条件可实现非常高的灵活性
1. 外部接口调用基于加了一堆middleware的guzzlehttp/guzzle, 其中包括记日志, uri替换, 通用的header以及重试机制
1. 易于进行接口权限管理, 可支持到单个API级别的权限控制(基于BasicAuth)
1. 配置简单, 只需要配置极少的全局常量即可运行
1. 运行环境隔离, 基于php.ini中的变量`ENV`(不区分大小写), 在同一台机器上无需配置即可获取当前所处的机器的角色, 方便集群部署
1. 日志详尽且便于检索, 过滤了可能存在的二进制的request/response body, 以避免在日志种出现大量乱码的情况
1. 代码层次清晰, 易于编写复用程度高的代码
1. 提供了API级别的单元测试框架, 以简化对代码重构之后的测试流程
1. 支持Command操作, 易于编写批量操作数据的脚本
1. PHP运行时报错信息打印到php_error.log, 打印了完整的调用栈, 方便排查问题
1. 支持自定义风格的response格式
1. 基于Header中的`Accept-Language`的多语言支持
1. 灵活且易于扩展的Validator, 方便对参数列表进行检查

## 注意事项

1. 项目依赖`APP_ROOT`和`APP_NAME`两个常量, 必须在`public/index.php`定义.
1. 日志目录依赖`APP_NAME`, 默认位于`/data/log/apps/{APP_NAME}`, 运行项目之前务必确认相应的目录是否存在
1. API的单元测试日志文件依赖`LOG_DIR`, 可在测试用例的bootstrap.php中定义

## 快速上手

### 1. 配置`~/.composer/config.json`

1. 添加以下配置以定义私有库地址。

```json
        {
            "url": "http://newgit.op.ksyun.com/ksyun-online/poc-framework.git",
            "type": "git"
        },
        {
            "url": "http://newgit.op.ksyun.com/ksyun-online/poc-app.git",
            "type": "git"
        }
```

1. 设置**不强制**使用HTTPS

最新版的composer已经强制添加的所有代码仓库必须使用HTTPS，这个限制在`composer create-project`命令中可以在最后加上选项`--no-secure-http`来取消。如果需要永久取消，可以在添加以下配置：

```json
{
    "config": {
        "secure-http": false
    }
}
```

1. 建议配置**Packagist / Composer中国全量镜像**，可以极大提高composer的使用体验

### 加上相应配置后，`~/.composer/config.json`大致如下：

```json
{
    "config": {},
    "repositories": [
        {
            "type": "composer",
            "url": "https://packagist.phpcomposer.com"
        },
        {
            "url": "http://newgit.op.ksyun.com/ksyun-online/poc-framework.git",
            "type": "git"
        },
        {
            "url": "http://newgit.op.ksyun.com/ksyun-online/poc-app.git",
            "type": "git"
        }
    ]
}
```

### 使用`composer create-project pocteam/poc-app-skeleton poc-app-demo`来创建一个新项目(可以在最后添加`-vvv`选项来查看详细的创建过程)

### 配置Nginx

Nginx相应vhost示例配置如下:

```nginx
server {
        listen 80;
        server_name profile.api.sdns.ksyun.com profile.inner.sdns.ksyun.com;
        root /data/web/profile/public;
        index index.php index.html;

        access_log /data/log/nginx/access-profile.log main;
        error_log /data/log/nginx/error-profile.log;

        fastcgi_intercept_errors on;

        location / {
                try_files $uri $uri/ /index.php?$args;
        }

        location ~ \.php$ {
                fastcgi_pass 127.0.0.1:9001;
                fastcgi_index index.php;
                include fastcgi.conf;
        }
}
```

### 代码实例

**建议将所有配置放在`src/Config/{ENV}`中, 其中ENV是在php.ini中配置的环境变量. 并且用php文件(区别于类文件)的方式.** 具体如下:

```php
// src/Config/dev/redis.php

return [
    'host' => '127.0.0.1',
    'port' => 6379,
    'db' => 1,
];
```

然后在`src/Config/configs.php`中通过`require 'src/Config/{ENV}'/redis.php`的方式引入redis配置.

另外, 如果有**去金山化**的需求, 可以再加一个`dump_config.php`的文件, 将上面那种方式中本应写在`configs.php`中代码写在`dump_config.php`中, 并通过在提交前执行它的方式把真实的配置放在`configs.php`中, 以达到和直接编辑`configs.php`相同的目的.

```php

$config = [];
$config['basic_auth'] = require __DIR__ . '/' . ENV . '/basic_auth.php';
$config['database'] = require __DIR__ . '/' . ENV . '/database.php';
$config['ks3'] = require __DIR__ . '/' . ENV . '/ks3.php';
$config['service'] = require __DIR__ . '/' . ENV . '/service.php';
$config['permitted_routes'] = require __DIR__ . '/' . ENV . '/permitted_routes.php';
$config['http_mq'] = require __DIR__ . '/' . ENV . '/http_mq.php';

file_put_contents(__DIR__ . '/configs.php', '<?php' . "\n\n\n");
file_put_contents(__DIR__ . '/configs.php', 'return ', FILE_APPEND);
file_put_contents(__DIR__ . '/configs.php', var_export($config, true) . ';', FILE_APPEND);

return $config;

```

**另外,建议将所有配置放在全局的$container中, 以达到在项目的任何位置访问配置的目的**

```php
$container = require __DIR__ . '/init.php';
$config = require __DIR__ . '/Config/configs.php';


$container['config'] = $config;
```
