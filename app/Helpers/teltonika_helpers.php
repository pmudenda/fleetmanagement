<?php
function teltonika_decode_io(string $bytes)
{
    $len = strlen($bytes);

    // Most Teltonika IO values are unsigned big-endian
    return match ($len) {
        1 => ord($bytes),
        2 => unpack('n', $bytes)[1], // uint16 BE
        4 => unpack('N', $bytes)[1], // uint32 BE
        8 => teltonika_uint64_be($bytes),
        default => bin2hex($bytes),  // fallback for unusual sizes
    };
}

function teltonika_uint64_be(string $bytes)
{
    // 8 bytes big-endian unsigned
    $parts = unpack('Nhi/Nlo', $bytes);
    $hi = $parts['hi'];
    $lo = $parts['lo'];

    // 64-bit PHP: return int
    if (PHP_INT_SIZE >= 8) {
        return ($hi << 32) | $lo;
    }

    // 32-bit PHP fallback: return string
    return (string) ($hi * 4294967296 + $lo);
}
