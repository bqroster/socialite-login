<?php

namespace Bqroster\SocialiteLogin\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSocial
 * @package Bqroster\SocialiteLogin\Models
 */
class UserSocial extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'response',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'response' => 'array',
    ];

    /**
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        $this->table = config('socialite-login.table.db');
        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('socialite-login.relationship.model'));
    }
}