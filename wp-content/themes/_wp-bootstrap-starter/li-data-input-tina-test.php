<?php
/*
 * Template Name: Data Input
 * Template Post Type: page
 */
 ?>

<!-- https://www.youtube.com/watch?v=GUcN9xRpO7U -->

<!-- this page template simply posts Tina age 25 to the tested database 'wp_alex_sql_write_test'-->

<?php
  if(isset($_POST['my-button'])){
    global $wpdb;
      $wpdb->insert(
        $wpdb->prefix.'alex_sql_write_test',
        [
          'name' => 'Tina', 
          'age' => 25
        ]
        );    
  }
?>

<h1>love island data input test</h1>
<form method='post'>
  <input type="submit" name="my-button" value="Add Tina">
</form>