<?php

/**
 * Catalog sync tool.
 *
 * Downloads the @datafast/ai-crawl npm bundle, parses its crawler tables, and
 * regenerates resources/crawlers.php. Used by maintainers and the weekly
 * GitHub Action — end users never need to run it.
 *
 * Usage:
 *   php bin/sync-catalog.php           Rewrite resources/crawlers.php from upstream.
 *   php bin/sync-catalog.php --check   Print drift and exit 1 if out of date (no write).
 *
 * @see https://www.npmjs.com/package/@datafast/ai-crawl
 */

const BUNDLE_URL = 'https://unpkg.com/@datafast/ai-crawl/dist/index.js';
const PACKAGE_URL = 'https://unpkg.com/@datafast/ai-crawl/package.json';
const DATA_FILE = __DIR__.'/../resources/crawlers.php';

$checkOnly = in_array('--check', $argv, true);

try {
    $upstream = parseUpstream();
} catch (Throwable $e) {
    fwrite(STDERR, "✖ Failed to read upstream catalog: {$e->getMessage()}\n");
    exit(2);
}

$current = require DATA_FILE;

$diff = diffCatalog($current, $upstream);
$hasChanges = $diff['changed'] || $current['version'] !== $upstream['version'];

printSummary($current, $upstream, $diff);

if (! $hasChanges) {
    echo "✓ Catalog already up to date (upstream v{$upstream['version']}).\n";
    exit(0);
}

if ($checkOnly) {
    echo "→ Drift detected. Run `composer sync-catalog` to update.\n";
    exit(1);
}

file_put_contents(DATA_FILE, renderDataFile($upstream));
echo "✓ resources/crawlers.php regenerated from upstream v{$upstream['version']}.\n";
exit(0);

/* -------------------------------------------------------------------------- */

function httpGet(string $url): string
{
    $context = stream_context_create(['http' => [
        'header' => "User-Agent: laravel-datafast-aicrawl-sync\r\n",
        'follow_location' => 1,
        'timeout' => 30,
    ]]);

    $body = @file_get_contents($url, false, $context);

    if ($body === false) {
        // Fall back to curl if allow_url_fopen is disabled.
        $body = shell_exec('curl -fsSL '.escapeshellarg($url).' 2>/dev/null');
    }

    if (! is_string($body) || $body === '') {
        throw new RuntimeException("empty response from {$url}");
    }

    return $body;
}

/**
 * @return array{version: string, providers: array, aliases: array}
 */
function parseUpstream(): array
{
    $bundle = httpGet(BUNDLE_URL);
    $package = httpGet(PACKAGE_URL);

    if (! preg_match('/"version":\s*"([^"]+)"/', $package, $m)) {
        throw new RuntimeException('could not read version from package.json');
    }
    $version = $m[1];

    return [
        'version' => $version,
        'providers' => parseProviders($bundle),
        'aliases' => parseAliases($bundle),
    ];
}

/**
 * @return array<int, array{provider: string, agents: array<int, array{agent: string, category: string}>}>
 */
function parseProviders(string $bundle): array
{
    if (! preg_match('/var AI_CRAWLER_PROVIDERS = (\[.*?\]);\s*var NORMALIZED_AGENTS/s', $bundle, $block)) {
        throw new RuntimeException('AI_CRAWLER_PROVIDERS block not found (bundle format changed?)');
    }

    preg_match_all(
        '/provider:\s*"([^"]+)",\s*agents:\s*\[(.*?)\]\s*\}/s',
        $block[1],
        $providerMatches,
        PREG_SET_ORDER,
    );

    if ($providerMatches === []) {
        throw new RuntimeException('no providers parsed');
    }

    $providers = [];

    foreach ($providerMatches as $pm) {
        preg_match_all(
            '/\{\s*agent:\s*"([^"]+)",\s*category:\s*AI_CRAWLER_CATEGORY\.([A-Z_]+)\s*\}/s',
            $pm[2],
            $agentMatches,
            PREG_SET_ORDER,
        );

        $agents = [];
        foreach ($agentMatches as $am) {
            $agents[] = ['agent' => $am[1], 'category' => strtolower($am[2])];
        }

        $providers[] = ['provider' => $pm[1], 'agents' => $agents];
    }

    return $providers;
}

/**
 * @return array<int, array{provider: string, agent: string, category: string, aliases: array<int, string>}>
 */
