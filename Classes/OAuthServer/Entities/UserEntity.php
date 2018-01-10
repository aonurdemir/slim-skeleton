<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Classes\OAuthServer\Entities;

use Classes\Odm\Documents\User;
use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity extends User implements UserEntityInterface
{

    /**
     * Return the user's identifier.
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}
