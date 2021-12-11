<?php
  // Template Name: Modify Islanders
ob_start();
get_header();

// establish a variable for episode number
// $episode = FIGURE OUT HOW TO PULL FROM THE DB

?>
<!-- FORM TO ADD A NEW ISLANDER -->
<!-- <form action='' method='post'>
  <label for="islander-name">New Islander's Name
    <input type="text" name="islander-name" id="islander-name" value=''>
  </label>
  <input type="submit" name="new-islander" value="Add Islander">
</form> -->

<!-- FUNCTION TO ADD NEW ISLANDER TO RADIO OPTIONS -->
<!-- <?php
// if (isset($_POST['new-islander'])){
//   $name = (! empty($_POST['islander-name'])) ? sanitize_text_field($_POST['islander-name']) : '' ;
//   $data = array(
//     'firstName' => $name
//   );
//   $table_name = $wpdb->prefix.'islanders';
//   $result = $wpdb->insert($table_name, $data);
//   if ($result==1) {
//     echo "<script>alert('Islander Data Saved');</script>";
//   } else{
//       echo "<script>alert('Unable to Save');</script>";
//   }

//   // after submitting this form, redirect the user to the same page safely
//   // $location = "http://" . $_SERVER['HTTP_POST'] . $_SERVER['REQUEST_URI'] ;
//   // wp_safe_redirect($location);
// }
?> -->


<?php 
  global $wpdb;
  $lastEpisode = $wpdb->get_var( 'SELECT MAX(episode) FROM `wp_islander_actions`', 0, 0 ); 
  $boy_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_boy_islanders`'); 
  $girl_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_girl_islanders`');  
  ?>
  
  
    <form action='' method='post' class="container-fluid">
      <div class="row p-1">
        <label for="episdoe">Episode</label>        
        <input type="text" name="episode" id="episode" value='<?php echo $lastEpisode ?>' class="col-2"> 
      </div>

      <div class="container row border p-1">
        <div class="islander-acting-girls col">
          <?php foreach ($girl_islanders as $girl_islander): ?> 
            <div class="islander-option"> 
              <input type="radio" name="islander-acting" id="<?= $girl_islander; ?>" value='<?= $girl_islander; ?>'>
              <label for="<?= $girl_islander; ?>"><?= $girl_islander; ?></label> 
            </div>
          <?php endforeach; ?>
        </div>
        <div class="islander-acting-boys col">
          <?php foreach ($boy_islanders as $boy_islander): ?> 
            <div class="islander-option"> 
              <input type="radio" name="islander-acting" id="<?= $boy_islander; ?>" value='<?= $boy_islander; ?>'>
              <label for="<?= $boy_islander; ?>"><?= $boy_islander; ?></label> 
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="container row border p-1">
          <div class="islander-action m-2">
            <input type="radio" name="action" id="melt" value='Melt'>
            <label for="melt">Melt</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="ick" value='Ick'>
            <label for="ick">Ick</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="peck" value='Peck'>
            <label for="peck">Peck</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="snog" value='Snog'>
            <label for="snog">Snog</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="start-conflict" value='Start Conflict'>
            <label for="start-conflict">StartCon</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="participate-conflict" value='Participate Conflict'>
            <label for="participate-conflict">PartCon</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="enter" value='Enter'>
            <label for="enter">Enter</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="exit" value='Exit'>
            <label for="exit">Exit</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="bfgf" value='BFGF'>
            <label for="bfgf">BFGF</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="breakup" value='Breakup'>
            <label for="breakup">Breakup</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="date" value='Date'>
            <label for="date">Date</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="dbs" value='DBS'>
            <label for="dbs">DBS</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="text-message" value='Text Message'>
            <label for="text-message">Text</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="chat-pull" value='Chat Pull'>
            <label for="chat-pull">Chat</label>        
          </div>
          <div class="islander-action m-2">
            <input type="radio" name="action" id="hideaway" value='Hideaway'>
            <label for="hideaway">Hideaway</label>        
          </div>

          
      </div>

      <div class="container row border p-1">
        <div class="islander-with-girls col">
          <?php foreach ($girl_islanders as $girl_islander): ?> 
            <div class="islander-option"> 
              <input type="radio" name="with-islander" id="<?= "with-".$girl_islander; ?>" value='<?= $girl_islander; ?>'>
              <label for="<?= "with-".$girl_islander; ?>"><?= $girl_islander; ?></label> 
            </div>
          <?php endforeach; ?>
        </div>
        <div class="islander-with-boys col">
          <?php foreach ($boy_islanders as $boy_islander): ?> 
            <div class="islander-option"> 
              <input type="radio" name="with-islander" id="<?= "with-".$boy_islander; ?>" value='<?= $boy_islander; ?>'>
              <label for="<?= "with-".$boy_islander; ?>"><?= $boy_islander; ?></label> 
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="row p-1">
        <label for="notes">Notes</label>    
        <input type="text" name="notes" id="notes" value='' class="col-12"> 
      </div>

      <div class="row p-1">
        
        <input type="submit" name="submit-action" value="Submit Action" class="btn-lg col-12"> 
      </div>
    </form>

<?php
if (isset($_POST['submit-action'])){
  global $wpdb;
  $episode = (! empty($_POST['episode'])) ? $_POST['episode'] : '' ;
  $islanderActing = (! empty($_POST['islander-acting'])) ? $_POST['islander-acting'] : '' ;
  $action = (! empty($_POST['action'])) ? $_POST['action'] : '' ;
  $withIslander = (! empty($_POST['with-islander'])) ? $_POST['with-islander'] : '' ;
  $notes = (! empty($_POST['notes'])) ? $_POST['notes'] : '' ;

  $data = array(
    'episode' => $episode,
    'islanderActing' => $islanderActing,
    'action' => $action,
    'withIslander' => $withIslander,
    'notes' => $notes,
  );
  $table_name = $wpdb->prefix.'islander-actions';
  $result = $wpdb->insert($table_name, $data);

  if ($result==1) {
    echo "<h3> Data Saved </h3>";
  } else{
    echo "<h3> DATA NOT SAVED </h3>";
  }

  // after submitting this form, redirect the user to the same page safely
  // $location = "http://" . $_SERVER['HTTP_POST'] . $_SERVER['REQUEST_URI'] ;
  // wp_safe_redirect($location);
}
?>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php get_footer(); ?>