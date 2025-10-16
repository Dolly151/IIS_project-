<?php
    require_once '../common/common.php';
    require_once '../services/permission_service.php';
    require_once '../services/request_service.php';

    PermissionService::requireRole(PermissionLevel::ANY);
    
    $request_service = new RequestService();
    $requests_to_approve = $request_service->getRoleRelevantRequests();
    $my_requests = $request_service->getMyRequests();
    
    function renderRequestsTable($requests)
    {
        if (empty($requests)) {
            return "<p>Žádné žádosti k zobrazení.</p>";
        }
        
        $html = "<table class='table'><thead><tr>";
        foreach (array_keys($requests[0]) as $col) {
            $html .= "<th>$col</th>";
        }
        $html .= "</tr></thead><tbody>";
        
        foreach ($requests as $req){
            $html .= "<tr>";
            foreach ($req as $val) {
                if (is_array($val)) {
                    $html .= "<td>" . implode(' ', $val) . "</td>";
                } 
                else {
                    $html .= "<td>$val</td>";
                }
            }

            $view = $_GET['view'] ?? 'my';
            if ($view == 'approve') {
                $html .= "<td><a href='actions/request_aprove_action.php?id=" . urlencode($req['ID']) . "' class='btn btn-success'>Schválit</a></td>";
                $html .= "<td><a href='actions/request_deny_action.php?id=" . urlencode($req['ID']) . "' class='btn btn-danger'>Zamítnout</a></td>";
            }
            
            $html .= "</tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }
    
    make_header('WIS - Žádosti', 'requests');
    $view = $_GET['view'] ?? 'my';
    
?>

<body>
    <div class="wrapper d-flex">
        <header>
            <?php include __DIR__ . '/menu.php'; ?>
        </header>
        <main>
            <div class="container d-flex flex-column py-5">
                <h1>Žádosti</h1>
                <nav>
                    <a href="?view=my" class="type">Moje žádosti</a>
                    <a href="?view=approve" class="type">Žádosti ke schválení</a>
                </nav>
                <hr>
                <div>
                    <?php
                        if ($view === 'approve') {
                            echo renderRequestsTable($requests_to_approve);
                        } 
                        else {
                            echo renderRequestsTable($my_requests);
                        }
                    ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>