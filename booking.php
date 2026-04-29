<?php
// connect to database
require_once "db_config.php";
// get trip id from URL
$trip_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$trip = null;
$error = "";
// check if the trip id is valid (positive number)
if ($trip_id <= 0) {
  // if id is invalid redirect to tour list
  header("Location: tour-packages.php");
  exit;
} else {
  try {
    // fetch trip details from DB
    $stmt = $pdo->prepare("SELECT * FROM trips WHERE trip_id = :id");
    $stmt->execute([':id' => $trip_id]);
    $trip = $stmt->fetch();
    if (!$trip) {
      // if not found redirect again
      header("Location: tour-packages.php");
      exit;
    }
  } catch (PDOException $e) {
    $error = "Error loading trip.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Fly with Basel - Booking</title>
</head>

<body>

  <header>
    <center>
      <p><a href="index.php">Back to Company Home</a></p>
      <h1><img src="images/logo.png" alt="Fly with Basel Logo" width="120" /> Fly with Basel</h1>
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
    <?php if ($error): ?>
      <!-- if there's an error show a message instead of the form -->
      <section>
        <h2>Oops</h2>
        <p><?php echo ($error); ?></p>
        <p><a href="tour-packages.php">Back to Tour Packages</a></p>
      </section>
    <?php else: ?>
      <!-- otherwise show the booking form for that trip -->
      <section>
        <h2>Book This Trip</h2>

        <p>
          <!-- display selected trip information -->
          <strong>Trip:</strong> <?php echo ($trip['trip_name']); ?><br />
          <strong>Destination:</strong> <?php echo ($trip['destination']); ?><br />
          <strong>Dates:</strong> <?php echo ($trip['start_date']); ?> to <?php echo ($trip['end_date']); ?><br />
          <strong>Duration:</strong> <?php echo (int)$trip['duration_days']; ?> days<br />
          <strong>Price per person:</strong> $<?php echo number_format((float)$trip['price'], 2); ?><br />
          <strong>Available seats:</strong> <?php echo (int)$trip['available_seats']; ?>
        </p>

        <form action="process-booking.php" method="post">
          <!-- hidden fields to send trip data safely -->
          <input type="hidden" name="trip_id" value="<?php echo (int)$trip['trip_id']; ?>">
          <input type="hidden" name="trip_name" value="<?php echo htmlspecialchars($trip['trip_name']); ?>">
          <input type="hidden" name="trip_price" value="<?php echo (float)$trip['price']; ?>">

          <!-- customer personal information -->
          <fieldset>
            <legend>Customer Information</legend>

            <p>
              <label for="customer_name">Full Name:</label><br />
              <input type="text" id="customer_name" name="customer_name" required />
            </p>

            <p>
              <label for="customer_email">Email Address:</label><br />
              <input type="email" id="customer_email" name="customer_email" required />
            </p>

            <p>
              <label for="customer_phone">Phone Number:</label><br />
              <input type="tel" id="customer_phone" name="customer_phone" required />
            </p>
          </fieldset>

          <fieldset>
            <legend>Booking Details</legend>

            <p>
              <label for="num_travelers">Number of Travelers:</label><br />
              <!-- max travelers is limited by available seats -->
              <input type="number" id="num_travelers" name="num_travelers"
                min="1" max="<?php echo (int)$trip['available_seats']; ?>" required />
            </p>

            <p>
              <label for="special_requests">Special Requests (optional):</label><br />
              <textarea id="special_requests" name="special_requests" rows="3" cols="30"></textarea>
            </p>
          </fieldset>

          <fieldset>
            <legend>Payment Information</legend>
            <!-- radio buttons ensure only one method is chosen -->
            <p>
              <strong>Payment Method:</strong><br />
              <input type="radio" id="visa" name="payment_method" value="Visa Card" required />
              <label for="visa">Visa Card</label><br />

              <input type="radio" id="master" name="payment_method" value="Master Card" required />
              <label for="master">Master Card</label>
            </p>

            <p>
              <label for="card_number">Card Number (16 digits):</label><br />
              <input type="text" id="card_number" name="card_number" maxlength="16" required />
            </p>

            <p>
              <label for="card_name">Cardholder Name:</label><br />
              <input type="text" id="card_name" name="card_name" required />
            </p>

            <p>
              <label for="expiry">Expiry Date (MM/YYYY):</label><br />
              <input type="text" id="expiry" name="expiry" placeholder="MM/YYYY" required />
            </p>
          </fieldset>
          <!-- submit booking form -->
          <p><input type="submit" value="Submit Booking" /></p>
        </form>

      </section>

    <?php endif; ?>
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