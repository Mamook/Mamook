//<![CDATA[
function MM_findObj(n, d) { //v4.01
	var p,i,x;
	d = d || document;
	if((p = n.indexOf('?'))>0 && parent.frames.length)
	{
		d = parent.frames[n.substring(p+1)].document;
		n = n.substring(0, p);
	}
	if(!(x = d[n]) && d.all)
		x = d.all[n];
	for(i=0; !x && i<d.forms.length; i++)
	{
		x = d.forms[i][n];
	}
	for(i=0; !x && d.layers && i<d.layers.length; i++)
	{
		x = MM_findObj(n, d.layers[i].document);
	}
	if(!x && d.getElementById)
		x = d.getElementById(n);
	return x;
};

function toggle(l, i){
	var foundObject = MM_findObj(l);
	foundObject.style.display = (foundObject.style.display == (('none') ? 'block' : 'none'));
	if(document.images)
	{
		document.images[i].src = foundObject.style.display == (('block') ? '/scripts/sitemap/images/minus.gif' : '/scripts/sitemap/images/plus.gif');
	}
};
//]]>