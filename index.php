<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta http-equiv="Cache-Control" content="no-store"/>
  <title>Tourist Guide Booking</title>
  <link rel="stylesheet" href="styless.css"/>
</head>
<body>

<header>
  <nav class="navbar">
    <div class="logo">TravelNow</div>

    <div class="user-controls">
      <?php if (isset($_SESSION['userid'])): ?>
        <span class="welcome-text">👋 Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="profile.php" class="btn">My Profile</a>
        <a href="logout.php" class="btn logout-btn">Logout</a>
      <?php else: ?>
        <button class="btn" id="openPopup">Login</button>
      <?php endif; ?>
    </div>

    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#packages">Packages</a></li>
      <li><a href="#contact">Contact Us</a></li>
      <li><a href="#guidebooking">Guide Book</a></li>
    </ul>
  </nav>
</header>

<!-- HOME Section -->
<section id="home" class="section">
  <h2>Welcome to TravelNow</h2>
  <p>Your ultimate travel guide and booking partner.</p>

  <div class="carousel-container">
    <div class="carousel-track">
      <!-- Original set -->
      <img src="img/GOA.jpeg" alt="Goa Beach">
      <img src="img/ROYAL.jpeg" alt="Royal Rajasthan">
      <img src="img/LADAK.jpeg" alt="Leh Ladakh">
      <img src="img/KERALA.jpeg" alt="Kerala Backwaters">
      <img src="img/GOLDEN.jpeg" alt="Golden Triangle">
      <img src="img/ISLAND.jpeg" alt="Andaman Islands">

      <!-- Duplicate set for seamless looping -->
      <img src="img/GOA.jpeg" alt="Goa Beach Duplicate">
      <img src="img/ROYAL.jpeg" alt="Royal Rajasthan Duplicate">
      <img src="img/LADAK.jpeg" alt="Leh Ladakh Duplicate">
      <img src="img/KERALA.jpeg" alt="Kerala Backwaters Duplicate">
      <img src="img/GOLDEN.jpeg" alt="Golden Triangle Duplicate">
      <img src="img/ISLAND.jpeg" alt="Andaman Islands Duplicate">
    </div>
  </div>
</section>


<!-- ABOUT Section -->
<section id="about" class="section about-section">
  <div class="about-container">
    <div class="about-text">
      <h2>About Us</h2>
      <p>
        At <strong>TravelNow</strong>, we believe that travel is not just a journey but a memorable experience that shapes lives. 
        With years of expertise, we connect wanderlust adventurers with expert guides and tailor-made travel packages. 
        From breathtaking mountain treks to serene beach escapes, we're here to make your travel dreams come true.
      </p>
      <p>
        <strong>Why Choose Us?</strong><br>
        ✓ Expert Local Guides<br>
        ✓ Seamless Booking Experience<br>
        ✓ Affordable Travel Packages<br>
        ✓ 24/7 Support & Assistance
      </p>
    </div>
    <div class="about-image">
      <img src="img/ABOUT.jpeg" alt="About Us - TravelNow">
    </div>
  </div>
</section>

<!-- SERVICES Section -->
<section id="services" class="section services-section">
  <h2 class="section-title">Our Services</h2>
  <p class="section-subtitle">Discover a range of travel services tailored to make your journey unforgettable.</p>
  <div class="services-container">
    <div class="service-card" onclick="location.href='guidebooking.php';">
      <img src="img/TG.jpeg" alt="Tourist Guide Booking">
      <h3>Tourist Guide Booking</h3>
      <p>Connect with expert local guides to explore hidden gems and popular landmarks effortlessly.</p>
    </div>
    <div class="service-card" onclick="location.href='bookpackage.php';">
      <img src="img/CTP.jpeg" alt="Custom Tour Packages">
      <h3>Custom Tour Packages</h3>
      <p>Create personalized travel itineraries tailored to your interests and schedule.</p>
    </div>
  </div>
</section>

<!-- PACKAGES Section -->
<section id="packages" class="section packages-section">
  <h2 class="section-title">Tour Packages</h2>
  <p class="section-subtitle">Explore the beauty and diversity of India with our exclusive tour packages.</p>
  <div class="packages-container">
    <?php
