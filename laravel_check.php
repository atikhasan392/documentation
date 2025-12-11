<?php
// ----------------------------------
// Laravel Full Environment Checker - OPcache Removed
// ----------------------------------

$required_php_version = '8.1';
$required_extensions = [
    'bcmath', 'ctype', 'curl', 'dom', 'fileinfo',
    'filter', 'json', 'mbstring', 'openssl', 'pcre',
    'pdo', 'pdo_mysql', 'pdo_sqlite', 'session', 'tokenizer', 'xml', 'zip',
];

// ----------------------
// Helper functions
// ----------------------
function cli_echo($text, $color = null) {
    $colors = [
        'green' => "\033[32m",
        'red'   => "\033[31m",
        'yellow'=> "\033[33m",
        'blue'  => "\033[34m",
        'reset' => "\033[0m"
    ];
    echo ($colors[$color] ?? '') . $text . $colors['reset'] . "\n";
}

function divider() {
    echo str_repeat("-", 50) . "\n";
}

function mem_to_mb($val){
    $val = strtoupper($val);
    if(str_ends_with($val,'G')) return intval($val)*1024;
    if(str_ends_with($val,'M')) return intval($val);
    return intval($val);
}

// ----------------------
// HEADER
// ----------------------
cli_echo("Laravel Environment Checker", 'blue');
divider();

// ----------------------
// PHP Version Check
// ----------------------
cli_echo("1. PHP Version Check", 'yellow');
$current_php_version = PHP_VERSION;
if (version_compare($current_php_version, $required_php_version, '>=')) {
    cli_echo("✅ PHP $current_php_version is sufficient", 'green');
} else {
    cli_echo("❌ PHP $current_php_version is too old. Minimum required: $required_php_version", 'red');
}
divider();

// ----------------------
// PHP Extensions Check
// ----------------------
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

// ----------------------
// Composer Check
// ----------------------
cli_echo("3. Composer Check", 'yellow');
$composer_output = function_exists('shell_exec') ? trim(shell_exec('composer --version 2>&1')) : '';
if ($composer_output && stripos($composer_output,'composer')!==false) {
    cli_echo("✅ Composer detected: $composer_output", 'green');
} else {
    cli_echo("⚠️ Composer not detected. Install Composer first.", 'yellow');
}
divider();

// ----------------------
// Node.js, npm & Bun Check
// ----------------------
cli_echo("4. Node.js & npm & Bun Check (Optional, frontend tools)", 'yellow');
$node_output = trim(shell_exec('node -v 2>&1'));
$npm_output  = trim(shell_exec('npm -v 2>&1'));
$bun_output  = trim(shell_exec('bun -v 2>&1'));

if (!empty($node_output) && stripos($node_output, 'v') === 0) {
    cli_echo("✅ Node.js detected: $node_output", 'green');
} else {
    cli_echo("ℹ️ Node.js not detected. Install later if needed.", 'blue');
}

if (!empty($npm_output)) {
    cli_echo("✅ npm detected: $npm_output", 'green');
} else {
    cli_echo("ℹ️ npm not detected. Install later if needed.", 'blue');
}

if (!empty($bun_output)) {
    cli_echo("✅ Bun detected: $bun_output", 'green');
} else {
    cli_echo("ℹ️ Bun not installed. Only needed if using Bun runtime.", 'blue');
}
divider();

// ----------------------
// PHP Recommended Settings Check
// ----------------------
cli_echo("5. PHP Recommended Settings Check", 'yellow');

$recommended_settings = [
    'memory_limit' => ['min'=>1024, 'unit'=>'M'],
    'upload_max_filesize' => ['min'=>5120, 'unit'=>'M'],
    'post_max_size' => ['min'=>5120, 'unit'=>'M'],
    'max_execution_time' => ['min'=>300, 'unit'=>'seconds'],
    'max_input_vars' => ['min'=>1000, 'unit'=>'count'],
    'date.timezone' => ['min'=>1, 'unit'=>'set'],
    'error_reporting' => ['min'=>1, 'unit'=>'set'],
    'display_errors' => ['min'=>1, 'unit'=>'On'],
    'log_errors' => ['min'=>1, 'unit'=>'On'],
    'max_file_uploads' => ['min'=>20, 'unit'=>'count'],
    'default_socket_timeout' => ['min'=>60, 'unit'=>'seconds'],
    'realpath_cache_size' => ['min'=>16, 'unit'=>'M'],
];

foreach($recommended_settings as $key => $info){
    $current = ini_get($key);
    $ok = false;

    if(in_array($key,['memory_limit','upload_max_filesize','post_max_size','realpath_cache_size'])){
        $ok = mem_to_mb($current) >= $info['min'];
    } elseif(in_array($key,['max_execution_time','max_input_vars','default_socket_timeout','max_file_uploads'])){
        $ok = intval($current) >= $info['min'] || ($key==='max_execution_time' && intval($current)===0);
    } elseif(in_array($key,['display_errors','log_errors'])){
        $ok = filter_var($current, FILTER_VALIDATE_BOOLEAN);
    } elseif($key==='date.timezone'){
        $ok = !empty($current);
    } elseif($key==='error_reporting'){
        $ok = intval($current) > 0;
    }

    $display_value = $current ?: 'Not Set';
    $mark = $ok ? '✅' : '❌';
    $color = $ok ? 'green' : 'yellow';

    cli_echo("$mark $key = $display_value (Recommended: >= {$info['min']} {$info['unit']})", $color);
}

divider();

// ----------------------
// Next Steps
cli_echo("Next Steps", 'blue');
cli_echo("1. ✅ Ensure PHP version meets minimum requirement", 'green');
if (!empty($missing_extensions)) cli_echo("2. ⚠️ Enable all missing PHP extensions in php.ini", 'yellow');
else cli_echo("2. ✅ All required PHP extensions are enabled", 'green');
cli_echo("3. ✅ Install Composer if not installed", 'green');
cli_echo("4. ✅ After PHP setup, you can install Laravel via Composer", 'green');
divider();
cli_echo("🎉 Your environment is now ready for Laravel installation!", 'blue');
divider();