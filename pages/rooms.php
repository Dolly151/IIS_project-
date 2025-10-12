<?php 
    require_once('../common/common.php');

    make_header('WIS - místnosti', 'requests')
?>                

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main>
            <div class="container profile d-flex flex-column h-auto py-5">
                <h1>Místnosti</h1>
                <nav>
                    <a href="?view=rooms" class="type">Místnosti</a>
                    <a href="?view=new" class="type">Zadat novou místnost</a>
                </nav>
                <hr>
            </div>
        </main>

</body>
</html>    