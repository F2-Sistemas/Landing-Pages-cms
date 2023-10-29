<?php

return [
    'common' => [
        'created_at' => 'Cadastrado em',
        'updated_at' => 'Atualizado em',
        'deleted_at' => 'Excluído em',
        'filters' => [
            'ternary' => [
                'active' => 'Ativo',
                'both' => 'Ambos',
                'inactive' => 'Inativo',
                'no_one' => 'Sem filtro',
            ],
        ],
        'errors' => [
            'generic_error_title' => 'Erro ao carregar página',
            'generic_error_message' => 'Por favor, volte mais tarde. Se o erro persistir, contate nossa equipe.',
        ],
    ],
    'Tenant' => [
        'table' => [
            'id' => 'ID',
            'name' => 'Agência',
            'created_at' => 'Cadastrado em',
            'updated_at' => 'Atualizado em',
        ],
        'form' => [
            'id' => 'Identificador interno (ID)',
            'id_helperText' => 'Use letras minúsculas e números. Não use espaços ou caracteres especiais.',
            'name' => 'Nome da agência',
            'meta_config' => [
                'label' => 'Configurações adicionais',
                'keyLabel' => 'Chave',
                'keyPlaceholder' => 'Propriedade',
                'valueLabel' => 'Valor',
                'valuePlaceholder' => 'Valor',
                'addActionLabel' => 'Adicionar propriedade',
                'color_label' => 'Cor',
            ],
        ],
        'actions' => [
            'create' => 'Cadastrar novo tenant',
            'delete' => 'Inativar tenant',
            'restore' => 'Restaurar tenant',
        ],
    ],
    'User' => [
        'table' => [
            'id' => 'ID',
            'name' => 'Nome',
            'email' => 'E-email',
            'tenant_id' => 'Agência',
            'is_admin' => 'É admin?',
            'deleted_at' => 'Ativo?',
            'created_at' => 'Cadastrado em',
            'updated_at' => 'Atualizado em',
        ],
        'form' => [
            'id' => 'ID',
            'name' => 'Nome',
            'email' => 'E-email',
            'tenant_id' => 'Agência',
            'is_admin' => 'É admin?',
            'role' => 'Papel',
            'roles' => 'Papéis',
            'permission' => 'Permissão',
            'permissions' => 'Permissões',
            'password' => 'Senha',
            'show_password' => 'Mostrar senha',
        ],
        'actions' => [
            'create' => 'Cadastrar novo usuário',
            'delete' => 'Inativar usuário',
            'restore' => 'Restaurar usuário',
        ],
    ],
    'Page' => [
        'modelLabel' => 'Página',
        'pluralModelLabel' => 'Páginas',
        'table' => [
            'id' => 'ID',
            'title' => 'Título',
            'slug' => 'Slug',
            'tenant_id' => 'Agência',
            'deleted_at' => 'Ativo?',
            'only_auth' => 'Apenas para usuários',
            'published' => 'Publicado?',
            'created_at' => 'Cadastrado em',
            'updated_at' => 'Atualizado em',
        ],
        'form' => [
            'id' => 'ID',
            'title' => 'Título',
            'slug' => 'Slug',
            'slug_helperText' => 'Identificador na URL. (Sem espaço. Apenas letras, números e hífen)',
            'name' => 'Nome',
            'view' => 'Template',
            'tenant_id' => 'Agência',
            'only_auth' => 'Apenas para usuários',
            'only_auth_helperText' => 'Se marcar essa opção, apenas usuários autenticados poderão ver essa página.',
            'published' => 'Publicado?',
            'visibility' => 'Visibilidade',
            'visual_settings' => 'Configurações de estilos',
            'published_helperText' => 'Se a página pode ser acessada.',
            'meta_config' => [
                'heading' => 'Informações da página',
            ],
        ],
        'actions' => [
            'create' => 'Cadastrar novo item',
            'delete' => 'Inativar item',
            'restore' => 'Restaurar item',
        ],
        'filters' => [
            'ternary' => [
                'placeholder' => 'Todas as páginas',
                'truelabel' => 'Páginas ativas',
                'falselabel' => 'Páginas inativas',
            ],
        ],
        'errors' => [
            'view_not_exists_title' => 'Erro ao carregar página',
            'view_not_exists_message' => 'Por favor, volte mais tarde. Se o erro persistir, contate nossa equipe.',
        ]
    ],
    /* 'Demo' => [
        'table' => [
            'id' => 'ID',
            'name' => 'Nome',
            'tenant_id' => 'Agência',
            'deleted_at' => 'Ativo?',
            'created_at' => 'Cadastrado em',
            'updated_at' => 'Atualizado em',
        ],
        'form' => [
            'id' => 'ID',
            'name' => 'Nome',
            'tenant_id' => 'Agência',
        ],
        'actions' => [
            'create' => 'Cadastrar novo item',
            'delete' => 'Inativar item',
            'restore' => 'Restaurar item',
        ],
    ], */
    'AuditFormList' => [
        'fill_form' => 'Preencher formulário',
        'form_canot_be_deleted_bc_has_answer' => 'Este formulário não pode ser excluído pois já tem resposta.',
    ],
    'AuditFormLists' => [
        'title' => 'Calendários de fiscalizações',
        'navigation_label' => 'Calendários de fisc.',
        'actions' => [
            'goto_calendar' => 'Ver calendário',
            'create' => 'Cadastrar fiscalização',
        ],
    ],
];
