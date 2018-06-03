document.body.style.margin='0';
document.body.style.overflow='hidden';
document.body.insertAdjacentHTML('afterBegin','<div id="scrollBar" style="background:rgba(0,0,0,0.8);width:7px;height:0;top:0;left:100%;margin:2px 0 0 -9px;border-radius:7px;position:absolute;"></div>');
document.getElementById('scrollBar').style.webkitTransition='margin 0.1s, width 0.1s';
document.getElementById('scrollBar').style.mozTransition='margin 0.1s, width 0.1s';
document.getElementById('scrollBar').style.msTransition='margin 0.1s, width 0.1s';
document.getElementById('scrollBar').style.oTransition='margin 0.1s, width 0.1s';
document.getElementById('scrollBar').style.Transition='margin 0.1s, width 0.1s';
document.getElementById('scrollBar').onmouseover=function(){document.getElementById('scrollBar').style.width='10px';document.getElementById('scrollBar').style.margin='2px 0 0 -12px';};
document.getElementById('scrollBar').onmouseout=function(){document.getElementById('scrollBar').style.width='7px';document.getElementById('scrollBar').style.margin='2px 0 0 -9px';};
function scrollBarAdjust() {
    var scrollBarHeight=(window.innerHeight-3)/document.body.offsetHeight*window.innerHeight;
    var scrollBarPosition=(window.innerHeight-3)/document.body.offsetHeight*-parseInt(document.body.style.marginTop);
    document.getElementById('scrollBar').style.height=scrollBarHeight+'px';
    document.getElementById('scrollBar').style.top=scrollBarPosition+'px';
}
scrollBarAdjust();
document.onmousewheel=function(event) {
    var direction=event.detail ? event.detail*(-120) : event.wheelDelta;
    var scroll=parseInt(document.body.style.marginTop)+direction;
    if(scroll>0)
        document.body.style.margin='0';
    else if(scroll<-document.body.offsetHeight+window.innerHeight)
        document.body.style.margin=(-document.body.offsetHeight+window.innerHeight)+'px 0 0 0';
    else
        document.body.style.margin=scroll+'px 0 0 0';
    scrollBarAdjust();
}
document.getElementById('scrollBar').onmousedown=function(event) {
    event.preventDefault();
    scrollBarOnmouseout=this.onmouseout;
    this.onmouseout=null;
    init={position:event.clientY,scroll:parseInt(document.body.style.marginTop)};
    window.onmousemove=function(event) {
        scroll=-(init.scroll+(event.clientY-init.position)*(document.body.offsetHeight/window.innerHeight));
        console.log(scroll);
        if(scroll>0)
            document.body.style.margin='0';
        else if(scroll<-document.body.offsetHeight+window.innerHeight)
            document.body.style.margin=(-document.body.offsetHeight+window.innerHeight)+'px 0 0 0';
        else
            document.body.style.margin=scroll+'px 0 0 0';
        scrollBarAdjust();
    }
    window.onmouseup=function() {
        document.getElementById('scrollBar').onmouseout=scrollBarOnmouseout;
        window.onmousemove=null;
    }
}
window.onresize=scrollBarAdjust;