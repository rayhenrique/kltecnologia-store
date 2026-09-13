<?php

return [
    'accepted' => 'O campo :attribute deve ser aceito.',
    'array' => 'O campo :attribute deve ser uma lista.',
    'boolean' => 'O campo :attribute deve ser verdadeiro ou falso.',
    'confirmed' => 'A confirmação de :attribute não confere.',
    'decimal' => 'O campo :attribute deve ter entre :min e :max casas decimais.',
    'email' => 'O campo :attribute deve conter um e-mail válido.',
    'file' => 'O campo :attribute deve ser um arquivo.',
    'image' => 'O campo :attribute deve ser uma imagem.',
    'in' => 'O valor selecionado para :attribute é inválido.',
    'max' => [
        'array' => 'O campo :attribute não pode ter mais de :max itens.',
        'file' => 'O arquivo :attribute não pode ser maior que :max kilobytes.',
        'numeric' => 'O campo :attribute não pode ser maior que :max.',
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'min' => [
        'array' => 'O campo :attribute deve ter pelo menos :min itens.',
        'file' => 'O arquivo :attribute deve ter pelo menos :min kilobytes.',
        'numeric' => 'O campo :attribute deve ser pelo menos :min.',
        'string' => 'O campo :attribute deve ter pelo menos :min caracteres.',
    ],
    'mimes' => 'O arquivo :attribute deve ser do tipo: :values.',
    'required' => 'O campo :attribute é obrigatório.',
    'string' => 'O campo :attribute deve ser um texto.',
    'unique' => 'O valor informado para :attribute já está em uso.',
    'attributes' => [
        'name' => 'nome', 'email' => 'e-mail', 'password' => 'senha',
        'password_confirmation' => 'confirmação da senha', 'current_password' => 'senha atual',
        'title' => 'título', 'description' => 'descrição', 'price' => 'preço',
        'cover' => 'capa', 'file' => 'arquivo do produto', 'status' => 'status',
    ],
];
