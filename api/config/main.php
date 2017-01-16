<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'), require(__DIR__ . '/../../common/config/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'   // here is our v1 modules
        ]
    ],
    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: GET");
            header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
            $response = $event->sender;

            if ($response->data !== null && Yii::$app->response->statusCode) {
                $response->data = [
                    'status' => $response->isSuccessful ? "Success" : 'Error',
                    'code' => $response->statusCode,
                    'message' => $response->statusText,
                    'data' => $response->data,
                ];
                $response->statusCode = 200;
            }
        },
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/category'], // our category api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/home'],
                    'pluralize' => false,
                    'extraPatterns' =>
                    [
                        'GET' => 'login',
                        'POST' => 'login',
                        'POST signup' => 'signup',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/blog'], // our blog api rule,
                    'pluralize' => false,
//                    'tokens' => [
//                        '{id}' => '<id:\\w+>'
//                    ],
                    'extraPatterns' =>
                    [
                        'POST' => 'create',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/comments'], // our comments api rule,
                    'pluralize' => false,
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                ],
            ],
        ]
    ],
    'params' => $params,
];
