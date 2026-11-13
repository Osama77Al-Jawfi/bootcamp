
<?php



header("Content-Type: application/json; charset=UTF-8");


try {

    $pdo = new PDO("sqlite::memory:");

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );


    $pdo->exec("
        CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL,
            email TEXT NOT NULL
        )
    ");

    $insert = $pdo->prepare("
        INSERT INTO users (username, email)
        VALUES (:username, :email)
    ");

    $users = [
        ["osama", "osama@example.com"],
        ["ahmed", "ahmed@example.com"],
        ["mohammed", "mohammed@example.com"]
    ];

    foreach ($users as $user) {

        $insert->execute([
            ":username" => $user[0],
            ":email" => $user[1]
        ]);

    }


    $username = $_GET["username"] ?? "";

    $mode = $_GET["mode"] ?? "secure";



    if ($mode === "vulnerable") {

        $sql = "
            SELECT id, username, email
            FROM users
            WHERE username = '$username'
        ";

        $stmt = $pdo->query($sql);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


    elseif ($mode === "secure") {

        $sql = "
            SELECT id, username, email
            FROM users
            WHERE username = :username
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":username" => $username
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    else {

        http_response_code(400);

        echo json_encode([
            "status" => 400,
            "message" => "Invalid mode"
        ], JSON_PRETTY_PRINT);

        exit;

    }


    echo json_encode([
        "status" => 200,
        "mode" => $mode,
        "search" => $username,
        "total_results" => count($results),
        "users" => $results
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);


} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "status" => 500,
        "message" => "Internal server error"
    ], JSON_PRETTY_PRINT);

}
