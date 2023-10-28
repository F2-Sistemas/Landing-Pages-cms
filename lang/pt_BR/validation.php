<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'O campo :attribute deve ser aceito.',
    'accepted_if' => 'O campo :attribute deve ser aceito quando :other for :value.',
    'active_url' => 'O campo :attribute deve conter um URL (Localizador Uniforme de Recursos) válido.',
    'after' => 'O campo :attribute deve conter uma data posterior a :date.',
    'after_or_equal' => 'O campo :attribute deve conter uma data igual ou posterior a :date.',
    'alpha' => 'O campo :attribute deve conter apenas letras.',
    'alpha_dash' => 'O campo :attribute deve conter apenas letras, números, traços, hífens e traços rasos.',
    'alpha_num' => 'O campo :attribute deve conter apenas letras e números.',
    'array' => 'O campo :attribute deve conter um grupo ou conjunto de elementos (array).',
    'ascii' => 'O campo :attribute deve conter apenas caracteres alfanuméricos e caracteres especiais de byte único.',
    'before' => 'O campo :attribute deve conter uma data anterior a :date.',
    'before_or_equal' => 'O campo :attribute deve conter uma data igual ou anterior a :date.',
    'between' => [
        'array' => 'O campo :attribute deve conter de :min a :max itens.',
        'file' => 'O campo :attribute deve conter de :min a :max kilobytes.',
        'numeric' => 'O campo :attribute deve conter valores de :min a :max.',
        'string' => 'O campo :attribute deve conter de :min a :max caracteres.',
    ],
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'can' => 'O campo :attribute contém um valor não autorizado.',
    'confirmed' => 'A confirmação do campo :attribute não corresponde.',
    'current_password' => 'A senha está incorreta.',
    'date' => 'O campo :attribute deve conter uma data válida.',
    'date_equals' => 'O campo :attribute deve conter uma data igual a :date.',
    'date_format' => 'O campo :attribute deve corresponder ao formato :format.',
    'decimal' => 'O campo :attribute deve conter :decimais casas decimais.',
    'declined' => 'O campo :attribute deve ser recusado.',
    'declined_if' => 'O campo :attribute deve ser recusado quando :other for :value.',
    'diferente' => 'Os campos :attribute e :other devem ser diferentes.',
    'digits' => 'O campo :attribute deve conter :digits dígitos.',
    'digits_between' => 'O campo :attribute deve conter de :min a :max dígitos.',
    'dimensions' => 'O campo :attribute possui dimensões de imagem inválidas.',
    'distinct' => 'O campo :attribute possui um valor duplicado.',
    'doesnt_end_with' => 'O campo :attribute não deve terminar com um dos seguintes: :values.',
    'doesnt_start_with' => 'O campo :attribute não deve começar com um dos seguintes: :values.',

    'email' => 'O campo :attribute deve conter um endereço válido de e-mail.',
    'ends_with' => 'O campo :attribute deve terminar com um dos seguintes: :values.',
    'enum' => 'O :attribute selecionado é inválido.',
    'exists' => 'O :attribute selecionado é inválido.',
    'file' => 'O campo :attribute deve conter um arquivo.',
    'filled' => 'O campo :attribute deve conter um valor.',
    'gt' => [
        'array' => 'O campo :attribute deve conter mais do que :value itens.',
        'file' => 'O campo :attribute deve ser maior que :value kilobytes.',
        'numeric' => 'O campo :attribute deve ser maior que :value.',
        'string' => 'O campo :attribute deve conter mais do que :value caracteres.',
    ],
    'gte' => [
        'array' => 'O campo :attribute deve conter :value ou mais itens.',
        'file' => 'O campo :attribute deve ser maior ou igual a :value kilobytes.',
        'numeric' => 'O campo :attribute deve ser maior ou igual a :value.',
        'string' => 'O campo :attribute deve ser maior ou igual a :value caracteres.',
    ],
    'image' => 'O campo :attribute deve ser uma imagem.',
    'in' => 'O :attribute selecionado é inválido.',
    'in_array' => 'O campo :attribute deve existir em :other.',
    'integer' => 'O campo :attribute deve conter um número inteiro.',
    'ip' => 'O campo :attribute deve conter um endereço válido de IP.',
    'ipv4' => 'O campo :attribute deve conter um endereço válido de IPv4.',
    'ipv6' => 'O campo :attribute deve conter um endereço válido de IPv6.',
    'json' => 'O campo :attribute deve conter uma string JSON válida.',
    'lowercase' => 'O campo :attribute deve estar em letras minúsculas.',
    'lt' => [
        'array' => 'O campo :attribute deve ter menos que :value itens.',
        'file' => 'O campo :attribute deve ser menor que :value kilobytes.',
        'numeric' => 'O campo :attribute deve conter valores menores que :value.',
        'string' => 'O campo :attribute deve ter menos que :value caracteres.',
    ],
    'lte' => [
        'array' => 'O campo :attribute não deve ter mais que :value itens.',
        'file' => 'O campo :attribute deve ser menor ou igual a :value kilobytes.',
        'numeric' => 'O campo :attribute deve conter valores menores ou iguais a :value.',
        'string' => 'O campo :attribute deve ser menor ou igual a :value caracteres.',
    ],
    'mac_address' => 'O campo :attribute deve conter um endereço MAC válido.',
    'max' => [
        'array' => 'O campo :attribute não deve ter mais que :max itens.',
        'file' => 'O campo :attribute não deve ser maior que :max kilobytes.',
        'numeric' => 'O campo :attribute não deve conter valores maiores que :max.',
        'string' => 'O campo :attribute não deve ser maior que :max caracteres.',
    ],
    'max_digits' => 'O campo :attribute não deve conter mais que :max dígitos.',
    'mimes' => 'O campo :attribute deve conter um arquivo do tipo: :values.',
    'mimetypes' => 'O campo :attribute deve conter um arquivo do tipo: :values.',
    'min' => [
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
        'file' => 'O campo :attribute deve ter pelo menos :min kilobytes.',
        'numeric' => 'O campo :attribute deve ter pelo menos :min.',
        'string' => 'O campo :attribute deve conter pelo menos :min caracteres.',
    ],
    'min_digits' => 'O campo :attribute deve conter pelo menos :min dígitos.',
    'missing' => 'O campo :attribute deve estar faltando.',
    'missing_if' => 'O campo :attribute deve estar faltando quando :other for :value.',
    'missing_unless' => 'O campo :attribute deve estar faltando, a menos que :other seja :value.',
    'missing_with' => 'O campo :attribute deve estar faltando quando :values estiver presente.',
    'missing_with_all' => 'O campo :attribute deve estar faltando quando :values estiverem presentes.',
    'multiple_of' => 'O campo :attribute deve ser um múltiplo de :value.',
    'not_in' => 'O :attribute selecionado é inválido.',
    'not_regex' => 'O formato do campo :attribute é inválido.',
    'numeric' => 'O campo :attribute deve ser um número.',
    'password' => [
        'letters' => 'O campo :attribute deve conter pelo menos uma letra.',
        'mixed' => 'O campo :attribute deve conter pelo menos uma letra maiúscula e uma minúscula.',
        'numbers' => 'O campo :attribute deve conter pelo menos um número.',
        'symbols' => 'O campo :attribute deve conter pelo menos um caractere especial.',
        'uncomprometed' => 'O :attribute fornecido apareceu em um vazamento de dados. Por favor escolha um :attribute diferente.',
    ],
    'present' => 'O campo :attribute deve estar presente.',
    'proibido' => 'O campo :attribute é proibido.',
    'proibido_if' => 'O campo :attribute é proibido quando :other é :value.',
    'prohibited_unless' => 'O campo :attribute é proibido a menos que :other esteja em :values.',
    'prohibits' => 'O campo :attribute proíbe :other de estar presente.',
    'regex' => 'O formato do campo :attribute é inválido.',
    'required' => 'O campo :attribute é obrigatório.',
    'required_array_keys' => 'O campo :attribute deve conter entradas para: :values.',
    'required_if' => 'O campo :attribute é obrigatório quando :other é :value.',
    'required_if_accepted' => 'O campo :attribute é obrigatório quando :other é aceito.',
    'required_unless' => 'O campo :attribute é obrigatório, a menos que :other esteja em :values.',
    'required_with' => 'O campo :attribute é obrigatório quando :values está presente.',
    'required_with_all' => 'O campo :attribute é obrigatório quando :values estão presentes.',
    'required_without' => 'O campo :attribute é obrigatório quando :values não está presente.',
    'required_without_all' => 'O campo :attribute é obrigatório quando nenhum dos :values está presente.',
    'same' => 'O campo :attribute deve corresponder a :other.',
    'size' => [
        'array' => 'O campo :attribute deve conter :size itens.',
        'file' => 'O campo :attribute deve ter :size kilobytes.',
        'numeric' => 'O campo :attribute deve ser :size.',
        'string' => 'O campo :attribute deve ter :size caracteres.',
    ],
    'starts_with' => 'O campo :attribute deve começar com: :values.',
    'string' => 'O campo :attribute deve conter uma sequência de caracteres alfanuméricos (string).',
    'timezone' => 'O campo :attribute deve conter um fuso horário válido.',
    'unique' => 'O :attribute já foi usado.',
    'uploaded' => 'O :attribute falhou ao carregar.',
    'uppercase' => 'O campo :attribute deve estar em letras maiúsculas.',
    'url' => 'O campo :attribute deve conter um URL (Localizador Uniforme de Recursos) válido.',
    'ulid' => 'O campo :attribute deve conter um ULID (Identificador Único Lexicograficamente Classificável) válido.',
    'uuid' => 'O campo :attribute deve conter um UUID (Identificador Único Universal) válido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'data.name' => [
            'validation.unique' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],
];
