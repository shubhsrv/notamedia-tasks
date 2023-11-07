<?php
/**
 * Downloads a Wikipedia page, extracts headings, abstracts, pictures, and links from sections,
 * and saves the data into the wiki_sections table in a MySQL database.
 *
 */
 
// DB connection details
$dbHost = "your_database_host";
$dbUser = "your_username";
$dbPassword = "your_password";
$dbName = "your_database_name";

// Create DB connection
$connection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Wikipedia page URL
$wikipediaURL = "https://www.wikipedia.org/";

// Download the page content
$pageContent = file_get_contents($wikipediaURL);

// Create a DOMDocument instance and load the HTML content
$htmlDom = new DOMDocument();
@$htmlDom->loadHTML($pageContent);

// Extract sections
$sections = $htmlDom->getElementsByTagName('section');

// Loop through sections and extract data
foreach ($sections as $section) {
    // Extract heading
    $heading = $section->getElementsByTagName('h2')->item(0)->textContent;

    // Extract abstract
    $abstractElement = $section->getElementsByTagName('p')->item(0);
    $abstract = $abstractElement ? $abstractElement->textContent : '';

    // Extract picture (if available)
    $pictureElement = $section->getElementsByTagName('img')->item(0);
    $picture = $pictureElement ? $pictureElement->getAttribute('src') : '';

    // Extract links (if available)
    $links = [];
    $linkElements = $section->getElementsByTagName('a');
    foreach ($linkElements as $linkElement) {
        $links[] = $linkElement->getAttribute('href');
    }

    // Prepare data for insertion into the database
    $dateCreated = date("Y-m-d H:i:s");
    $title = $connection->real_escape_string($heading);
    $url = $connection->real_escape_string($wikipediaURL);
    $abstract = $connection->real_escape_string(substr($abstract, 0, 256));
    $picture = $connection->real_escape_string(substr($picture, 0, 240));
    $linksJson = json_encode($links);

    // Insert data into the database
    $insertQuery = "INSERT INTO wiki_sections (date_created, title, url, picture, abstract, links) 
                    VALUES ('$dateCreated', '$title', '$url', '$picture', '$abstract', '$linksJson')";

    // Execute SQL query
    if ($connection->query($insertQuery) === TRUE) {
        echo "Data inserted successfully for section: $heading\n";
    } else {
        echo "Error inserting data: " . $connection->error . "\n";
    }
}

// Close the database connection
$connection->close();
?>
