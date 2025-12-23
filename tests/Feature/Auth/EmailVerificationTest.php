<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function email_can_be_verified()
    {
        Event::fake();

        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $this->actingAs($user);

        $response = $this->get($verificationUrl);

        // Laravel default redirect after verification
        $response->assertRedirect('/dashboard?verified=1');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);
    }

    /** @test */
    public function email_is_not_verified_with_invalid_hash()
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id'   => $user->id,
                'hash' => sha1('wrong-email@example.com'),
            ]
        );

        $this->actingAs($user);

        $this->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
