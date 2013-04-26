<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h3>Analytics</h3> 


<div class="row">
<div class="span8">
<div class="well">
  <dl class="dl-horizontal">
    <dt>Avg Receipt Amount</dt>
    <dd id='avg-receipt-amount'></dd>
    <dt>Avg Bill Amount</dt>
    <dd id='avg-bill-amount'></dd>
    <dt>Avg Users / Group</dt>
    <dd id='avg-users-per-group'></dd>
  </dl>
</div>
</div>
</div>




<div id="users-over-time-chart"></div>



<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/analytics.js"></script>
