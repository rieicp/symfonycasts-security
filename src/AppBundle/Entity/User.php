<?php
/**
 * Created by PhpStorm.
 * User: nwe
 * Date: 02.07.19
 * Time: 17:41
 */

namespace AppBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
