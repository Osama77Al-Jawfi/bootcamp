
<?php

$isHttps = isset($_SERVER["HTTPS"]) &&
           $_SERVER["HTTPS"] !== "off";

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "domain" => "",
    "secure" => $isHttps,
    "httponly" => true,
    "samesite" => "Strict"
]);


session_start();



if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}


if (!isset($_COOKIE["demo_insecure"])) {

    setcookie("demo_insecure", "visible_cookie_value", [
        "expires" => time() + 3600,
        "path" => "/",
        "secure" => $isHttps,
        "httponly" => false,
        "samesite" => "Lax"
    ]);

}

if (!isset($_COOKIE["demo_secure"])) {

    setcookie("demo_secure", "protected_cookie_value", [
        "expires" => time() + 3600,
        "path" => "/",
        "secure" => $isHttps,
        "httponly" => true,
        "samesite" => "Strict"
    ]);

}


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {

        http_response_code(403);
        $message = "CSRF validation failed.";

    } else {

        $action = $_POST["action"] ?? "";

        if ($action === "login") {

            $username = trim($_POST["username"] ?? "");

            if ($username === "") {

                $message = "Username is required.";

            } else {

                session_regenerate_id(true);

                $_SESSION["username"] = $username;
                $_SESSION["logged_in"] = true;

                $message = "Login successful. Session ID regenerated.";

            }

        } elseif ($action === "logout") {

            $_SESSION = [];

            if (ini_get("session.use_cookies")) {

                $params = session_get_cookie_params();

                setcookie(session_name(), "", [
                    "expires" => time() - 42000,
                    "path" => $params["path"],
                    "domain" => $params["domain"],
                    "secure" => $params["secure"],
                    "httponly" => $params["httponly"],
                    "samesite" => $params["samesite"]
                ]);

            }

            session_destroy();

            $message = "Logged out successfully.";

        }

    }

}



$isLoggedIn = !empty($_SESSION["logged_in"]);

$username = $_SESSION["username"] ?? "Guest";



$insecureCookie = $_COOKIE["demo_insecure"] ?? "Not received yet";

$secureCookie = $_COOKIE["demo_secure"] ?? "Not received yet";

$csrfToken = $_SESSION["csrf_token"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Session and Cookie Security</title>
</head>

<body>

    <h1>Session and Cookie Security</h1>

    <?php if ($message !== ""): ?>
        <p>
            <?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?>
        </p>
    <?php endif; ?>


    <h2>Session Information</h2>

    <p>
        Login Status:
        <?php echo $isLoggedIn ? "Logged In" : "Not Logged In"; ?>
    </p>

    <p>
        Username:
        <?php echo htmlspecialchars($username, ENT_QUOTES, "UTF-8"); ?>
    </p>


    <h2>Cookie Security Comparison</h2>

    <p>
        Insecure Cookie:
        <?php echo htmlspecialchars($insecureCookie, ENT_QUOTES, "UTF-8"); ?>
    </p>

    <p>
        HttpOnly Cookie:
        <?php echo htmlspecialchars($secureCookie, ENT_QUOTES, "UTF-8"); ?>
    </p>


    <h2>JavaScript Cookie Test</h2>

    <p id="cookieTest"></p>

    <script>
        document.getElementById("cookieTest").textContent =
            "JavaScript can see: " + document.cookie;
    </script>


    <h2>Login</h2>

    <form method="POST">

        <input type="hidden"
               name="csrf_token"
               value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8"); ?>">

        <input type="hidden" name="action" value="login">

        <label>Username:</label>

        <input type="text" name="username" required>

        <button type="submit">Login</button>

    </form>


    <h2>Logout</h2>

    <form method="POST">

        <input type="hidden"
               name="csrf_token"
               value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8"); ?>">

        <input type="hidden" name="action" value="logout">

        <button type="submit">Logout</button>

    </form>

</body>
</html>
