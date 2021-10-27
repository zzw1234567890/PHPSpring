# PHPSpring
> 基于 [php8](https://www.php.net/downloads.php) 和 [gateway-worker](http://doc2.workerman.net/) 框架，为了解决 `gateway-worker` 中的消息处理，参考 `java spring` 的设计实现的一个简单框架，提供 `http` 和 `websocket` 服务，并且不依赖 `apache/nginx` 服务器，只需要 `php` 环境即可运行。
> 环境版本：`php>=8.0.12` , `gateway-worker>=3.0.0`

- windows环境运行 `start_for_win.bat`
- Linux环境运行 `php start.php start|stop|status|restart`

## 项目结构

- `/Applications`: 应用目录，一个应用可以提供多个服务，如：`http`、`websocket`，编写业务逻辑的主要目录。
- `/config`: 配置目录，采用 `.json` 配置文件，该目录中的配置文件会自动读取并映射到全局配置类 `Lib\Config` 中，每个配置文件会对应配置类实例的属性。
- `/lib`: 框架核心目录。
- `/lib/attributes`: 注解存放目录。
- `/lib/aware`: 存放实例创建生命周期的一些接口。
- `/lib/extend`: 消息处理类、一些接口的实现类暂时存放该目录，比较杂，待改进。
- `/lib/utils`: 存放工具类。
- `/boot.php`: 框架引导文件，用来适配 `windows` 和 `linux` 环境。


## 核心类文件

- `Lib\AppContext`: 实例容器，负责初始化，加载配置，引入类加载器，实现实例创建、初始化、属性注入的生命周期。
- `Lib\ClassLoader`: 类加载器，负责加载文件，加载 `Component` 注解的类，并调用 `AppContext` 的方法创建实例。
- `Lib\Config`: 全局配置类，在 `/config/` 目录下的所有 `.json` 文件都会被映射到该类的实例的属性中，实例的属性名为配置文件名。

## IOC容器
> 容器存储类的实例，使用单例模式

- 调用 `AppContext` 的静态方法从容器中获取实例。

```php
$config = AppContext::$getInstance(Config::class);
```

## 依赖注入

- 通过 `Autowried` 注解给成员变量注入实例

```php
class A{
    #[Autowried(Config::class)]
    private $config;
}
```

### 消息分发器
> 消息分发器实现 `IDispatcher` 接口，提供了一个默认的消息分发器 `MessageDispatcher`，你可以定制自己的消息分发器，然后在 `Events.php` 中使用它。

### 消息处理器
> 消息处理使用案例：

```php
<?php

namespace App\Handler;

use Lib\Attribute\Autowried;
use Lib\Attribute\Handler;
use Lib\Attribute\HandlerType;
use Lib\Config;
use Lib\Extend\BaseHandler;

#[Handler]
class DefaultHandler extends BaseHandler{

    #[Autowried(Config::class)]
    private $config;

    // 此处接受的消息为：'{"type":"111","content": {"name":"xxx","sex":"x"}}'
    #[HandlerType("111")]
    private function hello($name, $sex){
        print_r($name . " " . $sex . "\n");
        print_r($this->getClientId() . "\n");
        print_r($this->getMessage() . "\n");
    }
}
```

- 使用 `Handler` 注解表示该类是一个消息处理器。
- 使用 `HandlerType` 注解表示要处理的消息类型，消息分发器会自动将注解中的消息类型映射到注解对应的方法上，并且在方法的参数中可以直接使用 `content` 中的 `key` 来获取对应的 `value`