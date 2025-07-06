<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>
       
        .footer {
            background-color: #111;
            color: #fff;
            padding: 20px 0;
            width: 100%;
            text-align: center; /* Center the text */
            margin-top: auto; /* Push the footer to the bottom */
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .footer-left {
            text-align: center;
        }

        .footer-logo {
            width: 120px;
            margin-bottom: 10px;
        }

        .footer-tagline {
            font-style: italic;
            font-size: 14px;
            color: #ddd;
        }

        .footer-center, .footer-right {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .footer-center a, .footer-right a {
            color: #fff;
            text-decoration: none;
            margin: 5px 0;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-center a:hover, .footer-right a:hover {
            color: #f90; /* Change this color to whatever hover color you want */
        }

        .footer-bottom {
            padding-top: 10px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #333;
        }
    </style>
</head>
<body>
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <img src="http://localhost/urban_edge/assets/urban_edge_white.png" alt="Urban Edge Logo" class="footer-logo">
                <p class="footer-tagline">“STYLE THAT STANDS OUT, JUST LIKE YOU”</p>
            </div>
            <div class="footer-center">
                <a href="about_us.php">ABOUT US</a>
                <a href="contact_us.php">CONTACT US</a>
                <a href="faqs.php">FAQS</a>
                <a href="terms_conditions.php">TERMS AND CONDITIONS</a>
                <a href="privacy_policy.php">PRIVACY POLICY</a>
            </div>
            <div class="footer-right">
                <a href="https://facebook.com" target="_blank">FACEBOOK</a>
                <a href="https://instagram.com" target="_blank">INSTAGRAM</a>
                <a href="https://twitter.com" target="_blank">TWITTER</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>2024 Urban Edge. All Rights Reserved. | Designed And Developed By Urban Edge Team.</p>
        </div>
    </div>
</body>
</html>
