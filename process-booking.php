<?php
// connect to database
require_once "db_config.php";

$error = "";
$success = false;
// clean input values remove extra spaces
function clean($v)
{
    return trim((string)$v);
}
// show a full error page and stop execution
function showErrorPage($msg)
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <title>Fly with Basel - Booking Error</title>
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
                <h2>Oops</h2>
                <p><?php echo htmlspecialchars($msg); ?></p>
                <p><a href="tour-packages.php">Back to Tour Packages</a></p>
            </section>
        </main>

        <footer>
            <hr />
            <h3>Contact Information:</h3>
            <p><strong>Email:</strong> flywithbasel@gmail.com</p>
            <p><strong>Phone Number:</strong> 0598342343</p>
            <p><strong>Address:</strong> Ramallah, Palestine</p>
            <p><small>&copy; 2025 Fly with Basel</small></p>
        </footer>
    </body>

    </html>
<?php
    exit;
}

// make sure the request is POST form submission
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    showErrorPage("Invalid request.");
}

// get trip info from hidden fields
$trip_id = isset($_POST["trip_id"]) ? (int)$_POST["trip_id"] : 0;
$trip_name = isset($_POST["trip_name"]) ? clean($_POST["trip_name"]) : "";
$trip_price = isset($_POST["trip_price"]) ? (float)$_POST["trip_price"] : 0.0;
// get customer info
$customer_name  = isset($_POST["customer_name"]) ? clean($_POST["customer_name"]) : "";
$customer_email = isset($_POST["customer_email"]) ? clean($_POST["customer_email"]) : "";
$customer_phone = isset($_POST["customer_phone"]) ? clean($_POST["customer_phone"]) : "";

$num_travelers = isset($_POST["num_travelers"]) ? (int)$_POST["num_travelers"] : 0;
$special_requests = isset($_POST["special_requests"]) ? clean($_POST["special_requests"]) : "";

$payment_method = isset($_POST["payment_method"]) ? clean($_POST["payment_method"]) : "";
$card_number = isset($_POST["card_number"]) ? preg_replace('/\s+/', '', clean($_POST["card_number"])) : "";
$card_name = isset($_POST["card_name"]) ? clean($_POST["card_name"]) : "";
$expiry = isset($_POST["expiry"]) ? clean($_POST["expiry"]) : "";

// check required trip data and customer fields
if ($trip_id <= 0 || $trip_name === "" || $trip_price <= 0) {
    showErrorPage("Missing trip information.");
}
// check required customer fields
if ($customer_name === "" || $customer_email === "" || $customer_phone === "") {
    showErrorPage("Please fill all required customer fields.");
}
// validate email format
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    showErrorPage("Invalid email format.");
}
// check number of travelers
if ($num_travelers <= 0) {
    showErrorPage("Number of travelers must be a positive number.");
}

// check payment method
if ($payment_method !== "Visa Card" && $payment_method !== "Master Card") {
    showErrorPage("Please choose a valid payment method.");
}
// card number must be exactly 16 digits
if (!preg_match('/^\d{16}$/', $card_number)) {
    showErrorPage("Card number must be exactly 16 digits.");
}
// cardholder name must be letters and spaces only
if (!preg_match('/^[A-Za-z ]+$/', $card_name)) {
    showErrorPage("Cardholder name must contain letters and spaces only.");
}
// expiry must be in MM/YYYY format
if (!preg_match('/^(0[1-9]|1[0-2])\/\d{4}$/', $expiry)) {
    showErrorPage("Expiry date must be in MM/YYYY format.");
}

// fetch trip from database to check availability
try {
    $stmt = $pdo->prepare("SELECT trip_id, trip_name, destination, available_seats, price FROM trips WHERE trip_id = :id");
    $stmt->execute([":id" => $trip_id]);
    $trip = $stmt->fetch();
    // if trip does not exist
    if (!$trip) {
        showErrorPage("Trip not found.");
    }

    $available = (int)$trip["available_seats"];
    $db_price = (float)$trip["price"];

    // ensure posted price matches DB price
    $trip_price = $db_price;


    if ($num_travelers > $available) {
        showErrorPage("Not enough seats available. Available seats: " . $available);
    }

    // check if same user already booked this trip
    $check = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE trip_id = :trip_id AND customer_email = :email");
    $check->execute([
        ":trip_id" => $trip_id,
        ":email" => $customer_email
    ]);
    // if booking already exists
    if ($check->fetchColumn() > 0) {
        showErrorPage("You have already booked this trip with this email.");
    }

    // calculate total amount
    $total_amount = $trip_price * $num_travelers;

    // store only last 4 digits of card
    $last4 = substr($card_number, -4);

    /// start database transaction
    $pdo->beginTransaction();
    // insert booking record
    $ins = $pdo->prepare("
        INSERT INTO bookings
        (trip_id, customer_name, customer_email, customer_phone, num_travelers, total_amount, payment_method, card_number, special_requests)
        VALUES
        (:trip_id, :customer_name, :customer_email, :customer_phone, :num_travelers, :total_amount, :payment_method, :card_number, :special_requests)
    ");
    // execute insert with parameters
    $ins->execute([
        ":trip_id" => $trip_id,
        ":customer_name" => $customer_name,
        ":customer_email" => $customer_email,
        ":customer_phone" => $customer_phone,
        ":num_travelers" => $num_travelers,
        ":total_amount" => $total_amount,
        ":payment_method" => $payment_method,
        ":card_number" => $last4,
        ":special_requests" => $special_requests
    ]);
    // update available seats
    $upd = $pdo->prepare("
        UPDATE trips
        SET available_seats = available_seats - :n
        WHERE trip_id = :id AND available_seats >= :n
    ");
    // execute update with parameters
    $upd->execute([
        ":n"  => $num_travelers,
        ":id" => $trip_id
    ]);
    // if no rows were updated, not enough seats
    if ($upd->rowCount() === 0) {
        $pdo->rollBack();
        showErrorPage("Not enough seats available.");
    }
    // commit changes if everything is ok
    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    showErrorPage("Error processing booking.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Fly with Basel - Booking Confirmation</title>
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
            <!-- show booking confirmation -->
            <h2>Booking Confirmed </h2>
            <!-- safely display user and trip info -->
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
            <p><strong>Trip:</strong> <?php echo htmlspecialchars($trip["trip_name"]); ?> (<?php echo htmlspecialchars($trip["destination"]); ?>)</p>
            <p><strong>Number of Travelers:</strong> <?php echo (int)$num_travelers; ?></p>
            <p><strong>Total Amount Paid:</strong> $<?php echo number_format((float)$total_amount, 2); ?></p>

            <p><a href="tour-packages.php">Back to Tour Packages</a></p>
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