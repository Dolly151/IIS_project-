<?php
require_once 'repository_factory.php'; // Adjust the path as needed

// Create an instance of the repository
$repository = RepositoryFactory::getRepository();

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
    $retrievedRecord = $repository->getByCondition('Uzivatel', ['ID', 'heslo', 'email'], ['email' => $email]);

    // Display the retrieved record
    echo "Retrieved Record:\n";
    print_r($retrievedRecord);

    // Delete the record by ID
    if (!empty($retrievedRecord)) {
        $idToDelete = $retrievedRecord[0]['ID'];
        $repository->deleteById('Uzivatel', $idToDelete);
        echo "Record with ID $idToDelete deleted successfully.\n";
    } else {
        echo "No record found to delete.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>