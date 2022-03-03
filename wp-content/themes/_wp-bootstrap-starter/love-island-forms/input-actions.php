<?php
  // Template Name: Input Actions
ob_start();
get_header();

// establish a variable for episode number
// $episode = FIGURE OUT HOW TO PULL FROM THE DB

?>

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

  // display if data was saved properly or not
  if ($result==1) {
    echo "<div class='col-12 row justify-content-center'><h2 class='success-message'> ✅ {$islanderActing} + {$action} + ${withIslander} ✅  </h2></div>";
  } else{
    echo "<h2> DATA NOT SAVED </h3>";
  }

  // Update the last entries so they pre-populate in the form correctly.
  $lastSeries = $wpdb->get_var( 'SELECT series FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastSeason = $wpdb->get_var( 'SELECT season FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $lastEpisode = $wpdb->get_var( 'SELECT episode FROM `wp_islander_actions` WHERE id=(SELECT MAX(id) FROM `wp_islander_actions`)' , 0, 0 ); 
  $boy_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_boy_islanders`'); 
  $girl_islanders = $wpdb->get_col( 'SELECT islander_name FROM `wp_current_girl_islanders`'); 
}
?>

  
<form action='' method='post' class="container-fluid">
  <div class="row">
    <div class="left-column col">
      <div class="islander-acting row border p-1 btn-group-vertical btn-group-toggle" data-toggle="buttons">
        <div class="islander-acting-girls col " >
          <?php foreach ($girl_islanders as $girl_islander): ?> 
            <label class="btn btn-lg btn-girl-islander mb-1 w-100" for="<?= $girl_islander; ?>">              
              <input type="radio" name="islander-acting" id="<?= $girl_islander; ?>" value='<?= $girl_islander; ?>' autocomplete="off">
              <?= $girl_islander; ?>
            </label> 
          <?php endforeach; ?>
        </div>
        <div class="islander-acting-boys col">
          <?php foreach ($boy_islanders as $boy_islander): ?> 
            <label class="btn btn-lg btn-boy-islander mb-1 w-100" for="<?= $boy_islander; ?>">              
              <input type="radio" name="islander-acting" id="<?= $boy_islander; ?>" value='<?= $boy_islander; ?>' autocomplete="off">
              <?= $boy_islander; ?>
            </label> 
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
      <div class="islander-actions row border p-1 ml-1 mr-1 col btn-group-vertical btn-group-toggle" data-toggle="buttons">
        <div class="islander-actions-column-left col">
          <label for="melt" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="melt" value='Melt'>Melt
          </label>
          <label for="snog" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="snog" value='Snog'>Snog
          </label>
          <label for="chat-pull" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="chat-pull" value='Chat Pull'>Chat Pull
          </label>  
          <label for="start-conflict" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="start-conflict" value='Start Conflict'>StartCon
          </label>
          <label for="date" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="date" value='Date'>Date
          </label>
          <label for="bfgf" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="bfgf" value='BFGF'>BFGF
          </label>
          <label for="ily" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="ily" value='ILY'>ILY
          </label>  
          <label for="enter" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="enter" value='Enter'>Enter
          </label>      
           <label for="couple" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="couple" value='Couple'>Couple
          </label> 
        </div>
        <div class="islander-actions-column-right col">
          <label for="ick" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="ick" value='Ick'>Ick
          </label>   
          <label for="peck" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="peck" value='Peck'>Peck
          </label>   
          <label for="text-message" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="text-message" value='Text Message'>Text
          </label>  
          <label for="participate-conflict" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="participate-conflict" value='Participate Conflict'>PartCon
          </label>  
          <label for="hideaway" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="hideaway" value='Hideaway'>Hideaway
          </label>  
          <label for="breakup" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="breakup" value='Breakup'>Breakup
          </label>  
          <label for="dbs" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="dbs" value='DBS'>DBS
          </label>  
          <label for="exit" class="btn btn-lg btn-islander-action mb-1 w-100">
            <input type="radio" name="action" id="exit" value='Exit'>Exit
          </label>      
        </div>        
      </div>
    </div>  
    <div class="right-column col"> 
      <div class="islander-acting row border p-1 btn-group-vertical btn-group-toggle" data-toggle="buttons">
        <div class="islander-acting-girls col " >
          <?php foreach ($girl_islanders as $girl_islander): ?> 
            <label class="btn btn-lg btn-girl-islander mb-1 w-100" for="<?=  "with-".$girl_islander; ?>">              
              <input type="radio" name="with-islander" id="<?= "with-".$girl_islander; ?>" value='<?= $girl_islander; ?>' autocomplete="off">
              <?= $girl_islander; ?>
            </label> 
          <?php endforeach; ?>
        </div>
        <div class="islander-acting-boys col">
          <?php foreach ($boy_islanders as $boy_islander): ?> 
            <label class="btn btn-lg btn-boy-islander mb-1 w-100" for="<?= "with-".$boy_islander; ?>">              
              <input type="radio" name="with-islander" id="<?= "with-".$boy_islander; ?>" value='<?= $boy_islander; ?>' autocomplete="off">
              <?= $boy_islander; ?>
            </label> 
          <?php endforeach; ?>
        </div>
      </div>
      <div class="notes-and-submit row border p-1 mt-3">
        <div class="action-notes p-1">
          <label for="notes">Notes</label>    
          <input type="text" name="notes" id="notes" value='' class="col-12"> 
        </div>
        <div class="submit-button p-1 col-12">
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