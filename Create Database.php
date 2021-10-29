<?php
$message= $invoiceID =$Descriptions= "";
require_once("dtn75570.php");
$stmt = $conn->query("SELECT invoice_id
 FROM `dtn75570`.`invoice`
 ORDER BY invoice_id");
foreach ($stmt as $row) { 
  $invoiceID .= "<option>{$row["invoice_id"]}</option>";
}
$stmt = $conn->query("SELECT item_id, description
 FROM `dtn75570`.`item`
 ORDER BY description");
foreach ($stmt as $row) { 
  $Descriptions .= "<option value='{$row["item_id"]}'>{$row["description"]}</option>";
}
if (isset($_POST["submit"])) {
  $invoiceID = $_POST["invoice_id"];
  $Descriptions = $_POST["description"];
  $quantity = $_POST["quantity"];
  $itemID = $_POST["item_id"];
  
  $query =
  "SELECT `invoice_id` 
   FROM `dtn75570`.`invoice_item`
   WHERE `item_id` = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$itemID]);
  
  
  if ($stmt->rowCount() == 0) {
    $stmt = $conn->prepare("INSERT INTO `dtn75570`.`invoice_item` (`item_id`, `quantity`) 
                            VALUES (?, ?");
    $stmt->execute([$itemID,$quantity]);
    $message .= "<h5>Inserted item</h5>Successfully inserted the item <b>#$itemID</b>.";
} else {
$query = "UPDATE `invoice_item`
     SET `quantity` = `quantity`+ $quantity;
     WHERE `item_id` = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$quantity,$itemID]);
}
}
$conn = null;
?>

<!doctype html>
<html>
  <head>
    <title>Insert Invoice Line</title>
    <link href="main.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <h3>Inserting Invoice Line</h3>
      <form method="post">
        <div><label>InvoiceID: </label><select name="invoice_id"><?php echo $invoiceID; ?></select></div>
        <div><label>Description: </label><select name="description"><?php echo $Descriptions; ?></select></div>
        <div><label>Quantity: </label><input type="text" name="quantity"></div>
        <div><input type="submit" name="submit"></div>
      </form>
      <?php echo $message; ?>
    </div>
  </body>
</html>
