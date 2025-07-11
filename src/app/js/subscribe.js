export default $ => {

  const $subscribe = $('.shortcode.subscribe')
  if($subscribe.length) {    

    $subscribe
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
      });


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
          $message.html('Enviando...')
          $message.addClass('warning')
          $message.show()

          fetch(
            'https://noshibari.com/wp-json/noshibari/subscribe',
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

            } else {

              $message.html(messageok)
              $message.addClass('success')
            }
              
            $message.show()

            setTimeout(() => {

              $message.hide()
              $form.show()
              $inputemail.val('')
              
            }, 4000)
          })

          return false
        }
      )
    })
  }
}