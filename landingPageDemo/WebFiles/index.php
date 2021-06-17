<?php require_once("includes/init.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <?php require_once("includes/header.php"); ?>
</head>

<body>

    <!-- adding nav bar -->
    <?php require_once("includes/elements/nav.php"); ?>

    <!-- hero section -->
    <div class="hero-image mb-4">
        <div class="hero-text">
            <h1 style="font-size:50px">YTONG Bausatzhaus
            </h1>
            <p>Bauherrenzentrum Berlin-Brandenburg</p>
            <div>
                <p>"Wir begleiten Sie
                    zu Ihrem perfekten
                    YTONG-Eigenheim!"</p>
            </div>
        </div>
    </div>

    <div class="p-4">
        <!-- content -->
        <div class='section text-center'>
            <div class='mb-4'>
                <h4>
                    <?php
                    $stmt = $connection->prepare("SELECT * from body_content");
                    $stmt->execute();
                    $stmt->bind_result($Id, $Header, $Content);
                    $results = array();
                    while ($stmt->fetch()) {
                        $bodyHeader = $Header;
                        $bodyContent = $Content;
                    }
                    echo $bodyHeader;
                    ?>
                </h4>
                <p>
                    <?php echo $bodyContent; ?>
                </p>
            </div>
        </div>

        <!-- card section -->
        <div>
            <div class="row text-center d-flex justify-content-center">
                <?php
                $stmt = $connection->prepare("SELECT * from card_content");
                $stmt->execute();
                $stmt->bind_result($Id, $Header, $Content);
                $results = array();
                while ($stmt->fetch()) {
                    $cardHeader = $Header;
                    $cardContent = $Content;
                ?>
                    <div class="col-md-3 m-4 bg-secondary rounded ">
                        <h5 class='p-4'>
                            <strong>
                                <?php echo $cardHeader ?>
                            </strong>
                        </h5>
                        <p>
                            <?php echo $cardContent ?>
                        </p>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>

</body>

</html>
<script src="assets/js/scripts.js"></script>