$packages = [
  [
    "img" => "GOA.jpeg",
    "name" => "Goa Beaches",
    "price" => "₹15,000",
    "days" => "3 Days, 2 Nights",
    "famous" => "Baga Beach, Calangute Beach, Fort Aguada, Basilica of Bom Jesus, Anjuna Market, Dudhsagar Falls"
  ],
  [
    "img" => "KERALA.jpeg",
    "name" => "Kerala Backwaters",
    "price" => "₹18,000",
    "days" => "4 Days, 3 Nights",
    "famous" => "Alleppey Houseboat, Kumarakom, Munnar Tea Gardens, Thekkady, Varkala Beach, Athirapally Falls"
  ],
  [
    "img" => "GOLDEN.jpeg",
    "name" => "Golden Triangle",
    "price" => "₹25,000",
    "days" => "5 Days, 4 Nights",
    "famous" => "Taj Mahal, India Gate, Hawa Mahal, Qutub Minar, Red Fort, Amber Fort"
  ],
  [
    "img" => "LADAK.jpeg",
    "name" => "Leh Ladakh",
    "price" => "₹35,000",
    "days" => "7 Days, 6 Nights",
    "famous" => "Pangong Lake, Nubra Valley, Magnetic Hill, Shanti Stupa, Khardung La Pass, Leh Palace"
  ],
  [
    "img" => "ISLAND.jpeg",
    "name" => "Andaman Islands",
    "price" => "₹28,000",
    "days" => "5 Days, 4 Nights",
    "famous" => "Radhanagar Beach, Cellular Jail, Ross Island, Neil Island, North Bay Island, Chidiya Tapu"
  ],
  [
    "img" => "ROYAL.jpeg",
    "name" => "Rajasthan Royal Tour",
    "price" => "₹30,000",
    "days" => "6 Days, 5 Nights",
    "famous" => "City Palace Jaipur, Hawa Mahal, Udaipur Lake Palace, Jaisalmer Fort, Mehrangarh Fort, Pushkar Lake"
  ],
];

    foreach ($packages as $p) {
      echo "<div class='package-card'>
              <img src='img/{$p['img']}' alt='{$p['name']}'>
              <h3>{$p['name']}</h3>
              <p>{$p['days']} | Starting at {$p['price']}</p>
              <button class='btn' onclick=\"location.href='bookpackage.php';\">Book Now</button>
            </div>";
    }
    ?>
  </div>
</section>

<!-- CONTACT Section -->
<section id="contact" class="section contact-section">
  <h2 class="section-title">Contact Us</h2>
  <p class="section-subtitle">We'd love to hear from you! Reach out for inquiries, bookings, or feedback.</p>
  <div class="contact-container">
    <div class="contact-form">
      <h3>Send Us a Message</h3>
      <form>
        <input type="text" placeholder="Your Name" required>
        <input type="email" placeholder="Your Email" required>
        <textarea placeholder="Your Message" rows="5" required></textarea>
        <button type="submit" class="btn">Send Message</button>
      </form>
    </div>
    <div class="contact-info">
      <h3>Contact Information</h3>
      <p><strong>Phone:</strong> +91 98765 43210</p>
      <p><strong>Gmail:</strong>travelnowbyteamx@gmail.com</p>
      <p><strong>Address:</strong> 123, Wanderlust Street, Delhi, India</p>
      <div class="map-placeholder">
        <iframe src="https://www.google.com/maps/embed?...your-map-here..." width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
    </div>
  </div>
</section>

<!-- LOGIN/REGISTER POPUP -->
<section id="popupSection" style="display: none;">
  <div class="popup-box">
    <button class="close-btn" id="closePopup">&times;</button>

    <!-- Login Form -->
    <div id="loginForm" class="form-container active">
      <h2>Login</h2>
      <form action="login.php" method="POST">
        <input type="text" name="userid" placeholder="Email or Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="btn">Login</button>
      </form>
      <p>Don't have an account? <button class="switch-btn" onclick="switchForm('registerForm')">Register</button></p>
    </div>

    <!-- Register Form -->
    <div id="registerForm" class="form-container">
      <h2>Register</h2>
      <form action="register.php" method="POST">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phoneNo" placeholder="Phone No" required pattern="[0-9]{10}">
        <input type="text" name="location" placeholder="Location (optional)">
        <textarea name="bio" placeholder="Tell us about yourself (optional)"></textarea>
        <input type="password" name="password" placeholder="Password" required minlength="6">
        <button type="submit" class="btn">Register</button>
      </form>
      <p>Already have an account? <button class="switch-btn" onclick="switchForm('loginForm')">Login</button></p>
    </div>
  </div>
</section>
<section id="guidebooking" class="section guidebooking-section">
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
        </section>    
<footer>
  <p>© 2024 TravelNow. All Rights Reserved.</p>
</footer>

<script src="script.js"></script>

<?php if (isset($_GET['booked']) && $_GET['booked'] === 'true'): ?>
  <div class="success-msg">✅ Guide booked successfully!</div>
<?php endif; ?>

<?php if (isset($_GET['package']) && $_GET['package'] === 'booked'): ?>
  <div class="alert success">🎉 Package booked successfully!</div>
<?php endif; ?>

</body>
</html>
