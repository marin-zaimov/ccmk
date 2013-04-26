<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h3>Receipts</h3> 

<table id="receipts" class="table table-hover">
  <tr>
    <th>Name</th>
    <th>Total</th>
    <th>Group</th>
    <th>Paid?</th>
  </tr>
</table>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/receipt.js"></script>
