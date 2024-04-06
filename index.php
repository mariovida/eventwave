<?php
    session_start();
    require_once 'vendor/autoload.php';
    require_once 'config.php';
    if(!isset($_SESSION['name'])) {
        header('Location: ./login');
    }
?>
<!DOCTYPE html>
<head>
    <?php
        define('meta', TRUE);
        include 'inc/head.php';
    ?>
    <script src="https://editor.unlayer.com/embed.js"></script>
    <script src="https://editor.unlayer.com/embed.js" data-key="K6sc66M6mxVAHpnd7p6U27y4XMBMYdQzsFUNfuQxxD7KUwzpGpdipXMaI1Zh41by"></script>
    <title>EventWave</title>
</head>
<body>
    <?php include 'inc/sidebar.php'; ?>

    <div class="content">
        <div class='wrapper'>
            <?php if($_SESSION['name']) { ?>
                <h3>Welcome, <?php echo $_SESSION['name']; ?></h3>
            <?php } else { ?>
                <h3>Welcome</h3>
            <?php } ?>
            
            <div class="wrapper-content"></div>
            <!--<form action="./send-mail.php" method="POST">
                <label for="email">Email Address:</label><br>
                <input type="email" id="email" name="email" required><br><br>

                <label for="subject">Subject:</label><br>
                <input type="text" id="subject" name="subject" required><br><br>

                <input type="hidden" id="unlayerHTML" name="message">

                <div id="editor"></div>

                <input type="submit" value="Send Email">
            </form>-->
        </div>
    </div>

    <script>
        unlayer.init({
            id: 'editor',
            projectId: 231851,
        });

        unlayer.addEventListener('design:updated', function(updates) {
            unlayer.exportHtml(function(data) {
                var json = data.design; // design json
                var html = data.html; // design html
                
                document.getElementById('unlayerHTML').value = html;
            })
        })
    </script>
</body>
</html>
