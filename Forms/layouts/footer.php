<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


<script>

    $('<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>').insertAfter('.quantity input');

    $('.quantity').each(function() {

      var spinner = $(this),

        input = spinner.find('input[type="number"]'),

        btnUp = spinner.find('.quantity-up'),

        btnDown = spinner.find('.quantity-down'),

        min = input.attr('min'),

        max = input.attr('max');



      btnUp.click(function() {

        var oldValue = parseFloat(input.val());

        if (oldValue >= max) {

          var newVal = oldValue;

        } else {

          var newVal = oldValue + 1;

        }

        spinner.find("input").val(newVal);

        // console.log(spinner.find('input').filter(':first'));

        // spinner.find('input').filter(':first').val(newVal);

        // spinner.find('input').filter(':first').trigger("change");

        spinner.find("input").trigger("change");

      });



      btnDown.click(function() {

        

        var oldValue = parseFloat(input.val());

        if (oldValue <= min) {

         

          var newVal = oldValue;

        } else {

          

          var newVal = oldValue - 1;

        }

        

        spinner.find("input").val(newVal);

        spinner.find("input").trigger("change");

      });



    });

</script>

</body>

</html>