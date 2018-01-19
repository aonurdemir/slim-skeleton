<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace Classes\OAuthServer\Repositories;

use Classes\Container;
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
    )
    {
//        $dm = Container::getContainer()->get('dm');
//        $user = $dm->getRepository('Classes\Odm\Documents\User')->findOneBy(array('username' => $username,'password' => $password));
//
//        if (isset($user) && !empty($user)) {
//            return new UserEntity($user);
//        }
//
//        return;
        if ($username === 'alex' && $password === 'whisky') {
            return new UserEntity();
        }
        return;
    }
}
