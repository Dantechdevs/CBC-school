<?php

return [
    'name'          => env('SCHOOL_NAME', 'CBC School Management System'),
    'motto'         => env('SCHOOL_MOTTO', 'Excellence Through Competency'),
    'type'          => env('SCHOOL_TYPE', 'primary'), // primary, secondary, mixed
    'address'       => env('SCHOOL_ADDRESS', ''),
    'phone'         => env('SCHOOL_PHONE', ''),
    'email'         => env('SCHOOL_EMAIL', ''),
    'academic_year' => (int) env('CURRENT_ACADEMIC_YEAR', now()->year),
    'current_term'  => (int) env('CURRENT_TERM', 1),

    'grade_levels' => [
        'pre_primary'     => ['PP1', 'PP2'],
        'lower_primary'   => ['Grade 1', 'Grade 2', 'Grade 3'],
        'upper_primary'   => ['Grade 4', 'Grade 5', 'Grade 6'],
        'junior_secondary'=> ['Grade 7', 'Grade 8', 'Grade 9'],
        'senior_secondary'=> ['Grade 10', 'Grade 11', 'Grade 12'],
    ],

    'rubric_levels' => [
        'EE' => ['label' => 'Exceeds Expectation',    'value' => 4, 'color' => 'green'],
        'ME' => ['label' => 'Meets Expectation',       'value' => 3, 'color' => 'blue'],
        'AE' => ['label' => 'Approaches Expectation',  'value' => 2, 'color' => 'yellow'],
        'BE' => ['label' => 'Below Expectation',        'value' => 1, 'color' => 'red'],
    ],

    'terms' => [1 => 'Term 1', 2 => 'Term 2', 3 => 'Term 3'],

    'assessment_weights' => [
        'formative'  => 40, // 40%
        'summative'  => 60, // 60%
    ],

    'fees' => [
        'mpesa_enabled' => env('MPESA_ENV', 'sandbox') !== null,
        'receipt_prefix' => 'RCP',
        'invoice_prefix' => 'INV',
    ],

    'sms' => [
        'enabled'   => !empty(env('AT_API_KEY')),
        'provider'  => 'africastalking',
        'sender_id' => env('AT_SENDER_ID', 'SCHOOL'),
    ],
];
