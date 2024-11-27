<?php

function getRequest($url) {
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function dateFormat($date) {
    $date = substr($date, 0, 4) . '-' . substr($date, 4);
    $date = substr($date, 0, 7) . '-' . substr($date, 7);
    return $date;
}

$dpis = [
    '720' => '1280x720',
    '1080' => '1920x1080',
    '720p' => '1280x720',
    '1080p' => '1920x1080',
    '1080i' => '1920x1080',
    'hd' => '1920x1080',
    'uhd' => '1920x1080',
    '2k' => '1920x1080',
    '2.5k' => '1920x1200',
    '2.8k' => '1920x1200',
    '4k' => '1920x1080',
    'm' => '720x1280',
    'small' => '1280x720',
    'thumbnail' => '320x240',
    'mobile' => '720x1280',
    'original' => '1920x1200',
    '1920x1200' => '1920x1200',
    '1920x1080' => '1920x1080',
    '1366x768' => '1366x768',
    '1280x768' => '1280x768',
    '1280x720' => '1280x720',
    '1024x768' => '1024x768',
    '800x600' => '800x600',
    '800x480' => '800x480',
    '768x1280' => '768x1280',
    '720x1280' => '720x1280',
    '640x480' => '640x480',
    '480x800' => '480x800',
    '400x240' => '400x240',
    '320x240' => '320x240',
    '240x320' => '240x320'
];

$BING = 'https://www.bing.com';
$regions = ['zh-CN', 'en-US', 'ja-JP', 'en-AU', 'en-UK', 'de-DE', 'en-NZ', 'en-CA'];

function main($params) {
    $region = $params['region'] ?? 'en-US';
    $date = $params['date'] ?? null;
    $dpi = $params['dpi'] ?? null;
    $type = $params['type'] ?? null;

    $day = $date ? floor(abs((time() - strtotime($date)) / (60 * 60 * 24))) : 0;

    $info = [
        'startdate' => '',
        'enddate' => '',
        'title' => '',
        'copyright' => '',
        'cover' => []
    ];

    $mkt = in_array($region, $GLOBALS['regions']) ? $region : 'en-US';
    $url = "{$GLOBALS['BING']}/HPImageArchive.aspx?idx={$day}&n=1&mkt={$mkt}&format=js";

    try {
        $response = getRequest($url);
        $image = $response['images'][0];

        $info['startdate'] = dateFormat($image['startdate']);
        $info['enddate'] = dateFormat($image['enddate']);
        $info['title'] = $image['title'];
        $info['copyright'] = $image['copyright'];
        $info['redirect'] = "{$GLOBALS['BING']}{$image['urlbase']}_1920x1080.jpg";
        $info['cover'] = [];

        foreach ($GLOBALS['dpis'] as $key => $value) {
            $cover = "{$GLOBALS['BING']}{$image['urlbase']}_{$value}.jpg";
            if ($dpi === $key) {
                $info['redirect'] = $cover;
            }
            $info['cover'][] = $cover;
        }

        if (!$type) {
            unset($info['redirect']);
        }
    } catch (Exception $e) {
        error_log("Error fetching Bing image: " . $e->getMessage());
        $info['error'] = $e->getMessage();
    }

    return $info;
}

// Example usage:
$params = [
    'region' => $_GET['region'] ?? 'en-US',
    'date' => $_GET['date'] ?? null,
    'dpi' => $_GET['dpi'] ?? null,
    'type' => $_GET['type'] ?? null
];

$cacheKey = md5(serialize($params));
$cacheFile = __DIR__ . '/cache/' . $cacheKey . '.cache';

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
    // 使用缓存文件
    $result = json_decode(file_get_contents($cacheFile), true);
} else {
    // 请求 Bing API 并缓存结果
    $result = main($params);
    file_put_contents($cacheFile, json_encode($result));
}

if ($params['type'] === 'image') {
    // 直接返回图片内容
    if (isset($result['redirect'])) {
        $imageUrl = $result['redirect'];
        $imageContent = file_get_contents($imageUrl);
        header('Content-Type: image/jpeg');
        echo $imageContent;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Image URL not found']);
    }
} else {
    // 返回 JSON 格式的结果
    header('Content-Type: application/json');
    echo json_encode($result);
}

?>