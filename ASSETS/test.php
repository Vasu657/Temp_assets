<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* Contact section styles */
        .contact {
          padding: 80px 20px;
          background-color: white;
        }

        .contact h2 {
          font-size: 36px;
          text-align: center;
          margin-bottom: 40px;
        }

        .contact-content {
          display: flex;
          justify-content: space-between;
          flex-wrap: wrap;
        }

        .contact-form {
          width: 50%;
          padding-right: 40px;
        }

        .contact-form h3 {
          font-size: 24px;
          margin-bottom: 20px;
        }

        .contact-form input,
        .contact-form textarea {
          display: block;
          width: 100%;
          padding: 10px;
          margin-bottom: 20px;
          border: 1px solid #ccc;
          border-radius: 4px;
        }

        .contact-form textarea {
          height: 150px;
        }

        .contact-info {
          width: 50%;
          padding-left: 40px;
        }

        .contact-info h3 {
          font-size: 24px;
          margin-bottom: 20px;
        }

        .contact-info p {
          margin-bottom: 10px;
        }

        .contact-info ul {
          list-style-type: none;
          padding: 0;
        }

        .contact-info ul li {
          margin-bottom: 10px;
        }

        /* Popup styles */
        #popup {
          position: fixed;
          top: 50%;
          right: 0;
          transform: translateY(-50%);
          background-color: #fff;
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
          padding: 20px;
          border-radius: 8px;
          display: none;
          z-index: 999;
          width: 250px; /* Decrease width */
          overflow: hidden; /* Ensure content does not overflow */
        }

        #popup.success {
          border: 2px solid #4caf50; /* Green border for success */
        }

        #popup.failure {
          border: 2px solid #f44336; /* Red border for failure */
        }

        #popup p {
          margin: 0;
          color: #333;
        }

        #popup.close-btn {
          position: absolute;
          top: 10px;
          right: 10px;
          color: #999;
          cursor: pointer;
          font-size: 20px;
        }

        /* Animation for sliding in */
        @keyframes slideInRight {
          from {
            transform: translateX(100%);
          }
          to {
            transform: translateX(0);
          }
        }

        /* Animation for sliding out */
        @keyframes slideOutRight {
          from {
            transform: translateX(0);
          }
          to {
            transform: translateX(100%);
          }
        }

        /* Fade in animation */
        @keyframes fadeIn {
          from {
            opacity: 0;
          }
          to {
            opacity: 1;
          }
        }

        /* Fade out animation */
        @keyframes fadeOut {
          from {
            opacity: 1;
          }
          to {
            opacity: 0;
          }
        }
    </style>
    <script>
        // JavaScript for popup animation
        function showPopup() {
          var popup = document.getElementById("popup");
          popup.style.display = "block";
          popup.style.animation = "slideInRight 0.5s forwards, fadeIn 0.5s forwards"; // Add fadeIn animation
        }

        function hidePopup() {
          var popup = document.getElementById("popup");
          popup.style.animation = "slideOutRight 0.5s forwards, fadeOut 0.5s forwards"; // Add fadeOut animation
          setTimeout(function() {
            popup.style.display = "none";
          }, 500);
        }

        // Show popup when showMessage is called
        function showMessage(message, isSuccess) {
          var formSubmitted = document.getElementById("contact-form").getAttribute("data-submitted");
          if (formSubmitted !== "true") {
            var popup = document.getElementById("popup");
            popup.innerText = message;
            popup.className = isSuccess ? "success" : "failure";
            showPopup();
            setTimeout(hidePopup, 3000);
          }
        }
    </script>
</head>
<body>

    <section class="contact" id="contact">
        <h2>Get in Touch</h2>
        <div class="contact-content">
            <div class="contact-form">
                <h3>Contact Us</h3>
                <form id="contact-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="document.getElementById('contact-form').setAttribute('data-submitted', 'true'); showMessage('Sending message...', false);">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <textarea name="message" placeholder="Message" required></textarea>
                    <button type="submit" class="btn">Submit</button>
                </form>
            </div>
            <div class="contact-info">
                <h3>Visit Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> 123 Main Street, City, State</p>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                <p><i class="fas fa-envelope"></i> info@securedigital.com</p>
                <h3>Opening Hours</h3>
                <ul>
                    <li>Monday - Friday: 9am - 6pm</li>
                    <li>Saturday: 10am - 4pm</li>
                    <li>Sunday: Closed</li>
                </ul>
            </div>
        </div>
    </section>

    <div id="popup"></div>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection
        $servername = "localhost"; // Change if your database server is different
        $username = "vasu@123";
        $password = "Vasu@123";
        $dbname = "safe";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Escape user inputs for security
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Check if email already exists in the database
        $sql_check = "SELECT * FROM contacts WHERE email='$email'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // If email exists, show error message
            echo "<script>showMessage('Email already exists. Please use a different email.', false);</script>";
        } else {
            // Insert user data into database
            $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
            if ($conn->query($sql) === TRUE) {
                // Success message
                echo "<script>showMessage('Your message has been sent successfully.', true);</script>";
            } else {
                // Error message
                echo "<script>showMessage('Sorry, something went wrong. Please try again later.', false);</script>";
            }
        }

        // Close connection
        $conn->close();
    }
    ?>

</body>
</html>
