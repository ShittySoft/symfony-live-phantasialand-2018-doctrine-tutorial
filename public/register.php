<?php

declare(strict_types=1);

namespace Application;

use Authentication\Entity\User;
use Authentication\Value\ClearTextPassword;
use Authentication\Value\EmailAddress;
use Doctrine\ORM\EntityManagerInterface;
use Infrastructure\Authentication\Notification\NotifyUserOfRegistrationViaStderr;
use Infrastructure\Authentication\ReadModel\UserExistsViaRepositoryHack;
use Infrastructure\Authentication\Repository\DoctrineRepositoryUsers;
use Infrastructure\Authentication\Repository\FileSystemUsers;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var $entityManager EntityManagerInterface */
$entityManager = require __DIR__ . '/../bootstrap.php';

$users = new DoctrineRepositoryUsers(
    $entityManager->getRepository(User::class),
    $entityManager
);

$users->store(User::register(
    EmailAddress::for($_POST['emailAddress']),
    ClearTextPassword::fromPassword($_POST['password']),
    new UserExistsViaRepositoryHack($users),
    new NotifyUserOfRegistrationViaStderr()
));