function parseAliases(string $bundle): array
{
    if (! preg_match('/var AI_CRAWLER_CANDIDATE_ALIASES = (\[.*?\]);\s*var DEFAULT_IGNORED_PATH_PREFIXES/s', $bundle, $block)) {
        throw new RuntimeException('AI_CRAWLER_CANDIDATE_ALIASES block not found (bundle format changed?)');
    }

    preg_match_all(
        '/provider:\s*"([^"]+)",\s*agent:\s*"([^"]+)",\s*category:\s*AI_CRAWLER_CATEGORY\.([A-Z_]+),\s*aliases:\s*\[([^\]]*)\]/s',
        $block[1],
        $matches,
        PREG_SET_ORDER,
    );

    if ($matches === []) {
        throw new RuntimeException('no aliases parsed');
    }

    $aliases = [];

    foreach ($matches as $m) {
        preg_match_all('/"([^"]+)"/', $m[4], $aliasStrings);

        $aliases[] = [
            'provider' => $m[1],
            'agent' => $m[2],
            'category' => strtolower($m[3]),
            'aliases' => $aliasStrings[1],
        ];
    }

    return $aliases;
}

/**
 * @return array{changed: bool, addedAgents: array<int, string>, removedAgents: array<int, string>, recategorized: array<int, string>}
 */
function diffCatalog(array $current, array $upstream): array
{
    $flat = static function (array $catalog): array {
        $out = [];
        foreach ($catalog['providers'] as $p) {
            foreach ($p['agents'] as $a) {
                $out[$p['provider'].' / '.$a['agent']] = $a['category'];
            }
        }

        return $out;
    };

    $cur = $flat($current);
    $new = $flat($upstream);

    $added = array_values(array_diff(array_keys($new), array_keys($cur)));
    $removed = array_values(array_diff(array_keys($cur), array_keys($new)));

    $recategorized = [];
    foreach ($new as $key => $category) {
        if (isset($cur[$key]) && $cur[$key] !== $category) {
            $recategorized[] = "{$key}: {$cur[$key]} → {$category}";
        }
    }

    $aliasesChanged = json_encode($current['aliases']) !== json_encode($upstream['aliases']);

    return [
        'changed' => $added || $removed || $recategorized || $aliasesChanged,
        'addedAgents' => $added,
        'removedAgents' => $removed,
        'recategorized' => $recategorized,
    ];
}

function printSummary(array $current, array $upstream, array $diff): void
{
    echo "Upstream version: {$upstream['version']} (local: {$current['version']})\n";

    $print = static function (string $label, array $items): void {
        if ($items === []) {
            return;
        }
        echo "\n{$label}:\n";
        foreach ($items as $item) {
            echo "  • {$item}\n";
        }
    };

    $print('Added agents', $diff['addedAgents']);
    $print('Removed agents', $diff['removedAgents']);
    $print('Recategorized', $diff['recategorized']);
    echo "\n";
}

function renderDataFile(array $data): string
{
    $providers = '';
    foreach ($data['providers'] as $p) {
        $providers .= "        ['provider' => ".export($p['provider']).", 'agents' => [\n";
        foreach ($p['agents'] as $a) {
            $providers .= "            ['agent' => ".export($a['agent']).", 'category' => ".export($a['category'])."],\n";
        }
        $providers .= "        ]],\n";
    }

    $aliases = '';
    foreach ($data['aliases'] as $a) {
        $aliasList = implode(', ', array_map('export', $a['aliases']));
        $aliases .= '        ['
            ."'provider' => ".export($a['provider']).', '
            ."'agent' => ".export($a['agent']).', '
            ."'category' => ".export($a['category']).', '
            ."'aliases' => [{$aliasList}]],\n";
    }

    $version = export($data['version']);

    return <<<PHP
<?php

/*
|--------------------------------------------------------------------------
| AI crawler catalog (DATA)
|--------------------------------------------------------------------------
|
| This file is GENERATED. Do not edit by hand — run:
|
|     composer sync-catalog          # rewrite from latest upstream
|     composer sync-catalog:check    # show drift without writing
|
| It mirrors AI_CRAWLER_PROVIDERS and AI_CRAWLER_CANDIDATE_ALIASES from the
| npm package @datafast/ai-crawl. `version` is the upstream package version
| this data was generated from.
|
| @see https://www.npmjs.com/package/@datafast/ai-crawl
|
*/

return [
    'version' => {$version},

    // Exact agent-token matches (case-insensitive substring of the User-Agent).
    'providers' => [
{$providers}    ],

    // Fallback alias matches, used only when no exact agent token matches.
    'aliases' => [
{$aliases}    ],
];

PHP;
}

function export(string $value): string
{
    return var_export($value, true);
}
