<?php
// connect to database
require_once "db_config.php";
// get filter values from URL GET
$destination = isset($_GET['destination']) ? trim($_GET['destination']) : "";
$start_date  = isset($_GET['start_date']) ? trim($_GET['start_date']) : "";
$end_date    = isset($_GET['end_date']) ? trim($_GET['end_date']) : "";
$min_price   = isset($_GET['min_price']) ? trim($_GET['min_price']) : "";
$max_price   = isset($_GET['max_price']) ? trim($_GET['max_price']) : "";
$min_days    = isset($_GET['min_days']) ? trim($_GET['min_days']) : "";
// store search results
$trips = [];
// store error message
$error = "";
// check if search button was clicked
$searched = (isset($_GET['do']) && $_GET['do'] === "1");

// base query with dynamic filters
$sql = "SELECT trip_id, trip_name, destination, duration_days, price, start_date
        FROM trips
        WHERE 1=1";
$params = [];
// filter by destination 
if ($destination !== "") {
    // contains search for destination
    $sql .= " AND destination LIKE :destination";
    $params[":destination"] = "%" . $destination . "%";
}
// filter by start date
if ($start_date !== "") {
    $sql .= " AND start_date >= :start_date";
    $params[":start_date"] = $start_date;
}
// filter by end date
if ($end_date !== "") {
    $sql .= " AND end_date <= :end_date";
    $params[":end_date"] = $end_date;
}
// filter by minimum price
if ($min_price !== "" && is_numeric($min_price)) {
    $sql .= " AND price >= :min_price";
    $params[":min_price"] = (float)$min_price;
}
// filter by maximum price
if ($max_price !== "" && is_numeric($max_price)) {
    $sql .= " AND price <= :max_price";
    $params[":max_price"] = (float)$max_price;
}
// filter by minimum duration
if ($min_days !== "" && ctype_digit($min_days) && (int)$min_days > 0) {
    $sql .= " AND duration_days >= :min_days";
    $params[":min_days"] = (int)$min_days;
}
// order results by start date
$sql .= " ORDER BY start_date ASC";
// run query only after searching
if ($searched) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $trips = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = "Error searching trips.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Fly with Basel - Search Trips</title>
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
        <section>
            <h2>Search & Filter Trips</h2>

            <form method="get" action="search-trips.php">
                <!-- marks that search was submitted -->
                <input type="hidden" name="do" value="1" />

                <fieldset>
                    <legend>Filters</legend>
                    <!-- destination filter -->
                    <p>
                        <label for="destination">Destination:</label><br />
                        <input type="text" id="destination" name="destination" value="<?php echo htmlspecialchars($destination); ?>" />
                    </p>
                    <!-- date filters -->
                    <p>
                        <label for="start_date">Start Date (from):</label><br />
                        <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" />
                    </p>

                    <p>
                        <label for="end_date">End Date (to):</label><br />
                        <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" />
                    </p>
                    <!-- price filters -->
                    <p>
                        <label for="min_price">Min Price:</label><br />
                        <input type="number" step="0.01" id="min_price" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>" />
                    </p>

                    <p>
                        <label for="max_price">Max Price:</label><br />
                        <input type="number" step="0.01" id="max_price" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>" />
                    </p>
                    <!-- duration filter -->
                    <p>
                        <label for="min_days">Minimum Duration (days):</label><br />
                        <input type="number" id="min_days" name="min_days" min="1" value="<?php echo htmlspecialchars($min_days); ?>" />
                    </p>
                    <!-- actions -->
                    <p>
                        <input type="submit" value="Search" />
                        <a href="search-trips.php">Reset</a>
                    </p>
                </fieldset>
            </form>

            <hr />

            <?php if ($searched): ?>
                <?php if ($error): ?>
                    <!-- show error if query failed -->
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php elseif (empty($trips)): ?>
                    <!-- no results found -->
                    <p><strong>No trips match your search criteria.</strong></p>
                <?php else: ?>
                    <!-- show search results -->
                    <h3>Search Results</h3>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Trip Name</th>
                                <th>Destination</th>
                                <th>Duration (Days)</th>
                                <th>Price (USD)</th>
                                <th>Start Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trips as $t): ?>
                                <tr>
                                    <td>
                                        <!-- link to trip details page -->
                                        <a href="trip-details.php?id=<?php echo (int)$t['trip_id']; ?>" target="_blank">
                                            <?php echo htmlspecialchars($t['trip_name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($t['destination']); ?></td>
                                    <td><?php echo (int)$t['duration_days']; ?></td>
                                    <td><?php echo number_format((float)$t['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($t['start_date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>

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