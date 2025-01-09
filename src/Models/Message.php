<?php

namespace Tabatii\LocalMail\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'from_address',
        'from_name',
        'subject',
        'html_body',
        'text_body',
        'attachments',
        'headers',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'headers' => 'array',
            'read_at' => 'timestamp',
        ];
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(Recipient::class);
    }

    public function scopeRead(Builder $query): void
    {
        $query->whereNotNull('read_at');
    }

    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }
}
