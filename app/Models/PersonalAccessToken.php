<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    /**
     * Mutator untuk mengenkripsi token sebelum disimpan.
     */
    public function setTokenAttribute($value)
    {
        // Hash token + salt ekstra
        $this->attributes['token'] = hash('sha256', $value . env('APP_KEY'));
    }
}
