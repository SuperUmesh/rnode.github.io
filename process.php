<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data from the form
    $ps4_ip = $_POST['ps4_ip'];  // PS4 IP Address
    $pkg_url = $_POST['pkg_url']; // URL to the .pkg file

    // Check if both fields are filled
    if (!empty($ps4_ip) && !empty($pkg_url)) {
        // Prepare the cURL request to the PS4 API
        $url = "http://{$ps4_ip}:12800/api/install";
        $data = json_encode([
            "type" => "direct",
            "packages" => [$pkg_url]
        ]);

        // Initialize cURL session
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        // Execute the cURL request and capture the response
        $response = curl_exec($ch);

        // Check if the request was successful
        if ($response === false) {
            $error = curl_error($ch);
            echo "<div style='color: red;'>Error: {$error}</div>";
        } else {
            echo "<div style='color: green;'>Package sent successfully to PS4 at {$ps4_ip}!</div>";
        }

        // Close the cURL session
        curl_close($ch);
    } else {
        echo "<div style='color: red;'>Please provide both PS4 IP Address and .pkg URL.</div>";
    }
} else {
    // If not a POST request, redirect to the form
    header('Location: index.html');
    exit();
}
?>
