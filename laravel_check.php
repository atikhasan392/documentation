<?php
// ----------------------------------
// Laravel Full Environment Checker - OPcache Removed (Software Check Combined)
// ----------------------------------

$required_php_version = '8.1';
$required_extensions = [
    'bcmath',
    'ctype',
    'curl',
    'dom',
    'fileinfo',
    'gd',
    'filter',
    'json',
    'mbstring',
    'openssl',
    'pcre',
    'pdo',
    'pdo_mysql',
    'pdo_sqlite',
    'session',
    'tokenizer',
    'xml',
    'zip',
];

function cli_echo($text, $color = null)
{
    $colors = [
        'green' => "\033[32m",
        'red'   => "\033[31m",
        'yellow' => "\033[33m",
        'blue'  => "\033[34m",
        'reset' => "\033[0m"
    ];
    echo ($colors[$color] ?? '') . $text . $colors['reset'] . "\n";
}

function divider()
{
    echo str_repeat("-", 50) . "\n";
}

function mem_to_mb($val)
{
    $val = strtoupper(trim($val));
    if ($val === '-1') return -1;
    if (str_ends_with($val, 'G')) return intval($val) * 1024;
    if (str_ends_with($val, 'M')) return intval($val);
    if (str_ends_with($val, 'K')) return intval($val) / 1024;
    return intval($val);
}

cli_echo("Laravel Environment Checker", 'blue');
divider();

// ---------------------------------------------------
// 1. Software Check
// ---------------------------------------------------
cli_echo("1. Software Check", 'yellow');

// PHP Version
$current_php_version = PHP_VERSION;
if (version_compare($current_php_version, $required_php_version, '>=')) {
    cli_echo("✅ PHP Version: $current_php_version", 'green');
} else {
    cli_echo("❌ PHP Version: $current_php_version (Minimum Required: $required_php_version)", 'red');
}

// Composer
$composer_output_raw = function_exists('shell_exec') ? trim(shell_exec('composer --version 2>&1')) : '';
$composer_output = '';

if ($composer_output_raw) {
    foreach (explode("\n", $composer_output_raw) as $line) {
        if (stripos($line, 'Composer version') !== false) {
            $composer_output = trim($line);
            break;
        }
    }
}

if (!empty($composer_output)) {
    cli_echo("✅ Composer: $composer_output", 'green');
} else {
    cli_echo("❌ Composer not found", 'red');
}

// Node
$node_output = trim(shell_exec('node -v 2>&1'));
if (!empty($node_output) && str_starts_with($node_output, 'v')) {
    cli_echo("✅ Node.js: $node_output", 'green');
} else {
    cli_echo("❌ Node.js not found", 'red');
}

// npm
$npm_output = trim(shell_exec('npm -v 2>&1'));
if (!empty($npm_output)) {
    cli_echo("✅ npm: $npm_output", 'green');
} else {
    cli_echo("❌ npm not found", 'red');
}

// Bun
$bun_output = trim(shell_exec('bun -v 2>&1'));
if (!empty($bun_output)) {
    cli_echo("✅ Bun: $bun_output", 'green');
} else {
    cli_echo("❌ Bun not found (optional)", 'yellow');
}

divider();

// ---------------------------------------
// 2. PHP Extension Check
// ---------------------------------------
cli_echo("2. PHP Extension Check", 'yellow');

$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
        cli_echo("❌ Missing: $ext", 'red');
    } else {
        cli_echo("✅ Present: $ext", 'green');
    }
}

if (!empty($missing_extensions)) {
    cli_echo("⚠️ Missing extensions detected. Enable them in php.ini", 'yellow');
}

divider();

// ---------------------------------------
// 3. PHP Recommended Settings (Custom Aligned Output)
// ---------------------------------------
cli_echo("3. PHP Recommended Settings Check", 'yellow');

$recommended_settings = [
    'memory_limit'           => ['min' => 1024, 'unit' => 'M'],
    'upload_max_filesize'    => ['min' => 5120, 'unit' => 'M'],
    'post_max_size'          => ['min' => 512, 'unit' => 'M'],
    'max_input_vars'         => ['min' => 3000],
    'date.timezone'          => ['min' => 'Asia/Dhaka'],
    'error_reporting'        => ['min' => 1],
    'display_errors'         => ['min' => 1],
    'log_errors'             => ['min' => 1],
    'max_file_uploads'       => ['min' => 100],
    'default_socket_timeout' => ['min' => 60],
    'realpath_cache_size'    => ['min' => 16, 'unit' => 'M'],
    'extension_dir'          => ['min' => 'ext']
];

// Calculate max lengths for alignment
$max_key_length = max(array_map('strlen', array_keys($recommended_settings)));

foreach ($recommended_settings as $key => $info) {
    $current = ini_get($key);

    $ok = false;

    // Separate handling for extension_dir
    if ($key === 'extension_dir') {
        $current = $current ?: 'Not Set';
        $ok = ($current === $info['min']);
    } elseif (in_array($key, ['memory_limit', 'upload_max_filesize', 'post_max_size', 'realpath_cache_size'])) {
        $ok = mem_to_mb($current) >= $info['min'];
    } elseif (in_array($key, ['max_input_vars', 'default_socket_timeout', 'max_file_uploads'])) {
        $ok = intval($current) >= $info['min'];
    } elseif (in_array($key, ['display_errors', 'log_errors'])) {
        $ok = ($current == 1 || strtolower($current) === 'on');
    } elseif ($key === 'date.timezone') {
        $ok = ($current === $info['min']);
    } elseif ($key === 'error_reporting') {
        $ok = intval($current) > 0;
    }

    $display_value = $current ?: 'Not Set';
    $mark = $ok ? '✅' : '❌';
    $color = $ok ? 'green' : 'yellow';

    $key_padded   = str_pad($key, $max_key_length, " ", STR_PAD_RIGHT);
    $value_padded = str_pad($display_value, 10, " ", STR_PAD_RIGHT);
    $line = "$mark $key_padded : $value_padded = (Recommended: {$info['min']}" . ($info['unit'] ?? '') . ")";

    cli_echo($line, $color);
}

divider();

// ---------------------------------------
// Finish
// ---------------------------------------
cli_echo("4. Next Steps", 'yellow');
cli_echo("1. Verify all required software is installed", 'green');
if (!empty($missing_extensions)) cli_echo("2. Enable missing PHP extensions", 'yellow');
else cli_echo("2. All required PHP extensions are enabled", 'green');
cli_echo("3. After setup, install Laravel via Composer", 'green');
divider();
cli_echo("✔  Environment check completed", 'blue');
divider();
