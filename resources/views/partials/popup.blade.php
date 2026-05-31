<!-- Button trigger modal -->
<button type="button" id='submit' class="btn btn-{{$data['color']}} float-{{$data['float']}}" data-toggle="modal" data-target="#{{$data['id']}}">
    {{$data['button']}}
  </button>
  <!-- Modal -->
  <div class="modal fade" id="{{$data['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style='height:300px; margin-top:35%;' >
        <div class="">
          <button type="button" class="close float-right my-1 mx-1" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            @if($data['type'] == 'info')
                <svg class='text-light' width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle class='text-info' cx="12" cy="12" r="10"></circle><line class='text-info' x1="12" y1="8" x2="12" y2="12"></line><line class='text-info' x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @elseif($data['type'] == 'warning')
                <svg class='text-light' width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon class='text-warning' points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line class='text-warning' x1="12" y1="8" x2="12" y2="12"></line><line class='text-warning' x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            @elseif($data['type'] == 'error')   
                <svg class='text-light' width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon "><polygon class='text-danger' points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line class='text-danger' x1="15" y1="9" x2="9" y2="15"></line><line class='text-danger' x1="9" y1="9" x2="15" y2="15"></line></svg>
            @endif
            <br>
            <br>
            <br>
            <span style="font-size: 15px;">
                {{$data['desc']}}
            </span>
        </div>
        <script>
          $(document).ready(function(){
            $('.fc').focus();
          });
        </script>
        <div class="">
          <button type="submit"  class="btn btn-{{$data['color']}} fc my-2 mx-2  float-right" autofocus>Confirm {{$data['button']}}</button>
          <button type="button" class="btn btn-secondary float-right my-2"  data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>