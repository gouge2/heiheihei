<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>直播回放</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            background: #000000;
        }
        #video {
            margin:0 auto;
            display: block;
            height: 100vh;
            width: 30%;
        }
    </style>
</head>
<body>
<video id="video" controls loop="false"></video>

<script src="__ADMIN_JS__/hls.js"></script>
<script>

    let m3u8_url= "{$str}",
        video   = document.getElementById('video');

    if (Hls.isSupported()) {
        let hls = new Hls();
        hls.loadSource(m3u8_url);
        hls.attachMedia(video);
        /* hls.on(Hls.Events.MANIFEST_PARSED,function() {
            video.play()
        }); */
    }

</script>
</body>
</html>