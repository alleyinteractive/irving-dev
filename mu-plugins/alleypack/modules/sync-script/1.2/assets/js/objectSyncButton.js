jQuery(function($) {

  // Ensure we have the localized values to create this button.
  if (undefined === alleypackSync) {
    return;
  }

  /**
   * Update the sync button to indicate sync in progress.
   */
  function setToSyncing(message) {
    var loader = jQuery( '<div />' );
    loader.addClass( 'alleypack-spinner' );

    $('button.alleypack-sync-button')
      .attr('disabled', 'disabled')
      .text(message)
      .append( loader );
  }

  function setToNotSyncing() {
    $('button.alleypack-sync-button')
      .attr('disabled', false)
      .text('Sync ' + alleypackSync.objectPlural);
  }

  /**
   * Sync feed items.
   *
   * @param {int} limit  Feed limit.
   * @param {int} offset Feed offset.
   */
  function sync_feed_items( offset = 0 ) {

    $.get(
      alleypackSync.endpoint,
      { offset },
      function(data) {
        if ( true === data ) {

          // Determine how offset should be set.
          if (alleypackSync.usePages) {
            offset++;
            setToSyncing( 'Page ' + offset );
            console.log('a');
          } else {
            offset = parseInt(offset) + parseInt(alleypackSync.limit);
            setToSyncing( 'Row ' + offset );
            console.log('b');
          }

          // Recursively based on new offset.
          sync_feed_items( offset );
        } else {
          setToNotSyncing();
        }
      }
    );
  }

  // Insert a new button after the "Add X" button.
  $('.page-title-action').after('<button class="alleypack-sync-button page-title-action">Sync ' + alleypackSync.objectPlural + '</button>');

  // Insert a new button after the "Apply" button.
  $('.taxonomy-category #doaction').after('<button class="alleypack-sync-button button action">Sync ' + alleypackSync.objectPlural + '</button>');

  // Sync button handler.
  $(document).on('click', 'button.alleypack-sync-button', function() {
    setToSyncing( 'Beginning Sync' );
    sync_feed_items();
  });

  if (alleypackSync.isSyncing) {
    fireRequest();
  } else {
    setToNotSyncing();
  }
});
