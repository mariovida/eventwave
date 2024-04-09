<?php
    $APP_URL = "https://mario-dev.eu/construction/events";
    $TEST_URL = "http://localhost/events";
    $pageUrl = $TEST_URL;
?>
<nav>
    <div class="nav-logo d-flex align-items-center">
        <img src="<?php echo $pageUrl; ?>/assets/logo.png" />
        <p>EventWave</p>
    </div>
    <div class="nav-links d-flex flex-column">
        <a href="<?php echo $pageUrl; ?>"><img src="<?php echo $pageUrl; ?>/assets/icons/home2.svg" />Home</a>
        <button type="button" data-bs-toggle="collapse" data-bs-target="#collapseContacts" aria-expanded="false" aria-controls="collapseContacts"><img src="<?php echo $pageUrl; ?>/assets/icons/contacts.svg" />Contacts<img class="chevron" src="<?php echo $pageUrl; ?>/assets/icons/chevron-right.svg" /></button>
        <div class="collapse" id="collapseContacts">
            <div class="card card-body">
                <a href="<?php echo $pageUrl; ?>/contacts">View all</a>
                <a href="<?php echo $pageUrl; ?>/contacts/create-new">Create new</a>
            </div>
        </div>
        <button type="button" data-bs-toggle="collapse" data-bs-target="#collapseEvents" aria-expanded="false" aria-controls="collapseEvents"><img src="<?php echo $pageUrl; ?>/assets/icons/events.svg" />Events<img class="chevron" src="<?php echo $pageUrl; ?>/assets/icons/chevron-right.svg" /></button>
        <div class="collapse" id="collapseEvents">
            <div class="card card-body">
                <a href="<?php echo $pageUrl; ?>/events">View all</a>
                <a href="<?php echo $pageUrl; ?>/events/create-new">Create new</a>
            </div>
        </div>
        <a id="logout-btn"><img src="<?php echo $pageUrl; ?>/assets/icons/logout.svg" />Log out</a>
    </div>
</nav>

<script>
    document.getElementById("logout-btn").addEventListener("click", function() {
        window.location.href = "<?php echo $pageUrl; ?>/logout";
    });
</script>