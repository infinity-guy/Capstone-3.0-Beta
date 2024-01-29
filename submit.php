<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

// Set your Google OAuth client ID
$clientID = '454578970455-t8dlm42bku1avm0vca4iiigtshjnai79.apps.googleusercontent.com';

// Google Sheets credentials
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/path_to_your_credentials_file.json');

// Initialize Sheets API
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setClientId($clientID); // Set the client ID here
$client->setApplicationName('Your Application Name');
$client->setScopes(['https://www.googleapis.com/auth/drive', 'https://spreadsheets.google.com/feeds']);

if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}

$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
ServiceRequestFactory::setInstance(
    new DefaultServiceRequest($accessToken)
);

// Google Sheets spreadsheet key
$spreadsheetKey = 'your_spreadsheet_key';

// Get the posted form data
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

// Create a new Google Sheets service object
$service = new Google\Spreadsheet\SpreadsheetService();
$spreadsheet = $service->getSpreadsheetFeed()->getByKey($spreadsheetKey);
$worksheet = $spreadsheet->getWorksheetFeed()->getEntries()[0];
$listFeed = $worksheet->getListFeed();

// Add the form data to Google Sheets
$rowData = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => $message,
];
$listFeed->insert($rowData);

// Redirect back to the HTML page
header('Location: index.html');
exit;
?>
