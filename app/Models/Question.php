<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'template_id',
        'text',
        'type',
        'required',
        'order'
    ];
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function options()
    {
        return $this->hasMany(OptionQuestion::class, 'question_id');
    }
}
