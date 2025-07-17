<?php
// register.php
// Handles user registration.

require_once 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = 'customer'; // Default role for new registrations

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);

    if ($stmt->execute()) {
        $message = "Registration successful! You can now log in.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SpiceVilla</title>
    <link rel="stylesheet" href="spicevilla.css">
    <link rel="stylesheet" href="about.css"> <!-- Reusing some styles -->
    <style>
        body {
            background-color: #000000e7;
            color: aliceblue;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px gold;
            width: 400px;
            text-align: center;
        }
        .register-container h1 {
            color: gold;
            margin-bottom: 20px;
        }
        .register-container label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
            color: gold;
        }
        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container input[type="email"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid gold;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: aliceblue;
        }
        .register-container input[type="submit"] {
            background-color: gold;
            color: black;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .register-container input[type="submit"]:hover {
            background-color: #ffd700;
        }
        .message {
            margin-top: 20px;
            color: #ffd700;
        }
        .login-link {
            margin-top: 20px;
            color: aliceblue;
        }
        .login-link a {
            color: gold;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Register">
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>