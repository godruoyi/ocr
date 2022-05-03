# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Released

----

### [2.1.0](https://github.com/godruoyi/ocr/tree/2.0.0) - 2022-05-03

#### Breaking Changes

- PHP 最低版本支持 7.1.3

#### Fixed

- 支持新版百度 AccessToken
- 完善测试用例
- 支持 PHP7.1.3 及以上版本

### [2.0.0](https://github.com/godruoyi/ocr/tree/2.0.0) - 2020-11-12

- 重写整个 sdk
- 加入 easy container 支持
- 重新整理各平台目前支持的接口文档
- 返回标准的 Pse Response
- 不再对请求成功做判断

### [1.1.0](https://github.com/godruoyi/ocr/tree/1.1.0) - 2017-12-06

#### Changed
- 重置OCR服务提供者结构

### Add
- 新增腾讯AI识别支持

### [1.0.4](https://github.com/godruoyi/ocr/tree/1.0.4) - 2017-12-02

#### Changed
- 改变 guzzlehttp/guzzle 客户端的依赖版本至 ^6.2

### [1.0.3](https://github.com/godruoyi/ocr/tree/1.0.3) - 2017-12-01

#### Add
- 银行卡识别(腾讯)
- 车牌号识别(腾讯)
- 营业执照识别(腾讯)

#### Changed
- 修复腾讯通用识别时的错误调用
