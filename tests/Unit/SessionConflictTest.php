<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionConflictTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Assert session time conflict.
     *
     * @dataProvider sessionsDatesDataProvider
     */
    public function test_session_data_time_conflict(\DateTime $sessionStart, \DateTime $sessionEnd, int $expected)
    {
        // New Session Start 
        $start = new Carbon("10-08-2021 09:30");

        // New Session End
        $end = new Carbon("10-08-2021 14:30");

        // Create user and associate a session for it
        $user = User::factory()->hasSessions(1, [
            'starts_at' => $sessionStart,
            'ends_at'   => $sessionEnd,
        ])->create();

        // Get all user's sessions that might conflict with the new session time interval
        $hasConflicts = $user->getSessionsBetween($start, $end);

        // Check if user has a conflict in sessions times
        $this->assertEquals($expected, count($hasConflicts));
    }

    public function sessionsDatesDataProvider() : array
    {
        return [
            "session_time_no_conflict_before" => [
                new Carbon("10-08-2021 08:30"), 		// Existing session starts_at
                new Carbon("10-08-2021 09:29"),			// Existing session ends_at
                0										// Should be a conflict
            ],
            "session_time_no_conflict_after" => [
                new Carbon("10-08-2021 18:30"),
                new Carbon("10-08-2021 19:30"),
                0
            ],
            "session_exact_time_conflict" => [
                new Carbon("10-08-2021 09:30"),
                new Carbon("10-08-2021 14:30"),
                1
            ],
            "session_start_time_conflict" => [
                new Carbon("10-08-2021 11:30"),
                new Carbon("10-08-2021 16:30"),
                1
            ],
            "session_end_time_conflict" => [
                new Carbon("10-08-2021 14:30"),
                new Carbon("10-08-2021 18:30"),
                1
            ],
            "session_start_and_end_time_conflict" => [
                new Carbon("10-08-2021 08:30"),
                new Carbon("10-08-2021 16:30"),
                1
            ],
        ];
    }
}
