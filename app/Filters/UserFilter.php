<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    /**
     * @return string[]
     */
    protected function filters(): array
    {
        return [
            'id' => 'exact',
            'email' => 'like',
            'first_name' => 'like',
            'last_name' => 'like',
            'second_name' => 'like',
            'login' => 'like',
            'sex' => 'exact',
            'birthday' => 'date',
        ];
    }
}
