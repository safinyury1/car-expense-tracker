<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'email'    => 'Поле :attribute должно быть действительным email адресом.',
    'min'      => [
        'string' => 'Поле :attribute должно содержать не менее :min символов.',
    ],
    'confirmed' => 'Подтверждение :attribute не совпадает.',
    'attributes' => [
        'email' => 'email',
        'password' => 'пароль',
        'name' => 'имя',
    ],
];