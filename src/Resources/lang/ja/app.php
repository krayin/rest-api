<?php

return [
    'common'        => [
        'auth'                  => [
            'login' => [
                'success' => 'ログインしました。',
                'logout'  => 'ログアウトしました。',
            ],
        ],

        'resource-not-found'    => '要求されたリソースが見つかりませんでした。',
        'forbidden-error'       => 'このリソースにアクセスする権限がありません。',
        'unauthenticated'       => '認証されていません。続行するにはログインしてください。',
        'internal-server-error' => 'サーバーで予期しないエラーが発生しました。しばらくしてからもう一度お試しください。',
    ],

    'products'      => [
        'create-success'           => '製品を作成しました。',
        'updated-success'          => '製品を更新しました。',
        'delete-success'           => '製品を削除しました。',
        'delete-failed'            => '製品の削除に失敗しました。',
        'inventory-create-success' => '在庫を保存しました。',
    ],

    'leads'         => [
        'create-success'  => 'リードを作成しました。',
        'updated-success' => 'リードを更新しました。',
        'delete-success'  => 'リードを削除しました。',
        'delete-failed'   => 'リードの削除に失敗しました。',
        'no-valid-files'  => '有効なファイルが見つかりませんでした。',
        'view'            => [
            'tags'   => [
                'create-success' => 'タグを追加しました。',
                'delete-success' => 'タグを解除しました。',
            ],

            'quotes' => [
                'create-success' => '見積を添付しました。',
                'delete-success' => '見積を解除しました。',
            ],
        ],
    ],

    'quotes'        => [
        'create-success' => '見積を作成しました。',
        'update-success' => '見積を更新しました。',
        'delete-success' => '見積を削除しました。',
        'delete-failed'  => '見積の削除に失敗しました。',
        'saved-to-draft' => '見積を下書きに保存しました。',
    ],

    'mail'          => [
        'create-success' => 'メールを作成しました。',
        'update-success' => 'メールを更新しました。',
        'delete-success' => 'メールを削除しました。',
        'delete-failed'  => 'メールの削除に失敗しました。',
        'saved-to-draft' => 'メールを下書きに保存しました。',
        'view'           => [
            'tags' => [
                'create-success' => 'タグを追加しました。',
                'delete-success' => 'タグを解除しました。',
            ],
        ],
    ],

    'activities'    => [
        'create-success' => '活動を作成しました。',
        'update-success' => '活動を更新しました。',
        'delete-success' => '活動を削除しました。',
        'delete-failed'  => '活動の削除に失敗しました。',
    ],

    'contacts'      => [
        'persons'       => [
            'create-success' => '個人を作成しました。',
            'update-success' => '個人を更新しました。',
            'delete-success' => '担当者を削除しました。',
            'delete-failed'  => '担当者の削除に失敗しました。',
            'view'           => [
                'tags' => [
                    'create-success' => 'タグを追加しました。',
                    'delete-success' => 'タグを解除しました。',
                ],
            ],
        ],

        'organizations' => [
            'create-success' => '組織を作成しました。',
            'update-success' => '組織を更新しました。',
            'delete-success' => '組織を削除しました。',
            'delete-failed'  => '組織の削除に失敗しました。',
        ],
    ],

    'settings'      => [
        'tags'            => [
            'create-success' => 'タグを作成しました。',
            'update-success' => 'タグを更新しました。',
            'delete-success' => 'タグを削除しました。',
            'delete-failed'  => 'タグの削除に失敗しました。',
        ],

        'web-forms'       => [
            'create-success'  => 'Webフォームを作成しました。',
            'updated-success' => 'Webフォームを更新しました。',
            'delete-success'  => 'Webフォームを削除しました。',
            'delete-failed'   => 'Webフォームの削除に失敗しました。',
        ],

        'attributes'      => [
            'create-success'    => '属性を作成しました。',
            'update-success'    => '属性を更新しました。',
            'destroy-success'   => '属性を削除しました。',
            'delete-failed'     => '属性の削除に失敗しました。',
            'user-define-error' => 'システム属性は削除できません。',
        ],

        'groups'          => [
            'create-success'  => 'グループを作成しました。',
            'update-success'  => 'グループを更新しました。',
            'destroy-success' => 'グループを削除しました。',
            'delete-failed'   => 'グループの削除に失敗しました。',
        ],

        'marketing'       => [
            'events'    => [
                'create-success'  => 'マーケティングイベントを作成しました。',
                'update-success'  => 'マーケティングイベントを更新しました。',
                'destroy-success' => 'マーケティングイベントを削除しました。',
                'delete-failed'   => 'イベントの削除に失敗しました。',
            ],

            'campaigns' => [
                'create-success'  => 'マーケティングキャンペーンを作成しました。',
                'update-success'  => 'マーケティングキャンペーンを更新しました。',
                'destroy-success' => 'マーケティングキャンペーンを削除しました。',
                'delete-failed'   => 'キャンペーンの削除に失敗しました。',
            ],
        ],

        'roles'           => [
            'create-success'            => 'ロールを作成しました。',
            'update-success'            => 'ロールを更新しました。',
            'delete-success'            => 'ロールを削除しました。',
            'delete-failed'             => '役割の削除に失敗しました。',
            'being-used'                => 'この役割はユーザーに割り当てられているため削除できません。',
            'last-delete-error'         => '少なくとも1つの役割が必要です。',
            'current-role-delete-error' => '現在のユーザーに割り当てられている役割は削除できません。',
        ],

        'users'           => [
            'create-success'      => 'ユーザーを作成しました。',
            'updated-success'     => 'ユーザーを更新しました。',
            'delete-success'      => 'ユーザーを削除しました。',
            'delete-failed'       => 'ユーザーの削除に失敗しました。',
            'last-delete-error'   => '少なくとも1人のユーザーが必要です。',
            'mass-delete-success' => '選択したユーザーを削除しました。',
            'mass-delete-failed'  => '選択したユーザーの削除に失敗しました。',
            'mass-update-success' => '選択したユーザーを更新しました。',
            'mass-update-failed'  => '選択したユーザーの更新に失敗しました。',
        ],

        'pipelines'       => [
            'create-success'       => 'パイプラインを作成しました。',
            'updated-success'      => 'パイプラインを更新しました。',
            'delete-success'       => 'パイプラインを削除しました。',
            'default-delete-error' => '既定のパイプラインは削除できません。',
        ],

        'sources'         => [
            'create-success' => 'ソースを作成しました。',
            'update-success' => 'ソースを更新しました。',
            'delete-success' => 'ソースを削除しました。',
            'delete-failed'  => 'ソースの削除に失敗しました。',
        ],

        'types'           => [
            'create-success' => 'タイプを作成しました。',
            'update-success' => 'タイプを更新しました。',
            'delete-success' => 'タイプを削除しました。',
            'delete-failed'  => 'タイプの削除に失敗しました。',
        ],

        'email-templates' => [
            'create-success' => 'メールテンプレートを作成しました。',
            'update-success' => 'メールテンプレートを更新しました。',
            'delete-success' => 'メールテンプレートを削除しました。',
            'delete-failed'  => 'メールテンプレートの削除に失敗しました。',
        ],

        'workflows'       => [
            'create-success' => 'ワークフローを作成しました。',
            'update-success' => 'ワークフローを更新しました。',
            'delete-success' => 'ワークフローを削除しました。',
            'delete-failed'  => 'ワークフローの削除に失敗しました。',
        ],

        'warehouses'      => [
            'create-success' => '倉庫を作成しました。',
            'update-success' => '倉庫を更新しました。',
            'delete-success' => '倉庫を削除しました。',
            'delete-failed'  => '倉庫の削除に失敗しました。',
            'view'           => [
                'locations' => [
                    'create-success' => '場所を作成しました。',
                    'update-success' => 'ロケーションを更新しました。',
                    'delete-success' => '場所を削除しました。',
                    'delete-failed'  => 'ロケーションの削除に失敗しました。',
                ],

                'tags'      => [
                    'create-success' => 'タグを追加しました。',
                    'delete-success' => 'タグを解除しました。',
                ],
            ],
        ],

        'webhooks'        => [
            'create-success' => 'Webhookを作成しました。',
            'update-success' => 'Webhookを更新しました。',
            'delete-success' => 'Webhookを削除しました。',
            'delete-failed'  => 'Webhookの削除に失敗しました。',
        ],

        'data-transfer'   => [
            'imports' => [
                'create-success'    => 'インポートを作成しました。',
                'delete-failed'     => 'インポートの削除に失敗しました。',
                'delete-success'    => 'インポートを削除しました。',
                'not-valid'         => 'インポートが有効ではありません。',
                'nothing-to-import' => 'インポートする項目がありません。',
                'setup-queue-error' => 'キューでインポートを処理するには、同期以外のキュードライバーを設定してください。',
                'update-success'    => 'インポートを更新しました。',
            ],
        ],
    ],

    'configuration' => [
        'save-success' => '設定を保存しました。',
    ],
];
