<?php
// in practice you get password during registration
$password = 'heslo123';

// you'll hash it and store into DB
$hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 11]); // using old algorithm

// later you get password during login and hash from DB
if (password_verify($password, $hash)) {
    echo 'VERIFIED!' . PHP_EOL;

    // hash may have been created long time ago with different algorithm or options
    // then it needs to be rehashed...
    if (password_needs_rehash($hash, PASSWORD_DEFAULT, ['cost' => 12])) {
        echo 'NEEDS REHASH!' . PHP_EOL;

        // ... so you'll generate hash with new setting and update it in DB
        $newHash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    }

    // user is authenticated
}
