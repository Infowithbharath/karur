<?php
// Database connection details
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

if (isset($_GET['accept_id'])) {
    $volunteer_id = $_GET['accept_id'];
    
    // Generate unique Volunteer ID (could be based on current time or UUID)
    $volunteer_id_number = 'V-' . strtoupper(uniqid());
    
    // Update the status and assign ID in the database
    $sql = "UPDATE volunteers SET status='Accepted', volunteer_id='$volunteer_id_number' WHERE id=$volunteer_id";
    
    if (!$conn->query($sql)) {
        die("Error updating record: " . $conn->error);
    }

    // Fetch volunteer details (for sending email)
    $vol_query = "SELECT * FROM volunteers WHERE id = $volunteer_id";
    $vol_result = $conn->query($vol_query);
    if ($vol_result && $vol_result->num_rows > 0) {
        $volunteer = $vol_result->fetch_assoc();
        
        // Email content
        $to = $volunteer['email'];
        $subject = "Volunteer Acceptance - Valarum Karur";
        $message = "Hello {$volunteer['first_name']},\n\nYou are now a Volunteer for Valarum Karur! Congratulations and all the best!\nYour Volunteer ID: {$volunteer_id_number}\n\nThank you!";
        $headers = "From: bkdhoni07@gmail.com";
        
        // Send email
        if (!mail($to, $subject, $message, $headers)) {
            die("Failed to send email.");
        }
    } else {
        die("Error: No record found for this volunteer.");
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Volunteer Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 20px;
        }

        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
        }

        th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .action-btns {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .btn-accept {
            background-color: #28a745;
            color: white;
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Admin Panel - View Volunteer Data</h3>
        <div class="table-container">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Qualification</th>
                        <th>Date of Birth</th>
                        <th>Blood Group</th>
                        <th>Club</th>
                        <th>Designation</th>
                        <th>Aadhar</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip</th>
                        <th>Photo</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reconnect for fetching data
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Fetch records for volunteers who have not yet been accepted or rejected
                    $sql = "SELECT * FROM volunteers WHERE status IS NULL";
                    $result = $conn->query($sql);

                    // Display records in table
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['first_name']}</td>
                                <td>{$row['last_name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['qualification']}</td>
                                <td>{$row['date_of_birth']}</td>
                                <td>{$row['blood_group']}</td>
                                <td>{$row['member_club']}</td>
                                <td>{$row['designation']}</td>
                                <td>{$row['aadhar_number']}</td>
                                <td>{$row['city']}</td>
                                <td>{$row['state']}</td>
                                <td>{$row['zip_code']}</td>
                                <td><img src='{$row['photo_path']}' alt='Photo' width='50' height='50'></td>
                                <td>-</td>
                                <td class='action-btns'>
                                    <a href='admin_panel.php?accept_id={$row['id']}' class='btn btn-accept'>Accept</a>
                                    <a href='#' class='btn btn-reject'>Reject</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='17' class='text-center'>No pending volunteers</td></tr>";
                    }

                    // Close connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
