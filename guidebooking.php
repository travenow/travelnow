<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php"); // redirect to login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Book a Guide</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f8fb;
      margin: 0;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    form {
      background-color: #fff;
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 450px;
    }

    h2 {
      text-align: center;
      color: #2c3e50;
      margin-bottom: 25px;
    }

    label {
      font-weight: 600;
      display: block;
      margin-top: 15px;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="email"],
    input[type="date"],
    input[type="number"],
    select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 15px;
      transition: border 0.3s ease-in-out;
    }

    input:focus,
    select:focus {
      border-color: #3498db;
      outline: none;
    }

    input[type="submit"] {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 14px;
      margin-top: 25px;
      border-radius: 6px;
      width: 100%;
      cursor: pointer;
      font-size: 16px;
      font-weight: bold;
      transition: background 0.3s ease-in-out;
    }

    input[type="submit"]:hover {
      background-color: #2980b9;
    }

    @media screen and (max-width: 480px) {
      form {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<form method="POST" action="book_guide.php">
  <h2>Book a Guide</h2>

  <label for="name">Full Name:</label>
  <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>

  <label for="email">Email Address:</label>
  <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required>

  <label for="booking_date">Booking Date:</label>
  <input type="date" name="booking_date" id="booking_date" required>

  <label for="people_count">Number of People:</label>
  <input type="number" name="people_count" id="people_count" min="1" required>

  <label for="guide_name">Preferred Guide:</label>
  <select name="guide_name" id="guide_name" required>
    <option value="">-- Select Guide --</option>
    <option value="John">John</option>
    <option value="Ayesha">Ayesha</option>
    <option value="Carlos">Carlos</option>
  </select>

  <input type="submit" name="submit" value="Book Now">
</form>

</body>
</html>
