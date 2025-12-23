<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'              => $this->faker->name(),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('password'),
            'remember_token'    => Str::random(10),
            'role'              => UserRole::STUDENT,
        ];
    }

    public function admin()
    {
        return $this->state(fn () => [
            'role' => UserRole::ADMIN,
        ]);
    }

    public function teacher()
    {
        return $this->state(fn () => [
            'role' => UserRole::TEACHER,
        ]);
    }

    public function student()
    {
        return $this->state(fn () => [
            'role' => UserRole::STUDENT,
        ]);
    }

    public function unverified()
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}
