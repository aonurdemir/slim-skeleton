<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Classes\OAuthServer\Repositories;

use Classes\OAuthServer\Entities\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $container = Container::getContainer();
        $dm = $container->get("dm");

        if ($username === 'alex' && $password === 'whisky') {
            return new UserEntity();
        }

        return;
    }
}
