<?php

return [
    'common' => [
        'resource-not-found'    => 'لم يتم العثور على المورد المطلوب.',
        'forbidden-error'       => 'ليس لديك إذن للوصول إلى هذا المورد.',
        'unauthenticated'       => 'أنت غير مصرح. يرجى تسجيل الدخول للمتابعة.',
        'internal-server-error' => 'حدث خطأ غير متوقع في الخادم. يرجى المحاولة لاحقًا.',
    ],

    'products' => [
        'create-success'           => 'تم إنشاء المنتج بنجاح.',
        'updated-success'          => 'تم تحديث المنتج بنجاح.',
        'delete-success'           => 'تم حذف المنتج بنجاح.',
        'delete-failed'            => 'فشل حذف المنتج.',
        'inventory-create-success' => 'تم تخزين المخزون بنجاح.',
    ],

    'leads' => [
        'create-success'  => 'تم إنشاء العميل المحتمل بنجاح.',
        'updated-success' => 'تم تحديث العميل المحتمل بنجاح.',
        'delete-success'  => 'تم حذف العميل المحتمل بنجاح.',
        'delete-failed'   => 'فشل حذف العميل المحتمل.',

        'view' => [
            'tags' => [
                'create-success' => 'تم إرفاق العلامة بنجاح.',
                'delete-success' => 'تم فصل العلامة بنجاح.',
            ],

            'quotes' => [
                'create-success' => 'تم إرفاق العرض بنجاح.',
                'delete-success' => 'تم فصل العرض بنجاح.',
            ],
        ],
    ],

    'quotes' => [
        'create-success'  => 'تم إنشاء العرض بنجاح.',
        'update-success'  => 'تم تحديث العرض بنجاح.',
        'delete-success'  => 'تم حذف العرض بنجاح.',
        'delete-failed'   => 'فشل حذف العرض.',
        'saved-to-draft'  => 'تم حفظ العرض في المسودات.',
    ],

    'mail' => [
        'create-success' => 'تم إنشاء البريد الإلكتروني بنجاح.',
        'update-success' => 'تم تحديث البريد الإلكتروني بنجاح.',
        'delete-success' => 'تم حذف البريد الإلكتروني بنجاح.',
        'delete-failed'  => 'فشل حذف البريد الإلكتروني.',
        'saved-to-draft' => 'تم حفظ البريد الإلكتروني في المسودات.',

        'view' => [
            'tags' => [
                'create-success' => 'تم إرفاق العلامة بنجاح.',
                'delete-success' => 'تم فصل العلامة بنجاح.',
            ],
        ],
    ],

    'activities' => [
        'create-success' => 'تم إنشاء النشاط بنجاح.',
        'update-success' => 'تم تحديث النشاط بنجاح.',
        'delete-success' => 'تم حذف النشاط بنجاح.',
        'delete-failed'  => 'فشل حذف النشاط.',
    ],

    'contacts' => [
        'persons' => [
            'create-success'  => 'تم إنشاء الشخص بنجاح.',
            'update-success'  => 'تم تحديث الشخص بنجاح.',
            'delete-success'  => 'تم حذف الشخص بنجاح.',
            'delete-failed'   => 'فشل حذف الشخص.',

            'view' => [
                'tags' => [
                    'create-success' => 'تم إرفاق العلامة بنجاح.',
                    'delete-success' => 'تم فصل العلامة بنجاح.',
                ],
            ],
        ],

        'organizations' => [
            'create-success'  => 'تم إنشاء المؤسسة بنجاح.',
            'update-success'  => 'تم تحديث المؤسسة بنجاح.',
            'delete-success'  => 'تم حذف المؤسسة بنجاح.',
            'delete-failed'   => 'فشل حذف المؤسسة.',
        ],
    ],

    'settings' => [
        'tags' => [
            'create-success' => 'تم إنشاء العلامة بنجاح.',
            'update-success' => 'تم تحديث العلامة بنجاح.',
            'delete-success' => 'تم حذف العلامة بنجاح.',
            'delete-failed'  => 'فشل حذف العلامة.',
        ],

        'web-forms' => [
            'create-success'  => 'تم إنشاء النموذج الويب بنجاح.',
            'updated-success' => 'تم تحديث النموذج الويب بنجاح.',
            'delete-success'  => 'تم حذف النموذج الويب بنجاح.',
            'delete-failed'   => 'فشل حذف النموذج الويب.',
        ],

        'groups' => [
            'create-success'  => 'تم إنشاء المجموعة بنجاح.',
            'update-success'  => 'تم تحديث المجموعة بنجاح.',
            'destroy-success' => 'تم حذف المجموعة بنجاح.',
            'delete-failed'   => 'فشل حذف المجموعة.',
        ],

        'roles' => [
            'create-success' => 'تم إنشاء الدور بنجاح.',
            'update-success' => 'تم تحديث الدور بنجاح.',
            'delete-success' => 'تم حذف الدور بنجاح.',
            'delete-failed'  => 'فشل حذف الدور.',
        ],

        'users' => [
            'create-success'      => 'تم إنشاء المستخدم بنجاح.',
            'updated-success'     => 'تم تحديث المستخدم بنجاح.',
            'delete-success'      => 'تم حذف المستخدم بنجاح.',
            'delete-failed'       => 'فشل حذف المستخدم.',
            'last-delete-error'   => 'مطلوب على الأقل مستخدم واحد.',
            'mass-delete-success' => 'تم حذف المستخدمين المحددين بنجاح.',
            'mass-delete-failed'  => 'فشل حذف المستخدمين المحددين.',
            'mass-update-success' => 'تم تحديث المستخدمين المحددين بنجاح.',
            'mass-update-failed'  => 'فشل تحديث المستخدمين المحددين.',
        ],

        'pipelines' => [
            'create-success'       => 'تم إنشاء المسار بنجاح.',
            'updated-success'      => 'تم تحديث المسار بنجاح.',
            'delete-success'       => 'تم حذف المسار بنجاح.',
            'default-delete-error' => 'لا يمكن حذف المسار الافتراضي.',
        ],

        'sources' => [
            'create-success' => 'تم إنشاء المصدر بنجاح.',
            'update-success' => 'تم تحديث المصدر بنجاح.',
            'delete-success' => 'تم حذف المصدر بنجاح.',
            'delete-failed'  => 'فشل حذف المصدر.',
        ],

        'types' => [
            'create-success' => 'تم إنشاء النوع بنجاح.',
            'update-success' => 'تم تحديث النوع بنجاح.',
            'delete-success' => 'تم حذف النوع بنجاح.',
            'delete-failed'  => 'فشل حذف النوع.',
        ],

        'email-templates' => [
            'create-success' => 'تم إنشاء قالب البريد الإلكتروني بنجاح.',
            'update-success' => 'تم تحديث قالب البريد الإلكتروني بنجاح.',
            'delete-success' => 'تم حذف قالب البريد الإلكتروني بنجاح.',
            'delete-failed'  => 'فشل حذف قالب البريد الإلكتروني.',
        ],

        'workflows' => [
            'create-success' => 'تم إنشاء سير العمل بنجاح.',
            'update-success' => 'تم تحديث سير العمل بنجاح.',
            'delete-success' => 'تم حذف سير العمل بنجاح.',
            'delete-failed'  => 'فشل حذف سير العمل.',
        ],

        'warehouses' => [
            'create-success' => 'تم إنشاء المستودع بنجاح.',
            'update-success' => 'تم تحديث المستودع بنجاح.',
            'delete-success' => 'تم حذف المستودع بنجاح.',
            'delete-failed'  => 'فشل حذف المستودع.',

            'view' => [
                'locations' => [
                    'create-success' => 'تم إنشاء الموقع بنجاح.',
                    'update-success' => 'تم تحديث الموقع بنجاح.',
                    'delete-success' => 'تم حذف الموقع بنجاح.',
                    'delete-failed'  => 'فشل حذف الموقع.',
                ],

                'tags' => [
                    'create-success' => 'تم إرفاق العلامة بنجاح.',
                    'delete-success' => 'تم فصل العلامة بنجاح.',
                ],
            ],
        ],

        'webhooks' => [
            'create-success' => 'تم إنشاء الربط بنجاح.',
            'update-success' => 'تم تحديث الربط بنجاح.',
            'delete-success' => 'تم حذف الربط بنجاح.',
            'delete-failed'  => 'فشل حذف الربط.',
        ],
    ],

    'configuration' => [
        'save-success' => 'تم حفظ التكوين بنجاح.',
    ],
];
