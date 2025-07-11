// https://github.com/metafizzy/flickity
export default $ => {

  const $slidergallery = $('.wp-block-gallery.slider')

  if($slidergallery.length) { 

    $slidergallery
    .each(function(){

      const $this = $(this)
      $this.flickity({
        autoPlay: true,
        prevNextButtons: false,
        wrapAround: true,
        pageDots: false
      })      

      window.addEventListener(
        'resize',
        () => {

          $this.flickity('resize')
        }
      )
    })
  }
}