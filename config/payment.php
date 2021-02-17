<?php

    return [
        'env' => env('PAYMENT_DEFAULT'),
        'default' => env('PAYMENT_DEFAULT'),
        'expiry_in' => env('PAYMENT_EXPIRY_IN', 4),
        'midtrans' => [
            'server_key' => env("MIDTRANS_SERVER_KEY"),
            'client_key' => env("MIDTRANS_CLIENT_KEY"),
            'payment_type' => [
                'bank_transfer' => [
                    'bca' => [
                        'title' => 'BCA',
                        'images' => [],
                        'status' => env('MINDTRANS_BCA', false),
                    ],
                    'mandiri' => [
                        'title' => 'Bank Mandiri',
                        'images' => [],
                        'status' => env('MINDTRANS_MANDIRI', false),
                    ],
                    'bni' => [
                        'title' => 'BNI',
                        'images' => [],
                        'status' => env('MINDTRANS_BNI', false),
                    ],
                    'bri' => [
                        'title' => 'BRI',
                        'images' => [],
                        'status' => env('MINDTRANS_BRI', false),
                    ],
                    'permata' => [
                        'title' => 'PERMATA',
                        'images' => [],
                        'status' => env('MINDTRANS_PERMATA', false),
                    ]
                ],
                'ewallet' => [
                    'gopay' => [
                        'title' => 'Gopay',
                        'images' => [],
                        'status' => env('MINDTRANS_gopay', false),
                    ],
                ]
            ]
        ],
        'xendit' => [
            'secret_key' => env("XENDIT_SECRET_KEY"),
            'merchant_key' => env("XENDIT_MERCHANT_KEY"),
            'payment_type' => [
                'bank_transfer' => [
                    'bca' => [
                        'title' => 'BCA',
                        'images' => [],
                        'status' => env('XENDIT_BCA', false),
                    ],
                    'mandiri' => [
                        'title' => 'Bank Mandiri',
                        'images' => [],
                        'status' => env('XENDIT_MANDIRI', false),
                    ],
                    'bni' => [
                        'title' => 'BNI',
                        'images' => [],
                        'status' => env('XENDIT_BNI', false),
                    ],
                    'bri' => [
                        'title' => 'BRI',
                        'images' => [],
                        'status' => env('XENDIT_BRI', false),
                    ],
                    'permata' => [
                        'title' => 'PERMATA',
                        'images' => [],
                        'status' => env('XENDIT_PERMATA', false),
                    ]
                ],
                'ewallet' => [
                    'ovo' => [
                        'title' => '',
                        'images' => [],
                        'status' => env('XENDIT_OVO', false),
                    ],
                    'linkaja' => [
                        'title' => '',
                        'images' => [],
                        'status' => env('XENDIT_LINKAJA', false),
                    ]
                ],
            ]
        ],
        'empatkali' => [
            'payment_type' => [

            ]
        ]
    ];
