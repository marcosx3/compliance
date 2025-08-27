<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
       /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;


    protected $fillable = ['complaint_id', 'question_id', 'text_response', 'response_option_id'];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function option()
    {
        return $this->belongsTo(OptionQuestion::class, 'response_option_id');
    }
}
