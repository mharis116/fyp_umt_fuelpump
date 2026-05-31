<script type="text/javascript">
    $(function() {
        if ("{{ $start ?? '' }}" && "{{ $end ?? '' }}") {
            var start = moment("{{ $start ?? '' }}");
            var end = moment("{{ $end ?? '' }}");
        } else {
            var start = moment().subtract('days');
            var end = moment();
        }

        // var start = moment().subtract('days');
        // var end = moment();

        function cb(start, end) {
            $('#reportrange #span').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        }
        
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            }
        }, cb);

        $('#span').click(function() {
            $('#span').val(null);
        });
        
        $('.ranges ul li').click(function() {
            if ($(this).attr('data-range-key') == 'Custom Range') {

            } else {
                $('#span').focus();
            }
        });
        $('.applyBtn').click(function() {
            $('#span').focus();
        });
        $('#span').focusout(function() {
            var val = $('#span').val();
            if (val.length >= 24) {
                $('#form').submit();
                $('.loaded .loader-wrapper').css('visibility', 'inherit')
                $('.loaded .loader-wrapper').css('opacity', '0.7')
            }
        });
        $('.clear').click(function() {
            var val = $('#span').val();
            if (val.length >= 24) {
                $('#reportrange').append('<input type="hidden" name="clear" value="clear">');
                $('#form').submit();
                $('.loaded .loader-wrapper').css('visibility', 'inherit')
                $('.loaded .loader-wrapper').css('opacity', '0.7')
            }
            $('#span').val(null);
            $('#span').focus();
        });
    });
</script>
<div class="item-group" id="reportrange" style="display:flex;">
    <input class="cal form-control" id="span" name="date" type="text" value='{{ isset($from, $to) ? ($from != '' ? $from . ' to ' . $to : '') : '' }}' style="width:100%;" placeholder='YYYY-MM-DD to YYYY-MM-DD' autocomplete="off">
    <div class="btn btn-primary clear">
        <svg class="feather feather-x-circle" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
    </div>
</div>
