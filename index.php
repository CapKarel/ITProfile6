require 'init.php';

// PŘIDÁNÍ ZÁJMU
if (isset($_POST['add_interest'])) {
    $name = trim($_POST['name']);
    
    if (empty($name)) {
        $_SESSION['message'] = "Pole nesmí být prázdné.";
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO interests (name) VALUES (?)");
            $stmt->execute([$name]);
            $_SESSION['message'] = "Zájem byl přidán.";
        } catch (PDOException $e) {
            // SQLite vyhodí chybu 23000 při porušení UNIQUE u 'name'
            $_SESSION['message'] = "Tento zájem už existuje.";
        }
    }
    header("Location: index.php");
    exit;
}

// MAZÁNÍ ZÁJMU
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM interests WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['message'] = "Zájem byl odstraněn.";
    header("Location: index.php");
    exit;
}




// Načtení všech zájmů
$stmt = $db->query("SELECT * FROM interests ORDER BY name ASC");
$interests = $stmt->fetchAll(PDO::FETCH_ASSOC);


<?php if (isset($_SESSION['message'])): ?>
    <div class="alert">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="Nový zájem..." required>
    <button type="submit" name="add_interest">Přidat</button>
</form>

<ul>
    <?php foreach ($interests as $interest): ?>
        <li>
            <?= htmlspecialchars($interest['name']) ?>
            <a href="edit.php?id=<?= $interest['id'] ?>">Upravit</a>
            <a href="index.php?delete=<?= $interest['id'] ?>" onclick="return confirm('Opravdu?')">Smazat</a>
        </li>
    <?php <?php endforeach; ?>
</ul>
