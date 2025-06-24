<?php
session_start();

// ✅ Redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

// ✅ Database connection
$host = 'localhost';
$dbname = 'travelnow';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Re-fetch session values if missing
if (!isset($_SESSION['profile_photo']) || !isset($_SESSION['cover_photo'])) {
    $stmt = $conn->prepare("SELECT name, location, bio, profile_photo, cover_photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        $_SESSION = array_merge($_SESSION, $user);
    }
    $stmt->close();
}

// ✅ Handle photo uploads (profile or cover)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $type = $_POST['type'];
    $userId = $_SESSION['userid'];

    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($_FILES['photo']['type'], $allowed)) {
        die("Unsupported file type");
    }

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $fileName = $type . '_' . $userId . '_' . time() . '.' . $ext;
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $filePath)) {
        $column = $type === 'cover' ? 'cover_photo' : 'profile_photo';
        $stmt = $conn->prepare("UPDATE users SET $column = ? WHERE id = ?");
        $stmt->bind_param("si", $filePath, $userId);
        $stmt->execute();
        $stmt->close();

        $_SESSION[$column] = $filePath;
        header("Location: profile.php");
        exit();
    } else {
        die("Upload failed");
    }
}

// ✅ Handle gallery uploads and store in DB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gallery_photos'])) {
    $userId = $_SESSION['userid'];
    $galleryDir = 'uploads/gallery/';
    if (!is_dir($galleryDir)) mkdir($galleryDir, 0755, true);

    foreach ($_FILES['gallery_photos']['tmp_name'] as $i => $tmpName) {
        $ext = pathinfo($_FILES['gallery_photos']['name'][$i], PATHINFO_EXTENSION);
        $uniqueName = 'gallery_' . $userId . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $filePath = $galleryDir . $uniqueName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $stmt = $conn->prepare("INSERT INTO user_gallery (user_id, image_path) VALUES (?, ?)");
            $stmt->bind_param("is", $userId, $filePath);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Create</title>
    <link rel="stylesheet" href="profile.css"/>
</head>
    <style>
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .history-table th, .history-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        .history-table th {
            background-color: #f0f0f0;
        }
        .tab-content h3 {
            margin-top: 2rem;
        }
    </style>
<body>
<div class="container">
    <h1>My Tourist Profile</h1>
    <div class="tabs">
        <div class="tab active" data-tab="profile">Profile</div>
        <div class="tab" data-tab="photos">My Photos</div>
        <div class="tab" data-tab="Edit">Edit</div>
        <div class="tab" data-tab="leaderboard">Leaderboard</div>
        <div class="tab" data-tab="history">History</div>
    </div>

    <div id="messageArea"></div>

    <div class="tab-content active" id="profile-tab">
               <div class="profile-section">
            <div class="profile-header">
                <div class="profile-avatar-container">
                    <?php if (!empty($_SESSION['profile_photo'])): ?>
                        <img class="profile-avatar" id="profileAvatar" src="<?php echo $_SESSION['profile_photo']; ?>">
                    <?php else: ?>
                        <div class="profile-avatar-placeholder" id="avatarPlaceholder">👤</div>
                        <img class="profile-avatar" id="profileAvatar" style="display: none;">
                    <?php endif; ?>
                    <div class="profile-avatar-upload">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="profile">
                            <label for="avatarInput">Change Photo</label>
                            <input type="file" name="photo" id="avatarInput" accept="image/*" onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($_SESSION['name']); ?></h2>
                    <div class="profile-location">
                        <span>📍</span> <?php echo htmlspecialchars($_SESSION['location'] ?? 'Location Unknown'); ?>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <h2>About Me</h2>
                <p><?php echo htmlspecialchars($_SESSION['bio'] ?? 'Tell us something about yourself!'); ?></p>
            </div>

            <div class="profile-stats">
                <h2>Travel Stats</h2>
                <ul>
                    <li>Countries visited: 23</li>
                    <li>Reviews written: 47</li>
                    <li>Photos shared: 156</li>
                    <li>Helpful votes: 382</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content" id="photos-tab">
                <div class="profile-gallery">
            <div class="gallery-header">
                <h2>My Travel Photos</h2>
                <form method="POST" enctype="multipart/form-data">
                    <label class="btn">
                        Upload
                        <input type="file" name="gallery_photos[]" accept="image/*" multiple onchange="this.form.submit()" hidden>
                    </label>
                </form>
            </div>
            <div class="gallery-grid" id="galleryGrid">
                <?php
                $userId = $_SESSION['userid'];
                $res = $conn->query("SELECT image_path FROM user_gallery WHERE user_id = $userId");
                while ($row = $res->fetch_assoc()) {
                    echo '<div class="gallery-item"><img src="' . htmlspecialchars($row['image_path']) . '" /></div>';
                }
                ?>
            </div>
        </div>
    </div>

    <div class="tab-content" id="Edit-tab">
               <form class="profile-form">
            <div class="form-group">
                <label for="displayName">Display Name</label>
                <input type="text" id="displayName" value="<?php echo htmlspecialchars($_SESSION['name']); ?>">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" value="<?php echo htmlspecialchars($_SESSION['location'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="bio">Biography</label>
                <textarea id="bio" rows="4"><?php echo htmlspecialchars($_SESSION['bio'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Cover Photo Options</label>
                <div class="message info">
                    Your cover photo will be displayed at the top of your profile page.
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="cover">
                    <button type="button" class="btn" id="coverPhotoBtn">Change Cover Photo</button>
                    <input type="file" name="photo" id="coverPhotoInput" accept="image/*" onchange="this.form.submit()">
                </form>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <div class="tab-content" id="leaderboard-tab">
                <h2>🏆 Top Travel Destinations</h2>
        <table class="leaderboard-table">
            <thead>
            <tr>
                <th>Rank</th>
                <th>Place</th>
                <th>Travelers</th>
            </tr>
            </thead>
            <tbody id="leaderboard-body">
            <!-- Dynamic Data -->
            </tbody>
        </table>
    </div>

    <div class="tab-content" id="history-tab">
        <h2>📜 My Booking History</h2>

        <h3>Tour Package Bookings</h3>
        <table class="history-table">
            <thead>
                <tr><th>Package</th><th>Start Date</th><th>People</th><th>Booked On</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php
            $userId = $_SESSION['userid'];
            $pkgRes = $conn->query("SELECT id, package, start_date, people, created_at FROM package_bookings WHERE user_id = $userId ORDER BY created_at DESC");
            if ($pkgRes->num_rows > 0) {
                while ($row = $pkgRes->fetch_assoc()) {
                    echo "<tr><td>{$row['package']}</td><td>{$row['start_date']}</td><td>{$row['people']}</td><td>{$row['created_at']}</td><td><button class='btn-cancel' data-id='{$row['id']}' data-type='package'>Cancel</button></td></tr>";
                }
            } else {
                echo '<tr><td colspan="5">No tour packages booked yet.</td></tr>';
            }
            ?>
            </tbody>
        </table>

        <h3>Guide Bookings</h3>
        <table class="history-table">
            <thead>
                <tr><th>Guide Name</th><th>Booking Date</th><th>People</th><th>Booked On</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php
            $guideRes = $conn->query("SELECT id, guide_name, booking_date, people_count, created_at FROM guide_bookings WHERE user_id = $userId ORDER BY created_at DESC");
            if ($guideRes->num_rows > 0) {
                while ($row = $guideRes->fetch_assoc()) {
                    echo "<tr><td>{$row['guide_name']}</td><td>{$row['booking_date']}</td><td>{$row['people_count']}</td><td>{$row['created_at']}</td><td><button class='btn-cancel' data-id='{$row['id']}' data-type='guide'>Cancel</button></td></tr>";
                }
            } else {
                echo '<tr><td colspan="5">No guide bookings yet.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<script src="profile.js"></script>
</body>
</html>
