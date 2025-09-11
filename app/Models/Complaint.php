<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
   use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'protocol',
        'user_id',
        'title',
        'description',
        'status',
    ];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'complaint_id');
    }

    public function complaintResponses() {
        return $this->hasMany(ComplaintResponse::class,'complaint_id');
    }

    public function historical()
    {
        return $this->hasMany(Historical::class, 'complaint_id');
    }
}
