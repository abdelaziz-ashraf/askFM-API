<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;


    protected $fillable = [
        'body',
        'user_id',
        'receiver'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver', 'id');
    }
}
