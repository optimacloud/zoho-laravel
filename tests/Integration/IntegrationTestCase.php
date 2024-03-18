<?php

namespace Optimacloud\Zoho\Tests\Integration;

use Optimacloud\Zoho\Tests\TestCase;
use Optimacloud\Zoho\Tests\Fixture\Models\User;

abstract class IntegrationTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
    }

    public function getUser(): User
    {
        return new User([
            'first_name' => 'Create',
            'last_name'  => 'From User',
            'email'      => 'user@optimacloud.com',
            'phone'      => '555555555551',
        ]);
    }
}
