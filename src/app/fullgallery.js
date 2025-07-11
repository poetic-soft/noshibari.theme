import { shuffle } from 'lodash'
import audio from 'assets/sound-c.mp3'
const sonido = new Audio(audio)
sonido.loop = true

export default $ => {

  let images,
      actual = 0,
      imagesdir = '/',
      $slides = {},
      $slide,
      imgs = {
        A: new Image(),
        B: new Image()
      },
      $container,
      $page = $('#page')

  const show = () => {

    let imgshow, imghide

    if(actual % 2 === 0) {
      
      imgshow = 'A'
      imghide = 'B'

    } else {

      imgshow = 'B'
      imghide = 'A'
    }

    const actualimage = imagesdir + '/' + images[actual]
    const showimg = imgs[imgshow]
    showimg.onload = function() {

      $slides[imgshow].css('background-image', 'url(' + actualimage + ')')

      $slides[imgshow].addClass('visible')
      $slides[imghide].removeClass('visible')
    }
    showimg.src = actualimage

    actual++
  }

  window.fullgallery = (dir) => {

    sonido.play()

    imagesdir = '/' + dir

    $('body')
    .append(
      `
      <div id="fullgallery">
        <div class="container">
          <div class="slide A"></div>
          <div class="slide B"></div>
        </div>
        <div class="close"></div>
      </div>
      `
    )
    
    $page.css('display', 'none')

    const $fullgallery = $('#fullgallery')
    const $close = $fullgallery.find('.close')
    $container = $fullgallery.find('.container')
    $slides.A = $container.find('.slide.A')
    $slides.B = $container.find('.slide.B')
    
    $container.on(
      'click',
      show
    )

    $close.on(
      'click',
      function() {

        sonido.pause()
        $fullgallery.remove()
        $page.css('display', 'inherit')
      }
    )

    fetch(
      '/wp-json/noshibari/fullgallery/images',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          path: dir
        })
      }
    )
    .then(response => {
      if (!response.ok) {

        throw new Error('Error en la respuesta');
      }
      return response.json(); // o .text(), según lo que devuelva el servidor
    })
    .then(data => {

      images = shuffle(data)
      show()
    })
    .catch(error => {

      console.error('Error en la solicitud:', error);
    });
  }
}