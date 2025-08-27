<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionQuestion extends Model
{
       /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    protected $table = 'options_questions';
    public $timestamps = false;

    protected $fillable = ['question_id', 'value'];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
