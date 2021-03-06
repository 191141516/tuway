<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/28
 * Time: 下午6:43
 */

return [
    'img' => [
        'mime' => [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/pjpeg',
        ],
        'size' => 2 * 1024 * 1024,
        'thumb' => [
            [
                'width' => 256,
                'height' => null
            ]
        ]
    ],
    'avatar' => [
        'mime' => [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/pjpeg',
        ],
        'size' => 512 * 1024,
        'thumb' => [
            [
                'width' => 64,
                'height' => null
            ]
        ]
    ]
];