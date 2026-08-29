import startjitsi from './react'

export default $ => {

  const $jitsi = $('.shortcode.jitsi')
  if($jitsi.length) {    

    $jitsi
    .each(function() {

      const $this = $(this)
      const $form = $this.find('form')
      const $inputemail = $form.find('input#email')
      const $inputtitle = $form.find('input#title')
      const $button = $form.find('.wp-block-button')
      const $message = $this.find('.message')
      const messageinvalid = $inputemail.data('message-invalid')
      const messageerror = $inputemail.data('message-error')
      const messageok = $inputemail.data('message-ok')
      
      $form.validate({
        messages: {
          email: {
            email: messageinvalid
          }
        }
      })  

      $inputemail
      .on(
        'keyup',
        function() {

          const $this = $(this) 
              
          if($this.valid()) {

            $button.prop('disabled', false);

          } else {

            $button.prop('disabled', true);
          }
        }
      )

      $button
      .on(
        'click',
        function() {

          $form.hide()

          $message.removeClass('warning error success')
          $message.html('Conectando...')
          $message.addClass('warning')
          $message.show()

          fetch(
            '/wp-json/noshibari/jitsi/jwt',
            {
              method: 'POST',
              headers: {
                "Content-Type": "application/json"
              },
              body: JSON.stringify({
                email: $inputemail.val(),
                title: $inputtitle.val()
              })
            }
          )
          .then(response => {

            $message.removeClass('warning error success')

            if(response.status != 200) {

              $message.html(messageerror)
              $message.addClass('error')
              $message.show()

              setTimeout(() => {

                $message.hide()
                $form.show()
                $inputemail.val('')
              }, 2000)

              return
            }

            $message.html(messageok)
            $message.addClass('success')
            $message.show()

            return response.json()
            .then(credentials => startjitsi(
              $,
              credentials
            ))
          })

          return false
        }
      )
    })
  }
}
