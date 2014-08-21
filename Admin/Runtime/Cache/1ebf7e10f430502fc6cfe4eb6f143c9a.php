<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>叫啥网 </title>
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="网上订餐系统多店版,快捷订餐系统多店版"/>
    <meta name="description" content=" 。"/>
    <link rel="stylesheet" type="text/css" href="/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="/css/expand.css"/>
    <link rel="stylesheet" type="text/css" href="/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="/css/mall.css"/>
    <link rel="stylesheet" type="text/css" href="/css/css.css"/>
    <script src="/js/conf.js"></script>
    <script src="/js/kj.js"></script>
    <script src="/js/kj.dialog.js"></script>
    <script src="/js/kj.ajax.js"></script>
    <script src="/js/header.js"></script>
    <script src="/js/member.js"></script>
    <script src="/js/kj.rule.js"></script>
    <script src="/js/kj.alert.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/kj2.css"/>
</head>
<body>


<div class="xiala111" id="id_menu_text1" onmouseover="kj.show(this);kj.show('#id_mb_view');" onmouseout="kj.hide(this);"
     style="display:none">
    <li><a href="/?autologin=1">个人用户</a></li>
    <li><a href="/?autologin=2">店铺用户</a></li>
    <li><a href="/?autologin=3">后台用户</a></li>
</div>
<script>
    var kjtest = new function () {
        this.showtest = function (o) {
            var offset = kj.offset(o);
            kj.set('#id_menu_text1', 'style.left', (offset.left) + 'px');
            kj.show('#id_menu_text1');
        }
        this.hidetest = function () {
            kj.hide('#id_menu_text1');
        }
    }
    var mb_view = kj.cookie_get('test_mb_view');
    if (!mb_view) {
        kj.onload(function () {
            kj.show("#id_mb_view");
            kj.cookie_set('test_mb_view', 1, 20000, '');
        });
    }
</script>
<div class="top">
    <ul class="main">
        <li class="x1"><span><img src="/images/position.png"></span><span>&nbsp;A - 大连市&nbsp;<a
                href="#">[切换地址]</a></span></li>
        <li class="x2">
            <span class="xqq"><a href="/common.php?app=api.login&plat=qq"></a></span> <span class="xsina"><a
                href="/common.php?app=api.login&plat=weibo"></a></span>		<span class="x2x">
		<a href="javascript:jsheader.showlogin();">登录</a>&nbsp;&nbsp;<a href="javascript:jsheader.showreg();">注册</a>
				</span>
        </li>
    </ul>
</div>
<div class="main">
    <div class="mainbg" id="id_mainbg">
        <div class="topmenu">
            <li><img src="/images/logo.png"></li>
            <li class="x1">
                <a href="/" class="x2">首页</a>
                <a href="/index.php?app=act.gift">礼品兑换</a>
                <a href="/index.php?app_act=help">帮助中心</a>
                <a href="/index.php?app_act=msg">意见反馈</a>
                <a href="/index.php?app_act=cart">购物车</a>
            </li>
        </div>
    </div>
<div class="mainbg mg_t1">
    <script src="/webcss/default/header.js"></script>
    <script src="/webcss/default/member.js"></script>
    <script src="/webcss/common/js/kj.rule.js"></script>
    <script src="/webcss/common/js/kj.alert.js"></script>
    <script>
        kj.onload(function(){
            var w = (kj.w()-980)/2;
            kj.set("#idmenu1" , "style.top" , '120px');
            kj.set("#idmenu1" , "style.left" , (w+390)+'px');
            jsheader.rule_uname = "default";
            jsheader.reg_verify = "1";
            jsheader.tempurl = "/webcss/default/";
            jsheader.is_verify = false;
        });
    </script><div class="payok mg1">
    <table align="center" width="380px"><tbody><tr><td>
        <div class="tipsright">
            <li class="tit">恭喜！您的订单已经提交！</li>
            <li>订单编号：<span style="color:#ff8800;">1140804160108192</span></li>
            <li>订单金额：<span style="color:#ff8800;font-size:20px">￥15</span></li>
            <li>支付方式：<span style="color:#ff8800;">货到付款</span></li>
            <li>订单状态：<span style="color:#ff8800;"><font color="#ff0000">等待处理</font></span></li>
            <li><input type="button" name="btn_detail" value="查看详情" class="button2" onclick="thisjs.detail();">&nbsp;&nbsp;<a href="/">首页</a>&nbsp;&nbsp;<a href="?app_act=shop&amp;id=1">再逛逛</a></li>
        </div>
        <div class="tipsblue" id="id_state_info">
            温馨提示：您可以在下单后5分钟之内取消订单，<a href="javascript:thisjs.cancel();">我要取消订单</a>
        </div>
    </td></tr>
    </tbody></table>
</div>
    <div id="id_paymethod_box" style="display:none">
        <div style="float:left;padding:10px 0px 0px 10px;line-height:30px">
            <label><input type="radio" name="paymethod" value="alipay">支付宝支付</label>

            <br>
            <br><br>
            <input type="button" name="btn_state_ok" value="确认支付" onclick="thisjs.pay();" class="button5">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="btn_state_cancel" value="取消" onclick="kj.dialog.close('#winpaymethod_box');" class="button5x">
        </div>
    </div>
    <script>
        var thisjs = new function() {
            this.detail = function () {
                kj.dialog({id:'detail',title:'订单明细',url:'/index.php?app=member&app_act=order.detail&id=1363',w:700,max_h:650,showbtnmax:false,showbtnhide:false,type:'iframe'});
            }
            this.cancel = function() {
                if( !confirm("确定要取消订单吗？") ) return;
                kj.ajax.get("/index.php?app=member&app_act=order.cancel&id=1363" , function(data) {
                    var obj_data = kj.json(data);
                    if(obj_data.isnull) {
                        alert("系统繁忙，请稍后再试");
                        return;
                    }
                    if(obj_data.code == "0") {
                        alert("成功取消");
                        kj.obj("#id_state_info").innerHTML = "您已取消当前订单，可以重新再点餐";
                    } else {
                        alert(obj_data.msg);
                    }
                });
            }
            //显示支付方式选择框
            this.show_paymethod = function() {
                var obj = kj.obj('#id_paymethod_box');
                if(obj) {
                    this.state_html = obj.innerHTML;
                    kj.remove(obj);
                }
                kj.dialog({'html':this.state_html,'id':'paymethod_box','type':'html','title':'选择支付方式','w':300,'showbtnmax':false});
            }
            //跳转支付
            this.pay = function() {
                var obj = kj.obj(":paymethod");
                var pay_method = '';
                for(var i = 0 ; i < obj.length ; i++) {
                    if(obj[i].checked) {
                        pay_method = obj[i].value;
                        break;
                    }
                }
                if(pay_method == '') {
                    alert("请选择支付方式");
                    return;
                }
                window.open("/index.php?app=member&app_act=order_pay&id=1363&pay_method=" + pay_method,'_self');
            }

        }
    </script>
</div>
<div class="footer">
    <a href="./index.php?app_act=help">关于我们</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="./index.php?app_act=reg.shop">诚邀加盟</a>&nbsp;&nbsp;&nbsp;&nbsp;<a
        href="./index.php?app_act=msg">意见反馈</a><br>
    CopyRight &copy 2014 <a href="/" target="_blank">叫啥网</a> 版权所有
</div>
<div class="qrcode" id="id_qrcode"><img src="/images/kjcms.jpg"></div>