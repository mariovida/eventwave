<?php
    $APP_URL = "http://localhost/events";
?>
<nav>
    <div class="nav-logo d-flex align-items-center">
        <img src="<?php echo $APP_URL; ?>/assets/logo.png" />
        <p>EventWave</p>
    </div>
    <div class="nav-links d-flex flex-column">
        <a href=""><img src="<?php echo $APP_URL; ?>/assets/icons/home2.svg" />Home</a>
        <a href=""><img src="<?php echo $APP_URL; ?>/assets/icons/contacts.svg" />Contacts</a>
        <a href=""><img src="<?php echo $APP_URL; ?>/assets/icons/events.svg" />Events</a>
        <a id="logout-btn"><img src="<?php echo $APP_URL; ?>/assets/icons/logout.svg" />Log out</a>
    </div>
</nav>

<script>
    document.getElementById("logout-btn").addEventListener("click", function() {
        window.location.href = "<?php echo $APP_URL; ?>/logout";
    });
</script>