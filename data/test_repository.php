<?php
require_once 'repository.php'; // Adjust the path as needed

// Create an instance of the repository
$repository = new Repository('localhost', 'root', '', 'mydb');

try {
    // Insert a record into the Uzivatel table
    $insertData = [
        'id' => null, // Assuming 'id' is auto-increment
        'jmeno' => 'John',
        'prijmeni' => 'Doe',
        'email' => 'john.doe@example.com',
        'heslo' => password_hash('password123', PASSWORD_DEFAULT)
    ];
    $repository->insert('Uzivatel', $insertData);

    // Retrieve the same record
    $email = 'john.doe@example.com';
    $retrievedRecord = $repository->getByCondition('Uzivatel', ['email' => $email]);

    // Display the retrieved record
    echo "Retrieved Record:\n";
    print_r($retrievedRecord);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>