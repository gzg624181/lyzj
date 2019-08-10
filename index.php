<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>恭喜，站点创建成功！</title>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="//cdn.bootcss.com/jquery/1.11.2/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<style>
.add_blank{
    position: absolute;
    left: 14%;
    width: 34%;
    font-size: 32px;
    height: 66px;
    text-align: center;
    background: #ff9966;
    line-height: 67px;
    display: none;
}
</style>

<!--达到要求弹出div-->


<div class="add_blank" >您有新的订单,请注意查看</div>

<!--新订单提醒-->
    <script type="text/javascript">
        function timeout() {
            var res;
            $.ajax({
                url:'index.php?act=dingshi',
                type:'get',
                datatype:'text',
                async:false,
                success:function (data) {
                    res = data;
                }
            });
            return res;
        }

        var i=timeout();

        function hello() {
            $.ajax({
                url:'index.php?act=dingshi',
                type:'get',
                datatype:'text',
                async:false,
                success:function (result) {
                    if (result != i){
                        i = result;
                        $(".add_blank").show()
                        playSound();
                    }
                }
            });
        }
        setInterval("hello()",10000);
    </script>
    <!--展示9秒关闭-->
    <script type="text/javascript">
        $(function(){
            setInterval(function(){
                $(".add_blank").hide();
            },9000);
        });
    </script>
    <!--订单声音提示-->
    <script>
        var playSound = function () {
                var borswer = window.navigator.userAgent.toLowerCase();
                if ( borswer.indexOf( "ie" ) >= 0 )
                {
                    //IE内核浏览器
                    var strEmbed = '<embed name="embedPlay" src="uploads/media/20190711/1562821037.mp3"> autostart="true" hidden="true" loop="false"></embed>';
                    if ( $( "body" ).find( "embed" ).length <= 0 )
                        $( "body" ).append( strEmbed );
                    var embed = document.embedPlay;

                    //浏览器不支持 audion，则使用 embed 播放
                    embed.volume = 100;
                    //embed.play();这个不需要
                } else
                {
                    //非IE内核浏览器
                    var strAudio = "<audio id='audioPlay' src='uploads/media/20190711/1562821037.mp3' hidden='true'>";

                    if($("#audioPlay").length<=0){
                        $( "body" ).append( strAudio );
                    }

                    var audio = document.getElementById( "audioPlay" );

                    //浏览器支持 audio
                    audio.play();
                }
            }
    </script>
</head>
<body>
	<div class="container" style="margin-top:9%;">
  		<div class="jumbotron">
        <div class="panel panel-success">
        <div class="panel-heading"><h1>查看是否有新订单</h1></div>
	</div>
</body>
</html>
