<?php
header('Content-Type: application/json; charset=utf-8');


// 加载设置
require_once __DIR__ . '/config.php';


// 限制post请求，其他请求直接返回错误
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'reply' => '仅支持 POST 请求'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}


// 读取
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);


// 获得问题
$userMessage = trim($data['message'] ?? '');

if ($userMessage === '') {
    echo json_encode([
        'success' => false,
        'reply' => '请输入内容'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (mb_strlen($userMessage, 'UTF-8') > 500) {
    echo json_encode([
        'success' => false,
        'reply' => '输入内容过长，请控制在 500 字以内'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}



// 构造 OpenAI 请求数据
$payload = [
    'model' => OPENAI_MODEL,
    'input' => [
        [
            'role' => 'system',
            'content' => [
                [
                    'type' => 'input_text',
                    'text' => SYSTEM_PROMPT
                ]
            ]
        ],
        [
            'role' => 'user',//控制AI行为
            'content' => [
                [
                    'type' => 'input_text',
                    'text' => $userMessage
                ]
            ]
        ]
    ]
];


// 初始化 CURL
$ch = curl_init('https://api.openai.com/v1/responses');

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . OPENAI_API_KEY
    ],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
    CURLOPT_TIMEOUT => 60
]);



// 发送请求
$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);

    echo json_encode([
        'success' => false,
        'reply' => '请求 OpenAI 失败：' . $error
    ], JSON_UNESCAPED_UNICODE);
    exit;
}


// 获取 HTTP 状态码
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


// 解析 JSON
$result = json_decode($response, true);

if ($httpCode !== 200) {
    $errorMessage = 'OpenAI 接口调用失败';

    if (!empty($result['error']['message'])) {
        $errorMessage .= '：' . $result['error']['message'];
    }

    echo json_encode([
        'success' => false,
        'reply' => $errorMessage
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 提取 AI 回答
$reply = '';

if (!empty($result['output_text'])) {
    $reply = $result['output_text'];
} elseif (!empty($result['output']) && is_array($result['output'])) {
    foreach ($result['output'] as $item) {
        if (!empty($item['content']) && is_array($item['content'])) {
            foreach ($item['content'] as $content) {
                if (!empty($content['text'])) {
                    $reply = $content['text'];
                    break 2;
                }
            }
        }
    }
}

if ($reply === '') {
    $reply = '没有获取到有效回复';
}


// 返回给前端
echo json_encode([
    'success' => true,
    'reply' => $reply
], JSON_UNESCAPED_UNICODE);