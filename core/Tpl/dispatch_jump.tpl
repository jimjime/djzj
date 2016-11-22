<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv='Refresh' content='{$waitSecond};URL={$jumpUrl}'>
<body>
<import type='css' file='admin.css.jump,js.tbox.box' />
<base target="_self" />
<script> 
function Jump(){
    window.location.href = '{$jumpUrl}';
}
document.onload = setTimeout("Jump()" , {$waitSecond}* 1000);
</script>
<eq name="status" value="1">
  <div class="Prompt">
  <div class="Prompt_top"></div>
  <div class="Prompt_con">
    <dl>
      <dt>提示信息</dt>
      <dd><span class="Prompt_ok"></span></dd>
      <dd>
        <h2>{$message}</h2>
        <present name="closeWin" >
          <p>系统将在 <span style="color:blue;font-weight:bold">{$waitSecond}</span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="{$jumpUrl}"><font color="red">这里</font></A> 关闭</p>
        </present>
        <notpresent name="closeWin" >
          <p>系统将在 <span style="color:blue;font-weight:bold">{$waitSecond}</span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="{$jumpUrl}"><font color="red">这里</font></A> 跳转<br/>
            或者 <a href="__ROOT__/">返回首页</a></p>
        </notpresent>
      </dd>
    </dl>
    <div class="c"></div>
    </div>
    <div class="Prompt_btm"></div>
  </div>
</eq>
<eq name="status" value="0">
  <div class="Prompt">
    <div class="Prompt_top"></div>
  <div class="Prompt_con">
    <dl>
      <dt>提示信息</dt>
      <dd><span class="Prompt_x"></span></dd>
      <dd>
      <h2 style="color:red">{$message}</h2>
        <present name="closeWin" >
      <p>系统将在 <span style="color:blue;font-weight:bold">{$waitSecond}</span> 秒后自动关闭，如果不想等待,直接点击 <A HREF="{$jumpUrl}"><font color="red">这里</font></A> 关闭</p>
      </present>
      <notpresent name="closeWin" >
        <p>系统将在 <span style="color:blue;font-weight:bold">{$waitSecond}</span> 秒后自动跳转,如果不想等待,直接点击 <A HREF="{$jumpUrl}"><font color="red">这里</font></A> 跳转<br/>
          或者 <a href="__ROOT__/">返回首页</a></p>
      </notpresent>
      </dd>
    </dl>
    <div class="c"></div>
    </div>
    <div class="Prompt_btm"></div>
  </div>
</eq>

    </body>
</html>