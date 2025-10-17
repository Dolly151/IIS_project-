<?php 
    session_start();

    function make_header($title, $file, $additional_css = '') { ?>
        <!DOCTYPE html>
        <html lang="en">    
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/css/main.css">
            <link rel="stylesheet" href="../assets/css/<?php echo $file?>.css">
            <?php if ($additional_css != '') {
                echo $additional_css;
            } ?>

            <title><?php echo $title;?></title>
        </head>
        <body>
    <?php 
    }

    /* TODO
    function make_footer() {

    }*/


    function redirect($dest)
    {
        $script = $_SERVER["PHP_SELF"];
        if (strpos($dest,'/') === 0) {
            $path = $dest;
        } else {
            $path = substr($script, 0, strrpos($script, '/')) . "/$dest";
        }
        $name = $_SERVER["SERVER_NAME"];
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://$name$path");
    }
?>