<?php

namespace Tabatii\LocalMail\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $fillable = [
        'address',
    ];

    public function messages(): hasMany
    {
        return $this->hasMany(Message::class);
    }
}
