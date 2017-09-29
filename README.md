# Feature

# Requirement

# Installation

```bash
composer require "godruoyi/ocr:~1.0"
```

# Usage

基本使用（以百度OCR为例）

```php

use Godruoyi\OCR\Application;

$app = new Application([
    'log' => [
        'level' => 'debug',
        'file' => __DIR__ . DIRECTORY_SEPARATOR .'test.log'
    ],

    'ocrs' => [
        'baidu' => [
            'app_key' => 'app_key',
            'secret_key' => 'secret_key'
        ],
    ]
]);

$result = $app->baidu->idcard($filePath);

```
# Docs



