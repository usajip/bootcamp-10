<!DOCTYPE html>
<html>
<body>

<?php
//shorthand if with multiple conditions
$age = 30;
$isAdult = ($age >= 18 && $age < 65) ? 'Ya' : 'Tidak';

$isSenior = ($age >= 65) ? 'Ya' : ($age < 18 ? 'Tidak' : 'Ya');
echo 'Is senior: ' . $isSenior . '<br>';

$name = "John Doe";
$age = 30;
echo "Name is an integer: " . (is_int($name) ? 'true' : 'false') . "<br>";
echo "Name: $name <br>";
echo 'Name: $name <br>';
echo 'Name: ' . $name . '<br>';
echo 'Is adult: ' . $isAdult . '<br>';
?> 

</body>
</html>