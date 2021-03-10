<?php

    return [
        'env' => env('PAYMENT_DEFAULT'),
        'default' => env('PAYMENT_DEFAULT', 'xendit'),
        'expiry_in' => env('PAYMENT_EXPIRY_IN', 240), // in minutes
        'midtrans' => [
            'server_key' => env("MIDTRANS_SERVER_KEY"),
            'client_key' => env("MIDTRANS_CLIENT_KEY"),
            'payment_type' => [
                'bank_transfer' => [
                    'bca' => [
                        'label' => 'BCA',
                        'images' => [],
                        'status' => env('MINDTRANS_BCA', false),
                    ],
                    'mandiri' => [
                        'label' => 'Bank Mandiri',
                        'images' => [],
                        'status' => env('MINDTRANS_MANDIRI', false),
                    ],
                    'bni' => [
                        'label' => 'BNI',
                        'images' => [],
                        'status' => env('MINDTRANS_BNI', false),
                    ],
                    'bri' => [
                        'label' => 'BRI',
                        'images' => [],
                        'status' => env('MINDTRANS_BRI', false),
                    ],
                    'permata' => [
                        'label' => 'PERMATA',
                        'images' => [],
                        'status' => env('MINDTRANS_PERMATA', false),
                    ]
                ],
                'ewallet' => [
                    'gopay' => [
                        'label' => 'Gopay',
                        'images' => [],
                        'status' => env('MINDTRANS_gopay', false),
                    ],
                ],
                'qris' => [
                    'label' => 'QRIS',
                    'images' => [],
                    'status' => env('MINDTRANS_QRIS', false),
                ]
            ]
        ],
        'xendit' => [
            'secret_key' => env("XENDIT_SECRET_KEY"),
            'merchant_key' => env("XENDIT_MERCHANT_KEY"),
            'payment_type' => [
                'bank_transfer' => [
                    'bca' => [
                        'label' => 'BCA',
                        'images' => [],
                        'status' => env('XENDIT_BCA', false),
                    ],
                    'mandiri' => [
                        'label' => 'Bank Mandiri',
                        'images' => [],
                        'status' => env('XENDIT_MANDIRI', false),
                    ],
                    'bni' => [
                        'label' => 'BNI',
                        'images' => [],
                        'status' => env('XENDIT_BNI', false),
                    ],
                    'bri' => [
                        'label' => 'BRI',
                        'images' => [],
                        'status' => env('XENDIT_BRI', false),
                    ],
                    'permata' => [
                        'label' => 'PERMATA',
                        'images' => [],
                        'status' => env('XENDIT_PERMATA', false),
                    ]
                ],
                'ewallet' => [
                    'ovo' => [
                        'label' => 'OVO',
                        'images' => [],
                        'status' => env('XENDIT_OVO', false),
                    ],
                    'linkaja' => [
                        'label' => '',
                        'images' => [],
                        'status' => env('XENDIT_LINKAJA', false),
                    ]
                ],
            ]
        ],
        'empatkali' => [
            'payment_type' => [

            ]
        ],
        'stubs' => [
            'filesystem' => [

            ],
            'path' => "Models/Traits",
        ],
    ];
