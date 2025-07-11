// https://github.com/metafizzy/flickity

export default $ => {

  let $sliderquery = $('.wp-block-query.slider')  
  if($sliderquery.length) { 

    $sliderquery
    .each(function(){

      const $this = $(this)
      const $query = $this.find('.wp-block-post-template')

      $query.flickity({
        autoPlay: false,
        prevNextButtons: false,
        wrapAround: true,
        pageDots: false,
        friction: 0.5
      })

      window.addEventListener(
        'resize',
        () => {

          $query.flickity('resize') 
        }
      )

      if($this.hasClass('autoplay')) {

        console.log('autoplay')

        setInterval(() => {

          $query.flickity('next', true, false)

        }, 6000)
      }

      $query.flickity('resize') 
    })
  }
}