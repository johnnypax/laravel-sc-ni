<?php

namespace Tests\Unit;

use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Helpers\ScoreHelper;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    protected $userRepo;
    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepo = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepo);
    }

    /** @test */
    public function it_calculates_user_score_correctly()
    {
        $fakeUser = [
            'id' => 1,
            'name' => 'Mario Rossi',
            'purchases' => 5,
            'active' => true
        ];

        $this->userRepo->method('getUserById')->willReturn($fakeUser);

        $score = $this->userService->calculateUserScore(1);

        $this->assertEquals(100, $score); // 5*10 + 50 = 100
        $this->assertTrue(is_int($score));
    }

    /** @test */
    public function it_throws_exception_when_user_not_found()
    {
        $this->userRepo->method('getUserById')->willReturn(null);

        $this->expectException(\Exception::class);

        $this->userService->calculateUserScore(999);
    }

    /** @test */
    public function it_uses_score_helper_properly()
    {
        $score = ScoreHelper::calculateScore(10, false);
        $this->assertEquals(100, $score);

        $scoreActive = ScoreHelper::calculateScore(10, true);
        $this->assertEquals(150, $scoreActive);
        $this->assertInstanceOf(UserService::class, $this->userService);
    }
}
