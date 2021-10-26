# PHPSpring
> 基于php8和workerman框架，为了解决workerman中的消息处理，参考java spring的设计实现的一个简单框架。
> 环境版本：php>=8.0.12, gateway-worker>=3.0.0

- windows环境运行 `start_for_win.bat`
- Linux环境运行 `php start.php start|stop|status|restart`

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
> 消息分发器实现 `IDispatcher` 接口，提供了一个默认的消息分发器 `MessageDispatcher`，你可以定制自己的消息分发器，然后在 `Events.php` 中使用它。默认的消息分发器使用案例：

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