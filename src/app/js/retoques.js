export default $ => {

  let $videoplayer = $('.wp-block-video video') 

  if($videoplayer.length) { 

    $videoplayer
    .each(function(){

      const $this = $(this) 
      $this.attr('controlsList', 'nodownload noplaybackrate')      
      $this.bind('contextmenu',function() { return false; });
      $this.attr('disablePictureInPicture', 'true');
    })
  }
}