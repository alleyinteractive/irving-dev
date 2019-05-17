jQuery(function($) {

  // Ensure we have the localized values to create this button.
  if (undefined === alleypackSync) {
    return;
  }

  /**
   * Update the sync button to indicate sync in progress.
   */
  function setToSyncing() {
    var loader = jQuery( '<div />' );
    loader.addClass( 'alleypack-spinner' );

    $('button.alleypack-sync-button')
      .attr('disabled', 'disabled')
      .text('Syncing ' + alleypackSync.objectPlural)
      .append( loader );
  }

  function setToNotSyncing() {
    $('button.alleypack-sync-button')
      .attr('disabled', false)
      .text('Sync ' + alleypackSync.objectPlural);
  }

  // Heartbeat API handler.
  $(document).on('heartbeat-tick', function( event, data ) {
    // Ensure we have the correct syncing key.
    if ( undefined === data[ alleypackSync.heartbeatKey ]) {
      return;
    }

    if (data[ alleypackSync.heartbeatKey ]) {
      setToSyncing();
    } else {
      setToNotSyncing();
    }
  });

  // Sync button handler.
  $(document).on('click', 'button.alleypack-sync-button', function() {
    setToSyncing();

    // Hit sync endpoint, and reload on completion.
    $.get(
      alleypackSync.endpoint,
      function(data) {
        setToNotSyncing();
      }
    );
  });

  // Insert a new button after the "Add X" button.
  $('.page-title-action').after('<button class="alleypack-sync-button page-title-action">Sync ' + alleypackSync.objectPlural + '</button>');
  var syncButton = $('button.alleypack-sync-button');

  if (alleypackSync.isSyncing) {
    // If already syncing, just update the button label.
    setToSyncing();
  } else {
    setToNotSyncing();
  }
});
