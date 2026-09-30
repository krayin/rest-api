<?php

return [
    'common'        => [
        'auth'                  => [
            'login' => [
                'success' => '로그인되었습니다.',
                'logout'  => '로그아웃되었습니다.',
            ],
        ],

        'resource-not-found'    => '요청한 리소스를 찾을 수 없습니다.',
        'forbidden-error'       => '이 리소스에 접근할 권한이 없습니다.',
        'unauthenticated'       => '인증되지 않았습니다. 계속하려면 로그인하세요.',
        'internal-server-error' => '서버에서 예기치 않은 오류가 발생했습니다. 나중에 다시 시도해 주세요.',
    ],

    'products'      => [
        'create-success'           => '제품이 성공적으로 생성되었습니다.',
        'updated-success'          => '제품이 성공적으로 수정되었습니다.',
        'delete-success'           => '제품이 성공적으로 삭제되었습니다.',
        'delete-failed'            => '제품 삭제에 실패했습니다.',
        'inventory-create-success' => '재고가 저장되었습니다.',
    ],

    'leads'         => [
        'create-success'  => '리드가 성공적으로 생성되었습니다.',
        'updated-success' => '리드가 업데이트되었습니다.',
        'delete-success'  => '리드가 성공적으로 삭제되었습니다.',
        'delete-failed'   => '리드 삭제에 실패했습니다.',
        'no-valid-files'  => '유효한 파일을 찾을 수 없습니다.',
        'view'            => [
            'tags'   => [
                'create-success' => '태그가 연결되었습니다.',
                'delete-success' => '태그가 해제되었습니다.',
            ],

            'quotes' => [
                'create-success' => '견적이 첨부되었습니다.',
                'delete-success' => '견적이 분리되었습니다.',
            ],
        ],
    ],

    'quotes'        => [
        'create-success' => '견적서가 생성되었습니다.',
        'update-success' => '견적서가 수정되었습니다.',
        'delete-success' => '견적서가 삭제되었습니다.',
        'delete-failed'  => '견적 삭제에 실패했습니다.',
        'saved-to-draft' => '견적이 임시 저장되었습니다.',
    ],

    'mail'          => [
        'create-success' => '이메일이 생성되었습니다.',
        'update-success' => '이메일이 성공적으로 수정되었습니다.',
        'delete-success' => '이메일이 성공적으로 삭제되었습니다.',
        'delete-failed'  => '이메일 삭제에 실패했습니다.',
        'saved-to-draft' => '이메일이 임시 저장되었습니다.',
        'view'           => [
            'tags' => [
                'create-success' => '태그가 연결되었습니다.',
                'delete-success' => '태그가 해제되었습니다.',
            ],
        ],
    ],

    'activities'    => [
        'create-success' => '활동이 성공적으로 생성되었습니다.',
        'update-success' => '활동이 성공적으로 수정되었습니다.',
        'delete-success' => '활동이 성공적으로 삭제되었습니다.',
        'delete-failed'  => '활동 삭제에 실패했습니다.',
    ],

    'contacts'      => [
        'persons'       => [
            'create-success' => '개인이 생성되었습니다.',
            'update-success' => '개인이 수정되었습니다.',
            'delete-success' => '연락처가 삭제되었습니다.',
            'delete-failed'  => '연락처 삭제에 실패했습니다.',
            'view'           => [
                'tags' => [
                    'create-success' => '태그가 연결되었습니다.',
                    'delete-success' => '태그가 해제되었습니다.',
                ],
            ],
        ],

        'organizations' => [
            'create-success' => '조직이 생성되었습니다.',
            'update-success' => '조직이 수정되었습니다.',
            'delete-success' => '조직이 삭제되었습니다.',
            'delete-failed'  => '조직 삭제에 실패했습니다.',
        ],
    ],

    'settings'      => [
        'tags'            => [
            'create-success' => '태그가 생성되었습니다.',
            'update-success' => '태그가 수정되었습니다.',
            'delete-success' => '태그가 삭제되었습니다.',
            'delete-failed'  => '태그 삭제에 실패했습니다.',
        ],

        'web-forms'       => [
            'create-success'  => '웹 폼이 생성되었습니다.',
            'updated-success' => '웹 폼이 업데이트되었습니다.',
            'delete-success'  => '웹 폼이 삭제되었습니다.',
            'delete-failed'   => '웹 폼 삭제에 실패했습니다.',
        ],

        'attributes'      => [
            'create-success'    => '속성이 생성되었습니다.',
            'update-success'    => '속성이 수정되었습니다.',
            'destroy-success'   => '속성이 삭제되었습니다.',
            'delete-failed'     => '속성 삭제에 실패했습니다.',
            'user-define-error' => '시스템 속성은 삭제할 수 없습니다.',
        ],

        'groups'          => [
            'create-success'  => '그룹이 생성되었습니다.',
            'update-success'  => '그룹이 수정되었습니다.',
            'destroy-success' => '그룹이 삭제되었습니다.',
            'delete-failed'   => '그룹 삭제에 실패했습니다.',
        ],

        'marketing'       => [
            'events'    => [
                'create-success'  => '마케팅 이벤트가 생성되었습니다.',
                'update-success'  => '마케팅 이벤트가 업데이트되었습니다.',
                'destroy-success' => '마케팅 이벤트가 삭제되었습니다.',
                'delete-failed'   => '이벤트 삭제에 실패했습니다.',
            ],

            'campaigns' => [
                'create-success'  => '마케팅 캠페인이 생성되었습니다.',
                'update-success'  => '마케팅 캠페인이 업데이트되었습니다.',
                'destroy-success' => '마케팅 캠페인이 삭제되었습니다.',
                'delete-failed'   => '캠페인 삭제에 실패했습니다.',
            ],
        ],

        'roles'           => [
            'create-success'            => '역할이 생성되었습니다.',
            'update-success'            => '역할이 수정되었습니다.',
            'delete-success'            => '역할이 삭제되었습니다.',
            'delete-failed'             => '역할 삭제에 실패했습니다.',
            'being-used'                => '사용자에게 할당된 역할은 삭제할 수 없습니다.',
            'last-delete-error'         => '최소 하나의 역할이 필요합니다.',
            'current-role-delete-error' => '현재 사용자에게 할당된 역할은 삭제할 수 없습니다.',
        ],

        'users'           => [
            'create-success'      => '사용자가 생성되었습니다.',
            'updated-success'     => '사용자가 업데이트되었습니다.',
            'delete-success'      => '사용자가 삭제되었습니다.',
            'delete-failed'       => '사용자 삭제에 실패했습니다.',
            'last-delete-error'   => '최소 한 명의 사용자가 필요합니다.',
            'mass-delete-success' => '선택한 사용자가 삭제되었습니다.',
            'mass-delete-failed'  => '선택한 사용자 삭제에 실패했습니다.',
            'mass-update-success' => '선택한 사용자가 업데이트되었습니다.',
            'mass-update-failed'  => '선택한 사용자 업데이트에 실패했습니다.',
        ],

        'pipelines'       => [
            'create-success'       => '파이프라인이 생성되었습니다.',
            'updated-success'      => '파이프라인이 업데이트되었습니다.',
            'delete-success'       => '파이프라인이 삭제되었습니다.',
            'default-delete-error' => '기본 파이프라인은 삭제할 수 없습니다.',
        ],

        'sources'         => [
            'create-success' => '소스가 생성되었습니다.',
            'update-success' => '소스가 수정되었습니다.',
            'delete-success' => '소스가 삭제되었습니다.',
            'delete-failed'  => '소스 삭제에 실패했습니다.',
        ],

        'types'           => [
            'create-success' => '유형이 생성되었습니다.',
            'update-success' => '유형이 수정되었습니다.',
            'delete-success' => '유형이 삭제되었습니다.',
            'delete-failed'  => '유형 삭제에 실패했습니다.',
        ],

        'email-templates' => [
            'create-success' => '이메일 템플릿이 생성되었습니다.',
            'update-success' => '이메일 템플릿이 수정되었습니다.',
            'delete-success' => '이메일 템플릿이 삭제되었습니다.',
            'delete-failed'  => '이메일 템플릿 삭제에 실패했습니다.',
        ],

        'workflows'       => [
            'create-success' => '워크플로우가 생성되었습니다.',
            'update-success' => '워크플로우가 수정되었습니다.',
            'delete-success' => '워크플로우가 삭제되었습니다.',
            'delete-failed'  => '워크플로 삭제에 실패했습니다.',
        ],

        'warehouses'      => [
            'create-success' => '창고가 생성되었습니다.',
            'update-success' => '창고가 수정되었습니다.',
            'delete-success' => '창고가 삭제되었습니다.',
            'delete-failed'  => '창고 삭제에 실패했습니다.',
            'view'           => [
                'locations' => [
                    'create-success' => '위치가 생성되었습니다.',
                    'update-success' => '위치가 업데이트되었습니다.',
                    'delete-success' => '위치가 삭제되었습니다.',
                    'delete-failed'  => '위치 삭제에 실패했습니다.',
                ],

                'tags'      => [
                    'create-success' => '태그가 연결되었습니다.',
                    'delete-success' => '태그가 해제되었습니다.',
                ],
            ],
        ],

        'webhooks'        => [
            'create-success' => '웹훅이 생성되었습니다.',
            'update-success' => '웹훅이 수정되었습니다.',
            'delete-success' => '웹훅이 삭제되었습니다.',
            'delete-failed'  => '웹훅 삭제에 실패했습니다.',
        ],

        'data-transfer'   => [
            'imports' => [
                'create-success'    => '가져오기가 생성되었습니다.',
                'delete-failed'     => '가져오기 삭제에 실패했습니다.',
                'delete-success'    => '가져오기가 삭제되었습니다.',
                'not-valid'         => '가져오기가 유효하지 않습니다.',
                'nothing-to-import' => '가져올 항목이 없습니다.',
                'setup-queue-error' => '대기열에서 가져오기를 처리하려면 sync 이외의 큐 드라이버를 설정하세요.',
                'update-success'    => '가져오기가 수정되었습니다.',
            ],
        ],
    ],

    'configuration' => [
        'save-success' => '설정이 저장되었습니다.',
    ],
];
