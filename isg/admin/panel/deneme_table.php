<?php
require '/home/laser/vendor/autoload.php';
require '../connect.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Ods');
$reader->setReadDataOnly(TRUE);
$spreadsheet = $reader->load("liste.ods");

$worksheet = $spreadsheet->getActiveSheet();
// Get the highest row and column numbers referenced in the worksheet
$highestRow = $worksheet->getHighestRow(); // e.g. 10
$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

echo '<table>' . "\n";
for ($row = 2; $row <= $highestRow; ++$row) {
    echo '<tr>' . PHP_EOL;

        $value1 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $value2 = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        $value3 = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
        $value4 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $value5 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
        $value6 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
        $value7 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();


        echo '<td>' . $value1 . '</td>' . PHP_EOL;
        echo '<td>' . $value2 . '</td>' . PHP_EOL;
        echo '<td>' . $value3 . '</td>' . PHP_EOL;
        echo '<td>' . $value4 . '</td>' . PHP_EOL;
        echo '<td>' . $value5 . '</td>' . PHP_EOL;
        echo '<td>' . $value6 . '</td>' . PHP_EOL;
        echo '<td>' . $value7 . '</td>' . PHP_EOL;

    $sql = "INSERT INTO `coop_workers`(`name`, `tc`, `position`, `sex`, `mail`, `phone`, `contract_date`,`company_id`)
    VALUES('$value1', '$value2', '$value3', '$value4', '$value5', '$value6', '$value7',413)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute();

    echo '</tr>' . PHP_EOL;
}
echo '</table>' . PHP_EOL;
 ?>
