<!DOCTYPE HTML>
<html>
<head>
    <title>Contact Me</title>
</head>
<body>

<?php
// This function checks and cleans up user input from the form
function validateInput($data, $fieldName) {
    global $errorCount;

    if (empty($data)) {
        echo "<p class='error'>The field '$fieldName' is required.</p>";
        ++$errorCount;
        $retval = "";
    } else {
        $retval = trim($data);
        $retval = stripslashes($retval);
        $retval = htmlspecialchars($retval);
    }

    return $retval;
}

// This function validates an email address and cleans it up
function validateEmail($data, $fieldName) {
    global $errorCount;

    if (empty($data)) {
        echo "<p class='error'>The field '$fieldName' is required.</p>";
        ++$errorCount;
        $retval = "";
    } else {
        $retval = trim($data);
        $retval = stripslashes($retval);
        $retval = htmlspecialchars($retval);

        if (!filter_var($retval, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error'>The field '$fieldName' must be a valid email address.</p>";
            ++$errorCount;
        }
    }

    return $retval;
}

// This function displays the contact form and keeps user input (sticky form)
function displayForm($Sender, $Email, $Subject, $Message) {
?>
    <h2>Contact Me</h2>
    <form action="ContactForm.php" method="post">
        <p><label for="Sender">Your Name:</label>
        <input type="text" name="Sender" id="Sender" value="<?php echo $Sender; ?>"></p>

        <p><label for="Email">Your Email:</label>
        <input type="text" name="Email" id="Email" value="<?php echo $Email; ?>"></p>

        <p><label for="Subject">Subject:</label>
        <input type="text" name="Subject" id="Subject" value="<?php echo $Subject; ?>"></p>

        <p><label for="Message">Message:</label><br>
        <textarea name="Message" id="Message" rows="6" cols="40"><?php echo $Message; ?></textarea></p>

        <p><input type="submit" name="Submit" value="Send Message"></p>
    </form>
<?php
}

// Initialize variables and error counter
$errorCount = 0;
$Sender = "";
$Email = "";
$Subject = "";
$Message = "";
$ShowForm = true;

// Check if the form was submitted
if (isset($_POST['Submit'])) {
    $Sender = validateInput($_POST['Sender'], "Your Name");
    $Email = validateEmail($_POST['Email'], "Your Email");
    $Subject = validateInput($_POST['Subject'], "Subject");
    $Message = validateInput($_POST['Message'], "Message");

    if ($errorCount == 0) {
        $ShowForm = false;
    }
}

// Show the form or send the email
if ($ShowForm) {
    displayForm($Sender, $Email, $Subject, $Message);
} else {
    $to = "williams_baptiste01@student.smc.edu"; // ✅ Your SMC email address
    $emailSubject = "Message from $Sender: $Subject";
    $emailBody = "You received a message from $Sender <$Email>:\n\n$Message";
    $headers = "From: $Email\r\n"; // ✅ Fixes the Bad Message Return Path warning

    // Send email to yourself
    mail($to, $emailSubject, $emailBody, $headers);

    // Send a copy to the sender
    mail($Email, "Copy of your message to $to", $emailBody, $headers);

    echo "<p>Thank you for contacting me, $Sender. Your message has been sent.</p>";
}

/*
Reflection:
// added to Reflection

1. What does each function do?
- validateInput() checks if the form field is empty and cleans it up so it’s safe.
- validateEmail() does the same thing but also checks if the email looks like a real one.
- displayForm() shows the form and keeps what the user typed if there are any errors.

2. How is user input protected?
- I used trim(), stripslashes(), and htmlspecialchars() to clean the input.
- That helps stop people from putting in weird or unsafe stuff.

3. What were the most confusing parts?
- I had a lot of trouble with syntax—like missing semicolons or wrong quotes.
- I also got a bunch of warnings when I tried to use the mail() function.
- At one point I saw:  
  “Warning: mail(): SMTP server response: 553 We do not relay non-local mail, sorry.”
- I didn’t know how to fix that, but I left the code in because the assignment didn’t say I had to actually send the email.

4. What output did I get when I tested it?
- The form showed up and worked.
- It gave me error messages when I left fields blank or typed a bad email.
- When I filled everything in, it said:  
  “Thank you for contacting me, Baptiste Williams. Your message has been sent.”
- But I still saw those mail() warnings.

5. What could be improved?
- I’d like to make the error messages look better with some color or styling. I know this 
was nt part of the assignment, it was just a thought. 
- I’d also want to figure out how to actually send the email if this was a real site.

6. Why send a copy of the form to the sender?
- So they know their message went through and they can see what they wrote.
*/


?>
</body>
</html>

