<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    function validateField($fieldName, $pattern, $errorMessage) {
        if (!preg_match($pattern, $_POST[$fieldName])) {
            return $errorMessage;
        }
        return "";
    }

    function validatePassword($fieldName, $errorMessage) {
        if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,16}$/", $_POST[$fieldName])) {
            return $errorMessage;
        }
        return "";
    }

    function validateEmail($fieldName, $errorMessage) {
        if (!filter_var($_POST[$fieldName], FILTER_VALIDATE_EMAIL)) {
            return $errorMessage;
        }
        return "";
    }


    $nameError = validateField("name", "/^[A-Za-z]+$/", "Name can only contain letters");
    $surnameError = validateField("surname", "/^[A-Za-z]+$/", "Surname can only contain letters");
    $usernameError = validateField("username", "/^[A-Za-z0-9.-_]+$/", "Username can only contain letters, numbers, dots, dashes, and underscores");
    $passwordError = validatePassword("password", "Password must contain at least 8-16 characters, one uppercase letter, one lowercase letter, and one number");
    $emailError = validateEmail("email", "Invalid email address");

    if ($_FILES["photo"]["error"] !== 0) {
        $photoError = "Error uploading photo";
    } else {
        $photoName = $_FILES["photo"]["name"];
        $photoTmpName = $_FILES["photo"]["tmp_name"];
        $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);


        $newPhotoName = uniqid() . "." . $photoExtension;


        $photoDestination = "photos/" . $newPhotoName;
        if (move_uploaded_file($photoTmpName, $photoDestination)) {
            // Successful upload, display the submitted information
            echo "<h2>Sign Up Successful</h2>";
            echo "<h3>Information:</h3>";
            echo "<p>Name: " . $_POST["name"] . "</p>";
            echo "<p>Surname: " . $_POST["surname"] . "</p>";
            echo "<p>Username: " . $_POST["username"] . "</p>";
            echo "<p>Email: " . $_POST["email"] . "</p>";
            echo "<p>Uploaded Photo: <br><img src='$photoDestination' alt='Uploaded Photo'></p>";
        } else {

            $photoError = "Error saving photo";
        }
    }


    if (!empty($nameError) || !empty($surnameError) || !empty($usernameError) || !empty($passwordError) || !empty($emailError) || !empty($photoError)) {
        echo "<h2>Error on Sign Up</h2>";
        echo "<ul>";
        echo "<li>$nameError</li>";
        echo "<li>$surnameError</li>";
        echo "<li>$usernameError</li>";
        echo "<li>$passwordError</li>";
        echo "<li>$emailError</li>";
        echo "<li>$photoError</li>";
        echo "</ul>";
        echo "<a href='signup.html'>Go back to Sign Up</a>";
    }
}
?>
