# PHPSpring
> 基于php8和workerman框架，为了解决workerman中的消息处理，参考java spring的设计实现的一个简单框架

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