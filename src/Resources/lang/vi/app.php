<?php

return [
    'common'        => [
        'auth'                  => [
            'login' => [
                'success' => 'Đăng nhập thành công.',
                'logout'  => 'Đăng xuất thành công.',
            ],
        ],

        'resource-not-found'    => 'Không tìm thấy tài nguyên được yêu cầu.',
        'forbidden-error'       => 'Bạn không có quyền truy cập tài nguyên này.',
        'unauthenticated'       => 'Bạn chưa được xác thực. Vui lòng đăng nhập để tiếp tục.',
        'internal-server-error' => 'Đã xảy ra lỗi không mong muốn trên máy chủ. Vui lòng thử lại sau.',
    ],

    'products'      => [
        'create-success'           => 'Sản phẩm đã được tạo thành công.',
        'updated-success'          => 'Sản phẩm đã được cập nhật thành công.',
        'delete-success'           => 'Sản phẩm đã được xóa thành công.',
        'delete-failed'            => 'Xóa sản phẩm thất bại.',
        'inventory-create-success' => 'Đã lưu tồn kho thành công.',
    ],

    'leads'         => [
        'create-success'  => 'Tạo khách hàng tiềm năng thành công.',
        'updated-success' => 'Đã cập nhật khách hàng tiềm năng thành công.',
        'delete-success'  => 'Xóa khách hàng tiềm năng thành công.',
        'delete-failed'   => 'Xóa khách hàng tiềm năng thất bại.',
        'no-valid-files'  => 'Không tìm thấy tệp hợp lệ.',
        'view'            => [
            'tags'   => [
                'create-success' => 'Đã gắn thẻ thành công.',
                'delete-success' => 'Đã gỡ thẻ thành công.',
            ],

            'quotes' => [
                'create-success' => 'Đã đính kèm báo giá thành công.',
                'delete-success' => 'Đã gỡ báo giá thành công.',
            ],
        ],
    ],

    'quotes'        => [
        'create-success' => 'Báo giá đã được tạo thành công.',
        'update-success' => 'Báo giá đã được cập nhật thành công.',
        'delete-success' => 'Báo giá đã được xóa thành công.',
        'delete-failed'  => 'Xóa báo giá thất bại.',
        'saved-to-draft' => 'Đã lưu báo giá vào bản nháp.',
    ],

    'mail'          => [
        'create-success' => 'Đã tạo email thành công.',
        'update-success' => 'Email đã được cập nhật thành công.',
        'delete-success' => 'Email đã được xóa thành công.',
        'delete-failed'  => 'Xóa email thất bại.',
        'saved-to-draft' => 'Đã lưu email vào bản nháp.',
        'view'           => [
            'tags' => [
                'create-success' => 'Đã gắn thẻ thành công.',
                'delete-success' => 'Đã gỡ thẻ thành công.',
            ],
        ],
    ],

    'activities'    => [
        'create-success' => 'Hoạt động được tạo thành công.',
        'update-success' => 'Hoạt động được cập nhật thành công.',
        'delete-success' => 'Hoạt động đã được xóa thành công.',
        'delete-failed'  => 'Xóa hoạt động thất bại.',
    ],

    'contacts'      => [
        'persons'       => [
            'create-success' => 'Người đã được tạo thành công.',
            'update-success' => 'Người đã được cập nhật thành công.',
            'delete-success' => 'Đã xóa liên hệ thành công.',
            'delete-failed'  => 'Xóa liên hệ thất bại.',
            'view'           => [
                'tags' => [
                    'create-success' => 'Đã gắn thẻ thành công.',
                    'delete-success' => 'Đã gỡ thẻ thành công.',
                ],
            ],
        ],

        'organizations' => [
            'create-success' => 'Tổ chức đã được tạo thành công.',
            'update-success' => 'Tổ chức đã được cập nhật thành công.',
            'delete-success' => 'Tổ chức đã được xóa thành công.',
            'delete-failed'  => 'Xóa tổ chức thất bại.',
        ],
    ],

    'settings'      => [
        'tags'            => [
            'create-success' => 'Thẻ được tạo thành công.',
            'update-success' => 'Thẻ đã được cập nhật thành công.',
            'delete-success' => 'Thẻ đã được xóa thành công.',
            'delete-failed'  => 'Xóa thẻ thất bại.',
        ],

        'web-forms'       => [
            'create-success'  => 'Đã tạo biểu mẫu web thành công.',
            'updated-success' => 'Đã cập nhật biểu mẫu web thành công.',
            'delete-success'  => 'Đã xóa biểu mẫu web thành công.',
            'delete-failed'   => 'Xóa biểu mẫu web thất bại.',
        ],

        'attributes'      => [
            'create-success'    => 'Thuộc tính đã được tạo thành công.',
            'update-success'    => 'Thuộc tính đã được cập nhật thành công.',
            'destroy-success'   => 'Thuộc tính đã được xóa thành công.',
            'delete-failed'     => 'Xóa thuộc tính thất bại.',
            'user-define-error' => 'Không thể xóa thuộc tính hệ thống.',
        ],

        'groups'          => [
            'create-success'  => 'Tạo nhóm thành công.',
            'update-success'  => 'Cập nhật nhóm thành công.',
            'destroy-success' => 'Xóa nhóm thành công.',
            'delete-failed'   => 'Xóa nhóm thất bại.',
        ],

        'marketing'       => [
            'events'    => [
                'create-success'  => 'Đã tạo sự kiện tiếp thị thành công.',
                'update-success'  => 'Đã cập nhật sự kiện tiếp thị thành công.',
                'destroy-success' => 'Đã xóa sự kiện tiếp thị thành công.',
                'delete-failed'   => 'Xóa sự kiện thất bại.',
            ],

            'campaigns' => [
                'create-success'  => 'Đã tạo chiến dịch tiếp thị thành công.',
                'update-success'  => 'Đã cập nhật chiến dịch tiếp thị thành công.',
                'destroy-success' => 'Đã xóa chiến dịch tiếp thị thành công.',
                'delete-failed'   => 'Xóa chiến dịch thất bại.',
            ],
        ],

        'roles'           => [
            'create-success'            => 'Vai trò đã được tạo thành công.',
            'update-success'            => 'Vai trò đã được cập nhật thành công.',
            'delete-success'            => 'Vai trò đã được xóa thành công.',
            'delete-failed'             => 'Xóa vai trò thất bại.',
            'being-used'                => 'Không thể xóa vai trò vì đang được gán cho người dùng.',
            'last-delete-error'         => 'Cần ít nhất một vai trò.',
            'current-role-delete-error' => 'Không thể xóa vai trò đang được gán cho người dùng hiện tại.',
        ],

        'users'           => [
            'create-success'      => 'Đã tạo người dùng thành công.',
            'updated-success'     => 'Đã cập nhật người dùng thành công.',
            'delete-success'      => 'Đã xóa người dùng thành công.',
            'delete-failed'       => 'Xóa người dùng thất bại.',
            'last-delete-error'   => 'Cần ít nhất một người dùng.',
            'mass-delete-success' => 'Đã xóa những người dùng được chọn thành công.',
            'mass-delete-failed'  => 'Xóa những người dùng được chọn thất bại.',
            'mass-update-success' => 'Đã cập nhật những người dùng được chọn thành công.',
            'mass-update-failed'  => 'Cập nhật những người dùng được chọn thất bại.',
        ],

        'pipelines'       => [
            'create-success'       => 'Quy trình đã được tạo thành công.',
            'updated-success'      => 'Đã cập nhật quy trình thành công.',
            'delete-success'       => 'Quy trình đã được xóa thành công.',
            'default-delete-error' => 'Không thể xóa quy trình mặc định.',
        ],

        'sources'         => [
            'create-success' => 'Tạo nguồn thành công.',
            'update-success' => 'Cập nhật nguồn thành công.',
            'delete-success' => 'Xóa nguồn thành công.',
            'delete-failed'  => 'Xóa nguồn thất bại.',
        ],

        'types'           => [
            'create-success' => 'Loại đã được tạo thành công.',
            'update-success' => 'Loại đã được cập nhật thành công.',
            'delete-success' => 'Loại đã được xóa thành công.',
            'delete-failed'  => 'Xóa loại thất bại.',
        ],

        'email-templates' => [
            'create-success' => 'Mẫu Email đã được tạo thành công.',
            'update-success' => 'Mẫu Email đã được cập nhật thành công.',
            'delete-success' => 'Mẫu Email đã được xóa thành công.',
            'delete-failed'  => 'Xóa mẫu email thất bại.',
        ],

        'workflows'       => [
            'create-success' => 'Quy trình đã được tạo thành công.',
            'update-success' => 'Quy trình đã được cập nhật thành công.',
            'delete-success' => 'Quy trình đã được xóa thành công.',
            'delete-failed'  => 'Xóa quy trình làm việc thất bại.',
        ],

        'warehouses'      => [
            'create-success' => 'Kho hàng đã được tạo thành công.',
            'update-success' => 'Kho hàng đã được cập nhật thành công.',
            'delete-success' => 'Kho hàng đã được xóa thành công.',
            'delete-failed'  => 'Xóa kho thất bại.',
            'view'           => [
                'locations' => [
                    'create-success' => 'Vị trí đã được tạo thành công.',
                    'update-success' => 'Đã cập nhật vị trí thành công.',
                    'delete-success' => 'Vị trí đã được xóa thành công.',
                    'delete-failed'  => 'Xóa vị trí thất bại.',
                ],

                'tags'      => [
                    'create-success' => 'Đã gắn thẻ thành công.',
                    'delete-success' => 'Đã gỡ thẻ thành công.',
                ],
            ],
        ],

        'webhooks'        => [
            'create-success' => 'Webhook đã được tạo thành công.',
            'update-success' => 'Webhook đã được cập nhật thành công.',
            'delete-success' => 'Webhook đã được xóa thành công.',
            'delete-failed'  => 'Xóa webhook thất bại.',
        ],

        'data-transfer'   => [
            'imports' => [
                'create-success'    => 'Import created successfully.',
                'delete-failed'     => 'Xóa bản nhập thất bại.',
                'delete-success'    => 'Import deleted successfully.',
                'not-valid'         => 'Bản nhập không hợp lệ.',
                'nothing-to-import' => 'Không có gì để nhập.',
                'setup-queue-error' => 'Hãy cấu hình trình điều khiển hàng đợi khác sync để xử lý bản nhập trong hàng đợi.',
                'update-success'    => 'Import updated successfully.',
            ],
        ],
    ],

    'configuration' => [
        'save-success' => 'Đã lưu cấu hình thành công.',
    ],
];
