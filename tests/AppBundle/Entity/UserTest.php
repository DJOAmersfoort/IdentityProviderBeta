<?php
declare(strict_types=1);

namespace Tests\AppBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Entity\User;
use AppBundle\Entity\Address;

class UserTest extends KernelTestCase
{
    /**
     * Creates a user with the given fields. All fields are optional
     *
     * @internal Only used in this class
     * @param    string $remoteId
     * @param    string $firstName
     * @param    string $lastName
     * @param    string $email
     * @param    string $address
     * @param    string $zipCode
     * @param    string $city
     * @return   User
     */
    private static function createUser(
        string $remoteId = null,
        string $firstName = null,
        string $lastName = null,
        string $email = null,
        string $address = null,
        string $zipCode = null,
        string $city = null
    ) : User {
        $user = new User;
        $user->setRemoteId($remoteId);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);

        $addr = new Address;
        $addr->setAddress($address);
        $addr->setZipCode($zipCode);
        $addr->setCity($city);

        $user->setAddress($addr);
        return $user;
    }

    /**
     * Test if a user generated from JSON matches what we'd expect.
     *
     * @dataProvider provideJson
     */
    public function testCreationFromJson($data, $user)
    {
        $result = User::buildFromArray(null, $data);
        $this->assertEquals($user, $result);
    }

    /**
     * Provides JSON with a variety of complexity
     *
     * @return array
     */
    public function provideJson() : array
    {
        $userFull = self::createUser(
            '1',
            'Roelof',
            'Roos',
            'github@roelof.io',
            'Marktplein 1',
            '1234 AB',
            'Zwolle'
        );
        $userBasic = self::createUser(
            '1',
            'Roelof',
            'Roos',
            'github@roelof.io'
        );

        return [
            'Full user detail' => [
                [
                    'id' => 1,
                    'memberType' => [
                        'lid',
                        'begeleider'
                    ],
                    'firstName' => 'Roelof',
                    'middleName' => null,
                    'lastName' => 'Roos',
                    'address' => 'Marktplein 1',
                    'zipcode' => '1234 AB',
                    'city' => 'Zwolle',
                    'birthDate' => '1992-01-01',
                    'phoneNumber' => '',
                    'mobileNumber' => '0612345678',
                    'email' => 'github@roelof.io',
                    'emailParents' => '',
                    'startDate' => '2005-01-01',
                    'endDate' => null,
                    'memberDay' => 'za'
                ],
                $userFull
            ],
            'From all info endpoint' => [
                [
                    'id' => 1,
                    'memberType' => [
                        'lid',
                        'begeleider'
                    ],
                    'firstName' => 'Roelof',
                    'middleName' => null,
                    'lastName' => 'Roos',
                    'email' => 'github@roelof.io',
                    'startDate' => '2005-01-01',
                    'endDate' => null,
                    'memberDay' => 'za'
                ],
                $userBasic
            ],
            'Minimal info' => [
                [
                    'id' => 6
                ],
                self::createUser('6')
            ]
        ];
    }
}
