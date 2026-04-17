<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password','department_id','created_by'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function expertAssignments()
    {
        return $this->hasMany(CourseAssignment::class, 'expert_id');
    }

    // Courses assigned as moderator
    public function moderatorAssignments()
    {
        return $this->hasMany(CourseAssignment::class, 'moderator_id');
    }

    // Courses assigned by this user (HOD)
    public function assignedCourses()
    {
        return $this->hasMany(CourseAssignment::class, 'assigned_by');
    }

    // Syllabuses created
    public function syllabuses()
    {
        return $this->hasMany(Syllabus::class, 'created_by');
    }

    public function givenRemarks()
{
    return $this->hasMany(SyllabusRemark::class, 'given_by');
}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
