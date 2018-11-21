<?php
function minbizeed_min_price_calculator()
{
    ?>
    <div class="admin_stats container">
        <h3>
            Minimum price calculator
        </h3>
        <div class="panel-body">
            <div class="calculator">
                <p>
                    <label>Retail Price: </label>
                    <input name="retail_price" type="number"/>
                </p>
                <p>
                    <label>Constant: 0.41</label>
                </p>
                <p class="min_price">
                    <label>Suggested Minimum Price: <span class="min_setter"></span></label>
                </p>

                <p class="div_by">
                    <label>Bid multiplier: </label>
                    <input type="number" name="div_by"/>
                </p>

                <p class="fin_price">
                    <label>Final Minimum Price: <span class="fin_setter"></span></label>
                </p>
            </div>
            <div class="results">
                <p class="r_e error_check"></p>

                <p class="r_s error_check"></p>

                <p>
                    <a href="#" id="calc" class="calc btn btn-primary">Calculate</a>
                    <a href="#" id="calc_2" class="calc btn btn-primary">Calculate</a>
                    <a href="#" id="reset" class="calc btn btn-danger">Reset</a>
                </p>
            </div>
        </div>
    </div>
    <style type="text/css">
        #reset {
            display: none;
        }

        .min_price, .div_by, #calc_2, .fin_price {
            display: none;
        }

        .min_price .min_setter, .fin_price .fin_setter {
            color: green;
            font-size: 14px;
        }

        .r_e {
            color: red;
        }

        .r_s {
            color: green;
        }
    </style>
    <script type="text/javascript">
        var $ = jQuery;
        jQuery(document).ready(function () {
            jQuery("#calc").click(function (e) {
                e.preventDefault();
                var retail_price = $('input[name="retail_price"]').val();
                if (retail_price > 0) {
                    var clicks = retail_price / 0.41;
                    var min_price = clicks * 0.01;
                    min_price = min_price.toFixed(2);
                    jQuery('.r_e').slideUp();
                    jQuery('.min_setter').html('');
                    jQuery('.min_setter').attr('id', min_price);
                    jQuery('.min_setter').prepend(min_price + " $");
                    jQuery('#calc').hide();
                    jQuery('.min_price,.div_by,#calc_2,#reset').slideDown();
                } else {
                    jQuery('.error_check').html('');
                    jQuery('.r_e').html('Please add a positive retail price').hide();
                    jQuery('.r_e').slideDown();
                }
            });
            jQuery("#calc_2").click(function (e) {
                e.preventDefault();
                var div_by = $('input[name="div_by"]').val();
                var min_price = jQuery('.min_setter').attr('id');
                if (div_by > 0 && min_price > 0) {
                    var f_price = min_price / div_by;
                    f_price = f_price.toFixed(2);
                    jQuery('.r_e').slideUp();
                    jQuery('.fin_setter').html('');
                    jQuery('.fin_setter').prepend(f_price + " $");
                    jQuery('#calc_2').hide();
                    jQuery('.fin_price').slideDown();
                } else {
                    jQuery('.error_check').html('');
                    jQuery('.r_e').html('Please add a positive divider').hide();
                    jQuery('.r_e').slideDown();
                }
            });
            jQuery("#reset").click(function (e) {
                e.preventDefault();
                $('input[name="retail_price"]').val("");
                $('input[name="div_by"]').val("");
                jQuery('.min_price,.div_by,.fin_price,#calc_2').fadeOut('fast');
                jQuery('#calc').fadeIn('fast');
            });
        });
    </script>
    <?php
}