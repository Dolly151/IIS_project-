<?php
require_once('../common/common.php');
require_once('../services/term_service.php');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$service = new TermService();
$term = $id > 0 ? $service->getTermDetail($id) : null;

make_header('Detail termínu', 'course_detail');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>

        <main class="container py-5">
            <h1>Detail termínu</h1>
            <hr>

            <?php if ($term): ?>
                <div class="container h-50">
                    <div class="row">
                        <div class="col-sm-3 "><strong><p>Název</p></strong></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($term['nazev']); ?></p></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><strong><p>Datum a čas</p></strong></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($term['datum']); ?></p></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><strong><p>Popis</p></strong></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($term['popis']); ?></p></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><strong><p>Kapacita</p></strong></div>
                        <div class="col-sm-9"><p><?php echo htmlspecialchars($term['nazev']); ?></p></div>
                    </div>
                    <?php if (!empty($term['kurz_ID'])): ?>
                        <div class="row">
                            <div class="col-sm-3"><strong><p>Předmět</p></strong></div>
                            <div class="col-sm-9">
                                <button class="btn btn-primary" href="course_detail.php?id=<?= (int) $term['kurz_ID'] ?>">
                                    Zobrazit detail předmětu
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </table>
            <?php else: ?>
                <p>Termín nebyl nalezen.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>