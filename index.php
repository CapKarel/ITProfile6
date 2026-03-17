<?php
require 'init.php';

// Přidání zájmu
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add"])) {
    $name = trim($_POST["name"]);

    if (empty($name)) {
        $_SESSION["msg"] = "Pole nesmí být prázdné.";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)");
            $stmt->execute([$name]);
            $_SESSION["msg"] = "Zájem byl přidán.";
        } catch (PDOException $e) {
            $_SESSION["msg"] = "Tento zájem už existuje.";
        }
    }

    header("Location: index.php");
    exit;
}

// Smazání
if (isset($_GET["delete"])) {
    $stmt = $db->prepare("DELETE FROM interests WHERE id = ?");
    $stmt->execute([$_GET["delete"]]);

    $_SESSION["msg"] = "Zájem byl odstraněn.";
    header("Location: index.php");
    exit;
}

// Editace
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit"])) {
    $id = $_POST["id"];
    $name = trim($_POST["name"]);

    if (empty($name)) {
        $_SESSION["msg"] = "Pole nesmí být prázdné.";
    } else {
        try {
            $stmt = $db->prepare("UPDATE interests SET name = ? WHERE id = ?");
            $stmt->execute([$name, $id]);
            $_SESSION["msg"] = "Zájem byl upraven.";
        } catch (PDOException $e) {
            $_SESSION["msg"] = "Tento zájem už existuje.";
        }
    }

    header("Location: index.php");
    exit;
}

// Načtení dat
$stmt = $db->query("SELECT * FROM interests");
$interests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>IT Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Moje zájmy</h1>

<?php if (isset($_SESSION["msg"])): ?>
    <p><?= $_SESSION["msg"]; unset($_SESSION["msg"]); ?></p>
<?php endif; ?>

<!-- Přidání -->
<form method="POST">
    <input type="text" name="name" placeholder="Nový zájem">
    <button type="submit" name="add">Přidat</button>
</form>

<hr>

<!-- Seznam -->
<ul>
<?php foreach ($interests as $interest): ?>
    <li>
        <!-- Edit form -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $interest["id"] ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($interest["name"]) ?>">
            <button type="submit" name="edit">Upravit</button>
        </form>

        <!-- Delete -->
        <a href="?delete=<?= $interest["id"] ?>" onclick="return confirm('Opravdu smazat?')">
            Smazat
        </a>
    </li>
<?php endforeach; ?>
</ul>

</body>
</html>
