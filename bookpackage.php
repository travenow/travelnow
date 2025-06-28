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
  <meta charset="UTF-8">
  <title>Book a Tour Package</title>
  <link rel="stylesheet" href="styles.css"> <!-- Optional if external -->
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .booking-container {
      max-width: 500px;
      margin: 40px auto;
      background: white;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .booking-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .booking-container form {
      display: flex;
      flex-direction: column;
    }

    .booking-container input,
    .booking-container select {
      padding: 12px 15px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    .booking-container select {
      background-color: #fff;
    }

    .booking-container .btn {
      background: #1e90ff;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .booking-container .btn:hover {
      background: #0d74d1;
    }

    .alert.success {
      max-width: 600px;
      margin: 20px auto;
      padding: 15px 20px;
      background-color: #d4edda;
      border-left: 6px solid #28a745;
      border-radius: 6px;
      color: #155724;
      font-size: 16px;
    }
  </style>
</head>
<body>
  <div class="booking-container">
    <h2>Book a Tour Package</h2>
    <form action="book_package.php" method="POST">
      <input type="text" name="name" placeholder="Your Full Name" 
             value="<?php echo isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : ''; ?>" required>

      <input type="email" name="email" placeholder="Your Email"
             value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" required>

      <input type="date" name="start_date" required>
      <input type="number" name="people" min="1" placeholder="No. of People" required>

      <select name="package" required>
        <option value="">-- Select a Package --</option>
        <option value="Goa Beaches">Goa Beaches</option>
        <option value="Kerala Backwaters">Kerala Backwaters</option>
        <option value="Golden Triangle">Golden Triangle</option>
        <option value="Leh Ladakh">Leh Ladakh</option>
        <option value="Andaman Islands">Andaman Islands</option>
        <option value="Rajasthan Royal Tour">Rajasthan Royal Tour</option>
      </select>

      <button type="submit" class="btn">Book Package</button>
    </form>
  </div>
</body>
</html>
