<?php
// include DB config
require_once "db_config.php";
// get trip ID from URL GET
$trip_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$trip = null;
$error = "";
// if trip ID is invalid
if ($trip_id <= 0) {
    $error = "Invalid trip ID.";
} else {
    try {
        // fetch trip info from DB
        $stmt = $pdo->prepare("SELECT * FROM trips WHERE trip_id = :id");
        $stmt->execute([':id' => $trip_id]);
        $trip = $stmt->fetch();
        // if not found
        if (!$trip) $error = "Trip not found.";
    } catch (PDOException $e) {
        $error = "Error loading trip details.";
    }
}
// helper function to split fields by "|" into list
function split_items($text)
{
    $parts = explode('|', (string)$text);
    $clean = [];
    foreach ($parts as $p) {
        $p = trim($p);
        if ($p !== "") $clean[] = $p;
    }
    return $clean;
}
// break down the trip fields if trip is loaded
$itinerary    = $trip ? split_items($trip['itinerary'])    : [];
$inclusions   = $trip ? split_items($trip['inclusions'])   : [];
$exclusions   = $trip ? split_items($trip['exclusions'])   : [];
$requirements = $trip ? split_items($trip['requirements']) : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?php echo $error ? "Trip Details" : htmlspecialchars($trip['trip_name']) . " - Details"; ?></title>
</head>

<body>

    <header>
        <center>
            <p><a href="tour-packages.php">Back to Tour Packages</a></p>
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
            <!-- if theres an error -->
            <section>
                <h2>Oops</h2>
                <p><?php echo htmlspecialchars($error); ?></p>
                <p><a href="tour-packages.php">Back to Tour Packages</a></p>
            </section>
        <?php else: ?>

            <section>
                <!-- basic trip info -->
                <h2><?php echo htmlspecialchars($trip['trip_name']); ?></h2>
                <p>
                    <strong>Destination:</strong> <?php echo htmlspecialchars($trip['destination']); ?><br />
                    <strong>Duration:</strong> <?php echo (int)$trip['duration_days']; ?> days<br />
                    <strong>Price:</strong> $<?php echo number_format((float)$trip['price'], 2); ?><br />
                    <strong>Dates:</strong> <?php echo htmlspecialchars($trip['start_date']); ?> to <?php echo htmlspecialchars($trip['end_date']); ?><br />
                    <strong>Available Seats:</strong> <?php echo (int)$trip['available_seats']; ?>
                </p>

                <figure>
                    <img src="<?php echo htmlspecialchars($trip['image_url']); ?>"
                        alt="<?php echo htmlspecialchars($trip['trip_name']); ?> image"
                        width="500" />
                    <figcaption><?php echo htmlspecialchars($trip['destination']); ?></figcaption>
                </figure>

                <p><?php echo htmlspecialchars($trip['description']); ?></p>
            </section>

            <section>
                <details>
                    <!-- expandable sections (details) -->
                    <summary>Day-by-Day Itinerary</summary>
                    <ul>
                        <?php foreach ($itinerary as $i => $item): ?>
                            <li><?php echo "Day " . ($i + 1) . ": " . htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>

                <details>
                    <summary>Included Services</summary>
                    <ul>
                        <?php foreach ($inclusions as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>

                <details>
                    <summary>Not Included</summary>
                    <ul>
                        <?php foreach ($exclusions as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>

                <details>
                    <summary>Requirements</summary>
                    <ul>
                        <?php foreach ($requirements as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
                <!-- booking button -->
                <p>
                    <a href="booking.php?id=<?php echo (int)$trip['trip_id']; ?>" target="_blank">
                        <strong>Book This Trip</strong>
                    </a>
                </p>
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