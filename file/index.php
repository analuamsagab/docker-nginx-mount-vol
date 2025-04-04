<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Page</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0058fc, #5ee7ff);
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        h2 {
            font-size: 1.8em;
        }

        a {
            color:rgb(255, 255, 255);
            text-decoration: none;
        }

        a:hover {
            color:rgb(0, 132, 255);
        }

        .storage-info {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: left;
        }

        .button-container {
            padding: 15px;
        }

        .button-container a {
            display: inline-block;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            color: white !important;
            width: 100px;
            text-align: center;
            margin-right: 10px;
        }

        .landing-container {
            padding: 15px;
        }

        .landing-container a {
            display: inline-block;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            color: white !important;
            width: 170px;
            text-align: center;
            margin-right: 15px;
	    align-items: left;
        }

        .go-back { background-color: #4B0082; }
        .go-home { background-color: #b30000; }
	.gdrive { background-color: #cc9100; }
	.back { background-color: #4B0082; }
        .go-back:hover { background-color: #6a0dad; }
        .go-home:hover { background-color: #e60000; }
	.gdrive:hover { background-color : #ffb500; }
	.back:hover { background-color: #6a0dad; }

        .table-container {
 	    widht: 100%;
	    padding 15px;
	    overflow-x: auto;
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
	    padding 15px;
            background-color: #1e1e1e;
            border-radius: 8px;
            margin-top: 10px;
	    white-space: nowrap;
	    overflow-x: auto;
        }

        th, td {
            padding: 12px;
            border: 1px solid #333;
            text-align: left;
        }

        th { background-color: #292929; }

        @media (max-width: 768px) {
            .button-container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .button-container a {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <h2>All Files & Oprekan</h2>

    <?php
    // Informasi penyimpanan
    $disk_free = disk_free_space("/");
    $disk_total = disk_total_space("/");
    $disk_free_gb = round($disk_free / 1024 / 1024 / 1024, 2);
    $disk_total_gb = round($disk_total / 1024 / 1024 / 1024, 2);
    $disk_used_gb = $disk_total_gb - $disk_free_gb;
    $disk_used_percent = round(($disk_used_gb / $disk_total_gb) * 100, 2);
    ?>

    <div class="storage-info">
        <p><strong>Total Penyimpanan:</strong> <?= $disk_total_gb ?> GB</p>
        <p><strong>Sisa Penyimpanan:</strong> <?= $disk_free_gb ?> GB</p>
        <p><strong>Terpakai:</strong> <?= $disk_used_gb ?> GB (<?= $disk_used_percent ?>%)</p>
    </div>

   <div class="button-container">
	
  <?php if (isset($_GET['dir']) && $_GET['dir'] !== "."): ?>
	<a class="go-home" href="?dir=.">Go Home</a>	
        <a class="go-back" href="?dir=<?= urlencode(dirname($_GET['dir'])) ?>">Go Back</a>
	 <?php endif; ?>
	<a class="gdrive" href="https://shorturl.at/qA7aA">GDrive</a>

   </div>
 <div class="table-container">
    <table>
        <tr>
            <th>File Name</th>
            <th>Size</th>
            <th>Type</th>
            <th>Last Modified</th>
        </tr>
        <?php
        function formatSize($bytes) {
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' B';
            }
        }
        
        $dir = isset($_GET['dir']) ? $_GET['dir'] : ".";
        $files = array_diff(scandir($dir), array(".", "..", ".htaccess", "index.php", "logo.png"));
        
        usort($files, function($a, $b) use ($dir) {
            $typeA = is_dir("$dir/$a") ? "0" : mime_content_type("$dir/$a");
            $typeB = is_dir("$dir/$b") ? "0" : mime_content_type("$dir/$b");
            
            if ($typeA === $typeB) {
                return strnatcasecmp($a, $b);
            }
            return strcmp($typeA, $typeB);
        });
        
        foreach ($files as $file) {
            $path = "$dir/$file";
            $size = is_file($path) ? formatSize(filesize($path)) : "-";
            $type = is_dir($path) ? "Folder" : mime_content_type($path);
            $modified = date("Y-m-d H:i:s", filemtime($path));
            $link = is_dir($path) ? "?dir=" . urlencode($path) : $path;
            
            echo "<tr>
                    <td><a href='$link'>$file</a></td>
                    <td>$size</td>
                    <td>$type</td>
                    <td>$modified</td>
                </tr>";
        }
        ?>
    </table>
</div>
<div class="landing-container">
	<a class="back" href="https://bagastekaje.my.id"><strong>Balik ke Landing Page</strong></a>
</div>
</body>
</html>
