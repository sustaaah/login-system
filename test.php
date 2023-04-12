<?php
$confirmCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

echo $confirmCode;
echo "<br />";
echo $confirmCode;