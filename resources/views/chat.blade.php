<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('96e68ac1a93c911b481f', {
      cluster: 'ap1'
    });

    var channel = pusher.subscribe('chat');
    channel.bind('message', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
  <img src="http://localhost:8000/storage/4/download-(2).jpg" alt="hinh anh">
</body>
</html>