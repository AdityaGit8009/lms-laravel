<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    protected $table = 'tbl_feedback_responses';
    protected $primaryKey = 'response_id';

    protected $fillable = [
        'form_id',
        'user_id',
        'email',
        'student_id',
        'student_name',
        'session_id',
        'center_id',
        'mobile',
        'answers_json',
    ];

    protected $casts = [
        'answers_json' => 'array',
    ];

    public function scopeForStudent(Builder $query, int $studentId, ?int $userId, ?string $email): Builder
    {
        return $query->where(function (Builder $identity) use ($studentId, $userId, $email) {
            $identity->where('student_id', $studentId);
            if ($userId !== null) {
                $identity->orWhere('user_id', $userId);
            }
            if ($email !== null && $email !== '') {
                $identity->orWhere('email', $email);
            }
        });
    }

    public function form()
    {
        return $this->belongsTo(FeedbackForm::class, 'form_id', 'form_id');
    }
}
