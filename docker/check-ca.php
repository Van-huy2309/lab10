<?php

$path = getenv('MYSQL_ATTR_SSL_CA') ?: '';

if ($path === '' || ! is_readable($path)) {
    fwrite(STDERR, "Cannot read MySQL CA certificate.\n");
    exit(1);
}

$pem = file_get_contents($path);
if ($pem === false || ! str_contains($pem, 'BEGIN CERTIFICATE')) {
    fwrite(STDERR, "MySQL CA file is not a PEM certificate.\n");
    exit(1);
}

echo "MySQL CA certificate OK\n";
