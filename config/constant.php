<?php

return [
    'email_csv_rules' => [
        'name' => 'bail|required|max:255|regex:/^[a-zA-Z0-9\s\.\']+$/',
        'email' => 'bail|required|email|unique:users,email|max:255',
        // 'dob' => 'bail|required|date|before:today',
        // 'address' => 'bail|required|string|max:255',
        // 'phone' => 'bail|required|string|min:10|max:15',
        // '' => 'bail|required|string',
    ],
];
