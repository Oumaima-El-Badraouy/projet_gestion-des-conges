<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "booking_tables";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Traiter la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Vérifier si un fichier a été téléchargé
    if (isset($_FILES["photo"])) {
        // Vérifier les erreurs de téléchargement
        if ($_FILES["photo"]["error"] == UPLOAD_ERR_OK) {
            // Téléchargement de la photo
            $photo_tmp = $_FILES["photo"]["tmp_name"];
            $photo_content = addslashes(file_get_contents($photo_tmp));

            // Préparer la requête SQL
            $sql = "INSERT INTO contact (name, email, subject, message, photo) VALUES ('$name', '$email', '$subject', '$message', '$photo_content')";

            // Exécuter la requête SQL
            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Afficher l'erreur de téléchargement
            $upload_errors = array(
                UPLOAD_ERR_OK => "There is no error, the file uploaded with success.",
                UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
                UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
                UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
                UPLOAD_ERR_NO_FILE => "No file was uploaded.",
                UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
                UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload."
            );
            $error_message = $_FILES["photo"]["error"];
            echo "Error uploading photo: " . $upload_errors[$error_message];
        }
    } else {
        echo "No file was uploaded.";
    }
}

// Fermer la connexion
$conn->close();
?>
