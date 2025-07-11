// https://github.com/metafizzy/flickity

export default $ => {

  let $sliderhero = $('.wp-block-group.slider > .wp-block-group__inner-container')
  if(!$sliderhero.length) {

    $sliderhero = $('.wp-block-group.slider')
  }
  if($sliderhero.length) { 

    $sliderhero
    .each(function(){

      const $this = $(this)

      $this.flickity({
        autoPlay: 4000,
        prevNextButtons: false,
        wrapAround: true,
        pageDots: false,
        friction: 0.5
      })

      window.addEventListener(
        'resize',
        () => {

          $this.flickity('resize') 
        }
      )

      if($this.hasClass('autoplay')) {

        setInterval(() => {

          $this.flickity('next', true, false)

        }, 6000)
      }

      $this.flickity('resize') 
    })
  }
}