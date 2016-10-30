<?php

return [
    'system' => [
        '500' => 'Looks like something went wrong, please try again.',
        '400' => 'We couldn\'t find that resource.',
        'login' => [
            'error' => [
                'facebook' => 'There was a problem logging you in with Facebook, please try again. (Error: :error)',
            ]
        ]
    ],
    'event' => [
        'not_your_own' => 'That game is not your own.',
        'created' => 'Created new game ":title".',
        'updated' => 'Updated game ":title".',
        'canceled' => 'Canceled game ":title".',
        'cannot_edit_canceled' => 'You cannot edit a canceled game.',
        'cannot_edit_past' => 'You cannot edit a past game.',
        'cannot_reschedule_active' => 'You cannot reschedule an active game.'
    ]
];
