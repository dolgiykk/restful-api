<?php

return [
    'auth' => [
        'user_not_found' => 'User not found.',
        'wrong_password' => 'Wrong password.',
        'logged_in' => 'Logged in successfully.',
        'logged_out' => 'Logged out successfully.',
    ],

    'email_verification' => [
        'already_verified' => 'Email already verified.',
        'verification_sent' => 'Verification email sent.',
        'invalid_link' => 'Invalid verification link.',
        'verified_successfully' => 'Email has been successfully verified.',
    ],

    'password' => [
        'wrong_password' => 'Wrong password.',
        'old_new_match' => 'Old and new passwords matched.',
        'changed_successfully' => 'Password changed successfully. All sessions were closed.',
        'invalid_token' => 'Invalid or expired token.',
    ],

    'personal_access_token' => [
        'session_closed' => 'All sessions on other devices were closed successfully.',
        'token_not_found' => 'Token not found.',
        'cannot_delete_current' => 'Cannot delete current token.',
        'deleted_successfully' => 'Token deleted successfully.',
        'delete_failed' => 'Token could not be deleted.',
    ],

    'two_factor_auth' => [
        'wrong_code' => 'Wrong verification code.',
        'verified_successfully' => '2FA verified successfully.',
    ],
];
