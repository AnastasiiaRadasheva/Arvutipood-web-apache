<?php

function read_log(string $path, int $max = 200): array
{
    if (!file_exists($path)) {
        return ["[PUUDUB] Fail puudub: $path"];
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return ["[VIGA] Ei saanud lugeda faili: $path"];
    }

    return array_slice($lines, -$max);
}

$access = read_log('/var/log/apache2/access.log');
$error  = read_log('/var/log/apache2/error.log');
$api    = read_log('/var/log/apache2/api.log');

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Süsteemi logid</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        pre {
            background: #111;
            color: #0f0;
            padding: 10px;
            max-height: 300px;
            overflow: auto;
            font-size: 12px;
        }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>

<h1>Süsteemi logid</h1>

<h2>Access log</h2>
<pre><?= htmlspecialchars(implode("\n", $access), ENT_QUOTES, 'UTF-8') ?></pre>

<h2>Error log</h2>
<pre><?= htmlspecialchars(implode("\n", $error), ENT_QUOTES, 'UTF-8') ?></pre>

<h2>API log</h2>
<pre><?= htmlspecialchars(implode("\n", $api), ENT_QUOTES, 'UTF-8') ?></pre>

</body>
</html>
