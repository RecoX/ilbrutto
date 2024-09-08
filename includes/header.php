<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il Brutto Nightclub</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/styles.css">       
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>

<header>
    <h1>Il Brutto</h1>
    <nav>
        <ul>
            <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="calendar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'calendar.php' ? 'active' : '' ?>">Calendar</a></li>
            <li><a href="upcoming.php" class="<?= basename($_SERVER['PHP_SELF']) == 'upcoming.php' ? 'active' : '' ?>">Upcoming</a></li>
        </ul>
    </nav>
</header>
