<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\User;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\User Test Case
 */
class UserTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\User
     */
    public $User;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->User = new User();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->User);

        parent::tearDown();
    }

    /**
     * _setPassword()のテスト
     *
     * @covers \App\Model\Entity\User::_setPassword
     *
     * @return void
     */
    public function testSetPassword()
    {
        $input = 'nice-password';
        $this->User->password = $input;

        $this->assertNotSame(
            $input,
            $this->User->password,
            'パスワードフィールドがハッシュ化されていない'
        );
    }
}
