<?php

/**
 * Adding some rules or something to users model
 */

return [
    'rules' => [
        'fullname' => 'required|string',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string',
        'password_confirmation' => 'required|string|same:password',
        'phone' => 'required|string|max:14',
        'date_of_birth' => 'required|date',
        'gender' => 'required|string|in:male,female',
        'role' => 'required|string|in:admin,psikolog,klien'
    ],
    'roles' => [
        'klien' => 'klien',
        'psikolog' => 'psikolog',
        'admin' => 'admin'
    ]
]

?>