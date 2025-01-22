<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "karur";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize form inputs
$first_name = $conn->real_escape_string($_POST['first_name'] ?? '');
$last_name = $conn->real_escape_string($_POST['last_name'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$gender = $conn->real_escape_string($_POST['gender'] ?? '');
$qualification = $conn->real_escape_string($_POST['qualification'] ?? '');
$date_of_birth = $conn->real_escape_string($_POST['date_of_birth'] ?? '');
$blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
$member_club = $conn->real_escape_string($_POST['member_club'] ?? '');
$designation = $conn->real_escape_string($_POST['designation'] ?? '');
$aadhar_number = $conn->real_escape_string($_POST['aadhar_number'] ?? '');
$address_line1 = $conn->real_escape_string($_POST['address_line1'] ?? '');
$address_line2 = $conn->real_escape_string($_POST['address_line2'] ?? '');
$city = $conn->real_escape_string($_POST['city'] ?? '');
$state = $conn->real_escape_string($_POST['state'] ?? '');
$nationality = $conn->real_escape_string($_POST['nationality'] ?? '');
$zip_code = $conn->real_escape_string($_POST['zip_code'] ?? '');

// Handle file upload
$photo_path = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
    $photo_path = 'uploads/' . basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
}

// Insert data into database
$sql = "INSERT INTO volunteers (first_name, last_name, email, gender, qualification, date_of_birth, blood_group, member_club, designation, aadhar_number, address_line1, address_line2, city, state, nationality, zip_code, photo_path)
VALUES ('$first_name', '$last_name', '$email', '$gender', '$qualification', '$date_of_birth', '$blood_group', '$member_club', '$designation', '$aadhar_number', '$address_line1', '$address_line2', '$city', '$state', '$nationality', '$zip_code', '$photo_path')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
