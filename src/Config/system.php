<?php

return [
    [
        'key'       => 'preorder',
        'name'      => 'preorder::app.admin.system.preorder',
        'sort'      => 4
    ], [
        'key'       => 'preorder.settings',
        'name'      => 'preorder::app.admin.system.settings',
        'sort'      => 1,
    ], [
        'key'       => 'preorder.settings.general',
        'name'      => 'preorder::app.admin.system.general',
        'sort'      => 1,
        'fields'    => [
            [
                'name'          => 'enable_preorder',
                'title'         => 'preorder::app.admin.system.enable-preorder',
                'type'          => 'boolean',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'preorder_type',
                'title'         => 'preorder::app.admin.system.preorder-type',
                'type'          => 'select',
                'options'       => [
                    [
                        'title'     => 'preorder::app.admin.system.partial-payment',
                        'value'     => 'partial'
                    ], [
                        'title'     => 'preorder::app.admin.system.complete-payment',
                        'value'     => 'complete'
                    ]
                ],
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'percent',
                'title'         => 'preorder::app.admin.system.preorder-percent',
                'type'          => 'text',
                'validation'    => 'between:0,99',
                'info'          => 'preorder::app.admin.system.preorder-percent-info',
                'depends'       => 'preorder_type:partial',
                'channel_based' => true,
                'locale_based'  => false
            ], [
                'name'          => 'message',
                'title'         => 'preorder::app.admin.system.message',
                'type'          => 'textarea',
                'channel_based' => true,
                'locale_based'  => true
            ]
            // , [
            //     'name' => 'enable_auto_mail',
            //     'title' => 'preorder::app.admin.system.enable-automatic-mail',
            //     'type' => 'boolean',
            //     'channel_based' => true
            // ]
        ]
    ]
];