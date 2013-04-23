<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h3>Unpaid Bills</h3> 

<table id="unpaid" class="table table-hover">
  <tr>
    <th>Group</th>
    <th>Receipt</th>
    <th>Receiver</th>
    <th>Total</th>
    <th>Amount Due</th>
  </tr>
</table>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bills.js"></script>
