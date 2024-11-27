# Bing daily wallpaper PHP API

Bing 每日一图 PHP API ([Docs](https://github.com/eallion/bing))  

### 1. `/index.php`

只有当请求 `type=image` 时，才直接返回 Bing 每日一图，其他情况都返回 json。

> `https://bing.example.com?type=image`

| /index.php    | 示例                                                         | 说明                                                         |
| -------- | ------------------------------------------------------------ | ------------------------------------------------------------ |
| `type`   | [type=image](https://bing.example.com?type=image)        | 当该字段等于 `image`时，返回参数会新增一个 `redirect`值为壁纸的地址，同时会重定向到该地址 |
| `region` | [region=zh-CN](https://bing.example.com?type=image&region=zh-CN) | Bing 每日壁纸的不同地区，每个地区的壁纸都不一样，默认：`en-US`，可选：`zh-CN, en-US, ja-JP, en-AU, en-UK, de-DE, en-NZ, en-CA` |
| `date`   | [date=2006-07-24](https://bing.example.com?type=image&date=2022-07-24) | 指定一个日期，格式：`2006-01-02`，获取这个日期的壁纸，目测只能获取当前日期的前 10 天的壁纸 |
| `dpi`    | [dpi=uhd](https://bing.example.com?type=image&region=zh-CN&dpi=uhd) | 修改 `redirect`默认的壁纸分辨率，默认`1920x1080`，支持 `720` `1080` `720p` `1080p` `1080i` `hd` `uhd` `2k` `2.5k` `2.8k` `4k` `m` `small` `thumbnail` `mobile` `original` `1920x1200` `1920x1080` `1366x768` `1280x768` `1280x720` `1024x768` `800x600` `800x480` `768x1280` `720x1280` `640x480` `480x800` `400x240` `320x240` `240x320`                   |

### 2. `cache/`

缓存目录，`0755`
