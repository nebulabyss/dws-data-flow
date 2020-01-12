<?php
require_once './pdo.php';

$stmt = $pdo->prepare('SELECT * FROM flow_data
                                ORDER BY flow_id DESC LIMIT 25');

$stmt->execute(array());

?>

<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>Breede-Gouritz Station: H7H006</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container col-4">
	<h2>Breede-Gouritz Station: H7H006</h2>
        <table class="table table-bordered table-striped table-dark">
          <thead class="thead-dark">
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>ha(m)</th>
            <th>Flow(m&sup3;/s)</th>
          </tr>
          </thead>
            <tbody>
        <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<td>' . htmlentities($row['date']) . '</td>';
                echo '<td>' . htmlentities($row['time']) . '</td>';
                echo '<td>' . htmlentities($row['height']) . '</td>';
                echo '<td>' . htmlentities($row['flow']) . '</td>';
                echo "</tr>";
                echo "\r\n";
            }
        ?>
            </tbody>
         </table>
 </div>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</body>
</html>