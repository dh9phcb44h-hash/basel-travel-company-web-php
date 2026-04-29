<?php
// connect to database
require_once "db_config.php";

try {
  // fetch 2 trips ordered by ID
  $stmt = $pdo->prepare("
    SELECT trip_id, trip_name
    FROM trips
    ORDER BY trip_id ASC
    LIMIT 2
  ");
  $stmt->execute();
  $topTrips = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $topTrips = []; // fallback if query fails
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Fly with Basel - ass1</title>
</head>

<body>
  <!-- Header section with logo -->
  <header>
    <center>
      <p><a href="../index.html">Back to Assignments</a></p>

      <h1>
        <img src="images/logo.png" alt="Fly with Basel Logo" width="120" />Fly
        with Basel
      </h1>
      <!-- Company tagline -->
      <!-- em: for italic font-->
      <p><em>Your journey begins with Basel.</em></p>
    </center>

    <!-- hr: for the lines-->
    <hr />
    <!-- Navigation bar -->
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
    <!-- Welcome title -->
    <section>
      <h2>Welcome to Fly with Basel</h2>
      <!-- News block showing popular trips -->
      <article>
        <h2>Latest News</h2>
        <p>
          At <strong>Fly with Basel</strong>, we turn your travel dreams into reality.
          Our most popular cultural tours include
          <!-- If we have at least 2 trips from DB, display their names -->
          <?php if (count($topTrips) >= 2): ?>
            <strong><?php echo htmlspecialchars($topTrips[0]['trip_name']); ?></strong>
            and
            <strong><?php echo htmlspecialchars($topTrips[1]['trip_name']); ?></strong>.
            <!-- If not enough trips found, show a fallback message -->
          <?php else: ?>
            our amazing packages.
          <?php endif; ?>
        </p>

      </article>

      <hr />
      <center>
        <figure>
          <img src="images/logo.png" alt="Fly with Basel Logo" width="200" />
          <figcaption>
            <em>See the world with us.</em>
          </figcaption>
        </figure>
        <p>
          At <strong>Fly with Basel</strong>, we turn your travel dreams into
          reality. Whether you're looking for relaxing getaways, family
          adventures, or romantic escapes, our team is here to make every trip
          unforgettable.
        </p>
      </center>
    </section>
    <aside>
      <h3>Quick Facts</h3>
      <ul>
        <li>Based in Ramallah, Palestine.</li>
        <li>Specialized in Middle East & international trips.</li>
        <li>Custom packages for families and students.</li>
        <li>Support available 7 days a week.</li>
      </ul>
    </aside>
  </main>

</body>
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

</html>