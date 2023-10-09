<?php
if (isset($_POST['ip'])) {
    $target = urldecode(trim($_POST['ip']));
    $blacklist = [';', '&', '|', '`', '&&', '||', 'flag', '*', 'cat', ' ', 'head', 'tail', 'sh', 'python', 'echo', 'txt', '@', '/', '>', '<'];
    $containsBlacklisted = false;
    foreach ($blacklist as $blacklistedChar) {
        if (strpos($target, $blacklistedChar) !== false) {
            $containsBlacklisted = true;
            break;
        }
    }

    if (!$containsBlacklisted) {
        $cmd = shell_exec('ping -c 3 ' . $target);
    } else {
        $cmd = "Sorry, the input contains blacklisted characters. Check the blacklick in src code @.@";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ping Pong</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="css/style2.css" rel="stylesheet">
</head>

<body class="text-center">
    <main class="form-ping">
        <form method="post">
            <img class="mb-4" src="Pingpong_logo.png">
            <h1 class="h3 mb-3 fw-normal">Ping machine</h1>

            <div class="form-floating">
                <input name="ip" class="form-control" id="floatingInput">
                <label for="floatingInput">Ip address</label>
                <!-- $blacklist = [';', '&', '|', '`', '&&', '||', 'flag', '*', 'cat', ' ', 'head', 'tail', 'sh', 'python', 'echo', 'txt', '@', '/', '>', '<']; -->
                <!-- flag.txt is the same folder so don't need to find xD -->
            </div>
            <div class="text-start">
                <?php if (isset($_POST['ip'])) {
                    echo $target;
                    echo  "<pre>{$cmd}</pre>";
                } ?>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Ping</button>
        </form>
    </main>
</body>

</html>