<?php
require_once __DIR__ . '/../data/repository_factory.php';

class TermService
{
    private Repository $repo;

    public function __construct()
    {
        $this->repo = RepositoryFactory::create();
    }

    /** jednoduchá validace povinných polí */
    public function isValidForCreate(): bool
    {
        return isset($_POST['nazev'], $_POST['typ'], $_POST['datum'], $_POST['kapacita']);
    }

    /** bezpečná konverze z <input type="datetime-local"> na SQL DATETIME */
    private function normalizeDatetime(string $in): string
    {
        $in = trim($in);
        // očekáváme formát "YYYY-MM-DDTHH:MM" (+ případně :SS)
        $in = str_replace('T', ' ', $in);
        if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $in)) {
            $in .= ':00';
        }
        return $in; // např. "2025-11-18 13:00:00"
    }

    /** vytvoření termínu pro daný kurz */
    public function createTerm(int $courseId): bool
    {
        $roomId = isset($_POST['room_id']) && $_POST['room_id'] !== '' ? (int)$_POST['room_id'] : null;

        $data = [
            'nazev'      => $_POST['nazev'],
            'typ'        => (int)$_POST['typ'],
            'datum'      => $this->normalizeDatetime($_POST['datum']),
            'popis'      => $_POST['popis'] ?? '',
            'kapacita'   => (int)$_POST['kapacita'],
            'kurz_ID'    => $courseId,
        ];
        // sloupec mistnost_ID přidej jen pokud je vybrán (vyhne se to NOT NULL chybě, pokud to máš NULLable)
        if ($roomId !== null) {
            $data['mistnost_ID'] = $roomId;
        }

        // volitelně můžeš nastavit default pro 'hodnoceni' na 0, pokud je v tabulce NOT NULL:
        // $data['hodnoceni'] = 0;

        return $this->repo->insert('Termin', $data); // použij stejné casing jako v DB (u tebe 'termin')
    }

    /** načti existující termíny kurzu (pro náhled po vytvoření) */
    public function listByCourse(int $courseId): array
    {
        return $this->repo->getByCondition('Termin', ['ID','nazev','typ','datum','kapacita','mistnost_ID'], ['kurz_ID' => $courseId]);
    }
}
