<?php

return [
    'common'        => [
        'auth'                  => [
            'login' => [
                'success' => '登录成功。',
                'logout'  => '退出登录成功。',
            ],
        ],

        'resource-not-found'    => '未找到请求的资源。',
        'forbidden-error'       => '您没有权限访问此资源。',
        'unauthenticated'       => '您尚未通过身份验证，请先登录。',
        'internal-server-error' => '服务器发生意外错误，请稍后重试。',
    ],

    'products'      => [
        'create-success'           => '产品创建成功。',
        'updated-success'          => '产品更新成功。',
        'delete-success'           => '产品删除成功。',
        'delete-failed'            => '产品删除失败。',
        'inventory-create-success' => '库存保存成功。',
    ],

    'leads'         => [
        'create-success'  => '线索创建成功。',
        'updated-success' => '线索更新成功。',
        'delete-success'  => '线索删除成功。',
        'delete-failed'   => '线索删除失败。',
        'no-valid-files'  => '未找到有效文件。',
        'view'            => [
            'tags'   => [
                'create-success' => '标签添加成功。',
                'delete-success' => '标签移除成功。',
            ],

            'quotes' => [
                'create-success' => '报价关联成功。',
                'delete-success' => '报价移除成功。',
            ],
        ],
    ],

    'quotes'        => [
        'create-success' => '报价单创建成功。',
        'update-success' => '报价单更新成功。',
        'delete-success' => '报价单删除成功。',
        'delete-failed'  => '报价删除失败。',
        'saved-to-draft' => '报价已保存为草稿。',
    ],

    'mail'          => [
        'create-success' => '邮件创建成功。',
        'update-success' => '邮件更新成功。',
        'delete-success' => '邮件删除成功。',
        'delete-failed'  => '邮件删除失败。',
        'saved-to-draft' => '邮件已保存为草稿。',
        'view'           => [
            'tags' => [
                'create-success' => '标签添加成功。',
                'delete-success' => '标签移除成功。',
            ],
        ],
    ],

    'activities'    => [
        'create-success' => '活动创建成功。',
        'update-success' => '活动更新成功。',
        'delete-success' => '活动删除成功。',
        'delete-failed'  => '活动删除失败。',
    ],

    'contacts'      => [
        'persons'       => [
            'create-success' => '联系人创建成功。',
            'update-success' => '联系人更新成功。',
            'delete-success' => '联系人删除成功。',
            'delete-failed'  => '联系人删除失败。',
            'view'           => [
                'tags' => [
                    'create-success' => '标签添加成功。',
                    'delete-success' => '标签移除成功。',
                ],
            ],
        ],

        'organizations' => [
            'create-success' => '组织创建成功。',
            'update-success' => '组织更新成功。',
            'delete-success' => '组织删除成功。',
            'delete-failed'  => '组织删除失败。',
        ],
    ],

    'settings'      => [
        'tags'            => [
            'create-success' => '标签创建成功。',
            'update-success' => '标签更新成功。',
            'delete-success' => '标签删除成功。',
            'delete-failed'  => '标签删除失败。',
        ],

        'web-forms'       => [
            'create-success'  => '网页表单创建成功。',
            'updated-success' => '网页表单更新成功。',
            'delete-success'  => '网页表单删除成功。',
            'delete-failed'   => '网页表单删除失败。',
        ],

        'attributes'      => [
            'create-success'    => '属性创建成功。',
            'update-success'    => '属性更新成功。',
            'destroy-success'   => '属性删除成功。',
            'delete-failed'     => '属性删除失败。',
            'user-define-error' => '系统属性无法删除。',
        ],

        'groups'          => [
            'create-success'  => '用户组创建成功。',
            'update-success'  => '用户组更新成功。',
            'destroy-success' => '用户组删除成功。',
            'delete-failed'   => '群组删除失败。',
        ],

        'marketing'       => [
            'events'    => [
                'create-success'  => '营销事件创建成功。',
                'update-success'  => '营销事件更新成功。',
                'destroy-success' => '营销事件删除成功。',
                'delete-failed'   => '事件删除失败。',
            ],

            'campaigns' => [
                'create-success'  => '营销活动创建成功。',
                'update-success'  => '营销活动更新成功。',
                'destroy-success' => '营销活动删除成功。',
                'delete-failed'   => '营销活动删除失败。',
            ],
        ],

        'roles'           => [
            'create-success'            => '角色创建成功。',
            'update-success'            => '角色更新成功。',
            'delete-success'            => '角色删除成功。',
            'delete-failed'             => '角色删除失败。',
            'being-used'                => '该角色已分配给用户，无法删除。',
            'last-delete-error'         => '至少需要保留一个角色。',
            'current-role-delete-error' => '无法删除分配给当前用户的角色。',
        ],

        'users'           => [
            'create-success'      => '用户创建成功。',
            'updated-success'     => '用户更新成功。',
            'delete-success'      => '用户删除成功。',
            'delete-failed'       => '用户删除失败。',
            'last-delete-error'   => '至少需要保留一个用户。',
            'mass-delete-success' => '所选用户删除成功。',
            'mass-delete-failed'  => '所选用户删除失败。',
            'mass-update-success' => '所选用户更新成功。',
            'mass-update-failed'  => '所选用户更新失败。',
        ],

        'pipelines'       => [
            'create-success'       => '销售流程创建成功。',
            'updated-success'      => '管道更新成功。',
            'delete-success'       => '销售流程删除成功。',
            'default-delete-error' => '默认管道无法删除。',
        ],

        'sources'         => [
            'create-success' => '来源创建成功。',
            'update-success' => '来源更新成功。',
            'delete-success' => '来源删除成功。',
            'delete-failed'  => '来源删除失败。',
        ],

        'types'           => [
            'create-success' => '类型创建成功。',
            'update-success' => '类型更新成功。',
            'delete-success' => '类型删除成功。',
            'delete-failed'  => '类型删除失败。',
        ],

        'email-templates' => [
            'create-success' => '邮件模板创建成功。',
            'update-success' => '邮件模板更新成功。',
            'delete-success' => '邮件模板删除成功。',
            'delete-failed'  => '邮件模板删除失败。',
        ],

        'workflows'       => [
            'create-success' => '工作流创建成功。',
            'update-success' => '工作流更新成功。',
            'delete-success' => '工作流删除成功。',
            'delete-failed'  => '工作流删除失败。',
        ],

        'warehouses'      => [
            'create-success' => '仓库创建成功。',
            'update-success' => '仓库更新成功。',
            'delete-success' => '仓库删除成功。',
            'delete-failed'  => '仓库删除失败。',
            'view'           => [
                'locations' => [
                    'create-success' => '位置创建成功。',
                    'update-success' => '位置更新成功。',
                    'delete-success' => '位置删除成功。',
                    'delete-failed'  => '位置删除失败。',
                ],

                'tags'      => [
                    'create-success' => '标签添加成功。',
                    'delete-success' => '标签移除成功。',
                ],
            ],
        ],

        'webhooks'        => [
            'create-success' => 'Webhook 创建成功。',
            'update-success' => 'Webhook 更新成功。',
            'delete-success' => 'Webhook 删除成功。',
            'delete-failed'  => 'Webhook 删除失败。',
        ],

        'data-transfer'   => [
            'imports' => [
                'create-success'    => '导入创建成功。',
                'delete-failed'     => '导入删除失败。',
                'delete-success'    => '导入删除成功。',
                'not-valid'         => '导入无效。',
                'nothing-to-import' => '没有可导入的内容。',
                'setup-queue-error' => '请配置 sync 以外的队列驱动，以便在队列中处理导入。',
                'update-success'    => '导入更新成功。',
            ],
        ],
    ],

    'configuration' => [
        'save-success' => '配置保存成功。',
    ],
];
