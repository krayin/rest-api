<?php

return [
    'common'        => [
        'auth'                  => [
            'login' => [
                'success' => 'Login realizado com sucesso.',
                'logout'  => 'Logout realizado com sucesso.',
            ],
        ],

        'resource-not-found'    => 'O recurso solicitado não foi encontrado.',
        'forbidden-error'       => 'Você não tem permissão para acessar este recurso.',
        'unauthenticated'       => 'Você não está autenticado. Faça login para continuar.',
        'internal-server-error' => 'Ocorreu um erro inesperado no servidor. Tente novamente mais tarde.',
    ],

    'products'      => [
        'create-success'           => 'Produto adicionado com sucesso.',
        'updated-success'          => 'Produto atualizado com sucesso.',
        'delete-success'           => 'Produto excluído com sucesso.',
        'delete-failed'            => 'Falha ao excluir o produto.',
        'inventory-create-success' => 'Estoque salvo com sucesso.',
    ],

    'leads'         => [
        'create-success'  => 'Negócio adicionado com sucesso.',
        'updated-success' => 'Lead atualizado com sucesso.',
        'delete-success'  => 'Negócio excluído com sucesso.',
        'delete-failed'   => 'Falha ao excluir o lead.',
        'no-valid-files'  => 'Nenhum arquivo válido encontrado.',
        'view'            => [
            'tags'   => [
                'create-success' => 'Tag anexada com sucesso.',
                'delete-success' => 'Tag desanexada com sucesso.',
            ],

            'quotes' => [
                'create-success' => 'Orçamento anexado com sucesso.',
                'delete-success' => 'Orçamento desanexado com sucesso.',
            ],
        ],
    ],

    'quotes'        => [
        'create-success' => 'Cotação adicionada com sucesso.',
        'update-success' => 'Cotação atualizada com sucesso.',
        'delete-success' => 'Cotação excluída com sucesso.',
        'delete-failed'  => 'Falha ao excluir o orçamento.',
        'saved-to-draft' => 'Orçamento salvo como rascunho.',
    ],

    'mail'          => [
        'create-success' => 'E-mail criado com sucesso.',
        'update-success' => 'E-mail atualizado com sucesso.',
        'delete-success' => 'E-mail excluído com sucesso.',
        'delete-failed'  => 'Falha ao excluir o e-mail.',
        'saved-to-draft' => 'E-mail salvo como rascunho.',
        'view'           => [
            'tags' => [
                'create-success' => 'Tag anexada com sucesso.',
                'delete-success' => 'Tag desanexada com sucesso.',
            ],
        ],
    ],

    'activities'    => [
        'create-success' => 'Atividade adicionada com sucesso.',
        'update-success' => 'Atividade atualizada com sucesso.',
        'delete-success' => 'Atividade deletada com sucesso.',
        'delete-failed'  => 'Falha ao excluir a atividade.',
    ],

    'contacts'      => [
        'persons'       => [
            'create-success' => 'Pessoa adicionada com sucesso.',
            'update-success' => 'Pessoa atualizada com sucesso.',
            'delete-success' => 'Pessoa excluída com sucesso.',
            'delete-failed'  => 'Falha ao excluir a pessoa.',
            'view'           => [
                'tags' => [
                    'create-success' => 'Tag anexada com sucesso.',
                    'delete-success' => 'Tag desanexada com sucesso.',
                ],
            ],
        ],

        'organizations' => [
            'create-success' => 'Empresa adicionada com sucesso.',
            'update-success' => 'Empresa atualizada com sucesso.',
            'delete-success' => 'Empresa excluída com sucesso.',
            'delete-failed'  => 'Falha ao excluir a organização.',
        ],
    ],

    'settings'      => [
        'tags'            => [
            'create-success' => 'Tag criada com sucesso.',
            'update-success' => 'Tag atualizada com sucesso.',
            'delete-success' => 'Tag excluída com sucesso.',
            'delete-failed'  => 'Falha ao excluir a tag.',
        ],

        'web-forms'       => [
            'create-success'  => 'Formulário web criado com sucesso.',
            'updated-success' => 'Formulário web atualizado com sucesso.',
            'delete-success'  => 'Formulário web excluído com sucesso.',
            'delete-failed'   => 'Falha ao excluir o formulário web.',
        ],

        'attributes'      => [
            'create-success'    => 'Atributo criados com sucesso.',
            'update-success'    => 'Atributo atualizados com sucesso.',
            'destroy-success'   => 'Atributo deletados com sucesso.',
            'delete-failed'     => 'Falha ao excluir o atributo.',
            'user-define-error' => 'Atributos do sistema não podem ser excluídos.',
        ],

        'groups'          => [
            'create-success'  => 'Grupo criado com sucesso.',
            'update-success'  => 'Grupo atualizado com sucesso.',
            'destroy-success' => 'Grupo excluído com sucesso.',
            'delete-failed'   => 'Falha ao excluir o grupo.',
        ],

        'marketing'       => [
            'events'    => [
                'create-success'  => 'Evento de marketing criado com sucesso.',
                'update-success'  => 'Evento de marketing atualizado com sucesso.',
                'destroy-success' => 'Evento de marketing excluído com sucesso.',
                'delete-failed'   => 'Falha ao excluir o evento.',
            ],

            'campaigns' => [
                'create-success'  => 'Campanha de marketing criada com sucesso.',
                'update-success'  => 'Campanha de marketing atualizada com sucesso.',
                'destroy-success' => 'Campanha de marketing excluída com sucesso.',
                'delete-failed'   => 'Falha ao excluir a campanha.',
            ],
        ],

        'roles'           => [
            'create-success'            => 'Cargo adicionado com sucesso.',
            'update-success'            => 'Cargo atualizado com sucesso.',
            'delete-success'            => 'Cargo excluído com sucesso.',
            'delete-failed'             => 'Falha ao excluir a função.',
            'being-used'                => 'A função não pode ser excluída porque está atribuída a um usuário.',
            'last-delete-error'         => 'É necessária pelo menos uma função.',
            'current-role-delete-error' => 'Não é possível excluir a função atribuída ao usuário atual.',
        ],

        'users'           => [
            'create-success'      => 'Usuário criado com sucesso.',
            'updated-success'     => 'Usuário atualizado com sucesso.',
            'delete-success'      => 'Usuário excluído com sucesso.',
            'delete-failed'       => 'Falha ao excluir o usuário.',
            'last-delete-error'   => 'É necessário pelo menos um usuário.',
            'mass-delete-success' => 'Usuários selecionados excluídos com sucesso.',
            'mass-delete-failed'  => 'Falha ao excluir os usuários selecionados.',
            'mass-update-success' => 'Usuários selecionados atualizados com sucesso.',
            'mass-update-failed'  => 'Falha ao atualizar os usuários selecionados.',
        ],

        'pipelines'       => [
            'create-success'       => 'Funil adicionado com sucesso.',
            'updated-success'      => 'Pipeline atualizado com sucesso.',
            'delete-success'       => 'Funil excluído com sucesso.',
            'default-delete-error' => 'O pipeline padrão não pode ser excluído.',
        ],

        'sources'         => [
            'create-success' => 'Fonte criada com sucesso.',
            'update-success' => 'Fonte atualizada com sucesso.',
            'delete-success' => 'Fonte excluída com sucesso.',
            'delete-failed'  => 'Falha ao excluir a origem.',
        ],

        'types'           => [
            'create-success' => 'Tipo adicionado com sucesso.',
            'update-success' => 'Tipo atualizado com sucesso.',
            'delete-success' => 'Tipo excluído com sucesso.',
            'delete-failed'  => 'Falha ao excluir o tipo.',
        ],

        'email-templates' => [
            'create-success' => 'Modelo de E-mail adicionado com sucesso.',
            'update-success' => 'Modelo de E-mail atualizado com sucesso.',
            'delete-success' => 'Modelo de E-mail excluído com sucesso.',
            'delete-failed'  => 'Falha ao excluir o modelo de e-mail.',
        ],

        'workflows'       => [
            'create-success' => 'Workflow adicionado com sucesso.',
            'update-success' => 'Workflow atualizado com sucesso.',
            'delete-success' => 'Workflow excluído com sucesso.',
            'delete-failed'  => 'Falha ao excluir o fluxo de trabalho.',
        ],

        'warehouses'      => [
            'create-success' => 'Depósito adicionado com sucesso.',
            'update-success' => 'Depósito atualizado com sucesso.',
            'delete-success' => 'Depósito deletado com sucesso.',
            'delete-failed'  => 'Falha ao excluir o depósito.',
            'view'           => [
                'locations' => [
                    'create-success' => 'Localização adicionada com sucesso.',
                    'update-success' => 'Local atualizado com sucesso.',
                    'delete-success' => 'Localização deletada com sucesso.',
                    'delete-failed'  => 'Falha ao excluir o local.',
                ],

                'tags'      => [
                    'create-success' => 'Tag anexada com sucesso.',
                    'delete-success' => 'Tag desanexada com sucesso.',
                ],
            ],
        ],

        'webhooks'        => [
            'create-success' => 'Webhook adicionado com sucesso.',
            'update-success' => 'Webhook atualizado com sucesso.',
            'delete-success' => 'Webhook deletado com sucesso.',
            'delete-failed'  => 'Falha ao excluir o webhook.',
        ],

        'data-transfer'   => [
            'imports' => [
                'create-success'    => 'Import created successfully.',
                'delete-failed'     => 'Falha ao excluir a importação.',
                'delete-success'    => 'Import deleted successfully.',
                'not-valid'         => 'A importação não é válida.',
                'nothing-to-import' => 'Nada para importar.',
                'setup-queue-error' => 'Configure um driver de fila diferente de sync para processar a importação na fila.',
                'update-success'    => 'Import updated successfully.',
            ],
        ],
    ],

    'configuration' => [
        'save-success' => 'Configuração salva com sucesso.',
    ],
];
