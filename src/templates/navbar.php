<?php 
    $currentPageFilename = pathinfo(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), PATHINFO_FILENAME);
    $userIsAdmin = $_SESSION['userData']['user_type_label'] == "admin";
?>

<div class="app-bar">
    <div class="app-bar-leading">
        <img 
            src="<?php echo $_SESSION['userData']['photo'] ?>" 
            alt="User Profile Picture"
        />
    </div>

    <div class="app-bar-title">
        <h3>
            <?php 
                echo explode("@", $_SESSION['userData']['svvid'])[0];
            ?>
        </h3>
    </div>

    <div class="app-bar-actions">
        <a href="/pages/bookings.php">
            <div 
                class="<?php 
                    echo "nav-item " . ($currentPageFilename == "bookings" ? "active" : "inactive");
                ?>"
            >
                <i class="ph-bookmark"></i>
                <span>Bookings</span>
            </div>
        </a>
        <a href="/pages/grounds.php">
            <div 
                class="<?php 
                    echo "nav-item " . ($currentPageFilename == "grounds" ? "active" : "inactive");
                ?>"
            >
                <i class="ph-map-pin"></i>
                <span>Ground</span>
            </div>
        </a>
        <a href="/scripts/logout.php">
            <i class="ph-sign-out"></i>
        </a>
    </div>
</div>