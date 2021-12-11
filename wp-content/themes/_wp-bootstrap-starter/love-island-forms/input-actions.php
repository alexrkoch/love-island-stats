<?php
  // Template Name: Input Actions
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


<?php 
  global $wpdb;
  $lastSeries = $wpdb->get_var( 'SELECT series FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastSeason = $wpdb->get_var( 'SELECT season FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastEpisode = $wpdb->get_var( 'SELECT episode FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $boy_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_boy_islanders`'); 
  $girl_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_girl_islanders`'); 
?>

<?php
if (isset($_POST['submit-action'])){
  global $wpdb;
  $series = (! empty($_POST['series'])) ? $_POST['series'] : '' ;
  $season = (! empty($_POST['season'])) ? $_POST['season'] : '' ;
  $episode = (! empty($_POST['episode'])) ? $_POST['episode'] : '' ;
  $islanderActing = (! empty($_POST['islander-acting'])) ? $_POST['islander-acting'] : '' ;
  $action = (! empty($_POST['action'])) ? $_POST['action'] : '' ;
  $withIslander = (! empty($_POST['with-islander'])) ? $_POST['with-islander'] : '' ;
  $notes = (! empty($_POST['notes'])) ? $_POST['notes'] : '' ;

  $data = array(
    'series' => $series,
    'season' => $season,
    'episode' => $episode,
    'islanderActing' => $islanderActing,
    'action' => $action,
    'withIslander' => $withIslander,
    'notes' => $notes,
  );
  $table_name = $wpdb->prefix.'islander_actions';
  $result = $wpdb->insert($table_name, $data);

  if ($result==1) {
    echo "<h3> Data Saved </h3>";
  } else{
    echo "<h3> DATA NOT SAVED </h3>";
  }

  $lastSeries = $wpdb->get_var( 'SELECT series FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastSeason = $wpdb->get_var( 'SELECT season FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastEpisode = $wpdb->get_var( 'SELECT episode FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $boy_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_boy_islanders`'); 
  $girl_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_girl_islanders`'); 

  // after submitting this form, redirect the user to the same page safely
  // $location = "http://" . $_SERVER['HTTP_POST'] . $_SERVER['REQUEST_URI'] ;
  // wp_safe_redirect($location);
}
?>

  
<form action='' method='post' class="container-fluid">
  <div class="row">
    <div class="left-column col">
      <div class="islander-acting row border p-1">
        <div class="islander-acting-girls col btn-group btn-group-toggle" data-toggle="buttons">
          <?php foreach ($girl_islanders as $girl_islander): ?> 
            <label class="btn btn-lg btn-girl-islander mb-1" for="<?= $girl_islander; ?>">              
              <input type="radio" name="islander-acting" id="<?= $girl_islander; ?>" value='<?= $girl_islander; ?>' autocomplete="off">
              <?= $girl_islander; ?>
            </label> 
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
      <div class="series-season-episode row border p-1 mt-3">
        <div class="p-1 ">
          <label class="col-5" for="series">Series</label>        
          <input class="col-5" type="text" name="series" id="series" value='<?php echo $lastSeries ?>'> 
        </div>
        <div class="p-1">
          <label class="col-5" for="season">Season</label>        
          <input class="col-5" type="number" name="season" id="season" value='<?php echo $lastSeason ?>'> 
        </div>
        <div class="p-1">
          <label class="col-5" for="episode">Episode</label>        
          <input class="col-5" type="number" name="episode" id="episode" value='<?php echo $lastEpisode ?>'> 
        </div>
      </div>
    </div>
    <div class="middle-column col">      
      <div class="islander-actions row border p-1 ml-1 mr-1">
        <div class="islander-actions-column-left col">
          <div class="islander-action">
            <input type="radio" name="action" id="melt" value='Melt'>
            <label for="melt">Melt</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="ick" value='Ick'>
            <label for="ick">Ick</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="peck" value='Peck'>
            <label for="peck">Peck</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="snog" value='Snog'>
            <label for="snog">Snog</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="start-conflict" value='Start Conflict'>
            <label for="start-conflict">StartCon</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="participate-conflict" value='Participate Conflict'>
            <label for="participate-conflict">PartCon</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="bfgf" value='BFGF'>
            <label for="bfgf">BFGF</label>        
          </div>
        </div>
        <div class="islander-actions-column-right col">
          <div class="islander-action">
            <input type="radio" name="action" id="breakup" value='Breakup'>
            <label for="breakup">Breakup</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="date" value='Date'>
            <label for="date">Date</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="dbs" value='DBS'>
            <label for="dbs">DBS</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="text-message" value='Text Message'>
            <label for="text-message">Text</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="chat-pull" value='Chat Pull'>
            <label for="chat-pull">Chat</label>        
          </div>
          <div class="islander-action">
            <input type="radio" name="action" id="hideaway" value='Hideaway'>
            <label for="hideaway">Hideaway</label>        
          </div>
        </div>        
      </div>
    </div>  
    <div class="right-column col"> 
      <div class="islander-with row border p-1">
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
      <div class="notes-and-submit row border p-1 mt-3">
        <div class="action-notes p-1">
          <label for="notes">Notes</label>    
          <input type="text" name="notes" id="notes" value='' class="col-12"> 
        </div>
        <div class="submit-button p-1">
          <input type="submit" name="submit-action" value="Submit Action" class="btn-lg col-12"> 
        </div>
      </div>
    </div>      
  </div>
</form>



<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php get_footer(); ?>