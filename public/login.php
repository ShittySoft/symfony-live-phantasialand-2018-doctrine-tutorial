<?php

declare(strict_types=1);

namespace Application;

use Authentication\Value\ClearTextPassword;
use Authentication\Value\EmailAddress;
use Infrastructure\Authentication\ReadModel\UserExistsViaRepositoryHack;
use Infrastructure\Authentication\Repository\FileSystemUsers;

require_once __DIR__ . '/../vendor/autoload.php';

$users        = new FileSystemUsers();
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
