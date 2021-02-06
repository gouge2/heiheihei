require(['./svga.min'], function (SVGA){

    let player = new SVGA.Player("#demoCanvas");
    let parser = new SVGA.Parser("#demoCanvas");
    // let bgImg = 'https://tao.lailu.live/Public/Upload/Room/Live/live_meet.svga'
    let div = document.querySelector("#demoCanvas")
    
    let bgImg = div.getAttribute('data-img')
    parser.load(bgImg,function(videoItem) {                          //this.bgImg，图片路径需要线上地址，本地引用会报错
        player.setVideoItem(videoItem);
        player.startAnimation();
      }
    );

});