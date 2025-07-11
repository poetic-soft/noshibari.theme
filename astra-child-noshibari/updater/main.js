jQuery(document)
.ready(function ($) {

  if (typeof arpPageData === 'undefined') return;

  setInterval(function () {

    $.post(
      arpPageData.ajax_url, 
      {
        action: 'check_page_update',
        post_id: arpPageData.post_id,
        last_modified: arpPageData.last_modified
      }, 
      function (response) {

        if (response.updated) {

          location.reload();
        }
      }
    );
  }, 1000);
});