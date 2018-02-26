<?php

### Variables Variables Variables
start_timer();
$this_file = '/error_log.php';
$this_file_length = strlen($this_file);
$this_path = __FILE__;
$root = substr($this_path, 0, -$this_file_length);
$error_logs = array();


### Function: List All Files
function list_files($path = '') {
    global $error_logs;
    if ($handle = @opendir($path)) {
        while (false !== ($filename = readdir($handle))) {
            if ($filename !== '.' && $filename !== '..') {
                if (is_dir($path . '/' . $filename)) {
                    list_files($path . '/' . $filename);
                } else {
                    if (is_file($path . '/' . $filename)) {
                        if ($filename === 'error_log') {
                            $error_logs[] = array('file' => $path . '/' . $filename, 'size' => filesize($path . '/' . $filename));
                            unlink($path . '/' . $filename);
                        }
                    }
                }
            }
        }
        closedir($handle);
    } else {
        die('Invalid Directory');
    }
}


### Function: Start Timer
function start_timer() {
    global $timestart;
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $timestart = $mtime;
    return true;
}


### Function: Stop Timer
function stop_timer($precision = 5) {
    global $timestart;
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    $mtime = $mtime[1] + $mtime[0];
    $timeend = $mtime;
    $timetotal = $timeend - $timestart;
    return number_format($timetotal, $precision);
}


### Function: Format Size
function format_size($rawSize) {
    if ($rawSize / 1073741824 > 1) {
        return round($rawSize / 1073741824, 1) . 'GB';
    } elseif ($rawSize / 1048576 > 1) {
        return round($rawSize / 1048576, 1) . 'MB';
    } elseif ($rawSize / 1024 > 1) {
        return round($rawSize / 1024, 1) . 'KB';
    } else {
        return round($rawSize, 1) . 'b';
    }
}

### Get The error_log Files
list_files($root);
?>
<html>
<head>
    <title>GaMerZ error_log Cleaner 1.00</title>
    <style type="text/css" media="screen">
        BODY, P {
            font-family: "Verdana", "Arial", sans-serif;
            font-size: 10px;
            color: black;
        }

        A, A:active, A:visited {
            text-decoration: none;
        }

        A:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<p>Scanning <b><?php echo $root; ?></b> For error_log Files</p>
<p>Listing All error_log Files:<br/>
    <?php
    $no = 0;
    $total_size = 0;
    if ($error_logs) {
        foreach ($error_logs as $key => $error_log) {
            $no++;
            echo $no . '. ' . $error_log['file'] . ' (' . format_size($error_log['size']) . ')<br />';
            $total_size += $error_log['size'];
        }
    } else {
        echo 'No error_log File Found';
    }
    ?>
</p>
<p><b><?php echo $no; ?></b> error_log Worth <b><?php echo format_size($total_size); ?></b> Found And Deleted.</p>
<p align="center">
    Powered By <a href="http://www.lesterchan.net/" target="_blank">GaMerZ error_log Cleaner 1.00</a><br/>Copyright
    &copy; <?php echo date('Y'); ?> Lester "GaMerZ" Chan, All Rights Reserved.<br/><br/>Page Generated
    In <?php echo stop_timer(); ?> Seconds
</p>
</body>
</html>