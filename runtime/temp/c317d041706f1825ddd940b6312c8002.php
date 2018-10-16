<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:89:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\public/../application/index\view\about\access.html";i:1539680989;s:80:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\application\index\view\public\layout.html";i:1539331739;s:83:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\application\index\view\public\searchimg.html";i:1539075084;s:82:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\application\index\view\about\menuindex.html";i:1539680920;s:83:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\application\index\view\public\copyright.html";i:1536803999;s:81:"D:\phpstudy2018\PHPTutorial\WWW\MyTest\application\index\view\public\imgfrom.html";i:1536803999;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css"  href="/static/home/css/common.css" />
  <link rel="stylesheet" href="/static/home/swiper/swiper.min.css"/>
  <script src="/static/home/js/jquery-1.12.3.min.js" type="text/javascript"></script>
  <script src="/static/home/swiper/swiper.min.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="/static/home/iconfont/iconfont.css">
  <link rel="stylesheet" type="text/css" href="/static/home/css/jquery.flex-images.css">
 <!--  <style type="text/css">
	
	body .demo-class .layui-layer-btn a{background:#00ce84;}
	body .demo-class .layui-layer-btn .layui-layer-btn1{background:#999;}
  </style> -->
  
  <title>拍蒜 - <?php echo (isset($title) && ($title !== '')?$title:'首页'); ?></title>
</head>
<body>
<input name="page" type="hidden" value="1"/>

<!-- header -->
  <div class="header" id="header">
      <div class="container-fluid header-container">
        <a class="logo" href="<?php echo url('index/index'); ?>"></a>
        <div class="search_container inPageSearch">
          
  <div class="search__dropDown pointer">
    <button  class="search__dropDown_button input_none inline-block pointer" id="searchtype"><span >设计</span>
      <i  class="iconfont icon-unfold"></i></button>
    <div class="wrap">
      <div  class="searchMenuContainer">
        <ul  class="list-reset selectOptions">
          <?php if(is_array($search_type) || $search_type instanceof \think\Collection || $search_type instanceof \think\Paginator): if( count($search_type)==0 ) : echo "" ;else: foreach($search_type as $skey=>$stype): ?>
         	<li  class="pointer selectOptions_li" data-type="<?php echo $skey; ?>"><?php echo $stype; ?></li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    </div>
  </div>
  <input type="hidden" name="type" value="<?php echo $type; ?>" />
  <input  type="text" name="name" value="<?php echo (isset($name) && ($name !== '')?$name:''); ?>" placeholder="开始免版权创意和灵感照片搜索..." class="search_input input_none">
  <i class="iconfont icon-xiangji1 similar_container_icon"></i>
  <div  class="search_container_text">搜索</div>

<script type="text/javascript">
	$(function(){
		setSearchName();
	});
	function setSearchName(){
		var searchtype = $("input[name=type]").val();
		var text = $("li.pointer.selectOptions_li[data-type="+searchtype+"]").html();
		$("#searchtype span").html(text);
	}
	$("li.pointer.selectOptions_li").click(function(){
		var type = $(this).attr('data-type');
		var text = $(this).html();
		$("input[name=type]").val(type);
		$("#searchtype span").html(text);
	});
	$("div.search_container_text").click(function(){
		var name = $("input[name=name]").val();
		if(name == '' || name == undefined || name == null) {
			layer.alert("请输入搜索内容！");
			return false;
		}
		var type = $("input[name=type]").val();
		window.location.href = "<?php echo url('Index/searchfromname'); ?>"+"?name="+name+"&type="+type;
	});
</script> 
        </div>
        <ul class="menu" id="menu">
        	<?php if(is_array($menu_list) || $menu_list instanceof \think\Collection || $menu_list instanceof \think\Paginator): if( count($menu_list)==0 ) : echo "" ;else: foreach($menu_list as $key=>$menu): ?>
        		 <li <?php if($menu_active == ($menu['controller'].'_'.$menu['action'])): ?> class="active" <?php endif; ?>><a href="<?php  echo url($menu['controller'].'/'.$menu['action']);  ?>"><?php echo $menu['name']; ?></a></li>
        	<?php endforeach; endif; else: echo "" ;endif; ?>
           
            <li><a href="#"><i  class="iconfont icon-gengduo1" style="font-size:30px;"></i></a></li>
          <li class="menu-btn active desktop-only"><a href="<?php echo url('login/register'); ?>">免费注册</a></li>
          <li class="menu-btn desktop-only"><a  href="<?php echo url('login/login'); ?>">HI，请登录</a></li>
        </ul>

      </div>

    </div>
  <!-- header end -->



<div class="wp980 cl hl_about">
  <div class="hl_sd">
   <div class="hl_aboutleft z">
     <h2 class="mb20">拍蒜图片</h2>
     <ul>
     <?php if($menuindex == 'index'): ?>
     	 <li class="on"><a href="#">授权执照</a></li>
     <?php else: ?>
     	<li><a href="<?php echo url('About/index'); ?>">授权执照</a></li>
     <?php endif; if($menuindex == 'access'): ?>
     	 <li class="on"><a href="#">拍蒜宣言</a></li>
     <?php else: ?>
     	<li><a href="<?php echo url('About/access'); ?>">拍蒜宣言</a></li>
     <?php endif; if($menuindex == 'privacy'): ?>
     	 <li class="on"><a href="#">隐私政策</a></li>
     <?php else: ?>
     	<li><a href="<?php echo url('About/privacy'); ?>">隐私政策</a></li>
     <?php endif; if($menuindex == 'clause'): ?>
     	 <li class="on"><a href="#">条款与条件</a></li>
     <?php else: ?>
     	<li><a href="<?php echo url('About/clause'); ?>">条款与条件</a></li>
     <?php endif; if($menuindex == 'copyright'): ?>
     	 <li class="on"><a href="#">版权政策</a></li>
     <?php else: ?>
     	<li><a href="<?php echo url('About/copyright'); ?>">版权政策</a></li>
     <?php endif; ?>
     
     </ul>
   </div>
 </div>
  <div class="hl_mn y">
    <h2>拍蒜宣言</h2>
		<p>拍蒜图片发布的所有照片都可以免费使用。您可以将它们用于商业和非商业目的。您不需要向摄影师、Unsplash、Pixabay、Pexels、Startup Stock Photos...或拍蒜图片征求许可或提供授权，尽管我们鼓励和感激保留照片归属。</p>
		
		<p>更准确地说，拍蒜和分发商授予您不可撤销的，非排他性的全球版权许可，可以免费下载，复制，修改，分发，执行和使用Paisuan.com中的照片，包括用于商业目的，未经摄影师或拍蒜的许可或归属。此许可证不包括从Paisuan.com获取照片以复制类似或竞争服务的权利。</p>

  </div>
</div>


	
<!-- partbanquan -->
<div class="indexpart partbanquan cl">
  <div class="hl_h2">为什么要使用拍蒜免版权图库？</div>
  <div class="hl_p">
    <p>我们正致力于搜索照片以寻找内容的最佳解决方案。所有照片均根据Creative Commons Zero（知识共享）</p>
    <p>和Public Domain（公有领域 ）或类似许可。这意味着您可以免费复制，修改，分发和</p>
    <p>使用这些照片，包括商业用途，而无需征得任何人的许可或提供归属信息。</p>
    <p>例如：广告、出版刊物、书籍插图、网络配图等。</p>
  </div>
  <div class="wp cl">
    <div class="hl_mode cl">
      <ul>
        <li>
          <div class="mode_ico mode1"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
        <li>
          <div class="mode_ico mode2"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
        <li>
          <div class="mode_ico mode3"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
        <li>
          <div class="mode_ico mode4"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
        <li>
          <div class="mode_ico mode5"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
        <li>
          <div class="mode_ico mode6"></div>
          <div class="mode_tit">50万张免版权照片</div>
          <div class="mode_subtit">照片数量不断增长。所有照片均根据Creative Commons Zero和Public Domain或类似许可</div>
        </li>
      </ul>
    </div>
  </div>

</div>	
	
	
<div class="indexpart partapi cl">
  <div class="hl_h2">所有图片在一个地方</div>
  <div class="hl_p">
    <p>拍蒜从50多个来源获得许可，中文 / 加速下载</p>
  </div>
    <div class="wp cl">
      <div class="hl_api">
        <ul>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
          <li><a href=""><img src="/static/home/img/th-475155.jpg"></a></li>
        </ul>
      </div>
    </div>
</div>

	
	


	<!-- footer -->
	<div class="footer cl">
	  <div class="footbox cl">
	    <div class="hl_h2">仅<?php echo config('yearmoney'); ?>元/年  现在就开始吧</div>
	    <div class="hl_p">你还在犹豫什么，高品质的创意平台更快的提高工作效率</div>
	    <a href="javascript:void(0)" class="hl_btn">社交登录</a>
	  </div>
	  <div class="wp cl">
	
	    <p class="hl_link cl">
	      <a href="">常见问题</a>
	      <a href="">执照</a>
	      <a href="">条款</a>
	      <a href="">隐私</a>
	      <a href="">关于我们</a>
	      <a href="">商务合作</a>
	      <a href="">自媒体推广</a>
	      <a href="">API</a>
	    </p>
	    <p class="hl_link cl">@2018 PAISUAN.COM  DUOZHA Team  Copyright © 2018 版权所有 </p>
	
	      <div class="hl_link2 cl">
	          友情链接：
	        <a href="">创造师导航</a>
	        <a href="">半撇私塾</a>
	        <a href="">稿定设计</a>
	        <a href="">FOTOR懒设计</a>
	        <a href="">新媒体管家</a>
	        <a href="">壹伴</a>
	        <a href="">美恰</a>
	        <a href="">墨刀</a>
	        <a href="">创客贴</a>
	        <a href="">MAKA</a>
	      </div>
	  </div>
	</div>
<!-- footer end -->

<!--页面js-->

<script type="text/javascript" src="/static/libs/layer/layer.js"></script>
<script src="/static/home/js/jquery.flex-images.js" type="text/javascript"></script>
<script type="text/javascript">
/* layer.config({
	skin:"demo-class",
}); */
	$(function(){
		setSearchName();
	});
	
	function setSearchName(){
		var searchtype = $("input[name=type]").val();
		var text = $("li.pointer.selectOptions_li[data-type="+searchtype+"]").html();
		$("#searchtype span").html(text);
	}
	$("li.pointer.selectOptions_li").click(function(){
		var type = $(this).attr('data-type');
		var text = $(this).html();
		$("input[name=type]").val(type);
		$("#searchtype span").html(text);
	});
	$("div.search_container_text").click(function(){
		var name = $("input[name=name]").val();
		if(name == '' || name == undefined || name == null) {
			layer.alert("请输入搜索内容！");
			return false;
		}
		var type = $("input[name=type]").val();
		window.location.href = "<?php echo url('Index/searchfromname'); ?>"+"?name="+name+"&type="+type;
	});
	function setPage(page) {
		$("input[name=page]").val(page);
	}
</script> 

<!--页面js-->
<!-- 额外HTML代码 -->
<?php echo (isset($extra_html) && ($extra_html !== '')?$extra_html:''); ?>
<!-- 额外HTML代码 -->
</body>
</html>
