<?php
require_once('../common/common.php');
require_once('../services/term_service.php');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$service = new TermService();
$term = $id > 0 ? $service->getTermDetail($id) : null;

make_header('Detail termínu', 'term_detail');
?>

<body>
    <div class="wrapper d-flex">

        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>


        <main class="container mt-5">
            <h1>Detail termínu</h1>
            <hr>

            <?php if ($term): ?>
                <table class="table">
                    <tr>
                        <th>Název</th>
                        <td><?= htmlspecialchars($term['nazev']) ?></td>
                    </tr>
                    <tr>
                        <th>Datum a čas</th>
                        <td><?= htmlspecialchars($term['datum']) ?></td>
                    </tr>
                    <tr>
                        <th>Popis</th>
                        <td><?= htmlspecialchars($term['popis'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Kapacita</th>
                        <td><?= (int) $term['kapacita'] ?></td>
                    </tr>
                    <?php if (!empty($term['kurz_ID'])): ?>
                        <tr>
                            <th>Předmět</th>
                            <td>
                                <a href="course_detail.php?id=<?= (int) $term['kurz_ID'] ?>">
                                    Zobrazit detail předmětu
                                </a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            <?php else: ?>
                <p>Termín nebyl nalezen.</p>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>