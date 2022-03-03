<?php
  // Template Name: Edit Islanders
ob_start();
get_header();


?>

<?php
if (isset($_POST['submit-edit-islanders'])){
  global $wpdb;
  $islanderTraveling = (! empty($_POST['edit-islanders'])) ? $_POST['edit-islanders'] : '' ;
  $islanderGender = (! empty($_POST['islander-gender'])) ? $_POST['islander-gender'] : '' ;
  $action = (! empty($_POST['islander-travel'])) ? $_POST['islander-travel'] : '' ;

  $islanderTravel = array(
     'islander_name' => $islanderTraveling
      );
      
  // decide which islander table to use
  if ($islanderGender == "Girl") {
    $islander_table_name = $wpdb->prefix.'current_girl_islanders';
  } elseif ($islanderGender == "Boy") {
    $islander_table_name = $wpdb->prefix.'current_boy_islanders';
  }
  
  // decide if adding or deleting an islander
  if ($action == "Enter") {
    $islander_result = $wpdb->insert($islander_table_name, $islanderTravel);
  } elseif ($action == "Exit") {
    $islander_result = $wpdb->delete($islander_table_name, $islanderTravel);
  }

  // display if data was saved properly or not
  if ($islander_result==1) {
    echo "<div class='col-12 row justify-content-center'><h2 class='success-message'> ✅ {$islanderTraveling} + {$action} ✅  </h2></div>";
  } else {
    echo "<h2> DATA NOT SAVED </h3>";
  }
}
?>


<form class="" action='' method='post'>
    <label class="p-1" for="edit-islanders">Islander</label>        
    <input class="p-1" type="text" name="edit-islanders" id="edit-islanders" value="">
    <div class="islander-acting column border p-1 btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-lg btn-girl-islander mb-1" for="islander-is-girl">
            <input type="radio" name="islander-gender" id="islander-is-girl" value='Girl'>Girl
        </label>
        <label class="btn btn-lg btn-boy-islander mb-1" for="islander-is-boy">
            <input type="radio" name="islander-gender" id="islander-is-boy" value='Boy'>Boy
        </label>
    </div>
    <div class="islander-acting column border p-1 btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn btn-lg btn-islander-action mb-1" for="islander-enter">
            <input type="radio" name="islander-travel" id="islander-enter" value='Enter'>Enter
        </label>
        <label class="btn btn-lg btn-islander-action mb-1" for="islander-exit">
            <input type="radio" name="islander-travel" id="islander-exit" value='Exit'>Exit
        </label>
    </div>
    <input class="p-1 ml-1 btn-md submit-button" type="submit" name="submit-edit-islanders" value="Edit Islanders"> 
</form>





<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php get_footer(); ?>