<?php

return [
    'common' => [
        'created_at' => 'Cadastrado em',
        'updated_at' => 'Atualizado em',
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
