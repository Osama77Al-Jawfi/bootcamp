
<?php

$maxSize = 5 * 1024 * 1024; 

$allowedTypes = [
    "pdf" => "application/pdf",
    "png" => "image/png",
    "jpg" => "image/jpeg",
    "jpeg" => "image/jpeg"
];

$uploadDir = "C:\\xampp\\private_uploads";



if (!is_dir($uploadDir)) {

    if (!mkdir($uploadDir, 0700, true)) {
        die("Error: Cannot create storage directory.");
    }

}


$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_FILES["uploaded_file"])) {

        $message = "Error: No file was uploaded.";

    } else {

        $file = $_FILES["uploaded_file"];

        if ($file["error"] !== UPLOAD_ERR_OK) {

            $message = "Error: File upload failed.";

        }
        elseif ($file["size"] > $maxSize) {

            $message = "Error: File exceeds the 5 MB limit.";

        }

        
        else {

            $extension = strtolower(
                pathinfo($file["name"], PATHINFO_EXTENSION)
            );

            if (!array_key_exists($extension, $allowedTypes)) {

                $message = "Error: File extension is not allowed.";

            }

            
            else {

                $finfo = new finfo(FILEINFO_MIME_TYPE);

                $mimeType = $finfo->file($file["tmp_name"]);

                if ($mimeType !== $allowedTypes[$extension]) {

                    $message = "Error: File content does not match its extension.";

                }

                
                else {

                    try {

                        $randomName = bin2hex(random_bytes(16));

                        $newFileName = $randomName . "." . $extension;

                        $destination = $uploadDir . DIRECTORY_SEPARATOR . $newFileName;


                        if (is_uploaded_file($file["tmp_name"]) &&
                            move_uploaded_file($file["tmp_name"], $destination)) {

                            $message = "Success: File uploaded securely.";

                        } else {

                            $message = "Error: Could not save the uploaded file.";

                        }

                    } catch (Exception $e) {

                        $message = "Error: File processing failed.";

                    }

                }

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Secure File Upload</title>
</head>

<body>

    <h2>Secure File Upload System</h2>

    <?php if ($message !== ""): ?>

        <p>
            <?php echo htmlspecialchars($message, ENT_QUOTES, "UTF-8"); ?>
        </p>

    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Select a file:</label>

        <input type="file" name="uploaded_file" required>

        <br><br>

        <button type="submit">Upload File</button>

    </form>

    <p>Allowed formats: PDF, PNG, JPG</p>

    <p>Maximum file size: 5 MB</p>

</body>

</html>
