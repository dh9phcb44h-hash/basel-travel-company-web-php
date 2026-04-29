<?php
// connect to database
require_once "db_config.php";

try {
  // fetch all trips ordered by ID
  $stmt = $pdo->prepare("
        SELECT trip_id, trip_name, destination, duration_days, price, start_date, available_seats
        FROM trips
        ORDER BY trip_id ASC
    ");
  $stmt->execute();
  // store result
  $trips = $stmt->fetchAll();
} catch (PDOException $e) {
  // fallback if DB fails
  die("Error fetching trips.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Fly with Basel - Tour Packages</title>
</head>

<body>
  <header>
    <center>
      <p><a href="index.php">Back to Company Home</a></p>
      <h1>
        <img src="images/logo.png" alt="Fly with Basel Logo" width="120" />
        Fly with Basel
      </h1>
      <p><em>Your journey begins with Basel.</em></p>
    </center>
    <hr />
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="destinations.php">Destinations</a></li>
        <li><a href="tour-packages.php">Tour Packages</a></li>
        <li><a href="search-trips.php">Search</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="faq.php">FAQ</a></li>
      </ul>
    </nav>

    <hr />
  </header>

  <main>
    <section>
      <h2>Available Tour Packages</h2>
      <p>Click on any package name in the table below to view full details.</p>

      <table border="1">
        <caption>Fly with Basel - Tour Packages</caption>

        <thead>
          <tr>
            <th>Trip Name</th>
            <th>Destination</th>
            <th>Duration (Days)</th>
            <th>Price (USD)</th>
            <th>Start Date</th>
            <th>Available Seats</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($trips)) : ?>
            <!-- no trips found -->
            <tr>
              <td colspan="6" align="center">No trips found.</td>
            </tr>
          <?php else : ?>
            <!-- loop through each trip and display it -->
            <?php foreach ($trips as $t) : ?>
              <tr>
                <td>
                  <!-- link to trip details page -->
                  <a href="trip-details.php?id=<?php echo (int)$t['trip_id']; ?>">
                    <?php echo htmlspecialchars($t['trip_name']); ?>
                  </a>
                </td>
                <td><?php echo htmlspecialchars($t['destination']); ?></td>
                <td><?php echo (int)$t['duration_days']; ?></td>
                <td><?php echo number_format((float)$t['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($t['start_date']); ?></td>
                <td><?php echo (int)$t['available_seats']; ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

        <tfoot>
          <tr>
            <td colspan="6" align="center">
              <em>All prices include accommodation and guided tours.</em>
            </td>
          </tr>
        </tfoot>
      </table>
    </section>
  </main>

  <footer>

    <hr />
    <h3>Links:</h3>
    <p>
      <a href="about.php">About</a> |
      <a href="gallery.php">Gallery</a> |
      <a href="faq.php">FAQ</a>
    </p>

    <hr />
    <h3>Contact Information:</h3>
    <p><strong>Email:</strong> flywithbasel@gmail.com</p>
    <p><strong>Phone Number:</strong> 0598342343</p>
    <p><strong>Address:</strong> Ramallah, Palestine</p>
    <p><small>&copy; 2025 Fly with Basel</small></p>
  </footer>
</body>

</html>