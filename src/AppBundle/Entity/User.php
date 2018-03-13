<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/8/2018
 * Time: 6:16 PM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Faker\Provider\Base;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $username;


    /**
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="string")
     */
    private $name;


    /**
     * @ORM\Column(type="string")
     */
    private $surname;


    /**
     * @ORM\Column(type="date")
     */
    private $birthday;


    /**
     * @ORM\Column(type="string")
     */
    private $email;


    /**
     * @ORM\Column(type="string")
     */
    private $phone_nr;


    /**
     * @ORM\Column(type="boolean")
     */
    private $is_admin;


    /**
     * @ORM\Column(type="string")
     */
    private $role;


    /**
     * @ORM\Column(type="float")
     */
    private $salary;


    /**
     * @ORM\Column(type="date")
     */
    private $created_at;


    /**
     * @ORM\Column(type="date")
     */
    private $updated_at;


    /**
     * @ORM\Column(type="datetime")
     */
    private $last_loggin;


    /**
     * @ORM\Column(type="float")
     */
    private $fuel_consumption;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhoneNr()
    {
        return $this->phone_nr;
    }

    /**
     * @param mixed $phone_nr
     */
    public function setPhoneNr($phone_nr)
    {
        $this->phone_nr = $phone_nr;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->is_admin;
    }

    /**
     * @param mixed $is_admin
     */
    public function setIsAdmin($is_admin)
    {
        $this->is_admin = $is_admin;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return mixed
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @param mixed $salary
     */
    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * @param mixed $updated_at
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    /**
     * @return mixed
     */
    public function getLastLoggin()
    {
        return $this->last_loggin;
    }

    /**
     * @param mixed $last_loggin
     */
    public function setLastLoggin($last_loggin)
    {
        $this->last_loggin = $last_loggin;
    }

    /**
     * @return mixed
     */
    public function getFuelConsumption()
    {
        return $this->fuel_consumption;
    }

    /**
     * @param mixed $fuel_consumption
     */
    public function setFuelConsumption($fuel_consumption)
    {
        $this->fuel_consumption = $fuel_consumption;
    }


    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        return [
            'ROLE_USER'
        ];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}