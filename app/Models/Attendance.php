<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'attendance_time',
    ];

    /**
     * Initialize attendance for all users as absent for a specific event.
     *
     * @param int $eventId
     */
    public static function initializeAttendanceForEvent($eventId)
    {
        $users = User::all();

        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'attendance_time' => null, // Marking user as absent initially
            ]);
        }
    }

    /**
     * Define a many-to-one relationship with the User model.
     *
     * This method establishes a relationship between the Attendance model and the User model.
     * It indicates that each Attendance record belongs to a single User.
     * It assumes that the 'attendances' table has a foreign key 'user_id'
     * that references the 'id' column in the 'users' table.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a many-to-one relationship with the Event model.
     *
     * This method establishes a relationship between the Attendance model and the Event model.
     * It indicates that each Attendance record belongs to a single Event.
     * It assumes that the 'attendances' table has a foreign key 'event_id'
     * that references the 'id' column in the 'events' table.
     *
     * @return BelongsTo
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}