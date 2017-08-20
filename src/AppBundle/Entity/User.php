<?php
declare(strict_types=1);

namespace AppBundle\Entity;

use AppBundle\Entity\Address;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A user, with all important data inserted. Provides enough information for
 * the Symfony firewall to do it's work too.
 *
 * @author Roelof Roos <github@roelof.io>
 */
class User implements UserInterface
{
    /**
     * Supplies the given user with data from the $data variable.  Returns the
     * updated user.
     *
     * @param  User $user  User to update, or `null` to create new
     * @param  array $data Data to use to update. Usually a response from the
     *                     IDP backend.
     * @return self
     */
    public static function buildFromArray(?User $user, array $data) : self
    {
        $user = $user ?? new User;

        $accessor = PropertyAccess::createPropertyAccessor();

        $specialMap = [
            'id' => 'remote_id',
            'address' => 'address.address',
            'zipcode' => 'address.zip_code',
            'city' => 'address.city'
        ];

        // Loop through the array to map the fields to the object, if the
        // accessor is capable. Some keys are mapped differently (such as id,
        // which is mapped to remote_id).
        foreach ($data as $key => $value) {
            if (!is_scalar($value) && $value !== null) {
                // Ignore non-scalar values
                continue;
            }

            // Find the key name we need to target
            $keyName = $specialMap[$key] ?? $key;

            // Only write if we can
            if ($accessor->isWritable($user, $keyName)) {
                $accessor->setValue($user, $keyName, $value);
            }
        }

        // Return the created user
        return $user;
    }

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $remoteId;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string hash-salted password
     */
    private $password;

    /**
     * @var Address
     */
    private $address;

    /**
     * Initialises the User, making sure there's always an address to retrieve
     */
    public function __construct()
    {
        $this->address = new Address;
    }

    /**
     * Get id
     *
     * @return string|null
     */
    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * Set remoteId
     *
     * @param string|null $remoteId
     * @return User
     */
    public function setRemoteId(?string $remoteId) : self
    {
        $this->remoteId = $remoteId;
        return $this;
    }

    /**
     * Get remoteId
     *
     * @return string|null
     */
    public function getRemoteId() : ?string
    {
        return $this->remoteId;
    }

    /**
     * Set the first name of the user
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName(?string $firstName) : self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Gets the first name of the user
     *
     * @return string|null
     */
    public function getFirstName() : ?string
    {
        return $this->firstName;
    }

    /**
     * Sets the middle name of the user
     *
     * @param string $middleName
     * @return User
     */
    public function setMiddleName(?string $middleName) : self
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Gets the middle name of the user
     *
     * @return string|null
     */
    public function getMiddleName() : ?string
    {
        return $this->middleName;
    }

    /**
     * Sets the last name of the user
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName(?string $lastName) : self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Gets the last name of the user
     *
     * @return string|null
     */
    public function getLastName() : ?string
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail(?string $email) : self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string|null
     */
    public function getEmail() : ?string
    {
        return $this->email;
    }

    /**
     * Sets the password, which shouldn't be hashed before it's sent to this
     * service, as that exposes the system to needless complexity.
     *
     * @param  string $password Plain-text password
     * @return self
     */
    public function setPassword(string $password) : self
    {
        $this->password = password_hash($password, \PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Returns the hash-salted password, or null if not (yet) set.
     *
     * @return string|null
     */
    public function getPassword() : ?string
    {
        return $this->password;
    }

    /**
     * Set address
     *
     * @param Address $address
     * @return User
     */
    public function setAddress(Address $address) : self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return Address
     */
    public function getAddress() : Address
    {
        return $this->address;
    }

    /*
        Dynamic or dummy Functions
     */

    /**
     * {@inheritdoc}
     */
    public function getSalt() : ?string
    {
        return null;
    }

    /**
     * Returns the username for the user, which is always the e-mail address.
     *
     * @return string|null
     */
    public function getUsername() : ?string
    {
        return $this->getEmail();
    }

    /**
     * Get the user roles, which is based on the department(s) the user is in
     *
     * @return array
     */
    public function getRoles() : array
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials() : void
    {
        return;
    }
}
