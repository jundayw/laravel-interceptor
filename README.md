# 安装方法

您可以通过 `Composer` 软件包管理器安装:

```shell
composer require jundayw/laravel-interceptor
```

接下来，你需要使用 `vendor:publish` Artisan 命令发布的配置和迁移文件。

配置文件将会保存在 config 文件夹中：

```shell
php artisan vendor:publish --provider="Jundayw\LaravelInterceptor\InterceptorServiceProvider"
```

或单独发布配置文件

```shell
php artisan vendor:publish --tag=interceptor-config
```

或单独发布迁移文件

```shell
php artisan vendor:publish --tag=interceptor-migrations
```

最后，您应该运行数据库迁移。

```shell
php artisan migrate --path=database/migrations/2022_08_31_182223_create_interceptor_table.php
```
数据填充：

```shell
php artisan db:seed --class=InterceptorSeeder
```

### 自定义迁移

如果你不想使用默认迁移，你应该在 `interceptor.php` 配置文件中将 `migration` 设置为 `false`。

您可以通过执行以下命令导出默认迁移：

```shell
php artisan vendor:publish --tag=interceptor-migrations
```

### 扩展云端过滤器

本扩展包默认支持 `Eloquent` 驱动的 `\Jundayw\LaravelInterceptor\Support\LocalFilter::class` 本地过滤器，

可自定义扩展 [百度云](https://ai.baidu.com/ai-doc/ANTIPORN/Vk3h6xaga)、[腾讯云](https://cloud.tencent.com/document/product/1124/51860)、[阿里云](https://help.aliyun.com/document_detail/70439.html) 的文本审核功能。

```php
<?php

namespace App\Filter\Cloud;

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Jundayw\LaravelInterceptor\Contracts\Filter;

class CloudFilter implements Filter
{
    use Macroable;

    public function __construct()
    {
        //
    }

    public function handle(string $content, string $type): Collection
    {
        // cloud filter
        // $matches
        // $keywords
        // $replacement
        return collect([
            'type' => (string) $type,// 验证类型
            'matches' => (array) $matches,// 内容中匹配到的敏感词集
            'keywords' => (string) $keywords,// 敏感词
            'replacement' => (string) $replacement,// 替换词或替换规则
        ]);
    }

}
```

编码实现后需要变更配置文件 `interceptor.php` 中过滤驱动 `driver` 为 `App\Filter\Cloud\CloudFilter::class`。

# 最佳实践

```php
use Jundayw\LaravelInterceptor\Contracts\Interceptor;

public function test(Interceptor $interceptor)
{
    $content = '本校小额贷款，安全、快捷、方便、无抵押，随机随贷，当天放款，上门服务。';

    $content = $interceptor->filter(content: $content,type: 'message', pass: function ($matches, $content) {
        return $content;
    }, review: function ($matches, $content) {
        // event(new ReViewEvent(...));
        return $content;
    }, replace: function ($matches, $content, $collect) {
        return str_replace(current($matches), $collect->get('replacement'), $content);
    }, block: function ($matches, $content) {
        throw new \Exception('含有违规词');
    });
    
    echo $content;
}
```

或者使用门面模式：

```php
use Jundayw\LaravelInterceptor\Support\Facades\Interceptor;

public function test()
{
    $content = '本校小额贷款，安全、快捷、方便、无抵押，随机随贷，当天放款，上门服务。';

    $content = Interceptor::filter(content: $content,type: 'message', pass: function ($matches, $content) {
        return $content;
    }, review: function ($matches, $content) {
        // event(new ReViewEvent(...));
        return $content;
    }, replace: function ($matches, $content, $collect) {
        return str_replace(current($matches), $collect->get('replacement'), $content);
    }, block: function ($matches, $content) {
        throw new \Exception('含有违规词');
    });
    
    echo $content;
}
```
