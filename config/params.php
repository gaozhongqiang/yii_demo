<?php

$baseConfig = [//原配置
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
];
$homeConfig = include_once __DIR__.'/home.php';//首页信息配置

return array_merge($baseConfig,$homeConfig);

