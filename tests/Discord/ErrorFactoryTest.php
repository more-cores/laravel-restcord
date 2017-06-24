<?php

namespace LaravelRestcord\Discord;

use LaravelRestcord\Discord\Webhooks\TooManyWebhooks;
use PHPUnit\Framework\TestCase;

class ErrorFactoryTest extends TestCase
{
    /** @var ErrorFactory */
    protected $errorFactory;

    public function setUp()
    {
        parent::setUp();

        $this->errorFactory = new ErrorFactory();
    }

    /** @test */
    public function doesntThrowAnythingWhenCodeIsntRecognized()
    {
        $this->assertNull($this->errorFactory->make(time(), uniqid()));
    }

    /** @test */
    public function recognizesTooManyWebhooks()
    {
        $code = 30007;
        $message = uniqid();

        $exception = $this->errorFactory->make($code, $message);

        $this->assertInstanceOf(TooManyWebhooks::class, $exception);
        $this->assertEquals($code, $exception->getCode());
        $this->assertEquals($message, $exception->getMessage());
    }
}
