<?php

declare(strict_types=1);

namespace Application;

use Authentication\Entity\User;
use Authentication\Value\ClearTextPassword;
use Authentication\Value\EmailAddress;
use Doctrine\ORM\EntityManagerInterface;
use Infrastructure\Authentication\ReadModel\UserExistsViaRepositoryHack;
use Infrastructure\Authentication\Repository\DoctrineRepositoryUsers;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var $entityManager EntityManagerInterface */
$entityManager = require __DIR__ . '/../bootstrap.php';

$users        = new DoctrineRepositoryUsers(
    $entityManager->getRepository(User::class),
    $entityManager
);
$userExists   = new UserExistsViaRepositoryHack($users);
$emailAddress = EmailAddress::for($_POST['emailAddress']);
$password     = ClearTextPassword::forLogin($_POST['password']);

if (! $userExists->__invoke($emailAddress)) {
    http_response_code(403);
    echo 'No dice';

    return;
}

$user = $users->get($emailAddress);

if (! $user->logIn($password)) {
    http_response_code(403);
    echo 'No dice';

    return;
}

echo 'OK!';
