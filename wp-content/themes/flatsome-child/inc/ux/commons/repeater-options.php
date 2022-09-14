<?php
// if(!$repeater_columns) $repeater_columns = '4';

return array(
  'type' => 'group',
  'heading' => __( 'Layout' ),
  'options' => array(
    'columns' => array(
      'type' => 'slider',
      'heading' => 'Columns',
      'conditions' => 'type !== "grid" && type !== "slider-full"',
      'default' => 3,
      'responsive' => true,
      'max' => '8',
      'min' => '1',
    ),
  ),
);
