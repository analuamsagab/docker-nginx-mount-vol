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
            #font-weight: bold;
            text-decoration: none;
            color: white !important;
            width: 150px;
            height: 20px;
            text-align: center;
            margin-right: 15px;
            align-items: left;
        }
        .search-form {
            display: flex;
            justify-content: left;
            margin-top: 10px;
	    margin-left: 7px;
        }

        .search-form input[type="text"] {
            padding: 12px;
            border-radius: 8px 0 0 8px;
            border: none;
            width: 210px;
            font-size: 16px;
        }

        .search-form button {
            padding: 12px 20px;
            border: none;
            border-radius: 0 8px 8px 0;
            background-color: #4B0082;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #6a0dad;
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
            padding: 15px;
            overflow-x: auto;
            white-space: nowrap;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding: 15px;
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
    <h2>Selamat Datang</h2>



    <?php
    // Informasi penyimpanan
    $disk_free = disk_free_space("/");
    $disk_total = disk_total_space("/");
    $disk_free_gb = round($disk_free / 1024 / 1024 / 1024, 2);
    $disk_total_gb = round($disk_total / 1024 / 1024 / 1024, 2);
    $disk_used_gb = $disk_total_gb - $disk_free_gb;
    $disk_used_percent = round(($disk_used_gb / $disk_total_gb) * 100, 2);

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

    function searchFilesRecursively($dir, $keyword) {
        $results = [];
        $items = array_diff(scandir($dir), ['.', '..']);
        foreach ($items as $item) {
            $path = "$dir/$item";
            if (is_dir($path)) {
                $results = array_merge($results, searchFilesRecursively($path, $keyword));
            } else {
                if (stripos($item, $keyword) !== false) {
                    $results[] = $path;
                }
            }
        }
        return $results;
    }
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

    <div class="search-form">
        <form method="get" class="search-form">
        <input type="text" name="search" placeholder="Ga nemu? cari disini aja bro...">
        <button type="submit">Cari</button>
    </form>
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
            $dir = isset($_GET['dir']) ? $_GET['dir'] : ".";
            $searchKeyword = isset($_GET['search']) ? trim($_GET['search']) : "";

            if (!empty($searchKeyword)) {
                echo "<tr><td colspan='4'><strong>Hasil pencarian untuk:</strong> " . htmlspecialchars($searchKeyword) . "</td></tr>";
                $files = searchFilesRecursively($dir, $searchKeyword);
            } else {
                $files = array_diff(scandir($dir), array(".", "..", ".htaccess", "index.php", "logo.png"));
                $files = array_map(function($f) use ($dir) { return "$dir/$f"; }, $files);
            }

            usort($files, function($a, $b) {
                $typeA = is_dir($a) ? "0" : mime_content_type($a);
                $typeB = is_dir($b) ? "0" : mime_content_type($b);
                return ($typeA === $typeB) ? strnatcasecmp(basename($a), basename($b)) : strcmp($typeA, $typeB);
            });

            foreach ($files as $path) {
                $file = basename($path);
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
        <a class="back" href="https://bagastekaje.my.id"><strong>Pulang Bro</strong></a>
    </div>
</body>
</html>
