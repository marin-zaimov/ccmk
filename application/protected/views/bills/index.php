<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h2>Unpaid Bills</h2> 
<table id="unpaid"></table>
<button id="wuddup">Wuddup</button>
<h2>Awaiting Confirmation</h2>
<table id="awaiting"></table>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bills.js"></script>
