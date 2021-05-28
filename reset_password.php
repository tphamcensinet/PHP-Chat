<?php

// Kết nối database, lấy dữ liệu chung
include('includes/general.php');

// Nếu tồn tại $user
if ($user) {
    header('Location: index.php'); // Di chuyển đến file index.php
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Get nội dung mail đã input
if (isset($_POST['mail'])) {
    $email_reset = isset($_POST['mail']) ? $_POST['mail'] : "";
    // Validate email/username
    if (filter_var($email_reset, FILTER_VALIDATE_EMAIL)) {
        // Get database email
        $sql = "SELECT * FROM users WHERE username = '$email_reset'";
        $account = mysqli_query($cn, $sql);
        if (mysqli_num_rows($account)) {

            $new_pwd = generateRandomString();
            $save_pwd = md5($new_pwd);
            
            $sql = "UPDATE users SET password='$save_pwd' WHERE username='$email_reset'";
           
            // Update new password
            mysqli_query($cn, $sql);
            
            $headers = "Content-type:text/html;charset=UTF-8" . "\r\n"; 
            $headers .= "From: PHP CHAT GROUP";
            $subject = "Reset password";
            $body = '
            <html> 
            <head> 
                <title>Welcome to CodexWorld</title> 
            </head> 
            <body> 
                <h1>Thanks you for joining with us!</h1> 
                <table cellspacing="0" style="border: 2px dashed #FB4314; width: 100%;"> 
                    <tr style="background-color: #e0e0e0;"> 
                        <th>Email:</th><td>' . $email_reset . '</td> 
                    </tr> 
                    <tr> 
                        <th>New password:</th><td>' . $new_pwd . '</td> 
                    </tr> 
                </table> 
            </body> 
            </html>'; 
            
        
            if (mail($email_reset, $subject, $body, $headers)) {
                echo "Email successfully sent to $email_reset...";
            } else {
                echo "Email sending failed...";
            }
            die;
        }
        else {
            echo "Email không tồn tại!";
        }
    }   
    else {
        echo "Email không hợp lệ!";
    }
}


// Kết nối header
include('includes/header.php');
?>

<div class="box-join">
    <h2>Reset password</h2>
    <form method="POST" id="formReset" action="#">
        <input type="text" placeholder="Email" class="data-input" name="mail" >
        <br />
        <a style="padding-right: 240px;" href="#" onclick="history.back()">Quay về</a>
        <br />
        <button class="btn-submit">Gửi mã xác nhận</button>
        <div class="alert danger"></div>
    </form>
</div>;

<?php
// Kết nối footer
include('includes/footer.php');
?>