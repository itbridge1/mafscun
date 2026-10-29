<?php

return [
    'pagination_limit' => env('PAGINATION_LIMIT',100),
    'gallery_image' => [
        [
            'height'=> 150,
            'width'=> 200,
        ],
        [
            'height'=> 1200,
            'width'=> 1600,
        ],
    ],
    'fileType' => [
        'link'    => 'Link',
        'file'    => 'File',
    ],
    'post'=>[
        'advisor' => 'Advisor - सल्लाहकार',
        'chairperson' => 'Chairperson - अध्यक्ष',
        'vice_chairperson' => 'Vice Chairperson - उपाध्यक्ष',
        'woman_chairperson' => 'Woman Chairperson - महिला उपाध्यक्ष',
        'secretory' => 'Secretory - सचिव',
        'vice_secretory' => 'Vice Secretory - सहसचिव',
        'treasurer' => 'Treasurer - कोषाध्यक्ष',
        'board_member' => 'Board Member - कार्यसमिति सदस्य',
        'asc_coordinator' => 'ASC Coordinator - लेखा सुपरिवेक्षण समिति  संयोजक',
        'asc_member' => 'ASC Member - लेखा सुपरिवेक्षण समिति सदस्य',
        'member' => 'Member - सदस्य',
        'manager_ceo' => 'Manager /CEO-व्यवस्थापक/प्र.का.अ.',
        'staff' => 'Staff-कर्मचारी',
        'subcomittee_member' => 'Subcomittee Member- उपसमिति सदस्य',
        'subcomittee_coordinator' => 'Subcomittee coordinator- उपसमिति संयोजक'
    ]
,
    'feedback_options' => [
        'management_feedback' => [
            'very_good' => 'उत्तम',
            'good' => 'राम्रो',
            'satisfactory' => 'सन्तोषजनक',
            'needs_improvement' => 'सुधार गर्नुर्पर्छ',
        ],
        'notification' => [
            'yes' => 'थियो',
            'no' => 'थिएन',
        ],
    ],
];
