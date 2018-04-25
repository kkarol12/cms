<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://js.pusher.com/4.1/pusher.min.js"></script>

<script>
  $(document).ready(function(){

    var pusher = new Pusher('bbac3062d4d13011126f', {
        cluster: 'eu',
        encrypted: true
    });

    var notificationChannel = pusher.subscribe('notifications');

    notificationChannel.bind('new_user', function(notification){

      var message = notification.message;

      toastr.success(`${message} just registered`);

    });

  });

</script>
