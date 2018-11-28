jQuery(document).ready(function($){
    $(document).on('click', '.ui-datepicker-close.ui-state-default.ui-priority-primary.ui-corner-all', function(){
       let selecDate = $(document).find('#blocked_date').val();
       $(document).find('#form_date_formatted').val(selecDate);
    });
    $('#blocked_date').datetimepicker({
        timeFormat: 'hh:mm:ss',
        dateFormat: 'yy-mm-dd'
    });
    var getDateIn = document.getElementById('blocked_date').value;
    var end = new Date(getDateIn);

    var _second = 1000;
    var _minute = _second * 60;
    var _hour = _minute * 60;
    var _day = _hour * 24;
    var timer;

    function showRemaining() {
        var now = new Date();
        var distance = end - now;
        if (distance < 0) {

            clearInterval(timer);
            document.getElementById('countdown').innerHTML = 'EXPIRED!';

            return;
        }
        var days = Math.floor(distance / _day);
        var hours = Math.floor((distance % _day) / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);

        document.getElementById('countdown').innerHTML = days + 'days ';
        document.getElementById('countdown').innerHTML += hours + 'hrs ';
        document.getElementById('countdown').innerHTML += minutes + 'mins ';
        document.getElementById('countdown').innerHTML += seconds + 'secs';
    }

    timer = setInterval(showRemaining, 1000);

});